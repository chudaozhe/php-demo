<?php
class Order extends ActiveRecord\Model
{
	// order belongs to a person
	static $belongs_to = array(
		array('person'));

	// order can have many payments by many people
	// the conditions is just there as an example as it makes no logical sense
	static $has_many = array(
		array('payments'),
		array('people',
			'through'    => 'payments',
			'select'     => 'people.*, payments.amount',
//			'conditions' => 'payments.amount < 200'
		));

}
