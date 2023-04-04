<?php

namespace demo\elasticsearch;

require_once __DIR__ . '/../vendor/autoload.php';

use Elasticsearch;

//索引管理
class indexManagement {
    use init;

    public function create()
    {
        //创建一个索引
        $params = [
            'index' => 'my_index'
        ];

        try {
            // Create the index
            $response = $this->cache()->indices()->create($params);
            var_dump($response);
        } catch (Elasticsearch\Common\Exceptions\ElasticsearchException $e) {
            $code = $e->getCode();
            if ($code == 400) {
                echo '重复了';
            }else{
                echo '未知错误';
            }
        }
    }

    public function create2()
    {
        $params = [
            'index' => 'my_index',
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'first_name' => [
                            'type' => 'keyword'
                        ],
                        'age' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ]
        ];

        // Create the index with mappings and settings now
        $response = $this->cache()->indices()->create($params);
        var_dump($response);
    }

    public function create3()
    {
        $params = [
            'index' => 'reuters',
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                    'analysis' => [
                        'filter' => [
                            'shingle' => [
                                'type' => 'shingle'
                            ]
                        ],
                        'char_filter' => [
                            'pre_negs' => [
                                'type' => 'pattern_replace',
                                'pattern' => '(\\w+)\\s+((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\b',
                                'replacement' => '~$1 $2'
                            ],
                            'post_negs' => [
                                'type' => 'pattern_replace',
                                'pattern' => '\\b((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\s+(\\w+)',
                                'replacement' => '$1 ~$2'
                            ]
                        ],
                        'analyzer' => [
                            'reuters' => [
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => ['lowercase', 'stop', 'kstem']
                            ]
                        ]
                    ]
                ],
                'mappings' => [
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'reuters',
                            'copy_to' => 'combined'
                        ],
                        'body' => [
                            'type' => 'text',
                            'analyzer' => 'reuters',
                            'copy_to' => 'combined'
                        ],
                        'combined' => [
                            'type' => 'text',
                            'analyzer' => 'reuters'
                        ],
                        'topics' => [
                            'type' => 'keyword'
                        ],
                        'places' => [
                            'type' => 'keyword'
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->cache()->indices()->create($params);
        var_dump($response);
    }
    public function delete()
    {
        //删除一个索引
        $params = ['index' => 'my_index'];
        $response = $this->cache()->indices()->delete($params);
        var_dump($response);
    }

    public function putSettings()
    {
        $params = [
            'index' => 'my_index',
            'body' => [
                'settings' => [
                    'number_of_replicas' => 0,
                    'refresh_interval' => -1
                ]
            ]
        ];

        $response = $this->cache()->indices()->putSettings($params);
    }

    public function getSettings()
    {
        // Get settings for one index
        $params = ['index' => 'my_index'];
        $response = $this->cache()->indices()->getSettings($params);

        // Get settings for several indices
        $params = [
            'index' => [ 'my_index', 'my_index2' ]
        ];
        $response = $this->cache()->indices()->getSettings($params);
    }

    public function putMapping()
    {
        // Set the index and type
        $params = [
            'index' => 'my_index',
            'body' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => [
                    'first_name' => [
                        'type' => 'text',
                        'analyzer' => 'standard'
                    ],
                    'age' => [
                        'type' => 'integer'
                    ]
                ]
            ]
        ];
        
        // Update the index mapping
        $this->cache()->indices()->putMapping($params);
    }

    public function getMapping()
    {
        // Get mappings for all indices
        $response = $this->cache()->indices()->getMapping();

        // Get mappings in 'my_index'
        $params = ['index' => 'my_index'];
        $response = $this->cache()->indices()->getMapping($params);

        // Get mappings for two indices
        $params = [
            'index' => [ 'my_index', 'my_index2' ]
        ];
        $response = $this->cache()->indices()->getMapping($params);
    }
}

