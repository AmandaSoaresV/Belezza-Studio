<?php
   require_once __DIR__ . '/../../../config/conexao.php'; 
   session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
   <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
    rel="stylesheet"
      href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <link rel="stylesheet" href="/assets/css/global.css" />
    
</head>
<body>
    <?php
        $header = __DIR__ . '/../layouts/header.php'; 
     if (file_exists($header))
         { include $header; } 
     else { include __DIR__ . '/../erro/erro.php'; } ?>

    <div class="container mt-5 mb-5 ">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center">Login</h1>
                <form name="formLogin" method="POST" action="login.php" class="mt-4">
                    <div class="mb-3">
                        <label for="email" class="form-label">Digite o email</label>
                        <input type="email" class="form-control" id="email" placeholder="Seu email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" placeholder="Sua senha">
                    </div>
                    <button type="submit" class="btn-marca ">Entrar</button>
                </form>
            </div>
        </div>
    </div>


    <?php
        $footer = __DIR__ . '/../layouts/footer.php'; 
     if (file_exists($footer))
         { include $footer; } 
     else { include __DIR__ . '/../erro/erro.php'; } ?>

</body>
</html>