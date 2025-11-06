<?php

class ProdutoDTO {
    private $id;
    private $nome;
    private $valor;
    private $quantidade;
    private $descricao;

    public function __construct(
        ?int $id = null,
        ?string $nome = null,
        ?float $valor = null,
        ?int $quantidade = null,
        ?string $descricao = null
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->valor = $valor;
        $this->quantidade = $quantidade;
        $this->descricao = $descricao;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNome(): ?string {
        return $this->nome;
    }

    public function getValor(): ?float {
        return $this->valor;
    }

    public function getQuantidade(): ?int {
        return $this->quantidade;
    }

    public function getDescricao(): ?string {
        return $this->descricao;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function setValor(float $valor): void {
        $this->valor = $valor;
    }

    public function setQuantidade(int $quantidade): void {
        $this->quantidade = $quantidade;
    }

    public function setDescricao(string $descricao): void {
        $this->descricao = $descricao;
    }
}