# Auditoria e compliance

Operações sensíveis geram eventos em `audit_logs`: pagamento registrado, configuração fiscal revisada, invoice submetida e invoice reconciliada. Cada evento guarda organização, usuário, ação, tipo/ID do recurso, metadata, IP, user-agent e timestamp.

Os registros não possuem endpoint de edição ou exclusão na aplicação. Retenção, exportação e controles de acesso ao relatório de auditoria devem ser definidos antes da produção.
