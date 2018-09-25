<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/pessoas', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoa->exibir($request, $response);
});

$app->get('/pessoas/{quantidade}', function(Request $request, Response $response){
    $profissionais = new \Model\profissionais();
    return $profissionais->exibirPorQuantidade($request, $response);
});

$app->post('/pessoas', function(Request $request, Response $response){
    $pessoas = new \Model\Pessoas();
    return $pessoas->inserir($request, $response);
});

$app->put('/pessoas', function(Request $request, Response $response){
    $profissionais = new \Model\profissionais();
    return $profissionais->alterar($request, $response);
});

$app->delete('/pessoas', function(Request $request, Response $response){
    $profissionais = new \Model\profissionais();
    return $profissionais->excluir($request, $response);
});

$app->get('/validaemail', function(Request $request, Response $response) {
    $pessoas = new \Model\Pessoas();
    return $pessoas->validaEmail($request, $response);
});