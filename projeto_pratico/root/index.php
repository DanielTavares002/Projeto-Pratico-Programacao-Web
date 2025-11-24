<?php
require_once '../src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die("Token CSRF inválido!");
    }
    
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    $resultado = loginUsuario($pdo, $email, $senha);
    
    if ($resultado['success']) {
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = $resultado['message'];
    }
}

if (verificarLogin()) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Tarefas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Login</h1>
            
            <?php if (isset($erro)): ?>
                <div class="alert error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <button type="submit" class="btn">Entrar</button>
            </form>
            
            <p>Não tem conta? <a href="registro.php">Cadastre-se aqui</a></p>
        </div>
    </div>
</body>
</html>