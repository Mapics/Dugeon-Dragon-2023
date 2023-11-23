<?php
class Monstre {
    protected $id;
    protected $name;
    protected $pv;
    protected $pa;
    protected $pd;
    protected $level;
    function __construct($name) {
        $this->name = $name;
        $this->pv = 100;
        $this->pa = 10;
        $this->pd = 10;
        $this->level = 1;
    }
}
?>