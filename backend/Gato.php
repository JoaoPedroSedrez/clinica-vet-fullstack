<?php

require_once "Pet.php";

class Gato extends Pet
{
    public function __construct(string $nome, int $idade) {
        parent::__construct($nome, 'Gato', $idade);
    }
    public function atender()
    {
        return "O gato {$this->nome} foi atendido!";
    }
}