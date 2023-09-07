<?php


namespace wfm;


trait TSingleton {

    private static ?self $instance = null; // "?self" - запись говорит, что здесь может быть экземпляр класса или "null"

    private function __construct () {
    }

    public static function getInstance ()/*: static*/
    {
        return static::$instance ?? static::$instance = new static();
    }



}