<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/servicos', function(Request $request, Response $response) {
    $servicos = new \Model\Servicos();
    return $servicos->exibir($request, $response);
});

$app->get('/servicos/{quantidade}', function(Request $request, Response $response){
    $servicos = new \Model\Servicos();
    return $servicos->exibirPorQuantidade($request, $response);
});

$app->post('/servicos', function(Request $request, Response $response){
    $servicos = new \Model\Servicos();
    return $servicos->inserir($request, $response);
});

$app->put('/servicos', function(Request $request, Response $response){
    $servicos = new \Model\Servicos();
    return $servicos->alterar($request, $response);
});

$app->delete('/servicos', function(Request $request, Response $response){
    $servicos = new \Model\Servicos();
    return $servicos->excluir($request, $response);
});