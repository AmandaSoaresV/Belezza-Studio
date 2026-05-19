<?php

$page = $_GET['page'] ?? 'index';

switch ($page) {

    case 'agendamento':
        require_once __DIR__ . '/../app/views/agendamento/agendamento.php';
        break;

    case 'dashboard':
        require_once __DIR__ . '/../app/views/dashboard/dashboard.php';
        break;

    case 'relatorio':
        require_once __DIR__ . '/../app/views/relatorio/relatorio.php';
        break;

    case 'seushorarios':
        require_once __DIR__ . '/../app/views/seushorarios/seushorarios.php';
        break;

    default:
        require_once __DIR__ . '/../app/views/index/index.php';
        break;
}