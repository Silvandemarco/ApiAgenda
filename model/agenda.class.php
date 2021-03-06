<?php

namespace Model;
use PDO;
use Medoo\Medoo;
use PHPMailer\PHPMailer\PHPMailer;
date_default_timezone_set('America/Sao_Paulo');
class Agenda {
    private $id;
    private $profissional;
    private $cliente;
    private $datetime;
    private $servicos;
    private $id_prof_serv;

    public function __construct(){
        $this->id = 0;
        $this->profissional = new Pessoas();
        $this->cliente = new Pessoas();
        $this->datetime = new \DateTime();
        $this->servicos = [];
        $this->id_prof_serv = [];
    }


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of profissional
     */ 
    public function getProfissional()
    {
        return $this->profissional;
    }
    /**
     * Set the value of profissional
     *
     * @return  self
     */ 
    public function setProfissional($profissional)
    {
        $this->profissional = $profissional;

        return $this;
    }

    /**
     * Get the value of cliente
     */ 
    public function getCliente()
    {
        return $this->cliente;
    }
    /**
     * Set the value of cliente
     *
     * @return  self
     */ 
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get the value of datatime
     */ 
    public function getDatatime()
    {
        return $this->datatime;
    }
    /**
     * Set the value of datatime
     *
     * @return  self
     */ 
    public function setDatatime($datatime)
    {
        $this->datatime = $datatime;

        return $this;
    }

    /**
     * Get the value of servicos
     */ 
    public function getServicos()
    {
        return $this->servicos;
    }
    /**
     * Set the value of servicos
     *
     * @return  self
     */ 
    public function setServicos($servicos)
    {
        $this->servicos = $servicos;

        return $this;
    }

    public function exibirHorasLivres($request, $response){
        //date_default_timezone_set('America/Sao_Paulo');
        // http://localhost/agenda?id_pessoa=1&data=2018-09-04&servico[]=1&servico[]=2
        if($request->getParam('id_pessoa') > 0)
            $this->profissional = $request->getParam('id_pessoa');

        if(!$request->getParam('data')  == NULL)
            $this->datatime = $request->getParam('data');
        else
            $this->datatime = date("dMY");

        if(!$request->getParam('servico')  == NULL)
            $this->id_prof_serv = $request->getParam('servico');

        if($request->getParam('id_pessoa') > 0 and count($this->id_prof_serv) > 0){
            require __DIR__ . '/../src/database.php';

            $horasTrabDia = $database->select('horas_dias_semana',['hora_inicial', 'hora_final'],["AND" => ["id_pessoa" => $this->profissional, "dia_semana" => Medoo::raw("DAYNAME('".$this->datatime."')")]]);
            $agendamentosDia = $database->select("vw_agendamentos",["hora_inicial", "hora_final"],["AND" =>["id_profissional" => $this->profissional,"data" => $this->datatime]]);
            $duracao = 0;
            for($i = 0;$i < count($this->id_prof_serv); $i++){
                $duracao += $database->select("servico","duracao",["id_servico" => $this->id_prof_serv[$i]])[0];
            }
            if($duracao == 0){
                $duracao = 1440;
            }

            $horas = [];
            $status = [];
            // Adiciona horas trab do dia no array
            for($i=0;$i<count($horasTrabDia);$i++){
                $horaInicial = strtotime($horasTrabDia[$i]["hora_inicial"]);
                $horaFinal = strtotime($horasTrabDia[$i]["hora_final"]);
                array_push($horas, $horaInicial, $horaFinal);
                array_push($status, true, false);
            }
            //adiciona horas agendadas no array
            for($i=0;$i<count($agendamentosDia);$i++){
                $horaInicial = strtotime($agendamentosDia[$i]["hora_inicial"]);
                $horaFinal = strtotime($agendamentosDia[$i]["hora_final"]);
                array_push($horas, $horaInicial, $horaFinal);
                array_push($status, false, true);
            }

            // Unique values
            $unique = array_unique($horas);

            // Duplicates
            $duplicates = array_diff_assoc($horas, $unique);

            // Get duplicate keys
            $duplicate_keys = array_keys(array_intersect($horas, $duplicates));

            // Deleta duplicado true
            for($i=0;$i<count($duplicate_keys);$i++){
                if($status[$duplicate_keys[$i]] == true){
                    unset($horas[$duplicate_keys[$i]]);
                    unset($status[$duplicate_keys[$i]]);
                }
            }

            // Retorna todos os valores do num array indexado numericamente.
            $horas = array_values($horas);
            $status = array_values($status);

            // Junta arrays
            $horas_Status = [];
            for($i=0;$i<count($horas);$i++){
                $array = ["hora" => $horas[$i], "status" => $status[$i]];
                array_push($horas_Status, $array);
            }

            // Ordena array
            sort($horas_Status);

            // Horas inicial e final livres
            $horas_livres = [];
            for($i=0;$i<count($horas_Status);$i++){
                $array = [];
                if($horas_Status[$i]["status"] == true){
                    $array = [
                        "hora_inicial" => $horas_Status[$i]["hora"],
                        "hora_final" => $horas_Status[$i+1]["hora"]
                    ];
                    array_push($horas_livres, $array);
                }
            }

            // Separar em grupos de duração
            $periodosLivres = [];
            for($i=0;$i<count($horas_livres);$i++){
                while(strtotime("+".$duracao." minutes",$horas_livres[$i]["hora_inicial"]) <= $horas_livres[$i]["hora_final"]){
                    array_push($periodosLivres, date("H:i:s",$horas_livres[$i]["hora_inicial"]));
                    $horas_livres[$i]["hora_inicial"] = strtotime("+".$duracao." minutes",$horas_livres[$i]["hora_inicial"]);
                }
            }

            //se dia for hoje, remove horas que já passou
            if(date("Y-m-d") == $this->datatime)
            {
                for($i=0;$i<count($periodosLivres);$i++)
                {
                    if(strtotime($periodosLivres[$i]) <= time())
                    {
                        array_splice($periodosLivres, $i,1);
                        $i--;
                    }
                }
            }

            $result = $periodosLivres;
            return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
        }
        else{
            $result = [
                "erro" => true,
                "msg" => "Informe o profissional e os serviços."
            ];
            return $response->withJson($result,400,JSON_UNESCAPED_UNICODE);
        }
    }

