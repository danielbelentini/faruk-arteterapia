<?php
/**
 * Componente: Depoimentos (bloco "Depoimentos").
 *
 * - Até 3 relatos: grade de 3 colunas a partir de md; no celular, carrossel.
 * - Mais de 3 relatos: carrossel também no desktop (3 por vez, com setas).
 * - No celular: só os pontos de navegação, sem setas.
 * O Swiper (local) é iniciado por assets/js/testimonials-carousel.js.
 * Na prévia do editor, mostra uma grade estática com até 3 relatos.
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

$defaults = faruk_testimonials_defaults();

$eyebrow = isset( $fields['eyebrow'] ) && '' !== trim( (string) $fields['eyebrow'] ) ? trim( (string) $fields['eyebrow'] ) : $defaults['eyebrow'];
$heading = isset( $fields['heading'] ) && '' !== trim( (string) $fields['heading'] ) ? trim( (string) $fields['heading'] ) : $defaults['heading'];

$items = array();
if ( ! empty( $fields['items'] ) && is_array( $fields['items'] ) ) {
	foreach ( $fields['items'] as $row ) {
		if ( ! empty( $row['text'] ) && ! empty( $row['author'] ) ) {
			$items[] = array(
				'text'   => $row['text'],
				'author' => $row['author'],
				'role'   => isset( $row['role'] ) ? $row['role'] : '',
			);
		}
	}
}
if ( empty( $items ) ) {
	$items = $defaults['items'];
}

$is_editor = faruk_is_editor_request();

if ( $is_editor ) {
	$items = array_slice( $items, 0, 3 );
}

$total       = count( $items );
$many        = $total > 3;
$use_swiper  = ! $is_editor;
$extra       = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';

if ( $use_swiper ) {
	wp_enqueue_style( 'swiper' );
	wp_enqueue_script( 'faruk-testimonials' );
}

// Controles: com mais de 3 relatos, setas só a partir de md; com até 3, só pontos e só no celular.
$controls_class = 'mt-8 flex items-center justify-center gap-4' . ( $many ? '' : ' md:hidden' );
?>
<section class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'sand' ) . $extra ); ?>">
	<div class="container-wide">

		<div class="mx-auto mb-14 max-w-2xl text-center">
			<span class="text-sm font-semibold uppercase tracking-wider text-laranja-dark">
				<?php echo esc_html( $eyebrow ); ?>
			</span>
			<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
				<?php echo esc_html( $heading ); ?>
			</h2>
		</div>

		<div
			<?php echo $use_swiper ? 'data-testimonials data-count="' . (int) $total . '"' : ''; ?>
			role="region"
			aria-roledescription="carousel"
			aria-label="<?php echo esc_attr( $heading ); ?>"
		>
			<?php // O Swiper corta o que passa da área (overflow hidden) e o CSS dele zera o padding (por isso o ! do Tailwind): o padding vertical dá espaço às sombras e a margem negativa compensa, mantendo o espaçamento original. ?>
			<div class="<?php echo $use_swiper ? 'swiper !-mb-2 !-mt-8 !py-10' : 'grid gap-6 md:grid-cols-3'; ?>">
				<div class="<?php echo $use_swiper ? 'swiper-wrapper' : 'contents'; ?>">
					<?php foreach ( $items as $item ) : ?>
						<div class="<?php echo $use_swiper ? 'swiper-slide !h-auto' : 'contents'; ?>">
							<figure class="card-base group h-full p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-terracota-dark/5">
								<span class="text-terracota/30 transition-colors group-hover:text-terracota/50">
									<?php faruk_the_icon( 'quote', 'h-8 w-8' ); ?>
								</span>
								<blockquote class="mt-4 text-sm leading-relaxed text-marrom/75">
									&ldquo;<?php echo esc_html( $item['text'] ); ?>&rdquo;
								</blockquote>
								<figcaption class="mt-6 flex items-center gap-3">
									<span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-terracota-light to-sage-light text-sm font-semibold text-white" aria-hidden="true">
										<?php echo esc_html( mb_strtoupper( mb_substr( trim( $item['author'] ), 0, 1 ) ) ); ?>
									</span>
									<span>
										<span class="block text-sm font-semibold text-marrom"><?php echo esc_html( $item['author'] ); ?></span>
										<?php if ( '' !== $item['role'] ) : ?>
											<span class="block text-xs text-marrom/50"><?php echo esc_html( $item['role'] ); ?></span>
										<?php endif; ?>
									</span>
								</figcaption>
							</figure>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $use_swiper ) : ?>
				<div class="<?php echo esc_attr( $controls_class ); ?>">
					<?php if ( $many ) : ?>
						<button
							type="button"
							data-t-prev
							class="hidden h-10 w-10 items-center justify-center rounded-full bg-white text-terracota ring-1 ring-marrom/10 transition-all hover:bg-terracota hover:text-white md:flex"
							aria-label="<?php esc_attr_e( 'Depoimento anterior', 'faruk-arteterapia' ); ?>"
						>
							<?php faruk_the_icon( 'chevron-left', 'h-5 w-5' ); ?>
						</button>
					<?php endif; ?>

					<div class="flex items-center gap-2" data-t-dots></div>

					<?php if ( $many ) : ?>
						<button
							type="button"
							data-t-next
							class="hidden h-10 w-10 items-center justify-center rounded-full bg-white text-terracota ring-1 ring-marrom/10 transition-all hover:bg-terracota hover:text-white md:flex"
							aria-label="<?php esc_attr_e( 'Próximo depoimento', 'faruk-arteterapia' ); ?>"
						>
							<?php faruk_the_icon( 'chevron-right', 'h-5 w-5' ); ?>
						</button>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
