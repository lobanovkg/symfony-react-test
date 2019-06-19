<?php

namespace App\Service;

use Elasticsearch\ClientBuilder;

class ElasticSearch
{
    /**
     * @var \Elasticsearch\Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts([$_ENV['ELASTIC_SEARCH_URL']])->build();
    }

    /**
     * @param string $index
     */
    public function deleteIndex($index)
    {
        $indexParams = ['index' => $index];
        if ($this->client->indices()->exists($indexParams)) {
            $this->client->indices()->delete($indexParams);
        }
    }

    /**
     * @param string $index
     */
    public function createIndex($index)
    {
        $indexParams = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'analysis' => [
                        'analyzer' => [
                            'lowercase' => [
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => ['lowercase'],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $this->client->indices()->create($indexParams);
    }

    /**
     * @param string $index
     * @param string type
     * @param array $data
     */
    public function insert($index, $type, $data)
    {
        $indexParams = [];
        if (is_array($data)) {
            foreach ($data as $elem) {
                $indexParams['body'][] = [
                    'index' => [
                        '_index' => $index,
                        '_type' => $type,
                        '_id' => $elem['id'],
                    ]
                ];
                $indexParams['body'][] = $elem;
            }
        }
        return $this->client->bulk($indexParams);
    }

    /**
     * @param $parameters
     *
     * @return array|callable
     */
    public function search($parameters)
    {
        return $this->client->search($parameters);
    }
}