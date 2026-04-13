<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../singleton/DbSingleton.php';

class CertificazioniController
{
  public function index(Request $request, Response $response, $args){
    $mysqli = DbSingleton::getInstance();
    $result = $mysqli->query("SELECT * FROM certificazioni");
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    $response->getBody()->write(json_encode($rows));
    return $response->withHeader("Content-Type", "application/json")->withStatus(200);
  }

  public function show(Request $request, Response $response, $args){
    $mysqli = DbSingleton::getInstance();
    $stmt = $mysqli->prepare("SELECT * FROM certificazioni WHERE id = ?");
    $stmt->bind_param("s", $args["id"]);
    $stmt->execute();

    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);
    
    $response->getBody()->write(json_encode($data));
    return $response->withHeader("Content-Type", "application/json")->withStatus(200);
  }

  public function create(Request $request, Response $response, array $args){
    $data = json_decode((string)$request->getBody(), true);

    $mysqli = DbSingleton::getInstance();
    $stmt = $mysqli->prepare("INSERT INTO certificazioni (alunno_id, titolo, votazione, ente) VALUES (?, ?,?,?)");
    $stmt->bind_param("ssss", $data['alunno_id'], $data['titolo'], $data['votazione'], $data['ente']);
    $stmt->execute();

    $response->getBody()->write("CREATED");
    return $response->withStatus(201);
  }

  public function update(Request $request, Response $response, array $args){
    $data = json_decode((string)$request->getBody(), true);

    $mysqli = DbSingleton::getInstance();
    $stmt = $mysqli->prepare("UPDATE certificazioni SET titolo = ?, ente = ? WHERE id = ?");
    $stmt->bind_param("sss", $data['certificazioni'], $data['titolo'], $args['id']);
    $stmt->execute();

    $response->getBody()->write("UPDATED");
    return $response->withStatus(200);
  }

  public function destroy(Request $request, Response $response, array $args){
    $mysqli = DbSingleton::getInstance();
    $stmt = $mysqli->prepare("DELETE FROM certificazioni WHERE id = ?");
    $stmt->bind_param("s", $args['id']);
    $stmt->execute();

    $response->getBody()->write("DELETED");
    return $response->withStatus(200);
  }

}