# Instalação local — Windows 11 + LocalWP

Guia para rodar o tema no seu computador e compilar o CSS.

## 1. Requisitos

| Programa | Versão | Para quê |
|---|---|---|
| [LocalWP](https://localwp.com) | atual | Roda o WordPress (PHP, MySQL, servidor) |
| [Node.js](https://nodejs.org) | **20 LTS** (mínimo 18) | Tailwind CSS e Swiper |
| [Composer](https://getcomposer.org) | 2.x | Carbon Fields |
| [Git](https://git-scm.com) | atual | Versionamento e deploy |
| [VS Code](https://code.visualstudio.com) | atual | Editor (opcional, recomendado) |

Confira no PowerShell:

```powershell
node -v
npm -v
composer -V
git --version
```

> Composer precisa do PHP. O **Site Shell** do LocalWP (botão direito no site → *Open site shell*) já traz o PHP do site e costuma trazer o Composer. Se `composer -V` falhar, instale o Composer pelo instalador oficial e aponte o `php.exe` que o LocalWP usa (ou instale o PHP 8.2 para Windows).

## 2. Criar o site no LocalWP

1. Abra o LocalWP → **Create a new site**.
2. Nome: `faruk` · ambiente **Preferred** (PHP 8.1 ou superior) · crie o usuário administrador.
3. Aguarde terminar e clique em **Open site folder**: a pasta do site tem `app/public/` (a raiz do WordPress).

## 3. Colocar o tema no lugar

O tema deve ficar em:

```
<pasta do site>\app\public\wp-content\themes\faruk-arteterapia\
```

- **Se você já tem o zip:** extraia `faruk-arteterapia-final.zip` e copie a pasta `faruk-arteterapia` para dentro de `wp-content\themes\`.
- **Se usar Git:** no PowerShell, dentro de `wp-content\themes\`:

```powershell
git clone <URL-DO-REPOSITORIO> faruk-arteterapia
```

## 4. Fontes locais

Copie os 4 arquivos para `faruk-arteterapia\assets\fonts\` (nomes exatos):

- `CormorantGaramond-Regular.woff2`
- `Outfit-Regular.woff2`
- `Outfit-Medium.woff2`
- `Outfit-SemiBold.woff2`

## 5. Instalar dependências e compilar

No VS Code: **File → Open Folder** na pasta do tema e abra o terminal (`Ctrl+'`). Ou, no PowerShell:

```powershell
cd "<pasta do site>\app\public\wp-content\themes\faruk-arteterapia"
npm install
composer install
npm run build
```

O que cada comando faz:
- `npm install` instala Tailwind e Swiper e copia o Swiper para `assets\vendor\swiper\`;
- `composer install` cria `vendor\` com o Carbon Fields;
- `npm run build` gera `assets\css\main.css`.

Durante o desenvolvimento, deixe `npm run dev` rodando em um terminal: o CSS é recompilado a cada alteração.

## 6. Ativar o tema

1. LocalWP → **WP Admin** (login com o usuário criado).
2. **Aparência → Temas →** ativar **Faruk Arteterapia**.
3. Se aparecer aviso de Carbon Fields ou Swiper ausente, repita o passo 5.

## 7. Criar as páginas

Em **Páginas → Adicionar nova**, crie com **exatamente** estes endereços (slugs), pois o menu padrão os usa:

| Página | Slug (endereço) | Modelo |
|---|---|---|
| Início | `inicio` | Padrão |
| Arteterapia Junguiana | `arteterapia-junguiana` | Padrão |
| Grupos, projetos e vivências | `grupos-projetos-e-vivencias` | Padrão |
| Sobre | `sobre` | Padrão |
| Contato | `contato` | Padrão |
| Política de Privacidade | `politica-de-privacidade` | Usa o template automático |
| Política de Cookies | `politica-de-cookies` | Usa o template automático |

Defina a página inicial: **Configurações → Leitura → Sua página inicial exibe → Uma página estática → Página inicial: Início**.

Em cada página, clique no **+** do editor e procure a categoria **Faruk Arteterapia**.

### Sugestão de blocos por página (compare com os prints do layout)

| Página | Blocos |
|---|---|
| Início | Hero Carrossel · Duas Portas de Entrada · Conceito Iceberg · Depoimentos · Chamada Final WhatsApp |
| Arteterapia Junguiana | Hero (1 slide, altura compacta) · Conceito Iceberg · Como Funciona · Linguagens e Materiais · Escopo e Limites · FAQ · Depoimentos · Chamada Final |
| Grupos, projetos e vivências | Hero (compacto) · Navegação por Âncoras · Grupos e Vivências · Escolas e Empresas · Supervisão · Chamada Final |
| Sobre | Hero (compacto) · Trajetória e Valores · Chamada Final |
| Contato | Hero (compacto) · Canais de Contato · Chamada Final |

## 8. Menus e opções

1. **Aparência → Menus**: crie o menu principal (5 itens) e atribua a **Menu principal (cabeçalho)**; crie o do rodapé e atribua a **Menu do rodapé**. (Sem menus atribuídos, o tema usa os 5 itens padrão.)
2. **Configurações do site** (menu lateral do admin): preencha WhatsApp, Instagram, telefone, e-mail, registro e, quando houver, o ID do GTM.

## 9. Problemas comuns

| Sintoma | Causa provável | Solução |
|---|---|---|
| Site sem estilo | `main.css` não gerado | `npm run build` |
| Aviso do Carbon Fields | `vendor\` ausente | `composer install` |
| Carrossel não funciona | Swiper ausente | `npm install` (ou `npm run vendor`) |
| Textos sem fonte correta | `.woff2` ausentes | Passo 4 |
| Página dá erro 404 | Links permanentes | **Configurações → Links permanentes →** Salvar |
| Classe nova do Tailwind sem efeito | `npm run dev` parado | Rode `npm run build` |
