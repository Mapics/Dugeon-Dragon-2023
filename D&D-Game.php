<?php
class Game {
    protected $ddDAO;
    protected $personnage;

    function __construct($ddDAO, $personnage) {
        $this->ddDAO = $ddDAO;
        $this->personnage = $personnage;
    }
}

?>
