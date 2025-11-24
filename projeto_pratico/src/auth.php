<?php
require_once 'db.php';

function registrarUsuario($pdo, $nome, $email, $senha) {
    // Verificar se email já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ["success" => false, "message" => "Email já cadastrado!"];
    }

    // Validação de senha forte
    if (!isPasswordStrong($senha)) {
        return ["success" => false, "message" => "Senha deve ter pelo menos 8 caracteres, incluindo maiúsculas, minúsculas, números e símbolos!"];
    }

    // Hash da senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir usuário
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    if ($stmt->execute([$nome, $email, $senhaHash])) {
        return ["success" => true, "message" => "Usuário cadastrado com sucesso!"];
    } else {
        return ["success" => false, "message" => "Erro ao cadastrar usuário!"];
    }
}

function loginUsuario($pdo, $email, $senha) {
    // Buscar usuário
    $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['logado'] = true;
        
        return ["success" => true, "message" => "Login realizado com sucesso!"];
    } else {
        return ["success" => false, "message" => "Email ou senha incorretos!"];
    }
}

function verificarLogin() {
    return isset($_SESSION['logado']) && $_SESSION['logado'] === true;
}

function logout() {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

function isPasswordStrong($senha) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $senha);
}

// === PROTEÇÃO CSRF ===
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>