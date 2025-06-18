<?php

require_once 'Atendivel.php';

abstract class Pet implements Atendivel
{
    protected string $nome;
    protected string $especie;
    protected int $idade;

    public function __construct(string $nome, string $especie, int $idade) {
        $this->nome = $nome;
        $this->especie = $especie;
        $this->idade = $idade;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getEspecie(): string
    {
        return $this->especie;
    }

    abstract public function atender();

}