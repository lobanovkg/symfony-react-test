<?php
/**
 * Created by PhpStorm.
 * User: Lobanov Kyryll
 * Date: 14.06.19
 * Time: 18:52
 */

namespace App\Model\ElasticsearchMigration\Type;

use App\Service\ElasticSearch;
use Doctrine\ORM\EntityManager;

class AbstractType
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ElasticSearch
     */
    protected $es;
    /**
     * AbstractType constructor.
     *
     * @param EntityManager $em
     * @param ElasticSearch $es
     */
    public function __construct(EntityManager $em, ElasticSearch $es)
    {
        $this->em = $em;
        $this->es = $es;
    }
}
