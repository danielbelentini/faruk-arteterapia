# Faruk Arteterapia — tema WordPress

Tema WordPress sob medida para o site de **Faruk, Arteterapeuta e Analista Junguiano**. Sem construtores de página (page builders) e sem plugins pagos: PHP nativo, Tailwind CSS e blocos do Gutenberg.

> Versão do tema: 0.1.0 · PHP 8.0+ · WordPress 6.4+

## 1. Visão geral da arquitetura

| Camada | Tecnologia | Observação |
|---|---|---|
| Tema | PHP nativo (WordPress) | Sem page builders |
| Estilos | Tailwind CSS v3 (CLI) | Compilado em `assets/css/main.css` |
| Blocos | Gutenberg + Carbon Fields (`Block::make`) | 19 blocos; templates em `template-parts/components/` |
| Ícones | Lucide, SVG inline | `inc/icons.php` (sem biblioteca no navegador) |
| Carrosséis | Swiper.js local | `assets/vendor/swiper/`, carregado só onde há o bloco |
| Fontes | `.woff2` hospedadas no tema | Cormorant Garamond e Outfit (sem Google Fonts) |
| Rastreamento | GTM + dataLayer | Taxonomia em `docs/gtm-taxonomia.md` |
| Privacidade | Banner de cookies + Google Consent Mode v2 | LGPD; padrão "negado" até o aceite |

Princípios: leve (JavaScript global mínimo), acessível (foco visível, `aria-*`, "reduzir movimento") e editável (todo texto dos blocos é editável no Gutenberg; campos vazios usam textos provisórios).

## 2. Estrutura de pastas

```
faruk-arteterapia/
├── style.css                 Cabeçalho do tema (o CSS real é compilado)
├── functions.php             Constantes, menus, scripts, estilos, avisos
├── header.php / footer.php   Cabeçalho, menu, rodapé, banner de cookies
├── index.php                 Template de reserva (posts e demais)
├── page.php                  Páginas em largura total (blocos)
├── page-legal.php            Modelo "Página Legal"
├── page-politica-de-privacidade.php / page-politica-de-cookies.php
├── tailwind.config.js        Cores, fontes e animações da identidade
├── package.json / composer.json
├── assets/
│   ├── css/input.css         Fonte do Tailwind  →  main.css (gerado)
│   ├── js/main.js            JS global (menu, GTM, FAQ, consentimento)
│   ├── js/hero-carousel.js   Carrossel do Hero (só onde há o bloco)
│   ├── js/testimonials-carousel.js
│   ├── fonts/                4 arquivos .woff2 (você adiciona)
│   └── vendor/swiper/        Gerado por "npm install"
├── inc/
│   ├── carbon-fields.php     Opções do tema + registro dos blocos
│   ├── helpers.php           Opções, GTM/Consent Mode, menus
│   └── icons.php             Ícones Lucide (SVG inline)
├── template-parts/
│   ├── legal-page.php
│   └── components/           Um template por bloco + logo + banner
├── scripts/copy-vendor.js    Copia o Swiper para assets/vendor/
├── vendor/                   Carbon Fields (gerado por "composer install")
└── docs/                     Guias (instalação, deploy, checklist, testes)
```

### Blocos do Gutenberg (categoria "Faruk Arteterapia")

Hero Carrossel · Hero de Página Interna · Duas Portas de Entrada · Conceito Iceberg · Sobre da Home · Definição · Perfil Sobre · Trajetória · Formação e Valores · Como Funciona · Linguagens e Materiais · Escopo e Limites · FAQ Perguntas Frequentes · Depoimentos · Canais de Contato · Chamada Final WhatsApp · Grupos e Vivências · Navegação por Âncoras · Escolas e Empresas · Supervisão para Arteterapeutas.

**Fundo da seção:** todos os blocos de seção têm o campo "Fundo da seção" (Padrão/creme, Areia ou Branco; Linguagens e Materiais também tem a faixa verde escura). Alterne os fundos entre blocos vizinhos para separar as seções. No bloco Canais de Contato (3 seções), as seguintes alternam a partir do fundo escolhido. O Hero de Página Interna usa imagem de fundo opcional; sem imagem, mostra o degradê da marca (terracota ou verde sálvia).

(O banner de cookies e o logo são componentes do tema, não blocos.)

## 3. Build

Pré-requisitos: Node.js 20 LTS, Composer e PHP 8.0+. Passo a passo no Windows: `docs/instalacao-local-windows.md`.

```bash
npm install                          # instala Tailwind e Swiper; copia o Swiper para assets/vendor/
composer install                     # instala o Carbon Fields em vendor/
npm run dev                          # Tailwind em modo observação (durante o desenvolvimento)
npm run build                        # CSS final minificado (produção)
composer install --no-dev --optimize-autoloader   # dependências de produção
```

Regras do Tailwind neste tema:
- classes escritas por inteiro (nunca montadas por concatenação), senão não entram no CSS;
- as cores/fontes oficiais estão em `tailwind.config.js`;
- sempre rode `npm run build` antes de publicar.

## 4. Opções do tema (painel "Configurações do site")

| Aba | Campo | Uso |
|---|---|---|
| Contato | Link do WhatsApp | Todos os botões de WhatsApp (ex.: `https://wa.me/5511999999999`) |
| Contato | Link do Instagram | Rodapé e Canais de Contato |
| Contato | Telefone de exibição | Rodapé (link de ligação); vazio = oculto |
| Contato | E-mail de contato | Rodapé; vazio = oculto |
| Profissional | Registro profissional | Rodapé |
| Rastreamento | ID do Google Tag Manager | `GTM-XXXXXXX`; vazio = GTM desativado |
| Privacidade (LGPD) | Link da Política de Privacidade | Vazio = usa a página `/politica-de-privacidade/` |
| Privacidade (LGPD) | Link da Política de Cookies | Vazio = usa a página `/politica-de-cookies/` |
| Privacidade (LGPD) | Desativar o banner de cookies | Mantenha o banner se o GTM estiver ativo |

## 5. Rastreamento e privacidade

- Eventos oficiais e parâmetros: `docs/gtm-taxonomia.md`.
- Estado padrão do Consent Mode v2: `ad_storage`, `ad_user_data`, `ad_personalization` e `analytics_storage` = `denied`. O aceite no banner atualiza para `granted` e envia `cookie_consent_granted`.
- O banner é exibido por JavaScript (compatível com cache de página).

## 6. Fluxo de trabalho e documentação

1. Desenvolvimento local: `docs/instalacao-local-windows.md`
2. Roteiro de testes: `docs/roteiro-de-testes.md`
3. Publicação: `docs/deploy-hostinger.md`
4. Depois do lançamento: `docs/checklist-pos-lancamento.md`

## 7. Pendências conhecidas

- Fontes `.woff2` em `assets/fonts/` (4 arquivos).
- Textos, imagens e depoimentos reais (os atuais são provisórios).
- Links dos Google Forms (com UTM) nos botões de inscrição, escolas, empresas e supervisão.
- Conteúdo jurídico das páginas de Privacidade e Cookies (revisão profissional).
- Comparar os ícones de `inc/icons.php` com a biblioteca Lucide (foram escritos manualmente).
- Favicons gerados a partir da mandala.

## Licença

GPL-2.0-or-later.
