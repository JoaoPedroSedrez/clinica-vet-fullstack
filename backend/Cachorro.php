<?php


require_once "Pet.php";

class Cachorro extends Pet
{

    public function __construct(string $nome, int $idade) {
        parent::__construct($nome, 'Cachorro', $idade);
    }

    public function atender() {
        return "O cachorro {$this->nome} foi atendido!";
    }


}