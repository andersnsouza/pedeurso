# PeDeUrso - Contexto do Projeto

## Stack
- Laravel 12 / PHP 8.4+
- PostgreSQL 16 (Docker)
- Redis (Docker) + Horizon
- Sanctum (autenticação API)

## Arquitetura
- **Controllers:** Finos, apenas recebem request e delegam para Services
- **Services:** Toda lógica de negócio
- **FormRequests:** Validação de entrada
- **Resources:** Serialização JSON:API
- **Enums:** Valores fixos (status, tipos)

## Configurações Importantes
- Middleware `tenant` registrado em `bootstrap/app.php` (placeholder)
- Trait `ApiResponse` em `app/Traits/` para respostas padronizadas
- Testes usam SQLite em memória (configurado em `phpunit.xml`)
- `.env` configurado para Docker local (PostgreSQL 5432, Redis 6379)

## Domínio de Referência
O domínio `Customer` serve como exemplo completo da arquitetura:
- `app/Models/Customer.php`
- `app/Services/CustomerService.php`
- `app/Http/Controllers/Api/CustomerController.php`
- `app/Http/Requests/Customer/`
- `app/Http/Resources/CustomerResource.php`
- `app/Enums/CustomerStatus.php`
- `tests/Feature/CustomerTest.php`

## Comandos Úteis
```bash
docker-compose up -d    # Subir PostgreSQL e Redis
php artisan migrate     # Rodar migrations
php artisan test        # Executar testes
php artisan horizon     # Iniciar worker de filas
```

## Pendências
- [ ] Implementar lógica do TenantMiddleware
- [ ] Criar sistema de autenticação (login/registro)
- [ ] Configurar CI/CD
