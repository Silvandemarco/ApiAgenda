<?php

namespace Model;
//use PDO;
class Pessoas {
    protected $id;
    protected $email;
    protected $telefone;
    protected $endereco;
    protected $numero;
    protected $complemento;
    protected $bairro;
    protected $cidade;
    protected $cep;
    protected $senha;

    public function __construct(){
        $this->id = 0;
        $this->email = "";
        $this->telefone = "";
        $this->endereco = "";
        $this->numero = 0;
        $this->complemento = "";
        $this->bairro = "";
        $this->cidade = new Cidades();
        $this->cep = "";
        $this->senha = "";
    }

    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getTelefone(){
		return $this->telefone;
	}

	public function setTelefone($telefone){
		$this->telefone = $telefone;
	}

	public function getEndereco(){
		return $this->endereco;
	}

	public function setEndereco($endereco){
		$this->endereco = $endereco;
	}

	public function getNumero(){
		return $this->numero;
	}

	public function setNumero($numero){
		$this->numero = $numero;
	}

	public function getComplemento(){
		return $this->complemento;
	}

	public function setComplemento($complemento){
		$this->complemento = $complemento;
	}

	public function getBairro(){
		return $this->bairro;
	}

	public function setBairro($bairro){
		$this->bairro = $bairro;
	}

	public function getCidade(){
		return $this->cidade;
	}

	public function setCidade($cidade){
		$this->cidade = $cidade;
	}

	public function getCep(){
		return $this->cep;
	}

	public function setCep($cep){
		$this->cep = $cep;
	}

	public function getSenha(){
		return $this->senha;
	}

	public function setSenha($senha){
		$this->senha = $senha;
	}
}