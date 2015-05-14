<?php

class Storage {

    private static $storage;
    private $data;

    public static function factory(){
        if(!self::$storage)
            self::$storage = new \Storage;
        return self::$storage;
    }

    public function insert($dbase, $key, $value){
        $this->data[$dbase][$key] = $value;
        return true;
    }

    public function select($dbase, $key){
        if(!isset($this->data[$dbase][$key]))
            return false;
        return $this->data[$dbase][$key];
    }

    public function delete($dbase, $key){
        if(isset($this->data[$dbase][$key]))
            unset($this->data[$dbase][$key]);
        return true;
    }

}
