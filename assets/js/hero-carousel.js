/**
 * Faruk Arteterapia — carrossel do Hero (bloco "Hero Carrossel").
 *
 * Usa o Swiper local (assets/vendor/swiper/). Este arquivo só é carregado
 * nas páginas que têm o bloco, depois do Swiper.
 *
 * Comportamento (igual ao protótipo):
 *  - fade de 1 s entre slides, troca automática a cada 6 s;
 *  - setas, pontos e botão de pausa;
 *  - com "reduzir movimento" ativado no sistema, a troca automática não inicia.
 *
 * As classes do Tailwind abaixo estão escritas por inteiro, para o
 * compilador (tailwind.config.js > content) as detectar.
 */
( function () {
	'use strict';

	var DOT_ACTIVE = [ 'w-8', 'bg-laranja' ];
	var DOT_IDLE = [ 'w-2', 'bg-white/40', 'hover:bg-white/60' ];

	function setClasses( el, add, remove ) {
		remove.forEach( function ( cls ) {
			el.classList.remove( cls );
		} );
		add.forEach( function ( cls ) {
			el.classList.add( cls );
		} );
	}

	function initCarousel( root ) {
		var container = root.querySelector( '.swiper' );
		var slides = root.querySelectorAll( '.swiper-slide' );

		if ( ! container || slides.length < 2 || typeof window.Swiper === 'undefined' ) {
			return;
		}

		var prevBtn = root.querySelector( '[data-hero-prev]' );
		var nextBtn = root.querySelector( '[data-hero-next]' );
		var pauseBtn = root.querySelector( '[data-hero-pause]' );
		var dots = root.querySelectorAll( '[data-hero-dot]' );

		var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		var swiper = new window.Swiper( container, {
			effect: 'fade',
			fadeEffect: { crossFade: true },
			speed: 1000,
			rewind: true,
			autoplay: reduceMotion ? false : { delay: 6000, disableOnInteraction: false },
		} );

		root.classList.add( 'is-ready' );

		// Mantém só o slide ativo acessível (foco e leitores de tela).
		function sync() {
			slides.forEach( function ( slide, i ) {
				var active = i === swiper.activeIndex;
				slide.inert = ! active;
			} );

			dots.forEach( function ( dot, i ) {
				if ( i === swiper.activeIndex ) {
					setClasses( dot, DOT_ACTIVE, DOT_IDLE );
					dot.setAttribute( 'aria-current', 'true' );
				} else {
					setClasses( dot, DOT_IDLE, DOT_ACTIVE );
					dot.removeAttribute( 'aria-current' );
				}
			} );
		}

		swiper.on( 'slideChange', sync );
		sync();

		if ( prevBtn ) {
			prevBtn.addEventListener( 'click', function () {
				swiper.slidePrev();
			} );
		}

		if ( nextBtn ) {
			nextBtn.addEventListener( 'click', function () {
				swiper.slideNext();
			} );
		}

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				swiper.slideTo( parseInt( dot.getAttribute( 'data-hero-dot' ), 10 ) || 0 );
			} );
		} );

		// Botão de pausa.
		if ( pauseBtn ) {
			if ( reduceMotion ) {
				// Sem troca automática, o botão não tem função.
				pauseBtn.classList.add( 'hidden' );
				return;
			}

			var iconPause = pauseBtn.querySelector( '[data-icon="pause"]' );
			var iconPlay = pauseBtn.querySelector( '[data-icon="play"]' );
			var playing = true;

			pauseBtn.addEventListener( 'click', function () {
				playing = ! playing;

				if ( playing ) {
					swiper.autoplay.start();
				} else {
					swiper.autoplay.stop();
				}

				pauseBtn.setAttribute(
					'aria-label',
					playing ? pauseBtn.getAttribute( 'data-label-pause' ) : pauseBtn.getAttribute( 'data-label-play' )
				);

				if ( iconPause && iconPlay ) {
					iconPause.classList.toggle( 'hidden', ! playing );
					iconPlay.classList.toggle( 'hidden', playing );
				}
			} );
		}
	}

	function init() {
		document.querySelectorAll( '[data-hero-carousel]' ).forEach( initCarousel );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
