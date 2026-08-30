<?php

if (!defined('BASE_URL')) {
    $normalizarCaminho = static fn(string $caminho): string => str_replace('\\', '/', $caminho);

    $pastaPublic = $normalizarCaminho((string) realpath(__DIR__ . '/../public'));
    $raizApache  = $normalizarCaminho($_SERVER['DOCUMENT_ROOT'] ?? '');

    $siteDentroDoApache = $pastaPublic !== ''
        && $raizApache !== ''
        && str_starts_with($pastaPublic, $raizApache);

    if ($siteDentroDoApache) {
        $caminhoWeb = substr($pastaPublic, strlen($raizApache));
        define('BASE_URL', rtrim($caminhoWeb, '/'));
    } else {
        define('BASE_URL', '');
    }
}

function url(string $pagina = ''): string
{
    $pagina = trim($pagina, '/');

    if ($pagina === '') {
        return BASE_URL . '/';
    }

    return BASE_URL . '/' . $pagina;
}

function asset(string $arquivo): string
{
    return url('assets/' . ltrim($arquivo, '/'));
}

function converterPrecoParaDecimal(string $preco): ?float
{
    $preco = trim($preco);

    if ($preco === '') {
        return null;
    }

    $preco = str_replace('.', '', $preco);
    $preco = str_replace(',', '.', $preco);

    if (!is_numeric($preco)) {
        return null;
    }

    return (float) $preco;
}

function mensagensDeRetorno(array $parametros, array $definicoes): array
{
    $mensagens = [];

    foreach ($definicoes as $chave => $definicao) {
        if (!isset($parametros[$chave])) {
            continue;
        }

        $valor = (string) $parametros[$chave];
        $plural = ((int) $valor) === 1 ? '' : 's';

        $mensagens[] = [
            'tipo' => $definicao['tipo'],
            'texto' => str_replace(['{valor}', '{plural}'], [$valor, $plural], $definicao['texto']),
        ];
    }

    return $mensagens;
}
