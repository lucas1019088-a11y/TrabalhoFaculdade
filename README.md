# Organiza — Gerenciador de tarefas em PHP

Projeto acadêmico de um gerenciador de tarefas com **PHP 8+, MySQL, MVC e Design Patterns**. Interface em português, responsiva, com cadastro/login e CRUD completo de tarefas.

## Funcionalidades

- Cadastro e autenticação de usuários; senhas protegidas com `password_hash(..., PASSWORD_BCRYPT)`.
- Dashboard: tarefas do usuário autenticado, contagem total, pendentes e concluídas.
- CRUD: criar, listar, editar (incluindo status) e excluir tarefas.
- Título, descrição, prioridade, prazo e status.
- Separação de tarefas por usuário (filtro por `user_id`).
- Segurança: consultas preparadas PDO, CSRF nos POSTs, escape de HTML e renovação da sessão no login.

## Como executar no XAMPP

1. Instale XAMPP com **PHP 8.0 ou superior**. Inicie **Apache** e **MySQL**.
2. Copie a pasta `organiza-php` para `C:\\xampp\\htdocs\\organiza-php`.
3. No phpMyAdmin (`http://localhost/phpmyadmin`), abra a aba **SQL** e execute `database/schema.sql` (ou use **Importar**).
4. Em `config/config.php`, ajuste usuário e senha do banco MySQL. No XAMPP local, com frequência é `root` e senha em branco.
5. Abra `http://localhost/organiza-php/public/` no navegador.
6. Clique em **Criar conta** e cadastre um usuário. Em seguida, teste a criação, edição e exclusão de tarefas.

> Importante: usar XAMPP apenas no ambiente local de desenvolvimento. Para produção, configure HTTPS, credenciais fortes, permissões e um servidor web seguro. Não publique senhas reais no GitHub.

## Organização MVC

```text
app/
  Controllers/     AuthController e TaskController: recebem requisições HTTP
  Models/          User: regras de acesso aos dados de usuários
  Repositories/    TaskRepository: operações de persistência das tarefas
  Core/            Database: conexão PDO; Helpers: sessão, CSRF e renderização
  Views/           HTML das páginas de autenticação e tarefas
config/            Configuração de conexão
public/            Front controller index.php + CSS
database/          schema.sql
```

O **front controller** `public/index.php` mapeia as rotas GET e POST para os controllers; os controllers consultam as classes de acesso aos dados e renderizam as Views.

## Design Patterns utilizados

1. **Singleton** (`app/Core/Database.php`): uma instância da classe de conexão PDO por execução da aplicação PHP, reduzindo duplicação de inicializações dentro da mesma requisição.
2. **Repository** (`app/Repositories/TaskRepository.php`): centraliza operações SQL do CRUD e isola os controllers de consultas diretas ao banco.

O projeto também implementa **Front Controller**, pois todas as rotas passam por `public/index.php`.

## Evidências para a avaliação

Depois de executar localmente, faça capturas de tela de:

- Tela de **login** ou **cadastro**.
- **Dashboard** com tarefas cadastradas.
- Tela **Nova tarefa** com formulário de inclusão.

## Testes manuais sugeridos

- Cadastro e login válidos; senha errada; email duplicado.
- Criar tarefa, visualizar no dashboard, editar, marcar como concluída e excluir.
- Criar dois usuários diferentes e confirmar que um não enxerga nem edita tarefas do outro.

## Publicar no GitHub

Crie um repositório vazio no GitHub e execute, no terminal aberto na pasta do projeto:

```bash
git init
git add .
git commit -m "Projeto PHP MVC - gerenciador de tarefas"
git branch -M main
git remote add origin https://github.com/SEU-USUARIO/organiza-php.git
git push -u origin main
```

Substitua `SEU-USUARIO` pelo seu nome de usuário. O link do repositório deve ser colado no campo **RESPOSTA DO ALUNO**, junto com sua conclusão.
