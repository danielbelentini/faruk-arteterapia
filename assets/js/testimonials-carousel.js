/**
 * Faruk Arteterapia — carrossel de Depoimentos (bloco "Depoimentos").
 *
 * Usa o Swiper local (assets/vendor/swiper/). Só é carregado nas páginas
 * que têm o bloco, depois do Swiper.
 *
 *  - Celular: 1 depoimento por vez, navegação só por pontos (sem setas).
 *  - A partir de md (768px): 3 por vez. Com até 3 depoimentos, tudo cabe e
 *    o bloco se comporta como grade (sem pontos); com mais de 3, vira
 *    carrossel com setas e pontos.
 *
 * As classes do Tailwind estão escritas por inteiro para o compilador
 * (tailwind.config.js > content) as detectar; os pontos usam .t-dot
 * (definido em assets/css/input.css).
 */
( function () {
	'use strict';

	function initTestimonials( root ) {
		var container = root.querySelector( '.swiper' );

		if ( ! container || typeof window.Swiper === 'undefined' ) {
			return;
		}

		var dots = root.querySelector( '[data-t-dots]' );
		var prev = root.querySelector( '[data-t-prev]' );
		var next = root.querySelector( '[data-t-next]' );

		var options = {
			slidesPerView: 1,
			spaceBetween: 24,
			watchOverflow: true,
			breakpoints: {
				768: { slidesPerView: 3, spaceBetween: 24 },
			},
		};

		if ( dots ) {
			options.pagination = {
				el: dots,
				clickable: true,
				bulletClass: 't-dot',
				bulletActiveClass: 'is-active',
				renderBullet: function ( index, className ) {
					return '<button type="button" class="' + className + '" aria-label="Ir para o depoimento ' + ( index + 1 ) + '"></button>';
				},
			};
		}

		if ( prev && next ) {
			options.navigation = { prevEl: prev, nextEl: next };
		}

		new window.Swiper( container, options );
	}

	function init() {
		document.querySelectorAll( '[data-testimonials]' ).forEach( initTestimonials );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
