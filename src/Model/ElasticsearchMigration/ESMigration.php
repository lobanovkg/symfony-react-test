<?php
/**
 * Created by PhpStorm.
 * User: Lobanov Kyryll
 * Date: 14.06.19
 * Time: 18:11
 */

namespace App\Model\ElasticsearchMigration;

use App\Model\ElasticsearchMigration\Type\Apartment;
use App\Service\ElasticSearch;
use Doctrine\ORM\EntityManager;

class ESMigration
{
    private $errors;
    private $em;
    private $es;

    /**
     * ESMigration constructor.
     *
     * @param               $indexName
     * @param EntityManager $em
     * @param ElasticSearch $es
     */
    public function __construct($indexName, EntityManager $em, ElasticSearch $es)
    {
        $this->em = $em;
        $this->es = $es;

        try {
            $this->run($indexName);
        } catch (\Exception $e) {
            $this->errors = $e->getMessage();
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function run($indexName)
    {
        switch ($indexName) {
            case Apartment::APARTMENT_INDEX_NAME:
                $apartment = new Apartment($this->em, $this->es);
                $apartment->run();
                break;
            default:
                throw new \InvalidArgumentException('Incorrect index name');
        }
    }
}
