<?php
class Salle{
    protected $id;
    protected $monstre;
    protected $piege;
    protected $marchand;

    function __construct($id) {
        $this->id = $id;
        $this->monstre = false;
        $this->piege = false;
        $this->marchand = false;
    }
}
?>