<?php

namespace demo\elasticsearch;

require_once __DIR__ . '/../vendor/autoload.php';

//索引文档
class indexing {
    use init;

    public function index()
    {
        //索引一个文档
        $params = [
            'index' => 'my_index',
            'id'    => 'my_id', //可选
            'routing'   => 'company_xyz', //可选
            'timestamp' => strtotime("-1d"), //可选
            'body'  => [ 'testField' => 'abc']
        ];

        // Document will be indexed to my_index/_doc/my_id
        $response = $this->cache()->index($params);
        print_r($response);
    }

    public function bulk()
    {
        for($i = 0; $i < 100; $i++) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'my_index',
                ]
            ];

            $params['body'][] = [
                'my_field'     => 'my_value',
                'second_field' => 'some more values'
            ];
        }

        $responses = $this->cache()->bulk($params);
    }

    /**
     * 批量分批索引
     */
    public function batches()
    {
        $params = ['body' => []];

        for ($i = 1; $i <= 1234567; $i++) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'my_index',
                    '_id'    => $i
                ]
            ];

            $params['body'][] = [
                'my_field'     => 'my_value',
                'second_field' => 'some more values'
            ];

            // Every 1000 documents stop and send the bulk request
            if ($i % 1000 == 0) {
                $responses = $this->cache()->bulk($params);

                // erase the old bulk request
                $params = ['body' => []];

                // unset the bulk response when you are done to save memory
                unset($responses);
            }
        }

        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $responses = $this->cache()->bulk($params);
        }
    }
}
