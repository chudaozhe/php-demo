<?php

namespace demo\elasticsearch;

require_once __DIR__ . '/../../vendor/autoload.php';

//获取文档
class getting {
    use init;

    public function get()
    {
        $params = [
            'index' => 'my_index',
            'id'    => 'my_id'
        ];
        // Get doc at /my_index/_doc/my_id
        $response = $this->cache()->get($params);
        var_dump($response);
    }
}

$obj=new getting();
$obj->get();
