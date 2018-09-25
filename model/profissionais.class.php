<?php

namespace Model;
use PDO;
class Profissionais /*extends Pessoas*/ {
    private $nome;
    private $sobrenome;
    private $fantasia;

    protected $id;
	protected $nascimento;
    protected $email;
    protected $telefone;
    protected $endereco;
    protected $numero;
    protected $complemento;
    protected $bairro;
    protected $cidade;
    protected $cep;
	protected $senha;
	protected $tipo;

    public function __construct(){
        //parent::__construct();
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
    
	/**
	 * Get the value of tipo
	 */ 
	public function getTipo()
	{
		return $this->tipo;
	}

	/**
	 * Set the value of tipo
	 *
	 * @return  self
	 */ 
	public function setTipo($tipo)
	{
		$this->tipo = $tipo;

		return $this;
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



    public function exibir($request, $response){
        if($request->getParam('id') > 0)
            $this->id = $request->getParam('id');
        
        //$pdo = \Model\Database::conexao();
        //$stmt = $pdo->prepare('SELECT * FROM `pessoa` INNER JOIN `profissional` ON pessoa.ID_PESSOA = profissional.ID_PESSOA INNER JOIN `cidade` on pessoa.ID_CIDADE = cidade.ID_CIDADE WHERE PESSOA.ID_PESSOA =:id or :id = 0');
        //$stmt->bindParam(":id",$this->id, PDO::PARAM_INT);
        //$stmt->execute();
        //$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        require __DIR__ . '/../src/database.php';
        if($this->getId() == 0)
            $result = $database->select('pessoa',["[><]profissional" => ["pessoa.ID_PESSOA" => "ID_PESSOA"]],'*');
        else
            $result = $database->select('pessoa',["[><]profissional" => ["pessoa.ID_PESSOA" => "ID_PESSOA"]],'*',["PESSOA.ID_PESSOA" => $this->id]);

        $rows = count($result);
        for ($i=0; $i<$rows;$i++){
            //$stmt = $pdo->prepare("SELECT * FROM CIDADE WHERE ID_CIDADE = :id");
            //$stmt->bindParam(":id",$result[$i]["ID_CIDADE"], PDO::PARAM_INT);
            //$stmt->execute();
            //$result2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $result2 = $database->select('CIDADE', '*',['id_cidade'=>$result[$i]["id_cidade"]]);
            $result[$i]["cidade"] = $result2[0];
        }
        return $response->withJson(["profissionais" => $result],200,JSON_UNESCAPED_UNICODE);
    }

    public function exibirPorQuantidade($request, $response){
        $quant = $request->getAttribute('route')->getArgument('quantidade');
        /*
        $pdo = \Model\Database::conexao();
        $stmt = $pdo->prepare("SELECT * FROM `pessoa` INNER JOIN `profissional` ON pessoa.ID_PESSOA = profissional.ID_PESSOA INNER JOIN `cidade` on pessoa.ID_CIDADE = cidade.ID_CIDADE  LIMIT :quant");
        $stmt->bindParam(":quant",$quant, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        */
        require __DIR__ . '/../src/database.php';
        $result = $database->select('pessoa',["[><]profissional" => ["pessoa.ID_PESSOA" => "ID_PESSOA"]],'*',["LIMIT" => $quant]);

        $rows = count($result);
        for ($i=0; $i<$rows;$i++){
            /*
            $stmt = $pdo->prepare("SELECT * FROM CIDADE WHERE ID_CIDADE = :id");
            $stmt->bindParam(":id",$result[$i]["ID_CIDADE"], PDO::PARAM_INT);
            $stmt->execute();
            $result2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            */
            $result2 = $database->select('CIDADE', '*',['id_cidade'=>$result[$i]["id_cidade"]]);
            $result[$i]["cidade"] = $result2[0];
        }
        return $response->withJson(["profissionais" => $result],200,JSON_UNESCAPED_UNICODE);
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
        /*
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
        */
        require __DIR__ . '/../src/database.php';
        $database->insert("pessoa", [
            "EMAIL" => $this->email,
            "TELEFONE" => $this->telefone,
            "ENDERECO" => $this->endereco,
            "NUMERO" => $this->numero,
            "COMPLEMENTO" => $this->complemento,
            "BAIRRO" => $this->bairro,
            "ID_CIDADE" => $this->cidade->getId(),
            "CEP" => $this->cep,
            "SENHA" => $this->senha
        ]);

        $this->setId($database->id());

        $database->insert("profissional", [
            "ID_PESSOA" => $this->id,
            "NOME" => $this->nome,
            "SOBRENOME" => $this->sobrenome,
            "FANTASIA" => $this->fantasia
        ]);

        return $response->withJson(
            [
                "erro" => false,
                "ID" => $this->getId(),
                "msg" => "Profissional ".$this->nome." inserido com sucesso."
            ],201,JSON_UNESCAPED_UNICODE
        );
    }

    public function alterar($request, $response){
        $this->id = $request->getParam('id');
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
        
        require __DIR__ . '/../src/database.php';
        $query = $database->update('pessoa',[
            "EMAIL" => $this->email,
            "TELEFONE" => $this->telefone,
            "ENDERECO" => $this->endereco,
            "NUMERO" => $this->numero,
            "COMPLEMENTO" => $this->complemento,
            "BAIRRO" => $this->bairro,
            "ID_CIDADE" => $this->cidade->getId(),
            "CEP" => $this->cep,
            "SENHA" => $this->senha
        ],
        [
            'id_pessoa'=>$this->id
        ]);
        $row = $query->rowCount();

        $query = $database->update("profissional", [
            "NOME" => $this->nome,
            "SOBRENOME" => $this->sobrenome,
            "FANTASIA" => $this->fantasia
        ],
        [
            'id_pessoa'=>$this->id
        ]);

        if( $query->rowCount() > 0 or $row > 0 ) {
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
                ],404,JSON_UNESCAPED_UNICODE
            );
         }
    }

    public function excluir($request, $response){
        $this->id = $request->getParam('id');
        
        require __DIR__ . '/../src/database.php';
        $query = $database->delete('pessoa',["id_pessoa" => $this->id]);
        $query = $database->delete('Profissional',["id_pessoa" => $this->id]);

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
                ],404,JSON_UNESCAPED_UNICODE
            );
         }
    }
}