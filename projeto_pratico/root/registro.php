<?php
require_once '../src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die("Token CSRF inválido!");
    }
    
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    if ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } else {
        $resultado = registrarUsuario($pdo, $nome, $email, $senha);
        
        if ($resultado['success']) {
            $sucesso = $resultado['message'];
        } else {
            $erro = $resultado['message'];
        }
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
    <title>Registro - Sistema de Tarefas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Criar Conta</h1>
            
            <?php if (isset($erro)): ?>
                <div class="alert error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <?php if (isset($sucesso)): ?>
                <div class="alert success"><?php echo $sucesso; ?> <a href="index.php">Fazer login</a></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                    <small>Mínimo 8 caracteres com letras maiúsculas, minúsculas, números e símbolos</small>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha:</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                </div>
                
                <button type="submit" class="btn">Cadastrar</button>
            </form>
            
            <p>Já tem conta? <a href="index.php">Faça login aqui</a></p>
        </div>
    </div>
</body>
</html>