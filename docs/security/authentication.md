# Autenticação

Fortify será o ponto único para registro, login, recuperação de senha, verificação de e-mail, atualização de senha e 2FA. O login recebe rate limit por e-mail/IP e o desafio de 2FA recebe limite independente.

O registro de usuário não cria automaticamente uma organização. Depois da confirmação de e-mail, o usuário passa pelo onboarding de organização e recebe membership administrativo apenas na organização que criar.

Sanctum protege a API. Toda rota tenant-scoped também exige o middleware de contexto da organização; autenticação sem membership não concede acesso a dados.

O login limita cinco tentativas por minuto combinando e-mail e IP. O desafio 2FA possui limitador independente.
