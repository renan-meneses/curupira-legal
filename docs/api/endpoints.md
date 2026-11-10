# API inicial

A API pública será versionada em `/api/v1`. Nenhum endpoint está ativo enquanto o scaffold Laravel não puder ser instalado.

| Método | Rota | Acesso | Objetivo |
| --- | --- | --- | --- |
| `GET` | `/api/v1/me` | autenticado | Usuário e organização ativa |
| `GET` | `/api/v1/organizations` | autenticado | Organizações do usuário |
| `POST` | `/api/v1/organizations/{organization}/switch` | membership | Trocar contexto de organização |
| `GET` | `/api/v1/company` | organização ativa | Listar empresas |
| `POST` | `/api/v1/company` | organização ativa | Cadastrar empresa |
| `GET` | `/api/v1/customers` | organização ativa | Listar clientes |
| `POST` | `/api/v1/customers` | organização ativa | Cadastrar cliente |
| `GET` | `/api/v1/contracts` | organização ativa | Listar contratos |
| `POST` | `/api/v1/contracts` | organização ativa | Criar contrato |
| `PATCH` | `/api/v1/contracts/{contract}/status` | organização ativa | Alterar estado e registrar versão |

Toda rota de domínio deverá exigir autenticação, membership e policy do recurso. IDs recebidos do cliente não substituem o escopo da organização.
