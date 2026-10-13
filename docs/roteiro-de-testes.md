# Roteiro de testes (ambiente local)

O código foi escrito sem execução em WordPress (não havia PHP no ambiente de desenvolvimento). Percorra este roteiro no LocalWP antes de publicar e anote o que falhar.

## A. Instalação
- [ ] `npm install`, `composer install` e `npm run build` terminam sem erro.
- [ ] Existem `assets/css/main.css`, `assets/vendor/swiper/` e `vendor/autoload.php`.
- [ ] O tema ativa sem tela branca e sem avisos no admin.
- [ ] Ativar `WP_DEBUG` (em `wp-config.php`) e confirmar que não há erros/avisos de PHP nas páginas.

## B. Blocos
- [ ] Todos os blocos aparecem na categoria **Faruk Arteterapia**.
- [ ] Cada bloco renderiza no editor e no site, com campos vazios (textos provisórios) e preenchidos.
- [ ] Hero: 1 slide (sem setas/pontos) e 2+ slides (fade, setas no desktop, pontos, botão de pausa).
- [ ] Depoimentos: 3 itens = grade no desktop; 4+ = carrossel; no celular, só pontos.
- [ ] FAQ: abre um por vez, com animação; funciona com teclado (Tab/Enter).
- [ ] Grupos e Vivências: card aberto tem botão com link; encerrado tem botão desativado e nota.
- [ ] Âncoras (`#grupos`, `#escolas-empresas`, `#supervisao`) rolam sem esconder o título atrás do header.

## C. Visual
- [ ] Cores, fontes e mandala conferem com a identidade.
- [ ] Layout confere com os prints (Início, Arteterapia, Grupos, Sobre, Contato).
- [ ] Ícones: comparar com o protótipo (foram escritos manualmente).
- [ ] Larguras: 360, 768, 1024 e 1440 px.

## D. Rastreamento e LGPD
- [ ] Com o ID do GTM preenchido, o contêiner carrega (aba Rede do navegador).
- [ ] Console do navegador: `dataLayer` mostra `gtag('consent','default', ... 'denied')`.
- [ ] Cliques enviam o evento certo (veja `docs/gtm-taxonomia.md`); `faq_toggle` só ao abrir.
- [ ] Banner aparece na primeira visita; "Aceitar" envia `cookie_consent_granted`; recarregar não mostra o banner de novo.
- [ ] "Apenas essenciais" mantém `denied`; "Gerenciar cookies" no rodapé reabre o banner.
- [ ] GTM Preview (modo de visualização) confirma os acionadores.

## E. Acessibilidade e desempenho
- [ ] Navegação só por teclado: foco visível, "Pular para o conteúdo" funciona.
- [ ] Lighthouse (Chrome) em Início: Acessibilidade e Boas práticas acima de 90.
- [ ] Sem erros no console.
