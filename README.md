# API para Controle de estoque e vendas

Projeto backend para gerenciar módulo simplificado de controle de estoque e vendas para um ERP.

## :wrench: Setup

- Execute os comandos: `composer install`, `npm install`, `cp .env.example .env`, `php artisan key:generate` e modifique o `.env` com os acessos necessários para bancos de dados;
- Faça a migração dos bancos de dados com o comando: `php artisan migrate`;
- Inicie a base de dados com o comando: `php artisan db:seed`;
- Para rodar o projeto: `composer run dev` ou `php artisan serve`;

## 🔀 Dependências e versões

- PHP: 8.4;
- Laravel: 12.0;
- MySQL: 8.0;

## ⚠️ Observações

- Utilizado [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum) para autenticação;
- Utilizado [Laravel Migrations](https://laravel.com/docs/12.x/migrations) para realizar as operações de banco de dados;
- Utilizado [Laravel Seeding](https://laravel.com/docs/12.x/seeding) para dados mock, crie novos dados e depois execute o comando `php artisan db:seed --class={NameSeeder}`;
- Utilizado [Módulo de linguagem pt-BR](https://github.com/lucascudo/laravel-pt-BR-localization) para traduções, ao criar um novo campo em um Model é necessário adicionar a tradução amigável do campo em `lang/pt_BR/validation.php` no array `attributes`;
- Link postman para testes [Postman](https://postman.co/workspace/My-Workspace~9336d82b-0f84-4658-814b-0d20d4429e92/collection/9066171-1f4f58e9-9813-4b4d-a10b-c48441174c83?action=share&creator=9066171) ou use o arquivo `api.postman_collection.json` na raiz do projeto;
