<?php
/**
 * Created by PhpStorm.
 * User: Lobanov Kyryll
 * Date: 18.06.19
 * Time: 23:14
 */

namespace App\Model\Search;

use App\Service\ElasticSearch;

/**
 * Class Search
 */
class Search
{
    /**
     * @var ElasticSearch
     */
    private $elasticSearch;

    private $conditions = [];

    private $indexName;

    private $type;

    /**
     * ApartmentSearch constructor.
     *
     * @param ElasticSearch $elasticSearch
     */
    public function __construct(ElasticSearch $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
    }

    /**
     * @param $indexName
     * @param $type
     *
     * @return $this
     */
    public function setIndexType($indexName, $type)
    {
        $this->indexName = $indexName;
        $this->type      = $type;

        return $this;
    }

    /**
     * @param $fieldName
     * @param $value
     *
     * @return $this
     */
    public function whereMatchPhrase($fieldName, $value)
    {
        $this->conditions[] = ['match_phrase_prefix' => [$fieldName => $value]];

        return $this;
    }

    /**
     * @param $fieldName
     * @param $value
     *
     * @return $this
     */
    public function whereMatch($fieldName, $value)
    {
        $this->conditions[] = ['term' => [$fieldName => $value]];

        return $this;
    }

    /**
     * @param $fieldName
     * @param $minValue
     * @param $maxValue
     *
     * @return $this
     */
    public function whereRange($fieldName, $minValue, $maxValue)
    {
        $rangeCondition = ['range' => [$fieldName => []]];
        if ($minValue) {
            $rangeCondition['range'][$fieldName]['gte'] = $minValue;
        }
        if ($maxValue) {
            $rangeCondition['range'][$fieldName]['lte'] = $maxValue;
        }
        $this->conditions[] = $rangeCondition;

        return $this;
    }

    /**
     * @return array
     */
    public function search()
    {
        $params = [
            'index' => $this->indexName,
            'type'  => $this->type,
        ];

        $params['body'] = [
            'query' => [
                'bool' => [
                    'must' => [$this->conditions],
                ],
            ],
        ];

        $response = $this->elasticSearch->search($params);

        return $this->filterResult($response);
    }

    /**
     * @param $response
     *
     * @return array
     */
    private function filterResult($response)
    {
        $result = [];

        if (isset($response['hits']['hits']) && is_array($response['hits']['hits'])) {
            foreach ($response['hits']['hits'] as $element) {
                if (isset($element['_source']) && is_array($element['_source'])) {
                    $result[] = $element['_source'];
                }
            }
        }

        return $result;
    }
}