    public function inserir($request, $response){
        $json = $request->getBody();

        $data = json_decode($json);

        $prof = $data->id_profissional;
        $cli = $data->id_cliente;
        $this->datetime = new \DateTime($data->datetime);
        $this->id_prof_serv = $data->servico;

        require __DIR__ . '/../src/database.php';

        $query = $database->insert("agenda", [
            "id_profissional" => $prof,
            "id_cliente" => $cli,
            "datetime" => $this->datetime->format('Y-m-d H:i:s'),
            "status" => "A"
        ]);

        if($query->rowCount() > 0){
        
            $this->setId($database->id());
            for($i=0;$i<count($this->id_prof_serv);$i++){
                $query = $database->insert("servico_agenda", [
                    "id_servico" => $this->id_prof_serv[$i]->id_servico,
                    "id_agenda" => $this->getId()
                ]);
                if($query->rowCount() == 0){
                    break;
                }
            }
            if($query->rowCount() > 0){
                return $response->withJson(
                    [
                        "erro" => false,
                        "id" => $this->getId(),
                        "msg" => "Agendamento inserido com sucesso."
                    ],201,JSON_UNESCAPED_UNICODE
                );
            }
            else{
                $database->delete("agenda", [
                    "id_agenda" => $this->getId()
                ]);
                return $response->withJson(
                    [
                        "erro" => true,
                        "msg" => "Falha no agendamento."
                    ],404,JSON_UNESCAPED_UNICODE
                );
            }
        }
        else{
            return $response->withJson(
                [
                    "erro" => true,
                    "msg" => "Falha no agendamento."
                ],404,JSON_UNESCAPED_UNICODE
            );
        }
    }

    public function exibir($request, $response){
        $prof = 0;
        $data = "";
        if($request->getParam('pessoa') > 0)
			$prof = $request->getParam('pessoa');
		if($request->getParam('data') != NULL)
            $data = $request->getParam('data');

		require __DIR__ . '/../src/database.php';
		
        if($prof == 0 && $data == null)
            return $response->withJson(
                [
                    "erro" => true,
                    "msg" => "Falha na consulta."
                ],404,JSON_UNESCAPED_UNICODE
            );
        else{
            $result = $database->select('agenda','*',["agenda.id_profissional" => $prof, "agenda.datetime[>=]" => $data." 00:00:00", "agenda.datetime[<=]" => $data." 23:59:59"]);
        }
        $rows = count($result);
        for ($i=0; $i<$rows;$i++){
            $result2 = $database->select('servico_agenda',["[><]servico" => ["servico_agenda.id_servico" => "id_servico"]],["servico.id_servico","servico.descricao","servico.valor","servico.duracao"],['servico_agenda.id_agenda'=>$result[$i]["id_agenda"]]);
            $result[$i]["servicos"] = $result2[0];
        }
        for ($i=0; $i<$rows;$i++){ 
            $result2 = $database->select('pessoa','*',["pessoa.id_pessoa" => $result[$i]["id_cliente"]]);
            $result[$i]["cliente"] = $result2[0];
        }
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function exibirMes($request, $response){
        $cli = 0;
        $mes = 0;
        $ano = 0;
        $prof = 0;
        if($request->getParam('pessoa') > 0)
			$cli = $request->getParam('pessoa');
		if($request->getParam('mes') > 0)
            $mes = $request->getParam('mes');
        if($request->getParam('ano') > 0)
            $ano = $request->getParam('ano');
        if($request->getParam('profissional') > 0)
			$prof = $request->getParam('profissional');
        $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		require __DIR__ . '/../src/database.php';
		
        if($cli == 0 || $mes == 0 || $ano == 0)
            return $response->withJson(
                [
                    "erro" => true,
                    "msg" => "Falha na consulta."
                ],404,JSON_UNESCAPED_UNICODE
            );
        else{
            if($prof == 0)
                $result = $database->select('agenda','*',["agenda.id_cliente" => $cli, "agenda.datetime[>=]" => $ano."-".$mes."-01 00:00:00", "agenda.datetime[<=]" => $ano."-".$mes."-".$ultimo_dia." 23:59:59"]);
            else
            $result = $database->select('agenda','*',["agenda.id_profissional" => $prof, "agenda.id_cliente" => $cli, "agenda.datetime[>=]" => $ano."-".$mes."-01 00:00:00", "agenda.datetime[<=]" => $ano."-".$mes."-".$ultimo_dia." 23:59:59"]);
        }
        $rows = count($result);
        for ($i=0; $i<$rows;$i++){
            $result2 = $database->select('servico_agenda',["[><]servico" => ["servico_agenda.id_servico" => "id_servico"]],["servico.id_servico","servico.descricao","servico.valor","servico.duracao"],['servico_agenda.id_agenda'=>$result[$i]["id_agenda"]]);
            $result[$i]["servicos"] = $result2[0];
        }
        for ($i=0; $i<$rows;$i++){ 
            $result2 = $database->select('pessoa','*',["pessoa.id_pessoa" => $result[$i]["id_profissional"]]);
            $result[$i]["profissional"] = $result2[0];
        }
        for ($i=0; $i<$rows;$i++){ 
            $result2 = $database->select('pessoa','*',["pessoa.id_pessoa" => $result[$i]["id_cliente"]]);
            $result[$i]["cliente"] = $result2[0];
        }
        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);
    }

