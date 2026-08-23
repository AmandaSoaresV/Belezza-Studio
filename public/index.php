<?php

require_once __DIR__ . '/../includes/app.php';

$page = $_GET['page'] ?? 'index';

switch ($page) {

    case 'api/agendamento':
    case 'api/agendamentos':
        require_once __DIR__ . '/../api/agendamento.php';
        exit;

    case 'api/servico':
    case 'api/servicos':
        require_once __DIR__ . '/../api/servico.php';
        exit;

    case 'api/dashboard':
        require_once __DIR__ . '/../api/dashboard.php';
        exit;

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
    
    case 'login':
        require_once __DIR__ . '/../app/views/login/login.php';
        break;

    case 'usuarios/cadastrar':
        require_once __DIR__ . '/../app/views/usuarios/cadastrar.php';
        break;

    case 'usuarios/editar':
        require_once __DIR__ . '/../app/views/usuarios/editar.php';
        break;

    case 'servicos':
        require_once __DIR__ . '/../app/views/servicos/index.php';
        break;

    case 'agendamentos/cadastrar':
        require_once __DIR__ . '/../app/views/agendamentos/cadastrar.php';
        break;

    case 'agendamentos/editar':
        require_once __DIR__ . '/../app/views/agendamentos/editar.php';
        break;

    case 'profissionais/cadastrar':
        require_once __DIR__ . '/../app/views/profissionais/cadastrar.php';
        break;

    default:
        require_once __DIR__ . '/../app/views/index/index.php';
        break;
}