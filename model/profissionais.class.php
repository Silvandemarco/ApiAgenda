<?php

namespace Model;
use PDO;
class Profissionais extends Pessoas {
    private $nome;
    private $sobrenome;
    private $fantasia;

    public function __construct(){
        parent::__construct();
        $this->nome = "";
        $this->sobrenome = "";
        $this->fantasia = "";
    }

    public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getSobrenome(){
		return $this->sobrenome;
	}

	public function setSobrenome($sobrenome){
		$this->sobrenome = $sobrenome;
	}

	public function getFantasia(){
		return $this->fantasia;
	}

	public function setFantasia($fantasia){
		$this->fantasia = $fantasia;
	}

    public function exibir($request, $response){
        if($request->getParam('id') > 0)
            $this->id = $request->getParam('id');
        
        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare('SELECT * FROM `pessoa` INNER JOIN `profissional` ON pessoa.ID_PESSOA = profissional.ID_PESSOA INNER JOIN `cidade` on pessoa.ID_CIDADE = cidade.ID_CIDADE WHERE PESSOA.ID_PESSOA =:id or :id = 0');
        $stmt->bindParam(":id",$this->id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $rows = $stmt->rowCount();
        for ($i=0; $i<$rows;$i++){
            $stmt = $pdo->prepare("SELECT * FROM CIDADE WHERE ID_CIDADE = :id");
            $stmt->bindParam(":id",$result[$i]["ID_CIDADE"], PDO::PARAM_INT);
            $stmt->execute();
            $result2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $result[$i]["cidade"] = $result2;
        }
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function exibirPorQuantidade($request, $response){
        $quant = $request->getAttribute('route')->getArgument('quantidade');
        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("SELECT * FROM `pessoa` INNER JOIN `profissional` ON pessoa.ID_PESSOA = profissional.ID_PESSOA INNER JOIN `cidade` on pessoa.ID_CIDADE = cidade.ID_CIDADE  LIMIT :quant");
        $stmt->bindParam(":quant",$quant, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $rows = $stmt->rowCount();
        for ($i=0; $i<$rows;$i++){
            $stmt = $pdo->prepare("SELECT * FROM CIDADE WHERE ID_CIDADE = :id");
            $stmt->bindParam(":id",$result[$i]["ID_CIDADE"], PDO::PARAM_INT);
            $stmt->execute();
            $result2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $result[$i]["cidade"] = $result2;
        }
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function inserir($request, $response){
        $this->setEmail($request->getParam('email'));
        $this->setTelefone($request->getParam('telefone'));
        $this->setEndereco($request->getParam('endereco'));
        $this->setNumero($request->getParam('numero'));
        $this->setComplemento($request->getParam('complemento'));
        $this->setBairro($request->getParam('bairro'));
        $this->cidade->setId($request->getParam('idCidade'));
        $this->setCep($request->getParam('cep'));
        $this->setSenha($request->getParam('senha'));
        $this->setNome($request->getParam('nome'));
        $this->setSobrenome($request->getParam('sobrenome'));
        $this->setFantasia($request->getParam('fantasia')); 
        $idCidade = $this->cidade->getId();      

        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("INSERT INTO `pessoa`(`EMAIL`, `TELEFONE`, `ENDERECO`, `NUMERO`, `COMPLEMENTO`, `BAIRRO`, `ID_CIDADE`, `CEP`, `SENHA`) VALUES (:email,:telefone,:endereco,:numero,:complemento,:bairro,:idCidade,:cep,:senha)");
        $stmt->bindParam(":email",$this->email);
        $stmt->bindParam(":telefone",$this->telefone);
        $stmt->bindParam(":endereco",$this->endereco);        
        $stmt->bindParam(":numero",$this->numero, PDO::PARAM_INT);        
        $stmt->bindParam(":complemento",$this->complemento);        
        $stmt->bindParam(":bairro",$this->bairro);        
        $stmt->bindParam(":idCidade",$idCidade, PDO::PARAM_INT);        
        $stmt->bindParam(":cep",$this->cep);        
        $stmt->bindParam(":senha",$this->senha);
        $stmt->execute();
        $this->setId($pdo->lastInsertId());

        $stmt = $pdo->prepare("INSERT INTO `profissional`(`ID_PESSOA`, `NOME`, `SOBRENOME`, `FANTASIA`) VALUES (:id,:nome,:sobrenome,:fantasia)");
        $stmt->bindParam(":id",$this->id, PDO::PARAM_INT);        
        $stmt->bindParam(":nome",$this->nome);        
        $stmt->bindParam(":fantasia",$this->fantasia);        
        $stmt->bindParam(":sobrenome",$this->sobrenome);        
        $stmt->execute();

        return $response->withJson(
            [
                "erro" => false,
                "msg" => "Profissional ".$this->nome." inserido com sucesso."
            ]
        );
    }

    public function alterar($request, $response){
        $this->id = $request->getParam('id');
        $this->nome = $request->getParam('nome');
        $this->uf = $request->getParam('uf');
        $this->pais = $request->getParam('pais');
        
        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("UPDATE `cidade` SET `NOME`=:nome,`UF`=:uf,`PAIS`=:pais WHERE `ID_CIDADE`=:id");
        $stmt->bindParam(":id",$this->id, PDO::PARAM_INT);
        $stmt->bindParam(":nome",$this->nome);
        $stmt->bindParam(":uf",$this->uf);
        $stmt->bindParam(":pais",$this->pais);
        $stmt->execute();

        if( $stmt->rowCount() > 0 ) {
            return $response->withJson(
                [
                    "erro" => false,
                    "msg" => "Cidade ".$this->id." alterado com sucesso."
                ]
            );
         } else {
            return $response->withJson(
                [
                    "erro" => true,
                    "msg" => "Cidade ".$this->id." não alterada."
                ]
            );
         }
    }

    public function excluir($request, $response){
        $this->id = $request->getParam('id');

        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("DELETE FROM `cidade` WHERE `ID_CIDADE`=:id");
        $stmt->bindParam(":id",$this->id, PDO::PARAM_INT);
        $stmt->execute();

        if( $stmt->rowCount() > 0 ) {
            return $response->withJson(
                [
                    "erro" => false,
                    "msg" => "Cidade ".$this->id." excluido com sucesso."
                ]
            );
         } else {
            return $response->withJson(
                [
                    "erro" => true,
                    "msg" => "Cidade ".$this->id." não encontrada."
                ]
            );
         }
    }
}