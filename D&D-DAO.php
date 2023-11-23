<?php
class ddDAO {
    protected $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }
}

?>