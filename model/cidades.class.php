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

    public function getId() {
        return $this->id;
    }  
    public function setId($_id) {
        $this->id= $_id;
    }

    public function getNome() {
        return $this->nome;
    } 
    public function setNome($name) {
        $this->nome= $name;
    }

    public function getUf() {
        return $this->uf;
    } 
    public function setUf($_uf) {
        $this->uf= $_uf;
    }

    public function getPais() {
        return $this->pais;
    }  
    public function setPais($_pais) {
        $this->pais= $_pais;
    }

    public function exibir($request, $response){
        if($request->getParam('id') > 0)
            $this->id = $request->getParam('id');

        require __DIR__ . '/../src/database.php';
        if($this->getId() == 0)
            $result = $database->select('CIDADE', '*');
        else
            $result = $database->select('CIDADE', '*',['id_cidade'=>$this->id]);

        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function exibirPorQuantidade($request, $response){
        $quant = $request->getAttribute('route')->getArgument('quantidade');
        require __DIR__ . '/../src/database.php';
        $stmt = $database->pdo->prepare("SELECT * FROM CIDADE LIMIT :quant");
        $stmt->bindParam(":quant",$quant, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function inserir($request, $response){
        $this->nome = $request->getParam('nome');
        $this->uf = $request->getParam('uf');
        $this->pais = $request->getParam('pais');
        /*
        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("INSERT INTO `cidade`(`NOME`, `UF`, `PAIS`) VALUES (:nome,:uf,:pais)");
        $stmt->bindParam(":nome",$this->nome);
        $stmt->bindParam(":uf",$this->uf);
        $stmt->bindParam(":pais",$this->pais);
        $stmt->execute();
        */
        require __DIR__ . '/../src/database.php';
        $database->insert("cidade", [
            "NOME" => $this->nome,
            "UF" => $this->uf,
            "PAIS" => $this->pais
        ]);

        return $response->withJson(
            [
                "erro" => false,
                "ID" => $database->id(),
                "msg" => "Cidade ".$this->nome." inserido com sucesso."
            ]
            ,201,JSON_UNESCAPED_UNICODE);
    }

    public function alterar($request, $response){
        $this->id = $request->getParam('id');
        $this->nome = $request->getParam('nome');
        $this->uf = $request->getParam('uf');
        $this->pais = $request->getParam('pais');
        /*
        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("UPDATE `cidade` SET `NOME`=:nome,`UF`=:uf,`PAIS`=:pais WHERE `ID_CIDADE`=:id");
        $stmt->bindParam(":id",$this->id, PDO::PARAM_INT);
        $stmt->bindParam(":nome",$this->nome);
        $stmt->bindParam(":uf",$this->uf);
        $stmt->bindParam(":pais",$this->pais);
        $stmt->execute();
        */
        require __DIR__ . '/../src/database.php';
        $query = $database->update('CIDADE',[
            "NOME" => $this->nome,
            "UF" => $this->uf,
            "PAIS" => $this->pais
        ],
        [
            'id_cidade'=>$this->id
        ]);

        if( $query->rowCount() > 0 ) {
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
                ,404,JSON_UNESCAPED_UNICODE
            );
         }
    }

    public function excluir($request, $response){
        $this->id = $request->getParam('id');
        /*
        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("DELETE FROM `cidade` WHERE `ID_CIDADE`=:id");
        $stmt->bindParam(":id",$this->id, PDO::PARAM_INT);
        $stmt->execute();
        */
        require __DIR__ . '/../src/database.php';
        $query = $database->delete('CIDADE',["ID_CIDADE" => $this->id]);

        if( $query->rowCount() > 0 ) {
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
                ,404,JSON_UNESCAPED_UNICODE
            );
         }
    }
}