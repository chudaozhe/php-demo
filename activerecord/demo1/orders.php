<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// initialize ActiveRecord
ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory(__DIR__ . '/models');
    $cfg->set_connections(array('development' => 'mysql://root:@docker-mysql/test'));

	// you can change the default connection with the below
    //$cfg->set_default_connection('production');
});



// using order has_many people through payments with options
// array('people', 'through' => 'payments', 'select' => 'people.*, payments.amount', 'conditions' => 'payments.amount < 200'));
// this means our people in the loop below also has the payment information since it is part of an inner join
// we will only see 2 of the people instead of 3 because 1 of the payments is greater than 200
$order = Order::find([2]);
echo "Order #$order->id for $order->item_name ($$order->price + $$order->tax tax)\n";

foreach ($order->people as $person)
	echo "  payment of $$person->amount by " . $person->name . "\n";
?>
