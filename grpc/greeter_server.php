<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Greeter extends Helloworld\GreeterStub
{
    public function SayHello(
        \Helloworld\HelloRequest $request,
        \Grpc\ServerContext $serverContext
    ): ?\Helloworld\HelloReply {
        $name = $request->getName();
        echo 'Received request: ' . $name . PHP_EOL;
        $response = new \Helloworld\HelloReply();
        $response->setMessage("Hello " . $name);
        return $response;
    }
}

$port = 50051;
$server = new \Grpc\RpcServer();
$server->addHttp2Port('0.0.0.0:'.$port);
$server->handle(new Greeter());
echo 'Listening on port :' . $port . PHP_EOL;
$server->run();
