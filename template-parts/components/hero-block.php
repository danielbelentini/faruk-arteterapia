<?php
/**
 * Componente: Hero / Carrossel (bloco "Hero Carrossel").
 *
 * Portado do Carousel.tsx do protótipo:
 *  - altura 92vh (mínimo 600px), slides em fade com zoom lento na imagem;
 *  - selo, título, subtítulo e botão por slide, com animações de entrada;
 *  - setas (a partir de md), pontos e botão discreto de pausa;
 *  - troca automática a cada 6 s (iniciada pelo hero-carousel.js).
 *
 * Sem JavaScript, ou com um só slide, aparece apenas o primeiro slide.
 * Na prévia do editor, é renderizado só o primeiro slide, estático.
 *
 * Recebe em $args: 'fields', 'attributes' e 'inner_blocks'.
 *
 * @package faruk-arteterapia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fields     = isset( $args['fields'] ) && is_array( $args['fields'] ) ? $args['fields'] : array();
$attributes = isset( $args['attributes'] ) && is_array( $args['attributes'] ) ? $args['attributes'] : array();

$slides = ( ! empty( $fields['slides'] ) && is_array( $fields['slides'] ) ) ? $fields['slides'] : faruk_hero_default_slides();

$is_editor = faruk_is_editor_request();

// No editor, mostra só o primeiro slide, sem animação.
if ( $is_editor ) {
	$slides = array_slice( $slides, 0, 1 );
}

$total    = count( $slides );
$multiple = $total > 1;

// Swiper (local) só é carregado quando há mais de um slide no site público.
if ( $multiple && ! $is_editor ) {
	wp_enqueue_style( 'swiper' );
	wp_enqueue_script( 'faruk-hero' );
}

$is_compact     = isset( $fields['height'] ) && 'compact' === $fields['height'];
$section_height = $is_editor ? 'h-[560px]' : ( $is_compact ? 'h-[64vh] min-h-[480px]' : 'h-[92vh] min-h-[600px]' );
$extra_class    = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<section
	class="hero-carousel relative w-full overflow-hidden <?php echo esc_attr( $section_height . $extra_class ); ?>"
	data-hero-carousel
	aria-roledescription="carousel"
	aria-label="<?php esc_attr_e( 'Destaques', 'faruk-arteterapia' ); ?>"
>
	<div class="swiper h-full w-full">
		<div class="swiper-wrapper">

			<?php foreach ( array_values( $slides ) as $index => $slide ) : ?>
				<?php
				$is_first  = ( 0 === $index );
				$badge     = isset( $slide['badge'] ) ? (string) $slide['badge'] : '';
				$title     = isset( $slide['title'] ) ? (string) $slide['title'] : '';
				$subtitle  = isset( $slide['subtitle'] ) ? (string) $slide['subtitle'] : '';
				$image_id  = isset( $slide['image'] ) ? absint( $slide['image'] ) : 0;
				$mobile_id = isset( $slide['image_mobile'] ) ? absint( $slide['image_mobile'] ) : 0;
				$cta_label = isset( $slide['cta_label'] ) ? (string) $slide['cta_label'] : '';
				$cta_type  = ( isset( $slide['cta_type'] ) && in_array( $slide['cta_type'], array( 'whatsapp', 'link', 'link_green', 'none' ), true ) ) ? $slide['cta_type'] : 'link';

				// Botões: 'none' não mostra nada; 'whatsapp' e 'link_green' são verdes.
				$cta_green = in_array( $cta_type, array( 'whatsapp', 'link_green' ), true );
				$cta_show  = ( 'none' !== $cta_type && '' !== $cta_label );

				if ( 'whatsapp' === $cta_type ) {
					$cta_href  = faruk_whatsapp_url();
					$cta_event = 'whatsapp_click';
				} else {
					$cta_href  = faruk_resolve_link( isset( $slide['cta_url'] ) ? (string) $slide['cta_url'] : '' );
					$cta_event = 'cta_click';
				}

				// Só o primeiro slide leva o H1 da página; os demais são H2.
				$heading_tag = $is_first ? 'h1' : 'h2';

				// O primeiro slide já nasce "ativo" no site público (evita piscar antes do Swiper iniciar).
				$slide_state = ( $is_first && ! $is_editor ) ? ' swiper-slide-active' : '';
				?>
				<div class="swiper-slide group h-full<?php echo esc_attr( $slide_state ); ?>">

					<!-- Imagem + overlays -->
					<div class="absolute inset-0 overflow-hidden">
						<?php
						// Só a imagem de celular: vale para todas as telas. As duas: celular até 767 px.
						if ( ! $image_id && $mobile_id ) {
							$image_id  = $mobile_id;
							$mobile_id = 0;
						}

						if ( $image_id ) {
							$image_attrs = array(
								'class'   => 'h-full w-full object-cover group-[.swiper-slide-active]:animate-slow-zoom motion-reduce:animate-none',
								'alt'     => '',
								'sizes'   => '100vw',
								'loading' => $is_first ? 'eager' : 'lazy',
							);

							if ( $is_first ) {
								$image_attrs['fetchpriority'] = 'high';
							}

							$mobile_srcset = ( $mobile_id && $mobile_id !== $image_id ) ? wp_get_attachment_image_srcset( $mobile_id, 'large' ) : '';

							if ( $mobile_srcset ) {
								// <picture>: o celular baixa só a imagem de celular; o desktop, só a principal.
								echo '<picture class="block h-full w-full">';
								echo '<source media="(max-width: 767px)" srcset="' . esc_attr( $mobile_srcset ) . '" sizes="100vw" />';
								echo wp_get_attachment_image( $image_id, 'full', false, $image_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup gerado pelo WordPress.
								echo '</picture>';
							} else {
								echo wp_get_attachment_image( $image_id, 'full', false, $image_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup gerado pelo WordPress.
							}
						} else {
							// Sem imagem: degradê com as cores da marca.
							echo '<div class="h-full w-full bg-gradient-to-br from-terracota-dark via-terracota to-sage-dark"></div>';
						}
						?>
					</div>
					<div class="absolute inset-0 bg-gradient-to-r from-marrom/80 via-marrom/50 to-marrom/20"></div>
					<div class="absolute inset-0 bg-gradient-to-t from-marrom/60 via-transparent to-transparent"></div>

					<!-- Conteúdo -->
					<div class="relative z-10 mx-auto box-content flex h-full max-w-8xl items-center px-6 md:px-12 lg:px-16">
						<div class="max-w-2xl">
							<?php if ( '' !== $badge ) : ?>
								<span class="inline-block rounded-full bg-laranja/20 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-laranja-light ring-1 ring-laranja/30 backdrop-blur-sm group-[.swiper-slide-active]:animate-fade-in motion-reduce:animate-none">
									<?php echo esc_html( $badge ); ?>
								</span>
							<?php endif; ?>

							<<?php echo esc_html( $heading_tag ); ?> class="mt-4 font-serif text-5xl leading-tight text-white group-[.swiper-slide-active]:animate-fade-in-up motion-reduce:animate-none md:text-6xl lg:text-7xl">
								<?php echo esc_html( $title ); ?>
							</<?php echo esc_html( $heading_tag ); ?>>

							<?php if ( '' !== $subtitle ) : ?>
								<p class="mt-4 max-w-xl text-lg leading-relaxed text-creme/80 [animation-delay:100ms] group-[.swiper-slide-active]:animate-fade-in-up motion-reduce:animate-none md:text-xl">
									<?php echo esc_html( $subtitle ); ?>
								</p>
							<?php endif; ?>

							<?php if ( $cta_show ) : ?>
								<a
									href="<?php echo $cta_href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado (faruk_whatsapp_url / faruk_resolve_link). ?>"
									<?php echo 'whatsapp' === $cta_type ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
									class="<?php echo $cta_green ? 'bg-[#25D366] hover:bg-[#1eb858] hover:shadow-green-600/30' : 'bg-terracota-light hover:bg-terracota hover:shadow-terracota/30'; ?> mt-8 inline-flex items-center gap-2 rounded-full px-8 py-4 text-base font-semibold text-white transition-all duration-300 [animation-delay:200ms] hover:-translate-y-0.5 hover:shadow-xl group-[.swiper-slide-active]:animate-fade-in-up motion-reduce:animate-none"
									data-gtm-event="<?php echo esc_attr( $cta_event ); ?>"
									data-gtm-location="hero"
									data-gtm-item="<?php echo esc_attr( $title ); ?>"
								>
									<?php if ( 'whatsapp' === $cta_type ) : ?>
										<?php faruk_the_icon( 'message-circle', 'h-5 w-5' ); ?>
										<?php echo esc_html( $cta_label ); ?>
									<?php else : ?>
										<?php echo esc_html( $cta_label ); ?>
										<?php faruk_the_icon( 'arrow-right', 'h-5 w-5' ); ?>
									<?php endif; ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>

		</div>
	</div>

	<?php if ( $multiple && ! $is_editor ) : ?>

		<!-- Setas -->
		<button
			type="button"
			data-hero-prev
			class="absolute left-4 top-1/2 z-20 hidden -translate-y-1/2 items-center justify-center rounded-full bg-white/10 p-2 text-white backdrop-blur-sm transition-all hover:bg-white/20 md:flex"
			aria-label="<?php esc_attr_e( 'Slide anterior', 'faruk-arteterapia' ); ?>"
		>
			<?php faruk_the_icon( 'chevron-left', 'h-6 w-6' ); ?>
		</button>
		<button
			type="button"
			data-hero-next
			class="absolute right-4 top-1/2 z-20 hidden -translate-y-1/2 items-center justify-center rounded-full bg-white/10 p-2 text-white backdrop-blur-sm transition-all hover:bg-white/20 md:flex"
			aria-label="<?php esc_attr_e( 'Próximo slide', 'faruk-arteterapia' ); ?>"
		>
			<?php faruk_the_icon( 'chevron-right', 'h-6 w-6' ); ?>
		</button>

		<!-- Pontos -->
		<div class="absolute bottom-8 left-1/2 z-20 flex -translate-x-1/2 gap-2">
			<?php for ( $i = 0; $i < $total; $i++ ) : ?>
				<button
					type="button"
					data-hero-dot="<?php echo (int) $i; ?>"
					class="h-2 rounded-full transition-all duration-300 <?php echo 0 === $i ? 'w-8 bg-laranja' : 'w-2 bg-white/40 hover:bg-white/60'; ?>"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %d: número do slide */ __( 'Ir para o slide %d', 'faruk-arteterapia' ), $i + 1 ) ); ?>"
				></button>
			<?php endfor; ?>
		</div>

		<!-- Pausa (discreto, alinhado ao texto, à esquerda) -->
		<div class="pointer-events-none absolute inset-x-0 bottom-[18px] z-20">
			<div class="mx-auto box-content max-w-8xl px-6 md:px-12 lg:px-16">
				<button
					type="button"
					data-hero-pause
					class="pointer-events-auto flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white/70 backdrop-blur-sm transition-all hover:bg-white/20 hover:text-white"
					aria-label="<?php esc_attr_e( 'Pausar carrossel', 'faruk-arteterapia' ); ?>"
					data-label-pause="<?php esc_attr_e( 'Pausar carrossel', 'faruk-arteterapia' ); ?>"
					data-label-play="<?php esc_attr_e( 'Retomar carrossel', 'faruk-arteterapia' ); ?>"
				>
					<span data-icon="pause"><?php faruk_the_icon( 'pause', 'h-4 w-4' ); ?></span>
					<span data-icon="play" class="hidden"><?php faruk_the_icon( 'play', 'h-4 w-4' ); ?></span>
				</button>
			</div>
		</div>

	<?php endif; ?>
</section>
