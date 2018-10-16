<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/agenda', function(Request $request, Response $response) {
    $agenda = new \Model\Agenda();
    return $agenda->exibirHorasLivres($request, $response);
});

$app->get('/agendaProfissional', function(Request $request, Response $response) {
    $agenda = new \Model\Agenda();
    return $agenda->exibir($request, $response);
});

$app->get('/agendaCliente', function(Request $request, Response $response) {
    $agenda = new \Model\Agenda();
    return $agenda->exibirMes($request, $response);
});

$app->get('/agendaMeses', function(Request $request, Response $response) {
    $agenda = new \Model\Agenda();
    return $agenda->meses($request, $response);
});

$app->post('/agenda', function(Request $request, Response $response){
    $agenda = new \Model\Agenda();
    return $agenda->inserir($request, $response);
});

$app->put('/cancelarAgendamento', function(Request $request, Response $response){
    $agenda = new \Model\Agenda();
    return $agenda->cancelar($request, $response);
});
/*
$app->get('/cidades/{quantidade}', function(Request $request, Response $response){
    $cidades = new \Model\cidades();
    return $cidades->exibirPorQuantidade($request, $response);
});



$app->delete('/cidades', function(Request $request, Response $response){
    $cidades = new \Model\cidades();
    return $cidades->excluir($request, $response);
});
*/