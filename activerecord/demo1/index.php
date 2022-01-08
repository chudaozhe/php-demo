<?php
require_once __DIR__ . '/../../vendor/autoload.php';
//require_once __DIR__ . '/../../ActiveRecord.php';

$cfg = ActiveRecord\Config::instance();
$cfg->set_model_directory(__DIR__ . '/models');
$cfg->set_connections(
    array(
        'development' => 'mysql://root:@docker-mysql/test',
    )
);
$cfg->set_default_connection('development');
//echo $post->title; # 'My first blog post!!'
//echo $post->author_id; # 5
//$post = Post::first();
try {
    $post = Article::find([1]);
    var_dump($post->attributes());
}catch (Exception $exception){
    var_dump($exception->getMessage());
}
//var_dump($post);