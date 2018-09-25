<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/inserirLogin', function(Request $request, Response $response) {
    $login = new \Model\Pessoas();
    return $login->inserirLogin($request, $response);
});

$app->post('/validaLogin', function(Request $request, Response $response){
    $login = new \Model\Pessoas();
    return $login->validaLogin($request, $response);
});
/*
$app->get('/cidades/{quantidade}', function(Request $request, Response $response){
    $cidades = new \Model\cidades();
    return $cidades->exibirPorQuantidade($request, $response);
});

$app->put('/cidades', function(Request $request, Response $response){
    $cidades = new \Model\Cidades();
    return $cidades->alterar($request, $response);
});

$app->delete('/cidades', function(Request $request, Response $response){
    $cidades = new \Model\cidades();
    return $cidades->excluir($request, $response);
});
*/