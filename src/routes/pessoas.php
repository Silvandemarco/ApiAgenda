<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/pessoas', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoas->exibirPessoa($request, $response);
});

$app->get('/recuperaSenha', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoas->recuperaSenha($request, $response);
});

$app->get('/pessoas/{quantidade}', function(Request $request, Response $response){
    $pessoas = new \Model\Pessoas();
    return $pessoas->exibirPessoaPorQuantidade($request, $response);
});

$app->get('/clientes', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoas->exibirCliente($request, $response);
});

$app->post('/pessoas', function(Request $request, Response $response){
    $pessoas = new \Model\Pessoas();
    return $pessoas->inserir($request, $response);
});

$app->put('/pessoas', function(Request $request, Response $response){
    $pessoas = new \Model\Pessoas();
    return $pessoas->alterarDados($request, $response);
});

$app->put('/endereco', function(Request $request, Response $response){
    $pessoas = new \Model\Pessoas();
    return $pessoas->alterarEndereco($request, $response);
});

$app->put('/alterarSenha', function(Request $request, Response $response){
    $pessoas = new \Model\Pessoas();
    return $pessoas->alterarSenha($request, $response);
});

$app->delete('/pessoas', function(Request $request, Response $response){
    $profissionais = new \Model\profissionais();
    return $profissionais->excluir($request, $response);
});

$app->get('/validaemail', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoas->validaEmail($request, $response);
});

$app->get('/horasdia', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoas->exibirHorasDia($request, $response);
});

$app->post('/horasdia', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoas->inserirHorasDia($request, $response);
});

$app->delete('/horasdia', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoas->deletarHorasDia($request, $response);
});

$app->get('/diasSemana', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoas->exibirDias($request, $response);
});