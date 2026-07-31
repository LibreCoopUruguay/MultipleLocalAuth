# MultipleLocalAuth

Plugin de autenticação para o Mapas Culturais que combina login local (e-mail e CPF) com múltiplas estratégias sociais (Google, Facebook, LinkedIn, Twitter, Login Cidadão, Gov.br, Decidim etc.), regras de senha configuráveis e proteção contra abuso.

## Recursos principais
- Cadastro local com fluxo multi-etapas, validação de CPF e aceite de termos LGPD.
- Login por e-mail ou CPF, com limite de tentativas e bloqueio temporário automático.
- Confirmação de conta por e-mail, recuperação de senha com token e troca de senha pelo painel.
- Integração com Google reCAPTCHA v2 (visível) para login, cadastro e recuperação.
- Autenticação social via Opauth (Google, Facebook, LinkedIn, Twitter, Login Cidadão, Gov.br, Decidim) com mapeamento automático de dados.
- Atualização opcional de avatar e metadados ao autenticar via Gov.br ou Decidim.
- Componentes Vue (`login`, `create-account`, `change-password`, `password-strongness`) prontos para a Base V2.

## Instalação
1. Faça download/clonagem deste repositório e coloque a pasta `MultipleLocalAuth` em `protected/application/plugins/` do Mapas Culturais.
2. Garanta que o módulo `LGPD` esteja habilitado, pois o cadastro consome `LGPD::acceptTerms`.
3. Instale dependências do Mapas Culturais (este plugin não adiciona dependências externas além das que já vêm com o core/opauth).

## Configuração
Edite `config.php` do Mapas Culturais:

```php
'plugins' => [
    // ... outros plugins
    'MultipleLocalAuth' => [
        'namespace' => 'MultipleLocalAuth',
    ],
],

'auth.provider' => \MultipleLocalAuth\Provider::class,

'auth.config' => [
    // ajuste conforme as tabelas abaixo
],
```

### Opções gerais
| Chave | Descrição | Padrão | Variável `.env` |
| --- | --- | --- | --- |
| `salt` | Salt usado pelo Opauth | `env('AUTH_SALT')` | `AUTH_SALT` |
| `timeout` | Tempo máximo da sessão OAuth | `24 hours` | `AUTH_TIMEOUT` |
| `loginOnRegister` | Autenticar automaticamente após cadastro | `false` | `AUTH_LOGIN_ON_REGISTER` |
| `enableLoginByCPF` | Permite login/cadastro via CPF | `true` | `AUTH_LOGIN_BY_CPF` |
| `requireCpf` | Exige CPF no cadastro | `true` | `AUTH_REQUIRED_CPF` |
| `metadataFieldCPF` | Campo de metadata que armazena CPF | `documento` | `AUTH_METADATA_FIELD_DOCUMENT` |
| `metadataFieldPhone` | Campo de metadata que armazena telefone | `telefone1` | `AUTH_METADATA_FIELD_PHONE` |
| `userMustConfirmEmailToUseTheSystem` | Exige validação por e-mail antes do uso | `false` | `AUTH_EMAIL_CONFIRMATION` |
| `sessionTime` | Duração da sessão (segundos) | `7200` | `AUTH_SESSION_TIME` |
| `statusCreateAgent` | Status default do agente criado | `Agent::STATUS_ENABLED` | `STATUS_CREATE_AGENT` |

### Comunicação e suporte
| Chave | Uso | Padrão | Variável `.env` |
| --- | --- | --- | --- |
| `urlSupportChat` | Link incluído nos e-mails | `''` | `AUTH_SUPPORT_CHAT` |
| `urlSupportEmail` | Link de contato por e-mail | `''` | `AUTH_SUPPORT_EMAIL` |
| `urlSupportSite` | URL geral de suporte | `''` | `AUTH_SUPPORT_SITE` |
| `textSupportSite` | Texto exibido com o link | `''` | `AUTH_SUPPORT_TEXT` |
| `urlImageToUseInEmails` | Imagem de fundo para e-mails | `null` | `AUTH_EMAIL_IMAGE` |
| `urlTermsOfUse` | URL dos termos de uso | `auth/termos-e-condicoes` | `LINK_TERMOS` |

### Regras de senha
| Chave | Descrição | Padrão | Variável `.env` |
| --- | --- | --- | --- |
| `passwordMustHaveCapitalLetters` | Exigir letra maiúscula | `true` | `AUTH_PASS_CAPITAL_LETTERS` |
| `passwordMustHaveLowercaseLetters` | Exigir letra minúscula | `true` | `AUTH_PASS_LOWERCASE_LETTERS` |
| `passwordMustHaveSpecialCharacters` | Exigir caractere especial | `true` | `AUTH_PASS_SPECIAL_CHARS` |
| `passwordMustHaveNumbers` | Exigir número | `true` | `AUTH_PASS_NUMBERS` |
| `minimumPasswordLength` | Tamanho mínimo da senha | `6` | `AUTH_PASS_LENGTH` |

### Proteção contra abuso
| Chave | Descrição | Padrão | Variável `.env` |
| --- | --- | --- | --- |
| `numberloginAttemp` | Tentativas antes do bloqueio | `5` | `AUTH_NUMBER_ATTEMPTS` |
| `timeBlockedloginAttemp` | Tempo de bloqueio (segundos) | `900` | `AUTH_BLOCK_TIME` |

