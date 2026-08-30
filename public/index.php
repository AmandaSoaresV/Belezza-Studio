<?php

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/sessao.php';

$page = $_GET['page'] ?? 'index';

switch ($page) {

    case 'api/agendamento':
    case 'api/agendamentos':
        exigirAdminNaApi();
        require_once __DIR__ . '/../api/agendamento.php';
        exit;

    case 'api/servico':
    case 'api/servicos':
        require_once __DIR__ . '/../api/servico.php';
        exit;

    case 'api/dashboard':
        exigirAdminNaApi();
        require_once __DIR__ . '/../api/dashboard.php';
        exit;

    case 'agendamento':
        exigirLogin();
        require_once __DIR__ . '/../app/views/agendamento/agendamento.php';
        break;

    case 'dashboard':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/dashboard/dashboard.php';
        break;

    case 'relatorio':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/relatorio/relatorio.php';
        break;

    case 'seushorarios':
        exigirLogin();
        require_once __DIR__ . '/../app/views/seushorarios/seushorarios.php';
        break;
    
    case 'login':
        require_once __DIR__ . '/../app/views/login/login.php';
        break;

    case 'logout':
        require_once __DIR__ . '/../app/views/login/sair.php';
        break;

    case 'usuarios':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/usuarios/index.php';
        break;

    case 'usuarios/cadastrar':
        require_once __DIR__ . '/../app/views/usuarios/cadastrar.php';
        break;

    case 'usuarios/editar':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/usuarios/editar.php';
        break;

    case 'servicos':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/servicos/index.php';
        break;

    case 'servicos/cadastrar':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/servicos/cadastrar.php';
        break;

    case 'servicos/editar':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/servicos/editar.php';
        break;

    case 'agendamentos/cadastrar':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/agendamentos/cadastrar.php';
        break;

    case 'agendamentos/editar':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/agendamentos/editar.php';
        break;

    case 'profissionais/cadastrar':
        exigirAdmin();
        require_once __DIR__ . '/../app/views/profissionais/cadastrar.php';
        break;

    default:
        require_once __DIR__ . '/../app/views/index/index.php';
        break;
}