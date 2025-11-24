// Funções JavaScript para interatividade
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de Tarefas carregado!');
    
    // Inicializar todas as funcionalidades
    initDeleteConfirmations();
    initFormValidations();
    initFilters();
    initTaskInteractions();
    initNotifications();
});

// Confirmação para exclusões
function initDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll('.btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Tem certeza que deseja excluir esta tarefa?')) {
                e.preventDefault();
            }
        });
    });
}

// Validação de formulários
function initFormValidations() {
    const taskForm = document.querySelector('form[method="POST"]');
    if (taskForm && taskForm.querySelector('input[name="titulo"]')) {
        taskForm.addEventListener('submit', function(e) {
            const titulo = this.querySelector('input[name="titulo"]');
            if (titulo && titulo.value.trim() === '') {
                e.preventDefault();
                showNotification('Por favor, insira um título para a tarefa.', 'error');
                titulo.focus();
            }
        });
    }

    // Validação de senha no registro
    const registerForm = document.querySelector('form[action*="registro"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const senha = this.querySelector('input[name="senha"]');
            const confirmarSenha = this.querySelector('input[name="confirmar_senha"]');
            
            if (senha && confirmarSenha && senha.value !== confirmarSenha.value) {
                e.preventDefault();
                showNotification('As senhas não coincidem!', 'error');
                confirmarSenha.focus();
            }
        });
    }
}

// Sistema de filtros
function initFilters() {
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('status-filter');
    const dateFilter = document.getElementById('date-filter');
    
    if (searchInput || statusFilter || dateFilter) {
        [searchInput, statusFilter, dateFilter].forEach(element => {
            if (element) {
                element.addEventListener('change', filterTasks);
                element.addEventListener('input', filterTasks);
            }
        });
    }
}

function filterTasks() {
    const searchTerm = document.getElementById('search')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('status-filter')?.value || 'all';
    const dateFilter = document.getElementById('date-filter')?.value || 'all';
    
    const tasks = document.querySelectorAll('.task-item');
    
    tasks.forEach(task => {
        const title = task.querySelector('h3').textContent.toLowerCase();
        const status = task.classList.contains('concluida') ? 'concluida' : 'pendente';
        const dueDate = task.querySelector('.due-date')?.textContent || '';
        const today = new Date();
        
        let show = true;
        
        // Filtro de busca
        if (searchTerm && !title.includes(searchTerm)) {
            show = false;
        }
        
        // Filtro de status
        if (statusFilter !== 'all' && status !== statusFilter) {
            show = false;
        }
        
        // Filtro de data
        if (dateFilter !== 'all' && dueDate) {
            const dueDateObj = new Date(dueDate.split(': ')[1].split('/').reverse().join('-'));
            switch (dateFilter) {
                case 'today':
                    if (dueDateObj.toDateString() !== today.toDateString()) show = false;
                    break;
                case 'week':
                    const weekLater = new Date(today);
                    weekLater.setDate(today.getDate() + 7);
                    if (dueDateObj < today || dueDateObj > weekLater) show = false;
                    break;
                case 'overdue':
                    if (dueDateObj >= today) show = false;
                    break;
            }
        }
        
        task.style.display = show ? 'flex' : 'none';
    });
}

// Interações com tarefas
function initTaskInteractions() {
    // Efeitos visuais ao concluir tarefas
    const concluirButtons = document.querySelectorAll('.btn-success, .btn-warning');
    concluirButtons.forEach(button => {
        button.addEventListener('click', function() {
            const taskItem = this.closest('.task-item');
            taskItem.classList.add('loading');
        });
    });
}

// Sistema de notificações
function initNotifications() {
    // Verificar se há mensagens do PHP para exibir
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('success')) {
            showNotification(alert.textContent, 'success');
        } else if (alert.classList.contains('error')) {
            showNotification(alert.textContent, 'error');
        }
    });
}

function showNotification(message, type = 'success') {
    // Remover notificações existentes
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Criar nova notificação
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remover após 5 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Adicionar animação de saída
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
`;
document.head.appendChild(style);

// Função para edição rápida (para implementar depois)
function enableQuickEdit(taskId, currentTitle, currentDescription) {
    const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
    if (!taskElement) return;
    
    const originalContent = taskElement.innerHTML;
    
    taskElement.innerHTML = `
        <form class="edit-form" onsubmit="saveQuickEdit(${taskId})" style="width: 100%;">
            <div class="form-group">
                <input type="text" name="titulo" value="${currentTitle}" required style="margin-bottom: 10px;">
            </div>
            <div class="form-group">
                <textarea name="descricao" rows="2" style="margin-bottom: 10px;">${currentDescription || ''}</textarea>
            </div>
            <div class="task-actions">
                <button type="submit" class="btn btn-success">Salvar</button>
                <button type="button" class="btn btn-secondary" onclick="cancelQuickEdit(${taskId}, \`${originalContent.replace(/`/g, '\\`')}\`)">Cancelar</button>
            </div>
        </form>
    `;
}

// Funções auxiliares para busca em tempo real
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Aplicar debounce na busca
const debouncedFilter = debounce(filterTasks, 300);
if (document.getElementById('search')) {
    document.getElementById('search').addEventListener('input', debouncedFilter);
}