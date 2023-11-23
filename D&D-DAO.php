<?php
class DD_DAO {
    protected $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }
}

?>