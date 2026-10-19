/**
 * Faruk Arteterapia — JavaScript global do tema.
 *
 * Só o que vale para TODAS as páginas. Os carrosséis (Swiper) ficam em
 * arquivos próprios, carregados apenas onde há o bloco:
 *   - assets/js/hero-carousel.js
 *   - assets/js/testimonials-carousel.js
 *
 * Módulos:
 *   1. Cabeçalho ao rolar
 *   2. Menu mobile
 *   3. dataLayer (GTM): delegação de cliques com data-gtm-*
 *   4. FAQ (accordion)
 *   5. Banner de cookies / Google Consent Mode v2 (LGPD)
 *   6. Botão "voltar ao topo"
 *
 * Rolagem suave e offset do header fixo nas âncoras são feitos em CSS
 * (html { scroll-behavior: smooth; scroll-padding-top }), em input.css.
 *
 * Importante: as classes do Tailwind abaixo estão escritas por inteiro,
 * para que o compilador (tailwind.config.js > content) as detecte.
 */
( function () {
	'use strict';

	window.dataLayer = window.dataLayer || [];

	/**
	 * Executa uma função sem deixar um erro quebrar os demais módulos.
	 *
	 * @param {Function} fn Função do módulo.
	 */
	function safe( fn ) {
		try {
			fn();
		} catch ( error ) {
			if ( window.console && window.console.warn ) {
				window.console.warn( '[faruk] módulo com erro:', error );
			}
		}
	}

	/* ------------------------------------------------------------
	 * 1. Cabeçalho: muda de aparência depois de rolar 20px
	 * ---------------------------------------------------------- */
	safe( function () {
		var header = document.getElementById( 'site-header' );

		if ( ! header ) {
			return;
		}

		var headerTop = [ 'bg-creme/80', 'backdrop-blur-sm' ];
		var headerScrolled = [ 'bg-creme/95', 'shadow-sm', 'backdrop-blur-md' ];

		function update() {
			var scrolled = window.scrollY > 20;

			headerTop.forEach( function ( cls ) {
				header.classList.toggle( cls, ! scrolled );
			} );

			headerScrolled.forEach( function ( cls ) {
				header.classList.toggle( cls, scrolled );
			} );
		}

		window.addEventListener( 'scroll', update, { passive: true } );
		update();
	} );

	/* ------------------------------------------------------------
	 * 2. Menu mobile
	 * ---------------------------------------------------------- */
	safe( function () {
		var toggle = document.getElementById( 'menu-toggle' );
		var menu = document.getElementById( 'mobile-menu' );

		if ( ! toggle || ! menu ) {
			return;
		}

		var menuClosed = [ 'invisible', 'max-h-0' ];
		var menuOpen = [ 'visible', 'max-h-96', 'border-t', 'border-creme-dark' ];

		function setMenu( open ) {
			menuClosed.forEach( function ( cls ) {
				menu.classList.toggle( cls, ! open );
			} );

			menuOpen.forEach( function ( cls ) {
				menu.classList.toggle( cls, open );
			} );

			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			toggle.setAttribute(
				'aria-label',
				open ? toggle.getAttribute( 'data-label-close' ) : toggle.getAttribute( 'data-label-open' )
			);

			var iconOpen = toggle.querySelector( '[data-icon="open"]' );
			var iconClose = toggle.querySelector( '[data-icon="close"]' );

			if ( iconOpen && iconClose ) {
				iconOpen.classList.toggle( 'hidden', open );
				iconClose.classList.toggle( 'hidden', ! open );
			}
		}

		toggle.addEventListener( 'click', function () {
			setMenu( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );

		// Fecha ao escolher um item do menu.
		menu.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( 'a' ) ) {
				setMenu( false );
			}
		} );

		// Fecha com a tecla Esc.
		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				setMenu( false );
				toggle.focus();
			}
		} );

		// Fecha ao passar para o layout desktop (breakpoint lg = 1024px).
		window.matchMedia( '(min-width: 1024px)' ).addEventListener( 'change', function ( event ) {
			if ( event.matches ) {
				setMenu( false );
			}
		} );
	} );

	/* ------------------------------------------------------------
	 * 3. dataLayer (Google Tag Manager)
	 *
	 * Um único ouvinte no documento (delegação) cobre qualquer elemento
	 * com data-gtm-event. Atributos aceitos:
	 *
	 *   data-gtm-event      nome do evento      (obrigatório)
	 *   data-gtm-location   -> link_location    (onde está o elemento)
	 *   data-gtm-text       -> link_text        (opcional; padrão: texto visível)
	 *   data-gtm-item       -> item_name        (opcional; ex.: nome do projeto)
	 *   data-gtm-lead-type  -> lead_type        (opcional; ex.: school, company)
	 *
	 * Enviado ao dataLayer:
	 *   { event, link_location, link_text, link_url, [item_name], [lead_type] }
	 *
	 * Eventos oficiais: whatsapp_click, instagram_click, form_lead_click,
	 * cta_click, anchor_nav_click, nav_click, faq_toggle, phone_click.
	 *
	 * Usa a fase de captura para ler o estado ANTES de outros scripts
	 * alterarem o elemento: assim "faq_toggle" dispara só ao ABRIR
	 * (aria-expanded="false" no momento do clique).
	 * ---------------------------------------------------------- */
	safe( function () {
		function cleanText( value ) {
			return String( value || '' ).replace( /\s+/g, ' ' ).trim().slice( 0, 100 );
		}

		document.addEventListener(
			'click',
			function ( event ) {
				var el = event.target.closest ? event.target.closest( '[data-gtm-event]' ) : null;

				if ( ! el || el.disabled || el.getAttribute( 'aria-disabled' ) === 'true' ) {
					return;
				}

				// Botões de abrir/fechar: só conta quando está fechado (vai abrir).
				if ( el.getAttribute( 'aria-expanded' ) === 'true' ) {
					return;
				}

				var payload = {
					event: el.getAttribute( 'data-gtm-event' ),
					link_location: el.getAttribute( 'data-gtm-location' ) || '',
					link_text: cleanText( el.getAttribute( 'data-gtm-text' ) || el.textContent ),
					link_url: el.href || '',
				};

				var item = el.getAttribute( 'data-gtm-item' );
				var leadType = el.getAttribute( 'data-gtm-lead-type' );

				if ( item ) {
					payload.item_name = cleanText( item );
				}

				if ( leadType ) {
					payload.lead_type = leadType;
				}

				window.dataLayer.push( payload );
			},
			true
		);
	} );

	/* ------------------------------------------------------------
	 * 4. FAQ (accordion)
	 *
	 * Cada bloco FAQ tem [data-faq]; abrir uma pergunta fecha as outras.
	 * O visual (abre/fecha com transição) vem do atributo data-open nas
	 * classes do Tailwind (group-data-[open=true]:...).
	 * ---------------------------------------------------------- */
	safe( function () {
		function setItem( item, open ) {
			var trigger = item.querySelector( '[data-faq-trigger]' );
			var panel = item.querySelector( '[data-faq-panel]' );

			item.setAttribute( 'data-open', open ? 'true' : 'false' );

			if ( trigger ) {
				trigger.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			}

			if ( panel ) {
				// Painel fechado fica fora da navegação por teclado e dos leitores de tela.
				panel.inert = ! open;
			}
		}

		document.querySelectorAll( '[data-faq]' ).forEach( function ( faq ) {
			var items = faq.querySelectorAll( '[data-faq-item]' );

			items.forEach( function ( item ) {
				setItem( item, item.getAttribute( 'data-open' ) === 'true' );
			} );

			faq.addEventListener( 'click', function ( event ) {
				var trigger = event.target.closest( '[data-faq-trigger]' );

				if ( ! trigger || ! faq.contains( trigger ) ) {
					return;
				}

				var current = trigger.closest( '[data-faq-item]' );
				var willOpen = current.getAttribute( 'data-open' ) !== 'true';

				items.forEach( function ( item ) {
					setItem( item, item === current ? willOpen : false );
				} );
			} );
		} );
	} );

	/* ------------------------------------------------------------
	 * 5. Banner de cookies + Google Consent Mode v2 (LGPD)
	 *
	 * O estado padrão ("denied") e a restauração de quem já aceitou são
	 * definidos no <head> (inc/helpers.php > faruk_gtm_head). Aqui:
	 *   - mostra o banner se ainda não houve escolha;
	 *   - "Aceitar": consent update -> granted, evento
	 *     cookie_consent_granted no dataLayer, salva "granted";
	 *   - "Apenas essenciais": mantém "denied" e salva "denied";
	 *   - botão "Gerenciar cookies" (rodapé) reabre o banner.
	 * ---------------------------------------------------------- */
	safe( function () {
		var banner = document.querySelector( '[data-cookie-banner]' );

		if ( ! banner ) {
			return;
		}

		var STORAGE_KEY = 'faruk_cookie_consent';

		function gtag() {
			window.dataLayer.push( arguments );
		}

		function readChoice() {
			try {
				return window.localStorage.getItem( STORAGE_KEY );
			} catch ( error ) {
				return null;
			}
		}

		function saveChoice( value ) {
			try {
				window.localStorage.setItem( STORAGE_KEY, value );
			} catch ( error ) {
				// Armazenamento bloqueado: a escolha vale só nesta página.
			}
		}

		function consentState( value ) {
			return {
				ad_storage: value,
				ad_user_data: value,
				ad_personalization: value,
				analytics_storage: value,
			};
		}

		function showBanner( focus ) {
			banner.hidden = false;

			// Dois quadros: o navegador registra o estado inicial antes da transição.
			window.requestAnimationFrame( function () {
				window.requestAnimationFrame( function () {
					banner.setAttribute( 'data-open', 'true' );

					if ( focus ) {
						var acceptButton = banner.querySelector( '[data-cookie-accept]' );

						if ( acceptButton ) {
							acceptButton.focus();
						}
					}
				} );
			} );
		}

		function hideBanner() {
			banner.setAttribute( 'data-open', 'false' );

			window.setTimeout( function () {
				banner.hidden = true;
			}, 500 );
		}

		var accept = banner.querySelector( '[data-cookie-accept]' );
		var decline = banner.querySelector( '[data-cookie-decline]' );

		if ( accept ) {
			accept.addEventListener( 'click', function () {
				gtag( 'consent', 'update', consentState( 'granted' ) );
				window.dataLayer.push( { event: 'cookie_consent_granted' } );
				saveChoice( 'granted' );
				hideBanner();
			} );
		}

		if ( decline ) {
			decline.addEventListener( 'click', function () {
				gtag( 'consent', 'update', consentState( 'denied' ) );
				saveChoice( 'denied' );
				hideBanner();
			} );
		}

		document.querySelectorAll( '[data-cookie-settings]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				showBanner( true );
			} );
		} );

		if ( readChoice() === null ) {
			showBanner( false );
		}
	} );

	/* ------------------------------------------------------------
	 * 6. Voltar ao topo
	 *
	 * O botão [data-back-to-top] (footer.php) aparece, com fade, depois
	 * que a primeira dobra (a altura da janela) é rolada, em qualquer
	 * página. Ao clicar, volta ao topo (suave, salvo "reduzir movimento").
	 * ---------------------------------------------------------- */
	safe( function () {
		var button = document.querySelector( '[data-back-to-top]' );

		if ( ! button ) {
			return;
		}

		var hidden = [ 'invisible', 'opacity-0', 'translate-y-2' ];
		var shown = [ 'visible', 'opacity-100', 'translate-y-0' ];
		var isVisible = null;
		var ticking = false;

		function setVisible( show ) {
			if ( show === isVisible ) {
				return;
			}

			isVisible = show;

			hidden.forEach( function ( cls ) {
				button.classList.toggle( cls, ! show );
			} );

			shown.forEach( function ( cls ) {
				button.classList.toggle( cls, show );
			} );
		}

		function update() {
			ticking = false;
			setVisible( window.scrollY > window.innerHeight * 0.9 );
		}

		window.addEventListener(
			'scroll',
			function () {
				if ( ! ticking ) {
					ticking = true;
					window.requestAnimationFrame( update );
				}
			},
			{ passive: true }
		);

		button.addEventListener( 'click', function () {
			var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

			window.scrollTo( { top: 0, behavior: reduce ? 'auto' : 'smooth' } );
		} );

		update();
	} );
}() );