### Google reCAPTCHA v2
| Chave | Descrição | Padrão |
| --- | --- | --- |
| `google-recaptcha-secret` | Secret da integração | `env('GOOGLE_RECAPTCHA_SECRET')` |
| `google-recaptcha-sitekey` | Site key usada no front | `env('GOOGLE_RECAPTCHA_SITEKEY')` |

Se ambas as chaves estiverem ausentes, o captcha é desativado.

### Estratégias de autenticação
Cada estratégia pode receber `visible => bool` para controlar se o botão aparece na interface.

#### Google
- `client_id`, `client_secret`, `redirect_uri`, `scope` (`email profile` por padrão).

#### Facebook
- `app_id`, `app_secret`, `scope` (default `email`).

#### LinkedIn
- `api_key`, `secret_key`, `redirect_uri`, `scope` (default `r_emailaddress`).

#### Twitter
- `app_id`, `app_secret`. (Fluxo direto do Opauth).

#### Login Cidadão
- `client_id`, `client_secret`, `auth_endpoint`, `token_endpoint`, `userinfo_endpoint`, `redirect_uri`, `scope`.

#### Gov.br
- `client_id`, `client_secret`, `scope`, `auth_endpoint`, `token_endpoint`, `userinfo_endpoint`, `redirect_uri`.
- `state_salt`, `code_verifier`, `code_challenge`, `code_challenge_method` para PKCE.
- `applySealId` (opcional): selo aplicado ao agente autenticado.
- `dic_agent_fields_update`: mapa de campos que podem ser atualizados automaticamente (JSON, ex: `{"name": "full_name"}`).
- `menssagem_authenticated`: mensagem exibida quando o usuário já autenticou via Gov.br.
- Identidade estável: matching **somente por CPF** (`sub`); `authUid` usa `sub` (não `jti`). Sem fallback por e-mail (evita hijack).
- Se o e-mail do Gov.br já existir em `usr.email` (único/obrigatório no core), a criação é interrompida e o usuário informa outro e-mail em `auth/govbr-email`.
- `verifyUpdateData` não sobrescreve perfil cujo CPF diverge do token Gov.br.

#### Decidim
- `client_id`, `client_secret`, `auth_endpoint`, `token_endpoint`, `userinfo_endpoint`, `redirect_uri`, `scope`.
- Atualiza automaticamente avatar do agente com a imagem fornecida.

Você pode adicionar ou remover estratégias conforme necessário; qualquer estratégia Opauth disponível no diretório do plugin pode ser configurada.

## Fluxos e endpoints
- `GET auth.index`: renderiza o componente de login.
- `GET auth.register`: fluxo multi-etapas de cadastro.
- `GET auth.recover`: formulário para solicitar redefinição de senha.
- `GET auth.confirma-email`: valida o token enviado por e-mail e ativa a conta.
- `POST auth.validate`: validação assíncrona do primeiro passo do cadastro.
- `POST auth.register`: criação de conta (gera agente, token de verificação e envia e-mail).
- `POST auth.login`: autenticação local (com bloqueio por tentativas via metadata).
- `POST auth.recover` / `POST auth.dorecover`: solicitação e conclusão da recuperação de senha.
- `POST auth.changepassword` / `POST auth.newpassword`: alteração de senha logado ou via token.
- `POST auth.adminchangeuseremail` / `POST auth.adminchangeuserpassword`: rotinas administrativas (acessos protegidos).
- `GET auth.passwordvalidationinfos`: retorna as regras de senha atuais para o front-end.
- `GET|POST auth.govbr-email`: coleta e-mail alternativo quando o e-mail do Gov.br já está em uso (criação de conta).

## Testes
Regras de conta Gov.br (CPF / e-mail único) têm testes unitários em `tests/`:

```bash
cd plugins/MultipleLocalAuth
php composer.phar install   # ou: composer install
./vendor/bin/phpunit
```

## Componentes que acompanham o plugin
- `components/login`: formulário de login com reCAPTCHA, recuperação de senha e botões sociais.
- `components/create-account`: esteira de cadastro com validações de senha, CPF e aceite de termos LGPD.
- `components/change-password`: formulário para troca de senha no painel.
- `components/password-strongness`: barra de força da senha, reutilizada em cadastro e redefinição.

Todos os componentes carregam textos a partir de `components/*/texts.php`, permitindo tradução personalizada.

## Personalização
- E-mails: templates Mustache em `views/auth/email-to-validate-account.html` e `views/auth/email-resert-password.html`. Você pode copiar/adaptar mantendo as variáveis esperadas.
- Telas: `views/auth/*.php` rendem os componentes Vue; é possível sobrescrever esses arquivos em um tema customizado.
- Estilos: CSS compilado em `assets/css/plugin-MultiplLocalAuth.css`. O SCSS-fonte está em `assets-src/sass/`.
- Traduções: arquivos `.po` em `translations/` (domínio `multipleLocal`).

## Boas práticas
- Configure o cron/serviço de fila de e-mail do Mapas Culturais antes de habilitar a confirmação por e-mail.
- Ajuste `metadataFieldCPF`/`metadataFieldPhone` para corresponder ao schema de metadados do seu deployment.
- Revise as mensagens carregadas via `textSupportSite`, `urlSupport*` para garantir contato adequado ao usuário.
- Gere URLs de callback das estratégias sociais com HTTPS e defina-as nos painéis dos provedores.

---
Mantemos este documento atualizado a partir do código-fonte do plugin. Contribuições são bem-vindas!