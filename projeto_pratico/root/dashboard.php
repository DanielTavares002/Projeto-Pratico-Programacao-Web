<?php
require_once '../src/auth.php';
require_once '../src/task.php';

if (!verificarLogin()) {
    header("Location: index.php");
    exit;
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Adicionar nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_tarefa'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die("Token CSRF inválido!");
    }
    
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $vencimento = $_POST['vencimento'] ?? null;
    $prioridade = $_POST['prioridade'] ?? 'media';
    
    if (!empty($titulo)) {
        adicionarTarefa($pdo, $_SESSION['usuario_id'], $titulo, $descricao, $vencimento, $prioridade);
    }
}

// Ações via GET (marcar como concluída/excluir)
if (isset($_GET['acao'])) {
    $tarefa_id = $_GET['id'] ?? null;
    
    if ($tarefa_id) {
        switch ($_GET['acao']) {
            case 'concluir':
                atualizarStatusTarefa($pdo, $tarefa_id, $_SESSION['usuario_id'], 'concluida');
                break;
            case 'pendente':
                atualizarStatusTarefa($pdo, $tarefa_id, $_SESSION['usuario_id'], 'pendente');
                break;
            case 'excluir':
                excluirTarefa($pdo, $tarefa_id, $_SESSION['usuario_id']);
                break;
        }
    }
    header("Location: dashboard.php");
    exit;
}

// Buscar tarefas e estatísticas
$tarefas = getTarefas($pdo, $_SESSION['usuario_id']);
$estatisticas = getEstatisticasTarefas($pdo, $_SESSION['usuario_id']);

// Ícones para prioridades
$icones_prioridade = [
    'baixa' => '🔵',
    'media' => '🟡', 
    'alta' => '🔴'
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Tarefas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
            <div class="user-actions">
                <span><?php echo $_SESSION['usuario_email']; ?></span>
                <a href="?logout" class="btn btn-secondary">Sair</a>
            </div>
        </header>

        <!-- Estatísticas -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total</h3>
                <span class="count"><?php echo $estatisticas['total'] ?? 0; ?></span>
            </div>
            <div class="stat-card">
                <h3>Pendentes</h3>
                <span class="count pending"><?php echo $estatisticas['pendentes'] ?? 0; ?></span>
            </div>
            <div class="stat-card">
                <h3>Concluídas</h3>
                <span class="count completed"><?php echo $estatisticas['concluidas'] ?? 0; ?></span>
            </div>
            <div class="stat-card">
                <h3>Alta Prioridade</h3>
                <span class="count high-priority"><?php echo $estatisticas['altas_pendentes'] ?? 0; ?></span>
            </div>
        </div>

        <!-- Formulário para adicionar tarefa -->
        <div class="card">
            <h2>Nova Tarefa</h2>
            <form method="POST">
                <input type="hidden" name="adicionar_tarefa" value="1">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <input type="text" name="titulo" placeholder="Título da tarefa" required>
                </div>
                
                <div class="form-group">
                    <textarea name="descricao" placeholder="Descrição (opcional)" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="vencimento">Data de vencimento:</label>
                        <input type="date" name="vencimento" id="vencimento">
                    </div>
                    
                    <div class="form-group">
                        <label for="prioridade">Prioridade:</label>
                        <select name="prioridade" id="prioridade">
                            <option value="baixa">🔵 Baixa</option>
                            <option value="media" selected>🟡 Média</option>
                            <option value="alta">🔴 Alta</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn">Adicionar Tarefa</button>
            </form>
        </div>

        <!-- Lista de tarefas -->
        <div class="card">
            <h2>Minhas Tarefas (<?php echo count($tarefas); ?>)</h2>
            
            <?php if (empty($tarefas)): ?>
                <p class="no-tasks">Nenhuma tarefa cadastrada. Adicione sua primeira tarefa acima!</p>
            <?php else: ?>
                <div class="tasks-list">
                    <?php foreach ($tarefas as $tarefa): ?>
                        <div class="task-item <?php echo $tarefa['status']; ?> prioridade-<?php echo $tarefa['prioridade']; ?>">
                            <div class="task-content">
                                <h3>
                                    <?php echo $icones_prioridade[$tarefa['prioridade']] . ' ' . htmlspecialchars($tarefa['titulo']); ?>
                                </h3>
                                
                                <?php if (!empty($tarefa['descricao'])): ?>
                                    <p><?php echo htmlspecialchars($tarefa['descricao']); ?></p>
                                <?php endif; ?>
                                
                                <div class="task-meta">
                                    <?php if ($tarefa['vencimento']): ?>
                                        <span class="due-date">
                                            Vence em: <?php echo date('d/m/Y', strtotime($tarefa['vencimento'])); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="created-date">
                                        Criada em: <?php echo date('d/m/Y H:i', strtotime($tarefa['criado_em'])); ?>
                                    </span>
                                    <span class="priority-badge prioridade-<?php echo $tarefa['prioridade']; ?>">
                                        <?php echo ucfirst($tarefa['prioridade']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="task-actions">
                                <?php if ($tarefa['status'] === 'pendente'): ?>
                                    <a href="?acao=concluir&id=<?php echo $tarefa['id']; ?>" class="btn btn-success">Concluir</a>
                                <?php else: ?>
                                    <a href="?acao=pendente&id=<?php echo $tarefa['id']; ?>" class="btn btn-warning">Pendente</a>
                                <?php endif; ?>
                                <a href="?acao=excluir&id=<?php echo $tarefa['id']; ?>" class="btn btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">Excluir</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>