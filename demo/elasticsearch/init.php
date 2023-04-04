<?php

namespace demo\elasticsearch;

trait init {
    public function cache($name = 'default') {
        $cache = [];
        if (!isset($cache[$name])) {
            $cache[$name] = \Elasticsearch\ClientBuilder::create()->setHosts(['http://elasticsearch:9200'])->build();
        }
        return $cache[$name];
    }
}