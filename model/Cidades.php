<?php

namespace Model;
use PDO;
class Cidades {
    private $id;
    private $nome;
    private $uf;
    private $pais;

    public function __construct(){
        $this->id = 0;
        $this->nome = "";
        $this->uf = "";
        $this->pais = "";
    }

    public function exibir($request, $response){
        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare('SELECT * FROM CIDADE');
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function exibirPorQuantidade($request, $response){
        $quant = $request->getAttribute('route')->getArgument('quantidade');
        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("SELECT * FROM CIDADE LIMIT :quant");
        $stmt->bindParam(":quant",$quant, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function inserir($request, $response){
        $this->nome = $request->getParam('nome');
        $this->uf = $request->getParam('uf');
        $this->pais = $request->getParam('pais');

        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("INSERT INTO `cidade`(`NOME`, `UF`, `PAIS`) VALUES (:nome,:uf,:pais)");
        $stmt->bindParam(":nome",$this->nome);
        $stmt->bindParam(":uf",$this->uf);
        $stmt->bindParam(":pais",$this->pais);
        $stmt->execute();

        return $response->withJson(
            [
                "erro" => false,
                "msg" => "Cidade ".$this->nome." inserido com sucesso."
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