# Cálculos de billing

## Horas aprovadas

O primeiro cálculo implementado usa somente apontamentos `approved` e `billable` dentro do período:

`valor bruto = tarifa horária em centavos × minutos aprovados ÷ 60`

O arredondamento é meio para cima no centavo mais próximo. Valores são armazenados como inteiros em centavos; o código não usa `float` para operações monetárias.

Cada geração cria um snapshot com tarifa, minutos e IDs dos apontamentos usados. A chave de idempotência é formada por contrato e período, impedindo duplicidade no mesmo tenant.

Contratos fixos, híbridos, descontos, impostos e fechamento imutável serão adicionados em regras separadas.
