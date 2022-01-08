<?php
class Article extends ActiveRecord\Model
{
    static $belongs_to = array(
        array('category'));

}