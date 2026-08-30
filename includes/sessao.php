<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function usuarioLogado(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function estaLogado(): bool
{
    return usuarioLogado() !== null;
}

function ehAdmin(): bool
{
    $usuario = usuarioLogado();

    return $usuario !== null && $usuario['tipo_perfil'] === 'admin';
}

function entrarNaSessao(array $usuario): void
{
    session_regenerate_id(true);

    $_SESSION['usuario'] = [
        'id_usuario' => $usuario['id_usuario'],
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
        'tipo_perfil' => $usuario['tipo_perfil'],
    ];
}

function sairDaSessao(): void
{
    $_SESSION = [];
    session_destroy();
}

function paginaInicialDoPerfil(string $tipoPerfil): string
{
    return $tipoPerfil === 'admin' ? '/dashboard' : '/seushorarios';
}

function destinoInterno(string $destino): ?string
{
    if ($destino === '' || !str_starts_with($destino, '/') || str_starts_with($destino, '//')) {
        return null;
    }

    return $destino;
}
