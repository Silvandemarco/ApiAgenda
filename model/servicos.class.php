<?php

namespace Model;
use PDO;
class Servicos {
    private $id;
    private $profissional;
    private $descricao;
    private $valor;
    private $duracao;

    public function __construct(){
        $this->id = 0;
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
                $result = $database->select('servico','*',["servico.id_profissional" => $this->getProfissional()->getId()]);
            else
                $result = $database->select('servico','*',["servico.id_profissional" => $this->getProfissional()->getId(),"servico.id_servico" => $this->id]);
        }
        
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function inserir($request, $response){
		$json = $request->getBody();

		$data = json_decode($json);
		
        $this->profissional = $data->id_profissional;
        $this->descricao = $data->descricao;
        $this->valor = $data->valor;
        $this->duracao = $data->duracao;
		
		require __DIR__ . '/../src/bCrypt.php';
		require __DIR__ . '/../src/database.php';

		$query = $database->insert("servico", [
			"id_profissional" => $this->profissional,
			"descricao" => $this->descricao,
			"valor" => $this->valor,
			"duracao" => $this->duracao
		]);

		$this->setId($database->id());

		if($query->rowCount() > 0){

			return $response->withJson(
				[
					"erro" => false,
					"ID" => $this->getId(),
					"msg" => "Serviço ".$this->descricao." inserido com sucesso."
				],201,JSON_UNESCAPED_UNICODE
			);
		
		}
		else{
			return $response->withJson(
				[
					"erro" => true,
					"msg" => "Falha ao inserir o serviço."
				],404,JSON_UNESCAPED_UNICODE
			);
		}
	}
	
	public function alterar($request, $response){
		$json = $request->getBody();

		$data = json_decode($json);
		
        $this->profissional = $data->id_profissional;
        $this->descricao = $data->descricao;
        $this->valor = $data->valor;
        $this->duracao = $data->duracao;
        $this->id = $data->id_servico;
		
		require __DIR__ . '/../src/bCrypt.php';
		require __DIR__ . '/../src/database.php';

		$query = $database->update("servico", [
			"id_profissional" => $this->profissional,
			"descricao" => $this->descricao,
			"valor" => $this->valor,
			"duracao" => $this->duracao
		],
		[
			"id_servico" => $this->id
		]);

		//$this->setId($database->id());

		if($query->rowCount() > 0){

			return $response->withJson(
				[
					"erro" => false,
					"ID" => $this->id,
					"msg" => "Serviço ".$this->descricao." alterado com sucesso."
				],201,JSON_UNESCAPED_UNICODE
			);
		
		}
		else{
			return $response->withJson(
				[
					"erro" => true,
					"msg" => "Falha ao alterar o serviço."
				],201,JSON_UNESCAPED_UNICODE
			);
		}
	}
	
	public function excluir($request, $response){
        if(!empty($request->getParam('id_servico')))
			$this->id = $request->getParam('id_servico');
		
		require __DIR__ . '/../src/database.php';

		$query = $database->delete('servico',["id_servico" => $this->id]);

		if($query->rowCount() > 0){

			return $response->withJson(
				[
					"erro" => false,
					"msg" => "Serviço excluido com sucesso."
				],201,JSON_UNESCAPED_UNICODE
			);
		
		}
		else{
			return $response->withJson(
				[
					"erro" => true,
					"msg" => "Falha ao excluir o serviço."
				],404,JSON_UNESCAPED_UNICODE
			);
		}
    }
}