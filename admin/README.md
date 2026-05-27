# Área de administração — Casa da Juventude Center

Painel em **PHP vanilla** (sem framework) para gestão de alunos, cursos, professores, funcionários e inscrições.

O site público (`index.html`, `cursos.html`, etc.) **não é alterado** por esta área.

## Requisitos

- PHP 8.1 ou superior (extensões: `pdo_mysql`, `mbstring`, `session`)
- MySQL 5.7+ ou MariaDB 10.3+
- Apache com `mod_rewrite` (recomendado) ou servidor compatível

## Instalação (XAMPP / local)

### 1. Base de dados

No phpMyAdmin ou linha de comandos MySQL:

```bash
mysql -u root -p < admin/database/schema.sql
mysql -u root -p < admin/database/seed.sql
```

Isto cria a base `casa_juventude`, as tabelas e os **9 cursos** alinhados com o site público.

### 2. Configuração

Na raiz do projeto:

```bash
copy .env.example .env
```

Edite `.env` com os dados da sua base de dados e o URL do admin:

```env
APP_URL=http://localhost/casa-da-juventude-center/admin
DB_HOST=127.0.0.1
DB_NAME=casa_juventude
DB_USER=root
DB_PASS=
```

Ajuste `APP_URL` ao caminho real do seu servidor.

### 3. Acesso

Abra no browser:

`http://localhost/casa-da-juventude-center/admin/`

Na **primeira visita**, se não existir administrador, é criado automaticamente com as credenciais definidas em `.env`:

| Campo | Valor padrão (.env.example) |
|-------|-----------------------------|
| Email | `admin@casadajuventude.com` |
| Palavra-passe | `Admin@2025` |

**Altere a palavra-passe em produção** (por agora pode atualizar o hash na tabela `administradores` ou remover `ADMIN_SEED_PASSWORD` do `.env` após o primeiro login).

## Rotas principais

| Rota | Descrição |
|------|-----------|
| `?r=login` | Entrada |
| `?r=dashboard` | Painel |
| `?r=alunos/list` | Listar alunos |
| `?r=cursos/list` | Listar cursos |
| `?r=professores/list` | Listar professores |
| `?r=funcionarios/list` | Listar funcionários |
| `?r=inscricoes/list` | Listar inscrições |

## Segurança (OWASP)

- Sessões com cookie `HttpOnly` e `SameSite=Strict`
- Palavras-passe com `password_hash` / `password_verify`
- PDO com prepared statements (anti-SQL injection)
- Token CSRF em todos os formulários POST
- Escape HTML (`htmlspecialchars`) em todas as saídas
- Limite de tentativas de login (5 / 15 minutos)
- Cabeçalhos: `X-Frame-Options`, `CSP`, `X-Content-Type-Options`
- Pastas `config/`, `database/`, `src/`, `views/` bloqueadas via `.htaccess`

## Estrutura

```
admin/
  index.php          # Front controller
  bootstrap.php
  config/
  database/          # schema.sql, seed.sql
  src/               # Controllers, Models, Auth
  views/             # Templates PHP
  assets/css/        # Estilos só do admin
```

## Produção

1. Defina `APP_ENV=production` no `.env`
2. Use HTTPS e confirme que o cookie de sessão fica `Secure`
3. Remova ou deixe vazio `ADMIN_SEED_PASSWORD` após criar o administrador
4. Não exponha o ficheiro `.env` na web
