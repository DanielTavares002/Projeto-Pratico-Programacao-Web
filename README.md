# 📋 Sistema de Gerenciamento de Tarefas (To-Do List)

Um sistema completo de gerenciamento de tarefas desenvolvido em PHP, MySQL e JavaScript com interface moderna e responsiva.

## 🚀 Funcionalidades

### 🔐 Autenticação e Segurança
- **Registro de usuários** com validação de dados
- **Sistema de login** seguro com sessões
- **Proteção CSRF** em todos os formulários
- **Senhas criptografadas** com hash
- **Validação de senha forte** (mínimo 8 caracteres com maiúsculas, minúsculas, números e símbolos)

### 📝 Gerenciamento de Tarefas
- **Criar tarefas** com título, descrição, data de vencimento e prioridade
- **Visualizar tarefas** organizadas por prioridade
- **Marcar como concluída/pendente**
- **Exclusão lógica** (soft delete) mantendo histórico
- **Sistema de prioridades** (Alta 🔴, Média 🟡, Baixa 🔵)
- **Filtros e busca** em tempo real

### 🎨 Interface e UX
- **Design moderno** com gradientes e animações
- **Interface totalmente responsiva**
- **Estatísticas em tempo real**
- **Notificações elegantes**
- **Ícones intuitivos** para prioridades

## 🛠 Tecnologias Utilizadas

- **Backend:** PHP 7.4+
- **Banco de Dados:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript Vanilla
- **Estilo:** CSS puro com design moderno
- **Segurança:** CSRF Protection, Password Hash

## 📋 Pré-requisitos

- Servidor web (Apache/Nginx)
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Navegador moderno com JavaScript habilitado

## 🚀 Instalação

### 1. Configuração do Ambiente

bash
# Clone o repositório ou extraia os arquivos
cd C:\xampp\htdocs\projetos\Projeto-Pratico-Programacao-Web\projeto_pratico\


### 2. Configuração do Banco de Dados

#### Opção 1: Importar via phpMyAdmin
1. Acesse `http://localhost/phpmyadmin`
2. Crie um banco de dados chamado `projeto_app`
3. Importe o arquivo `sql/schema.sql`

#### Opção 2: Executar SQL manualmente

sql
CREATE DATABASE IF NOT EXISTS projeto_app CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE projeto_app;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);

CREATE TABLE `tarefas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `prioridade` enum('baixa','media','alta') DEFAULT 'media',
  `status` enum('pendente','concluida','excluida') DEFAULT 'pendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `tarefas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
);


### 3. Configuração da Aplicação

Verifique as configurações de conexão no arquivo `src/db.php`:

php
$host = 'localhost';
$dbname = 'projeto_app';
$username = 'root';
$password = ''; // Senha do seu MySQL


## 📁 Estrutura do Projeto


projeto_pratico/
├── root/                 # Páginas principais
│   ├── index.php        # Página de login
│   ├── registro.php     # Página de registro
│   ├── dashboard.php    # Painel principal
│   ├── css/
│   │   └── style.css    # Estilos principais
│   └── js/
│       └── main.js      # JavaScript da aplicação
├── src/                 # Lógica da aplicação
│   ├── db.php          # Conexão com banco
│   ├── auth.php        # Autenticação e segurança
│   └── task.php        # Gerenciamento de tarefas
└── sql/
    └── schema.sql      # Estrutura do banco


## 🔧 Configuração

### Acesso à Aplicação
1. Inicie o servidor web e MySQL
2. Acesse: `http://localhost/projetos/Projeto-Pratico-Programacao-Web/projeto_pratico/root/index.php`
3. Crie uma conta ou faça login

### Configurações do Banco
- **Host:** localhost
- **Banco:** projeto_app
- **Usuário:** root
- **Senha:** (vazia por padrão no XAMPP)

## 💡 Como Usar

### Para Usuários
1. **Crie uma conta** em "Cadastre-se aqui"
2. **Faça login** com email e senha
3. **Adicione tarefas** usando o formulário no dashboard
4. **Organize por prioridade** usando o sistema de cores
5. **Filtre tarefas** usando a barra de busca e filtros
6. **Marque como concluída** quando finalizar uma tarefa

### Recursos Principais
- **Prioridades:** Use cores para identificar urgência (Vermelho = Alta, Amarelo = Média, Verde = Baixa)
- **Datas de vencimento:** Defina prazos para suas tarefas
- **Estatísticas:** Acompanhe seu progresso com os contadores
- **Busca:** Encontre tarefas rapidamente pelo título

## 🛡 Segurança Implementada

- **Proteção CSRF:** Todos os formulários possuem tokens de segurança
- **SQL Injection Prevention:** Uso de prepared statements
- **XSS Prevention:** Dados sanitizados na exibição
- **Session Management:** Sessões seguras para autenticação
- **Password Hashing:** Senhas criptografadas com algoritmo seguro

## 🎨 Personalização

### Cores e Temas
As cores podem ser personalizadas no arquivo `css/style.css`:

css
:root {
  --primary-color: #3498db;
  --success-color: #27ae60;
  --warning-color: #f39c12;
  --danger-color: #e74c3c;
}


### Prioridades
As cores das prioridades podem ser ajustadas:

css
.prioridade-alta { border-left-color: #e74c3c; }
.prioridade-media { border-left-color: #f39c12; }
.prioridade-baixa { border-left-color: #27ae60; }


## 🔄 Melhorias Futuras

- [ ] Sistema de recuperação de senha
- [ ] Edição em linha de tarefas
- [ ] Categorias personalizadas
- [ ] Exportação de relatórios (PDF/Excel)
- [ ] API REST para integração
- [ ] Modo escuro
- [ ] Lembretes por email

## 🐛 Solução de Problemas

### Erro de Conexão com Banco

php
// Verifique no src/db.php:
$host = 'localhost';
$dbname = 'projeto_app';
$username = 'root';
$password = ''; // Sua senha do MySQL


### Página em Branco
- Verifique se o PHP está mostrando erros
- Confirme que todas as extensões necessárias estão habilitadas (PDO, MySQL)

### Problemas de CSS/JS
- Verifique se os caminhos dos arquivos estão corretos
- Confirme que o servidor web está servindo arquivos estáticos

## 📞 Suporte

Em caso de problemas:
1. Verifique os logs de erro do PHP
2. Confirme as permissões de arquivo
3. Valide a configuração do banco de dados

## 👥 Desenvolvimento

Este projeto foi desenvolvido como trabalho prático para a disciplina de Programação Web, demonstrando conceitos de:
- CRUD completo
- Autenticação e sessões
- Segurança web
- Design responsivo
- Interatividade com JavaScript

## 📄 Licença

Este projeto é para fins educacionais.

---