    public function cancelar($request, $response){

        if($request->getParam('id_agenda') > 0)
			$idAgenda = $request->getParam('id_agenda');
			$status = $request->getParam('status');


        require __DIR__ . '/../src/database.php';

        $query = $database->update("agenda", ["status" => $status],["id_agenda" => $idAgenda]);

        if($query->rowCount() > 0){
            //$enviaremail = mail("silvandemarco@gmail.com", "Teste", "teste teste", "From: webmaster@example.com");
            //echo $enviaremail;
            if($status == "C")
            {
                $agenda = $database->select('agenda','*',["id_agenda" => $idAgenda]);
                //print_r($agenda);
                $cliente = $database->select('pessoa','*',["id_pessoa" => $agenda[0]['id_cliente']]);
                //echo $agenda[0]['id_cliente'];
                $profissional = $database->select('pessoa','*',["id_pessoa" => $agenda[0]['id_profissional']]);
                //echo $agenda[0]['id_profissional'];
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.mail.yahoo.com';
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'silvandemarco@yahoo.com.br';
                $mail->Password = 's1Lv@N d3m@4C0*';
                $mail->Port = 587;

                $mail->setFrom('silvandemarco@yahoo.com.br');
                $mail->addReplyTo('silvandemarco@yahoo.com.br');
                $mail->addAddress($cliente[0]['email'], $cliente[0]['nome']);
                $mail->addAddress($profissional[0]['email'], $profissional[0]['nome']);
                //$mail->addCC('email@email.com.br', 'Cópia');
                //$mail->addBCC('email@email.com.br', 'Cópia Oculta')

                $mail->isHTML(true);
                $mail->Subject = 'Agendamento cancelado.';
                $mail->Body    = 'O agendamento marcado para '.$agenda[0]['datetime'].' foi cancelado.';
                //$mail->AltBody = 'Para visualizar essa mensagem acesse http://site.com.br/mail';
                //$mail->addAttachment('/tmp/image.jpg', 'nome.jpg');
                /*
                if(!$mail->send()) {
                    echo 'Não foi possível enviar a mensagem.<br>';
                    echo 'Erro: ' . $mail->ErrorInfo;
                } else {
                    echo 'Mensagem enviada.';
                }
                */
            }

            return $response->withJson(
                [
                    "erro" => false,
                    "msg" => "Agendamento alterado."
                ],200,JSON_UNESCAPED_UNICODE
            );
            
        }
        else{
            return $response->withJson(
                [
                    "erro" => true,
                    "msg" => "Falha no cancelamento."
                ],404,JSON_UNESCAPED_UNICODE
            );
        }
    }

    public function meses($request, $response){
        $Cliente = 0;
        if($request->getParam('cliente') > 0)
            $Cliente = $request->getParam('cliente');
        
        require __DIR__ . '/../src/database.php';

        if($Cliente > 0){
            //$query = $database->select("agenda", ['ultimo' => Medoo::raw("MAX(datetime)"),'primeiro' => Medoo::raw("MIN(datetime)")],["id_cliente" => $Cliente]);
            $query = $database->pdo->prepare("SELECT MONTH(datetime) as mes, year(datetime) as ano FROM `agenda` WHERE id_cliente = :cliente GROUP BY  MONTH(datetime), year(datetime)");
            $query->bindParam(':cliente', $Cliente, PDO::PARAM_INT);
            
            $query->execute();
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $response->withJson($result,200,JSON_UNESCAPED_UNICODE);

    }
}