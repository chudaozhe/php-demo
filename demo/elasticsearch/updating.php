<?php

namespace demo\elasticsearch;

require_once __DIR__ . '/../vendor/autoload.php';

//更新文档
class updating {
    use init;

    public function update()
    {
        $params = [
            'index' => 'my_index',
            'id'    => 'my_id',
            'body'  => [
                'doc' => [
                    'new_field' => 'abc'
                ]
            ]
        ];

        // Update doc at /my_index/my_type/my_id
        $response = $this->cache()->update($params);
        var_dump($response);
    }

    public function scriptedUpdate()
    {
        $params = [
            'index' => 'my_index',
            'id'    => 'my_id',
            'body'  => [
                'script' => 'ctx._source.counter += count',
                'params' => [
                    'count' => 4
                ]
            ]
        ];

        $response = $this->cache()->update($params);
        var_dump($response);
    }

    public function upserts()
    {
        //Upserts 更新
        $params = [
            'index' => 'my_index',
            'id' => 'my_id',
            'body' => [
                'script' => 'ctx._source.counter += count',
                'params' => [
                    'count' => 4
                ],
                'upsert' => [
                    'counter' => 1
                ]
            ]
        ];

        $response = $this->cache()->update($params);
        var_dump($response);
    }

}