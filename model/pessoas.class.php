<?php

namespace Model;
use PDO;
class Pessoas {
	protected $id;
	protected $nome;
	protected $sobrenome;
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
		$this->nome = "";
		$this->sobrenome = "";
		$this->nascimento = "";
		$this->tipo = "";
	}
	
	/**
	 * Get the value of nome
	 */ 
	public function getNome()
	{
		return $this->nome;
	}

	/**
	 * Set the value of nome
	 *
	 * @return  self
	 */ 
	public function setNome($nome)
	{
		$this->nome = $nome;

		return $this;
	}

	/**
	 * Get the value of sobrenome
	 */ 
	public function getSobrenome()
	{
		return $this->sobrenome;
	}

	/**
	 * Set the value of sobrenome
	 *
	 * @return  self
	 */ 
	public function setSobrenome($sobrenome)
	{
		$this->sobrenome = $sobrenome;

		return $this;
	}

	/**
	 * Get the value of nascimento
	 */ 
	public function getNascimento()
	{
		return $this->nascimento;
	}

	/**
	 * Set the value of nascimento
	 *
	 * @return  self
	 */ 
	public function setNascimento($nascimento)
	{
		$this->nascimento = $nascimento;

		return $this;
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

	public function inserirLogin($request, $response){
		$json = $request->getBody();

		$data = json_decode($json);
		
        $this->email = $data->email;
		$this->senha=$data->senha;
		
		require __DIR__ . '/../src/bCrypt.php';

		$hash = \Bcrypt::hash($this->senha);

		require __DIR__ . '/../src/database.php';

		return $response->withJson($hash,200,JSON_UNESCAPED_UNICODE);
	}

	public function validaLogin($request, $response){
		$json = $request->getBody();

		$data = json_decode($json);
		
        $this->email = $data->email;
		$this->senha = $data->senha;
		
		require __DIR__ . '/../src/bCrypt.php';
		require __DIR__ . '/../src/database.php';

		//$hash = '$2a$08$MTgxNjQxOTEzMTUwMzY2OOc15r9yENLiaQqel/8A82XLdj.OwIHQm'; // Valor retirado do banco
		$result = $database->select('pessoa',['id_pessoa','senha'],["email" => $this->email]);
		$hash = $result[0]['senha'];

		if (\Bcrypt::check($this->senha, $hash)) {
			return $response->withJson(true,200,JSON_UNESCAPED_UNICODE);
		} else {
			return $response->withJson(false,401,JSON_UNESCAPED_UNICODE);
		}
	}

	public function validaEmail($request, $response){
		if(!empty($request->getParam('email'))){
			$this->email = $request->getParam('email');

			require __DIR__ . '/../src/database.php';

			if(filter_var($this->email, FILTER_VALIDATE_EMAIL)){
				$result = $database->select('pessoa','email',["email" => $this->email]);

				if(count($result) == 0)	
					return $response->withJson(true,200,JSON_UNESCAPED_UNICODE);
				else
					return $response->withJson(false,200,JSON_UNESCAPED_UNICODE);
			}
			else{
				return $response->withJson(false,200,JSON_UNESCAPED_UNICODE);
			}
		}
		else{
			return $response->withJson(false,200,JSON_UNESCAPED_UNICODE);
		}
	}

	public function inserir($request, $response){
		$json = $request->getBody();

		$data = json_decode($json);
		
        $this->telefone = $data->telefone;
        $this->endereco = $data->endereco;
        $this->numero = $data->numero;
        $this->complemento = $data->complemento;
        $this->bairro = $data->bairro;
        $id_cidade = $data->id_cidade;
        $this->cep = $data->cep;
        $this->email = $data->email;
		$this->nome = $data->nome;
		$this->sobrenome = $data->sobrenome;
		$this->nascimento = $data->nascimento;
		$this->tipo = $data->tipo;
		$this->senha = $data->senha;
		
		require __DIR__ . '/../src/bCrypt.php';
		require __DIR__ . '/../src/database.php';

		$hash = \Bcrypt::hash($this->senha);

		$query = $database->insert("pessoa", [
			"email" => $this->email,
			"telefone" => $this->telefone,
			"endereco" => $this->endereco,
			"numero" => $this->numero,
			"complemento" => $this->complemento,
			"bairro" => $this->bairro,
			"id_cidade" => $id_cidade,
			"cep" => $this->cep,
			"nome" => $this->nome,
			"sobrenome" => $this->sobrenome,
			"nascimento" => $this->nascimento,
			"tipo" => $this->tipo,
			"senha" => $hash
		]);

		$this->setId($database->id());

		if($query->rowCount() > 0){

			return $response->withJson(
				[
					"erro" => false,
					"ID" => $this->getId(),
					"msg" => "Pessoa ".$this->nome." inserido com sucesso."
				],201,JSON_UNESCAPED_UNICODE
			);
		
		}
		else{
			return $response->withJson(
				[
					"erro" => true,
					"msg" => "Falha ao inserir a pessoa."
				],404,JSON_UNESCAPED_UNICODE
			);
		}
    }

	public function exibirHorasDia($request, $response){
		if(!empty($request->getParam('pessoa')))
			$this->id = $request->getParam('pessoa');
		if(!empty($request->getParam('dia')))
			$dia = $request->getParam('dia');

		require __DIR__ . '/../src/database.php';
		$result = $database->select('horas_dias_semana', ['id_pessoa','dia_semana','hora_inicial','hora_Final'],['id_pessoa'=>$this->id,'dia_semana'=>$dia]);

		return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
	}

	public function inserirHorasDia($request, $response){
		$json = $request->getBody();

		$data = json_decode($json);

		//var_dump($data[0]);
		$linhas = 0;
		for($i = 0; $i<count($data); $i++){
			$this->id = $data[$i]->id_pessoa;
			$dia = $data[$i]->dia_semana;
			$horaInicial = $data[$i]->hora_inicial;
			$horaFinal = $data[$i]->hora_final;

			require __DIR__ . '/../src/database.php';

			$query = $database->insert('horas_dias_semana', 
			[
				'id_pessoa' => $this->id,
				'dia_semana' => $dia,
				'hora_inicial' => $horaInicial,
				'hora_Final'=> $horaFinal
			]);
			if($query->rowCount() > 0){
				$linhas++;
			}
		}

		if($linhas > 0){

			return $response->withJson(
				[
					"erro" => false,
					"msg" => $linhas." períodos de horas inseridas com sucesso."
				],201,JSON_UNESCAPED_UNICODE
			);
		
		}
		else{
			return $response->withJson(
				[
					"erro" => true,
					"msg" => "Falha ao inserir as horas."
				],404,JSON_UNESCAPED_UNICODE
			);
		}
	}

	public function deletarHorasDia($request, $response){
		// $json = $request->getBody();

		// $data = json_decode($json);

		// $this->id = $data->id_pessoa;
		// $dia = $data->dia_semana;

		if(!empty($request->getParam('pessoa')))
			$this->id = $request->getParam('pessoa');
		if(!empty($request->getParam('dia')))
			$dia = $request->getParam('dia');
		
		require __DIR__ . '/../src/database.php';

		$query = $database->delete('horas_dias_semana', ['id_pessoa'=>$this->id,'dia_semana'=>$dia]);

		if($query->rowCount() > 0){

			return $response->withJson(
				[
					"erro" => false,
					"msg" => "Horas excluidas com sucesso."
				],201,JSON_UNESCAPED_UNICODE
			);
		
		}
		else{
			return $response->withJson(
				[
					"erro" => true,
					"msg" => "Falha ao excluir as horas."
				],404,JSON_UNESCAPED_UNICODE
			);
		}
	}

	public function exibirDias($request, $response){
		if(!empty($request->getParam('pessoa')))
			$this->id = $request->getParam('pessoa');

		$dias = [
			[
				"dia" => "Sunday", 
				"status" => false
			],
			[
				"dia" => "Monday", 
				"status" => false
			],
			[
				"dia" => "Tuesday", 
				"status" => false
			],
			[
				"dia" => "Wednesday", 
				"status" => false
			],
			[
				"dia" => "Thursday", 
				"status" => false
			],
			[
				"dia" => "Friday", 
				"status" => false
			],
			[
				"dia" => "Friday", 
				"status" => false
			]
		];
		require __DIR__ . '/../src/database.php';
		for($i = 0;$i<count($dias); $i++){
			$result = $database->select('horas_dias_semana', ['id_pessoa','dia_semana','hora_inicial','hora_Final'],['id_pessoa'=>$this->id,'dia_semana'=>$dias[$i]["dia"]]);
			if(count($result)>0){
				$dias[$i]["status"] = true;
			}
		}
		return $response->withJson($dias,200,JSON_UNESCAPED_UNICODE);
	}

	public function exibirPessoa($request, $response){

        if($request->getParam('id') > 0)
			$this->id = $request->getParam('id');
		if($request->getParam('email') != NULL)
            $this->email = $request->getParam('email');
		if($request->getParam('tipo') != NULL)
			$this->tipo = $request->getParam('tipo');
			
		require __DIR__ . '/../src/database.php';

		// $result = $database->select('pessoa','*',
		// [
		// 	"OR" => [
		// 		"pessoa.id_pessoa" => $this->id,
		// 		$this->id => 0
		// 	],
		// 	"OR" => [
		// 		"pessoa.email" => $this->email,
		// 		$this->email => ""
		// 	],
		// 	"OR" => [
		// 		"pessoa.tipo" => $this->tipo,
		// 		$this->tipo => ""
		// 	]
		// ]);
		if($this->tipo == null){
			if($this->getId() == 0 && $this->email == null)
				$result = $database->select('pessoa','*');
			elseif($this->email == null)
				$result = $database->select('pessoa','*',["pessoa.id_pessoa" => $this->id]);
			elseif($this->id == 0)
				$result = $database->select('pessoa','*',["pessoa.email" => $this->email]);
		}
		else{
			$result = $database->select('pessoa','*',["pessoa.tipo" => $this->tipo]);
		}
        
        $rows = count($result);
        for ($i=0; $i<$rows;$i++){
            $result2 = $database->select('cidade', '*',['id_cidade'=>$result[$i]["id_cidade"]]);
            $result[$i]["cidade"] = $result2[0];
        }
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function exibirPessoaPorQuantidade($request, $response){
        $quant = $request->getAttribute('route')->getArgument('quantidade');

        require __DIR__ . '/../src/database.php';
        $result = $database->select('pessoa','*',["LIMIT" => $quant]);

        $rows = count($result);
        for ($i=0; $i<$rows;$i++){
            $result2 = $database->select('CIDADE', '*',['id_cidade'=>$result[$i]["id_cidade"]]);
            $result[$i]["cidade"] = $result2[0];
        }
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }
}