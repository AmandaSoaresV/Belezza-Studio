<?php
require_once __DIR__ . '/../../../includes/sessao.php';

sairDaSessao();

header('Location: /login?saiu=1');
exit;
