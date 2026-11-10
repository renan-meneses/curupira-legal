# Modelo de segurança

## Limites de confiança

O navegador é não confiável; autorização deve ocorrer no servidor por usuário, organização e recurso. PostgreSQL é a fonte de dados; workers Redis devem revalidar autorização e idempotência antes de operações críticas. Provedores fiscais são terceiros e seus retornos precisam ser persistidos como eventos auditáveis.

## Regras obrigatórias

- Toda consulta de domínio deve receber o contexto da organização.
- Policies devem verificar membership e permissão antes de acesso a IDs fornecidos pelo cliente.
- Segredos, certificados e chaves devem ficar fora do Git e criptografados em repouso.
- Login, recuperação de senha e emissão fiscal devem ter rate limit e auditoria.
- Valores monetários devem usar inteiros em centavos ou decimal exato; nunca `float`.

O backend ainda não está instalado nesta entrega; estas regras são critérios de aceite para a próxima etapa.

## Testes de autorização

A suíte Feature cobre requisição sem autenticação, ausência de contexto, uso de organização sem membership e tentativa de troca para organização não autorizada. Esses cenários devem permanecer bloqueados em qualquer alteração de middleware ou rotas.
