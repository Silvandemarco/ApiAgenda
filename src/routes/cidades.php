<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/cidades', function(Request $request, Response $response) {
    $cidades = new \Model\cidades();
    return $cidades->exibir($request, $response);
});

$app->get('/cidades/{quantidade}', function(Request $request, Response $response){
    $cidades = new \Model\cidades();
    return $cidades->exibirPorQuantidade($request, $response);
});

$app->post('/cidades', function(Request $request, Response $response){
    $cidades = new \Model\Cidades();
    return $cidades->inserir($request, $response);
});

$app->put('/cidades', function(Request $request, Response $response){
    $cidades = new \Model\Cidades();
    return $cidades->alterar($request, $response);
});

$app->delete('/cidades', function(Request $request, Response $response){
    $cidades = new \Model\cidades();
    return $cidades->excluir($request, $response);
});