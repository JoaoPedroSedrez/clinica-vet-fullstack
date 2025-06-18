<?php

require_once "Pet.php";

class Veterinario
{
    private string $nome;

    public function __construct(string $nome){
        $this->nome = $nome;
    }

    public function atenderPet(Pet $pet) {
        $registro = $pet->atender();
        return "O veterinário(a) {$this->nome} atendeu o {$pet->getnome()}: \n{$registro}";
    }

    public function getNome() {
        return $this->nome;
    }
}