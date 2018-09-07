<?php

namespace Model;
use PDO;
class Servicos {
    private $id;
    private $nome;
    private $profissional;
    private $descricao;
    private $valor;
    private $duracao;

    public function __construct(){
        $this->id = 0;
        $this->nome = "";
        $this->profissional = new Profissionais();
        $this->descricao = "";
        $this->valor = "";
        $this->duracao = "";
    }
    
    public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}

	public function getNome(){
		return $this->nome;
	}
	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getProfissional(){
		return $this->profissional;
	}
	public function setProfissional($profissional){
		$this->profissional = $profissional;
	}

	public function getDescricao(){
		return $this->descricao;
	}
	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getValor(){
		return $this->valor;
	}
	public function setValor($valor){
		$this->valor = $valor;
	}

	public function getDuracao(){
		return $this->duracao;
	}
	public function setDuracao($duracao){
		$this->duracao = $duracao;
    }
    
    public function exibir($request, $response){
        if($request->getParam('id_servico') > 0)
            $this->id = $request->getParam('id_servico');

        if($request->getParam('id_profissional') > 0)
            $this->profissional->setId($request->getParam('id_profissional'));

        require __DIR__ . '/../src/database.php';

        if($this->getProfissional()->getId() == 0){
            if($this->getId() == 0)
                $result = $database->select('servico','*');
            else
                $result = $database->select('servico','*',["servico.id_servico" => $this->id]);
        }
        else{
            if($this->getId() == NULL)
                $result = $database->select('servico',["[><]prof_serv" => ["servico.id_servico" => "id_servico"]],'*',["prof_serv.id_profissional" => $this->getProfissional()->getId()]);
            else
                $result = $database->select('servico',["[><]prof_serv" => ["servico.id_servico" => "id_servico"]],'*',["prof_serv.id_profissional" => $this->getProfissional()->getId(),"servico.id_servico" => $this->id]);
        }
        
        return $response->withJson(["servicos" => $result],200,JSON_UNESCAPED_UNICODE);
    }

    
}