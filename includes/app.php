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
