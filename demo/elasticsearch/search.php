<?php

namespace demo\elasticsearch;

require_once __DIR__ . '/../../vendor/autoload.php';

//搜索操作
class search {
    use init;

    public function match()
    {
        //Match查询
        $params = [
            'index' => 'my_index',
            'body'  => [
                'query' => [
                    'match' => [
                        'testField' => 'abc'
                    ]
                ]
            ]
        ];

        $results = $this->cache()->search($params);
        print_r($results);
    }
    public function matchUsingRawJSON()
    {
        $json = '{
            "query" : {
                "match" : {
                    "testField" : "abc"
                }
            }
        }';

        $params = [
            'index' => 'my_index',
            'body' => $json
        ];

        $results = $this->cache()->search($params);
        print_r($results);
    }

    public function matchResult()
    {
        $params = [
            'index' => 'my_index',
            'body'  => [
                'query' => [
                    'match' => [
                        'testField' => 'abc'
                    ]
                ]
            ]
        ];

        $results = $this->cache()->search($params);

        $milliseconds = $results['took'];
        $maxScore     = $results['hits']['max_score'];

        $score = $results['hits']['hits'][0]['_score'];
        $doc   = $results['hits']['hits'][0]['_source'];
    }
    public function bool()
    {
        //Bool查询
        $params = [
            'index' => 'my_index',
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [ 'match' => [ 'testField' => 'abc' ] ],
                            [ 'match' => [ 'testField2' => 'xyz' ] ],
                        ]
                    ]
                ]
            ]
        ];

        $results = $this->cache()->search($params);
        print_r($results);
    }

    public function bool2()
    {
        $params = [
            'index' => 'my_index',
            'body'  => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'term' => [ 'my_field' => 'abc' ]
                        ],
                        'should' => [
                            'match' => [ 'my_other_field' => 'xyz' ]
                        ]
                    ]
                ]
            ]
        ];

        $results = $this->cache()->search($params);
    }

    public function scrolling()
    {
        $params = [
            'scroll' => '30s',          // how long between scroll requests. should be small!
            'size'   => 50,             // how many results *per shard* you want back
            'index'  => 'my_index',
            'body'   => [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ]
        ];

        // Execute the search
        // The response will contain the first batch of documents
        // and a scroll_id
        $response = $this->cache()->search($params);

        // Now we loop until the scroll "cursors" are exhausted
        while (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {

            // **
            // Do your work here, on the $response['hits']['hits'] array
            // **

            // When done, get the new scroll_id
            // You must always refresh your _scroll_id!  It can change sometimes
            $scroll_id = $response['_scroll_id'];

            // Execute a Scroll request and repeat
            $response = $this->cache()->scroll([
                'body' => [
                    'scroll_id' => $scroll_id,  //...using our previously obtained _scroll_id
                    'scroll'    => '30s'        // and the same timeout window
                ]
            ]);
        }
    }
}

$obj=new search();
$obj->match();
