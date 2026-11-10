# Visão de arquitetura

Curupira Legal será um monólito modular Laravel com Inertia/React. O navegador conversa com a camada HTTP; casos de uso orquestram regras por módulo; PostgreSQL mantém dados transacionais e Redis atende sessões, filas e cache.

## Direção visual

A fundação usa uma linguagem editorial financeira: verde mata para confiança operacional, terracota para ações e alertas, creme para respiro e tipografia DM Sans/DM Mono/Playfair Display para combinar precisão com personalidade brasileira sutil.

## Limite desta entrega

O backend Laravel permanece bloqueado pela indisponibilidade do Packagist. A interface atual é explicitamente demonstrativa e não deve ser usada como autenticação ou processamento financeiro.
