<?php

namespace Model;
use PDO;
use Medoo\Medoo;
class Agenda {
    private $id;
    private $profissional;
    private $cliente;
    private $datatime;
    private $servicos;

    public function __construct(){
        $this->id = 0;
        $this->profissional = new Pessoas();
        $this->cliente = new Pessoas();
        $this->datatime = new \DateTime();
        $this->servicos = [];
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
        // http://localhost/agenda?id_pessoa=1&data=2018-09-04&servico[]=1&servico[]=2
        if($request->getParam('id_pessoa') > 0)
            $this->profissional = $request->getParam('id_pessoa');

        if(!$request->getParam('data')  == NULL)
            $this->datatime = $request->getParam('data');
        else
            $this->datatime = date("dMY");

        if(!$request->getParam('servico')  == NULL)
            $this->servicos = $request->getParam('servico');

        if($request->getParam('id_pessoa') > 0 and count($this->servicos) > 0){
            require __DIR__ . '/../src/database.php';

            $horasTrabDia = $database->select('horas_dias_semana',['hora_inicial', 'hora_final'],["AND" => ["id_pessoa" => $this->profissional, "dia_semana" => Medoo::raw("DAYNAME('".$this->datatime."')")]]);
            $agendamentosDia = $database->select("vw_agendamentos",["hora_inicial", "hora_final"],["AND" =>["id_profissional" => $this->profissional,"data" => $this->datatime]]);
            $duracao = 0;
            for($i = 0;$i < count($this->servicos); $i++){
                $duracao += $database->select("servico","duracao",["id_servico" => $this->servicos[$i]])[0];
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
}