<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/profissionais', function(Request $request, Response $response) {
    $profissionais = new \Model\Profissionais();
    return $profissionais->exibir($request, $response);
});

$app->get('/profissionais/{quantidade}', function(Request $request, Response $response){
    $profissionais = new \Model\profissionais();
    return $profissionais->exibirPorQuantidade($request, $response);
});

$app->post('/profissionais', function(Request $request, Response $response){
    $profissionais = new \Model\profissionais();
    return $profissionais->inserir($request, $response);
});

$app->put('/profissionais', function(Request $request, Response $response){
    $profissionais = new \Model\profissionais();
    return $profissionais->alterar($request, $response);
});

$app->delete('/profissionais', function(Request $request, Response $response){
    $profissionais = new \Model\profissionais();
    return $profissionais->excluir($request, $response);
});