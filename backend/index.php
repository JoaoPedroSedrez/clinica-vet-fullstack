<?php

require_once 'Clinica.php';
require_once 'Veterinario.php';
require_once 'Cachorro.php';
require_once 'Gato.php';

# Procedimento padrão do PHP para APIrest
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(204);
    exit;
}

# Continuação do procedimento padrão
header("Access-Control-Allow-Origin: *"); # API aceita requisicões de qualquer domínio
header("Content-Type: application/json"); # Resposta da API será em json
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); # métodos que podem ser usados
header("Access-Control-Allow-Headers: Content-Type");

$clinica = new Clinica();

// Captura método e caminho
$method = $_SERVER['REQUEST_METHOD']; # Pega qual método está sendo usadom(POST, GET, PUT, DELETE)
$uri = $_SERVER['REQUEST_URI']; # caminho da URL e, se houver, os parâmetros de consulta (query string)
$uri = explode('?', $uri)[0]; # Remove a parte da "query string" da URL, ou seja, tudo depois do ?
$uri = trim($uri, '/'); # Remove as barras do início e do fim da url
$uri = explode('/', $uri); # Transforma a url separadas por / em um array

// procura o índice 'atendimentos' na URI
$index = array_search('atendimentos', $uri); # Procura 'atendimentos' dentro do array
if ($index === false) {
    http_response_code(404);
    echo json_encode(["erro" => "Rota não encontrada"]);
    exit;
}


// Rotas

// Verifica se a ação é limpar histórico (POST com ?acao=limpar)
if ($method === 'POST' && isset($_GET['acao']) && $_GET['acao'] === 'limpar') { # Verifica se é 'POST', se tem 'acao' na URL e se a 'acao' == 'limpar'
    echo json_encode(["mensagem" => $clinica->limparHistorico()]); # limpa o histórico e adiciona no json
    exit;
}


switch ($method) {
    case 'GET':
        parse_str($_SERVER['QUERY_STRING'], $params); # pega os parâmetros da query e coloca num array
        if (isset($params['id'])) { # Se tiver id vai buscar algo específico, se não tiver, busca todos
            $atendimento = $clinica->buscarAtendimentoPorId($params['id']);
            if ($atendimento) {
                echo json_encode($atendimento);
            } else {
                http_response_code(404);
                echo json_encode(["erro" => "Atendimento não encontrado"]);
            }
        } else {
            echo json_encode($clinica->listarHistorico());
        }
        break;

    case 'POST':
        $dados = json_decode(file_get_contents("php://input"), true); # le o json e transforma em array php
        if (!$dados || !isset($dados['veterinario'], $dados['tipo'], $dados['nome'], $dados['idade'])) { # valida se os dados estão corretos
            http_response_code(400);
            echo json_encode(["erro" => "Dados incompletos"]);
            exit;
        }

        $vet = new Veterinario($dados['veterinario']); #cria objeto
        $tipo = strtolower($dados['tipo']);
        $pet = ($tipo === 'cachorro')
            ? new Cachorro($dados['nome'], $dados['idade'])
            : (($tipo === 'gato')
                ? new Gato($dados['nome'], $dados['idade'])
                : null);

        $msg = $clinica->registrarAtendimento($vet, $pet); # chama o método que registra o atendimento e salva em um json
        echo json_encode(["mensagem" => $msg]);
        break;

    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $params); # busca qual id será editado
        $id = $params['id'] ?? null; # se nao achar, id é null

        $dados = json_decode(file_get_contents("php://input"), true); # converte php em json
        if (!$id || !isset($dados['mensagem'])) { # se tem id e se tem mensagem no corpo do array
            http_response_code(400);
            echo json_encode(["erro" => "ID ou mensagem ausente"]);
            exit;
        }

        $msg = $clinica->editarMensagemId($id, $dados['mensagem']);
        echo json_encode(["mensagem" => $msg]);
        break;

    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $params); # pega os parametros da query
        $id = $params['id'] ?? null;

        if (!$id) { # se não ter id, retorna bad request
            http_response_code(400);
            echo json_encode(["erro" => "ID ausente"]);
            exit;
        }

        $msg = $clinica->excluirAtendimento($id); # chama o método exlcuirAtendimento e exclui
        echo json_encode(["mensagem" => $msg]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["erro" => "Método não permitido"]);
        break;
}
