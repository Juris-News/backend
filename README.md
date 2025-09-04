# ⚖️ Juris News API

Backend em **Laravel 11** para o sistema **Juris News**, um assistente jurídico digital que organiza e distribui as últimas notícias da área jurídica.  
A API fornece autenticação segura, gerenciamento de usuários e integrações para consumo no frontend.

---

## 🚀 Stack & Pacotes

-   [Laravel 11.7.0](https://laravel.com/)
-   [Laravel Breeze (API)](https://laravel.com/docs/11.x/starter-kits#breeze-and-api)
-   [Laravel Sanctum](https://laravel.com/docs/11.x/sanctum) — autenticação por tokens
-   [Laravel Permission v6.x](https://spatie.be/docs/laravel-permission/v6/introduction)
-   [Laravel Backup v8.x](https://spatie.be/docs/laravel-backup/v8/introduction)
-   [Laravel Settings](https://github.com/spatie/laravel-settings)
-   [Laravel Log Activity v4.x](https://spatie.be/docs/laravel-activitylog/v4/introduction)

---

## ⚡ Instalação & Uso

1. Clone o repositório:

```bash
git clone https://github.com/emanuelleLS/juris-newss.git
cd juris-newss
```

2. Copie o arquivo `.env.example` para `.env` e configure suas variáveis de ambiente (DB, Mail, etc):

```bash
cp .env.example .env
```

3. Atualize as dependências do Composer:

```bash
composer update
```

4. Gere a chave da aplicação:

```bash
php artisan key:generate
```

5. Execute as migrações do banco de dados:

```bash
php artisan migrate
```

6. (Opcional) Popule dados iniciais:

```bash
php artisan db:seed
```

7. Crie o link de storage:

```bash
php artisan storage:link
```

8. Rode o servidor local:

```bash
php artisan serve
```

9. Execute o worker de filas (se necessário):

```bash
php artisan queue:work
```

---

## 📖 Documentação da API

(Em breve será disponibilizada documentação no Postman/Swagger)

---

## 📜 Licença

Este projeto está licenciado sob a **MIT License**.  
© 2025 — Desenvolvido por **Emanuelle Lino Scheifer**
