<?php

/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/4/2015
 * Time: 10:00 AM
 */
class ShoppingCard extends BaseObject
{

    public static function add($bookId)
    {
        $card = self::getChart();
        var_dump($card);
        $card[$bookId] = $bookId;
        self::store($card);
        $card = self::getChart();
        var_dump($card);
    }

    public static function remove($bookId)
    {
        $card = self::getChart();
        unset($card[$bookId]);
    }

    public static function getChart()
    {
        return isset($_SESSION['card']) && is_array($_SESSION['card']) ? $_SESSION['card'] : array();
    }

    public static function contains($bookId)
    {
        return array_key_exists($bookId, self::getChart());
    }

    public static function store(array $card)
    {
        $_SESSION['card'] = $card;
    }

    public static function size()
    {
        return sizeof(self::getChart());
    }
}