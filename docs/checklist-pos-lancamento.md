# Checklist pós-lançamento: publicação, SEO e analytics

## 1. Antes de divulgar
- [ ] Textos, imagens e depoimentos reais (e **autorizados** por quem os escreveu).
- [ ] Links dos Google Forms (com UTM) nos botões de inscrição, escolas, empresas e supervisão.
- [ ] **Configurações do site** preenchidas: WhatsApp, Instagram, telefone, e-mail, registro profissional.
- [ ] Páginas e menus conforme `docs/instalacao-local-windows.md`.
- [ ] Favicons gerados a partir da mandala e título/descrição de cada página (SEO).
- [ ] HTTPS ativo e site abrindo com e sem "www" (redirecionamento para um só).

## 2. GTM
1. Crie o contêiner **Web** em tagmanager.google.com e copie o ID `GTM-XXXXXXX`.
2. No WordPress: **Configurações do site → Rastreamento →** cole o ID e salve.
3. Confira no código-fonte do site: o script do GTM no `<head>` e o `noscript` logo após o `<body>`.
4. No GTM, crie acionadores do tipo **Evento personalizado** para cada evento (`whatsapp_click`, `instagram_click`, `form_lead_click`, `cta_click`, `anchor_nav_click`, `nav_click`, `faq_toggle`, `phone_click`) e variáveis de camada de dados para `link_location`, `link_text`, `link_url`, `item_name` e `lead_type` (veja `docs/gtm-taxonomia.md`).
5. Use o **Modo de visualização** do GTM para testar cada clique antes de publicar o contêiner.

## 3. Consent Mode v2 no GTM
O tema já define o estado padrão `denied` e atualiza para `granted` quando o visitante aceita.

1. No GTM: **Administrador → Configurações do contêiner →** marque **Ativar visão geral do consentimento**.
2. Abra a tag do **Google Analytics 4 (configuração)** → *Configurações de consentimento* → exija `analytics_storage`.
3. Tags de publicidade (se houver): exija `ad_storage`, `ad_user_data` e `ad_personalization`.
4. No Modo de visualização: antes do aceite, as tags protegidas **não devem disparar**; depois do aceite (evento `cookie_consent_granted`), devem disparar.
5. Teste também o caminho "Apenas essenciais" (nada de medição) e a reabertura pelo link **Gerenciar cookies** no rodapé.

## 4. Conteúdo jurídico (LGPD)
O tema **não traz texto jurídico pronto**.
- [ ] Redigir/validar com profissional a **Política de Privacidade** e a **Política de Cookies** (considerar o termo de aceite do atendimento e o tratamento de dados dos formulários).
- [ ] Colar o texto no editor de cada página (`/politica-de-privacidade/` e `/politica-de-cookies/`).
- [ ] Informar nas políticas: GTM/Google Analytics, Google Forms, WhatsApp e Instagram (links externos).
- [ ] Conferir os links no rodapé e no banner.

## 5. SEO básico
- [ ] Título e descrição por página (plugin gratuito de SEO ou campos do tema, se adicionados).
- [ ] Um único `h1` por página (o Hero já usa `h1`).
- [ ] Textos alternativos nas imagens; imagens em WebP/JPEG otimizadas (largura útil, não originais enormes).
- [ ] Sitemap enviado ao Google Search Console; propriedade verificada.
- [ ] Perfil do Google Business (se houver atendimento presencial).

## 6. WPO e cache
**Atenção com scripts de consentimento/GTM.** Otimizadores que adiam, combinam ou removem JavaScript podem quebrar o Consent Mode e o dataLayer.

### Hostinger (LiteSpeed)
- Ative o **LiteSpeed Cache** (gratuito) pelo hPanel/plugin.
- Mantenha cache de página ligado; **não** combine/adie JavaScript no início.
- Limpe o cache sempre que publicar uma nova versão do tema.

### WP Rocket (plugin pago, opcional)
- **Não** ative “Atrasar a execução do JavaScript” sem **excluir** estes itens: `googletagmanager.com`, `gtag`, `dataLayer` e `main.js` do tema.
- Evite “Remover CSS não usado” até testar: o CSS do tema já é minificado e enxuto.
- Não use LiteSpeed Cache e WP Rocket ao mesmo tempo.

### Cloudflare (opcional)
- Ative SSL em modo **Full (strict)**; deixe **Rocket Loader desligado** (interfere com GTM e com o banner).
- Cache de arquivos estáticos (CSS, JS, fontes, imagens) ligado; não faça cache de `/wp-admin/`.
- Se o site usar cache de página, o banner continua correto: ele é exibido por JavaScript.

## 7. Após o lançamento
- [ ] Teste real: clique em cada botão e confira no GTM (Tempo real do GA4).
- [ ] Verifique em celular de verdade (iPhone e Android).
- [ ] Agende backups e atualizações do WordPress.
- [ ] Revisar eventos e metas do GA4 após 7 dias de dados.
