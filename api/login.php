<?php

session_start();

$login =trim($_POST['login'] ?? null); 

if (empty($login)) {
   echo "Preencha o campo de login.";
} else {
    echo "Login recebido: ";
}

    $_SESSION['login'] = $login;

    echo "<p> <a href='login.php'>Voltar ao login</a></p>"
?>