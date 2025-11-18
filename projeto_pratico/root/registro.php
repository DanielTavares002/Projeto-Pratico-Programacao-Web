<?php

require 'conexao.php';

// Inicializar variáveis
$erro = '';
$sucesso = '';


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Verificar se o email já existe
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->rowCount() > 0){
        $erro = "Este email já está cadastrado!";
    } else {
        // Inserir usuário
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        if($stmt->execute([$nome, $email, $senha])){
            $sucesso = "Cadastro realizado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Cadastro de Usuário</h2>

<?php
if($erro) {
    echo "<p style='color:red;'>$erro</p>";
}
if($sucesso) {
    echo "<p style='color:green;'>$sucesso</p>";
}
?>


<form method="POST">
    <div class="container">
        <div class="box">
            <label>Nome:</label><br>
            <input type="text" name="nome" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Senha:</label><br>
            <input type="password" name="senha" required><br><br>

            <button type="submit">Cadastrar</button>
            <p><a href="index.php">Já possui conta? Login</a></p>
        </div>
    </div>

</form>
</body>
</html>
