<?php
/**
 * Created by PhpStorm.
 * User: Lobanov Kyryll
 * Date: 19.06.19
 * Time: 0:07
 */

namespace App\Controller;

use App\Model\Search\Search;
use App\Service\ElasticSearch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validation;

class SearchController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function search(Request $request)
    {
        /** @todo: move properties to entity */
        $parametersAsArray = [
            'name'      => '',
            'min-price' => 0,
            'max-price' => 0,
            'bedrooms'  => 0,
            'bathrooms' => 0,
            'storeys'   => 0,
            'garages'   => 0,
        ];
        if ($content = $request->getContent()) {
            $contentAsArray = json_decode($content, true);
            foreach ($contentAsArray as $key => $item) {
                if ('' === $item) {
                    continue;
                }
                $parametersAsArray[$key] = $item;
            }
            unset($contentAsArray);
        }

        $validator = Validation::createValidator();

        $constraint = new Collection(
            [
                'name'      => new Type('string'),
                'min-price' => new Type('integer'),
                'max-price' => new Type('integer'),
                'bedrooms'  => new Type('integer'),
                'bathrooms' => new Type('integer'),
                'storeys'   => new Type('integer'),
                'garages'   => new Type('integer'),
            ]
        );

        $violations = $validator->validate($parametersAsArray, $constraint);

        if ($violations) {
            /** @todo: realise validation message */
        }

        $result = [];
        try {
            $result = $this->apartmentSearch($parametersAsArray);
        } catch (\Exception $e) {
            /** @todo: add to logger */
        }

        /** Needed for test, could be delete for PROD */
        sleep(1);

        return $this->json(['search_result' => $result]);
    }

    /**
     * @param $params
     *
     * @return array
     */
    private function apartmentSearch($params)
    {
        $searchConditionType = [
            'matchPhrase' => ['name'],
            'match' => ['bedrooms', 'bathrooms', 'storeys', 'garages'],
        ];

        $apartmentSearch = new Search(new ElasticSearch());
        $apartmentSearch->setIndexType('apartments', 'apartments');

        foreach ($searchConditionType as $type => $fields) {
            foreach ($fields as $field) {
                switch ($type) {
                    case 'matchPhrase':
                        if ($params[$field]) {
                            $apartmentSearch->whereMatchPhrase($field, $params[$field]);
                        }
                        break;
                    case 'match':
                        if ($params[$field]) {
                            $apartmentSearch->whereMatch($field, $params[$field]);
                        }
                        break;
                }
            }
        }

        /**
         * HARD CODE (evil)
         * @todo: optimize range type
         */
        if ($params['min-price'] || $params['max-price']) {
            $apartmentSearch->whereRange('price', $params['min-price'], $params['max-price']);
        }

        return $apartmentSearch->search();
    }
}
