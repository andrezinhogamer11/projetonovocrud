<?php

use App\Database\Mariadb;
use App\Models\Tarefa;
use App\Models\Usuario;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

require __DIR__ . './vendor/autoload.php'; // se der erro, use __DIR__ . '/vendor/autoload.php';


$app = AppFactory::create();
$banco = new Mariadb();
    


$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, function (
    Request $request,
    Throwable $expection,
    bool $diplayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write('{"error": "abacaxi com pimenta!"}');
    return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
});
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('<a href="/hello/world">Try /hello/world</a>');
    return $response;
});


$app->get('/usuario/{id}/tarefas', function (Request $request, Response $response, $args) use ($banco) {
    $user_id = $args['id'];
    $tarefa = new Tarefa($banco->getConnection());
    $tarefas = $tarefa->getAllByUser($user_id);
    $response->getBody()->write(json_encode($tarefas));
    return $response;
});

$app->post('/tarefa', function (Request $request, Response $response) use ($banco) {
    $body = $request->getParsedBody();

    try {
        $tarefa = new Tarefa($banco->getConnection());
        $tarefa->titulo = $body['titulo'] ?? '';
        $tarefa->descricao = $body['descricao'] ?? '';
        $tarefa->status = isset($body['status']) ? (bool)$body['status'] : false;
        $tarefa->user_id = $body['user_id'] ?? 0;

        if (empty($tarefa->titulo) || empty($tarefa->descricao) || empty($tarefa->user_id)) {
            throw new \Exception('Campos obrigatórios: titulo, descricao, user_id');
        }

        $tarefa->create();

        $response->getBody()->write(json_encode(['message' => 'Tarefa criada com sucesso']));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

$app->get('/tarefa/{id}', function (Request $request, Response $response, $args) use ($banco) {
    $tarefa = new Tarefa($banco->getConnection());
    $resultado = $tarefa->getTarefaById((int)$args['id']);

    if ($resultado) {
        $response->getBody()->write(json_encode($resultado));
        return $response->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(['error' => 'Tarefa não encontrada']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
});

$app->put('/tarefa/{id}', function (Request $request, Response $response, array $args) use ($banco) {
    $body = json_decode($request->getBody()->getContents(), true);
    $id = $args['id'];

    try {
        $tarefa = new Tarefa($banco->getConnection());
        $tarefa->id = $id;
        $tarefa->titulo = $body['titulo'] ?? '';
        $tarefa->descricao = $body['descricao'] ?? '';
        $tarefa->user_id = $body['user_id'] ?? '';
        $tarefa->status = isset($body['status']) ? (bool)$body['status'] : false;


        if (empty($tarefa->titulo) || empty($tarefa->descricao) || empty($tarefa->user_id)) {
            throw new \Exception("Campos obrigatórios: titulo e descricao");
        }

        if ($tarefa->update()) {
            $response->getBody()->write(json_encode(['message' => 'Tarefa atualizada com sucesso']));
        } else {
            $response->getBody()->write(json_encode(['error' => 'Tarefa não encontrada ou não atualizada']));
        }

        return $response->withHeader('Content-Type', 'application/json');

    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});



$app->delete('/tarefa/{id}', function (Request $request, Response $response, $args) use ($banco) {
    $tarefa = new Tarefa($banco->getConnection());

    $sucesso = $tarefa->delete((int)$args['id']);

    if ($sucesso) {
        $response->getBody()->write(json_encode(['message' => 'Tarefa excluída com sucesso']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(['error' => 'Erro ao excluir a tarefa']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
});




$app->get('/usuario/{id}', function (Request $request, Response $response, $args) use ($banco) {
    $user_id = $args['id'];
    $usuario = new Usuario($banco->getConnection());
    $usuarios = $usuario->getByuserID($user_id) ?? [];
  
    $response->getBody()->write(json_encode($usuarios));
    return $response->withHeader('Content-Type','application/json');
});


$app->delete('/usuario/{id}', function (Request $request, Response $response, $args) use ($banco) {
  $user_id = $args['id'];
  $usuario = new Usuario($banco->getConnection());
  $usuarios = $usuario->deleteUser($user_id) ?? [];

  $response->getBody()->write(json_encode($usuarios));
  return $response->withHeader('Content-Type','application/json');
});




$app->post('/usuario',
  function (Request $request, Response $response, array $args) use ($banco){
    $campos_obrigatorios = ['nome',"login",'senha',"email"];
    $body = $request->getParsedBody();
    try{
      $usuario = new Usuario($banco->getConnection());
      $usuario->nome = $body["nome"] ?? '';
      $usuario->email = $body["email"] ?? '';
      $usuario->senha = $body["senha"] ?? '';
      $usuario->login = $body["login"] ?? '';
      $usuario->foto_path = $body["foto_path"] ?? '';
      foreach($campos_obrigatorios as $campo){
        if(empty($usuario->{$campo})){
          throw new \Exception("o campo {$campo} é obrigatório");
        };
      }
      $usuario->createUser();
    }catch(\Exception $e){
      $response->getBody()->write(json_encode(['massage' => $e->getMessage() ]));
      return $response->withHeader('Content-Type','application/json') ->withStatus(400);
    }
    $response->getBody()->write(json_encode([
      'message' => 'Usuario cadastrado com sucesso'
    ]));
    return $response->withHeader('Content-Type','application/json');
  });


  $app->put('/usuario/{id}',
  function (Request $request, Response $response, array $args) use ($banco){
    $campos_obrigatorios = ['nome',"login",'senha',"email"];
    $body = json_decode($request->getBody()->getContents(), true);
    
    
    try{
      $usuario = new Usuario($banco->getConnection());
      $usuario->id = $args['id'];
      $usuario->nome = $body["nome"] ?? '';
      $usuario->email = $body["email"] ?? '';
      $usuario->senha = $body["senha"] ?? '';
      $usuario->login = $body["login"] ?? '';
      $usuario->foto_path = $body["foto_path"] ?? '';
      foreach($campos_obrigatorios as $campo){
        if(empty($usuario->{$campo})){
          throw new \Exception("o campo {$campo} é obrigatório");
        };
      }
      $usuario->update();
    }catch(\Exception $e){
      $response->getBody()->write(json_encode(['massage' => $e->getMessage() ]));
      return $response->withHeader('Content-Type','application/json') ->withStatus(400);
    }
    $response->getBody()->write(json_encode([
      'message' => 'Usuario atualizado com sucesso'
    ]));
    return $response->withHeader('Content-Type','application/json');
  });








  $app->run();