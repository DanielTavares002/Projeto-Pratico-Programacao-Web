<?php
require_once 'db.php';

function getTarefas($pdo, $usuario_id) {
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = ? AND status != 'excluida' ORDER BY 
        CASE prioridade 
            WHEN 'alta' THEN 1 
            WHEN 'media' THEN 2 
            WHEN 'baixa' THEN 3 
        END, criado_em DESC");
    $stmt->execute([$usuario_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function adicionarTarefa($pdo, $usuario_id, $titulo, $descricao = null, $vencimento = null, $prioridade = 'media') {
    $stmt = $pdo->prepare("INSERT INTO tarefas (usuario_id, titulo, descricao, vencimento, prioridade) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$usuario_id, $titulo, $descricao, $vencimento, $prioridade]);
}

function atualizarStatusTarefa($pdo, $tarefa_id, $usuario_id, $status) {
    $stmt = $pdo->prepare("UPDATE tarefas SET status = ?, atualizado_em = CURRENT_TIMESTAMP WHERE id = ? AND usuario_id = ?");
    return $stmt->execute([$status, $tarefa_id, $usuario_id]);
}

function excluirTarefa($pdo, $tarefa_id, $usuario_id) {
    // Soft delete - marca como excluída
    return atualizarStatusTarefa($pdo, $tarefa_id, $usuario_id, 'excluida');
}

function getTarefa($pdo, $tarefa_id, $usuario_id) {
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$tarefa_id, $usuario_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Estatísticas
function getEstatisticasTarefas($pdo, $usuario_id) {
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes,
            SUM(CASE WHEN status = 'concluida' THEN 1 ELSE 0 END) as concluidas,
            SUM(CASE WHEN prioridade = 'alta' AND status = 'pendente' THEN 1 ELSE 0 END) as altas_pendentes
        FROM tarefas 
        WHERE usuario_id = ? AND status != 'excluida'
    ");
    $stmt->execute([$usuario_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>