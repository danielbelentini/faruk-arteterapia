<?php
/**
 * Componente: Conceito Iceberg (bloco "Conceito Iceberg").
 *
 * Texto explicativo ao lado da ilustração do iceberg (consciente / inconsciente).
 * Usado na Início ("O que é a Arteterapia Junguiana?") e na Arteterapia ("A abordagem junguiana").
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

$defaults = faruk_iceberg_defaults();
$content  = array();

foreach ( $defaults as $key => $default ) {
	$value           = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$content[ $key ] = '' !== $value ? $value : $default;
}

$side       = ( isset( $fields['iceberg_side'] ) && 'left' === $fields['iceberg_side'] ) ? 'left' : 'right';
$custom_id  = ! empty( $fields['custom_image'] ) ? absint( $fields['custom_image'] ) : 0;
$uid        = wp_unique_id( 'iceberg-' );
$extra      = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';

$section_class = 'section-padding' . faruk_section_bg_class( $fields, 'default' ) . $extra;
$figure_class  = 'flex justify-center' . ( 'left' === $side ? ' lg:order-first' : '' );
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
	<div class="container-wide">
		<div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-20">

			<div>
				<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
					<?php echo esc_html( $content['eyebrow'] ); ?>
				</span>
				<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
					<?php echo esc_html( $content['heading'] ); ?>
				</h2>
				<p class="mt-6 text-base leading-relaxed text-marrom/70">
					<?php echo esc_html( $content['paragraph_1'] ); ?>
				</p>
				<p class="mt-4 text-base leading-relaxed text-marrom/70">
					<?php echo esc_html( $content['paragraph_2'] ); ?>
				</p>
			</div>

			<div class="<?php echo esc_attr( $figure_class ); ?>">
				<div class="w-full max-w-md animate-gentle-float">
					<?php if ( $custom_id ) : ?>
						<?php
						echo wp_get_attachment_image(
							$custom_id,
							'large',
							false,
							array(
								'class'   => 'h-auto w-full',
								'loading' => 'lazy',
							)
						);
						?>
					<?php else : ?>
						<svg viewBox="0 0 400 480" class="h-auto w-full" role="img" aria-label="<?php echo esc_attr( $content['label_conscious'] . ' / ' . $content['label_unconscious'] ); ?>" xmlns="http://www.w3.org/2000/svg">
							<defs>
								<linearGradient id="<?php echo esc_attr( $uid ); ?>-top" x1="0" y1="0" x2="0" y2="1">
									<stop offset="0%" stop-color="#C46A4A" />
									<stop offset="100%" stop-color="#A94A2A" />
								</linearGradient>
								<linearGradient id="<?php echo esc_attr( $uid ); ?>-bottom" x1="0" y1="0" x2="0" y2="1">
									<stop offset="0%" stop-color="#6E8564" />
									<stop offset="100%" stop-color="#3D4F36" />
								</linearGradient>
							</defs>

							<!-- Linha d'água -->
							<rect x="0" y="150" width="400" height="330" fill="#6E8564" fill-opacity="0.1" />
							<line x1="0" y1="150" x2="400" y2="150" stroke="#546A4B" stroke-opacity="0.4" stroke-width="2" stroke-dasharray="8 6" />

							<!-- Ponta visível -->
							<polygon points="200,30 255,150 145,150" fill="url(#<?php echo esc_attr( $uid ); ?>-top)" />

							<!-- Parte submersa -->
							<polygon points="145,150 255,150 340,290 290,420 200,460 110,420 60,290" fill="url(#<?php echo esc_attr( $uid ); ?>-bottom)" fill-opacity="0.85" />

							<!-- Rótulos -->
							<text x="330" y="100" text-anchor="end" font-size="16" fill="#A94A2A" class="animate-fade-in" style="animation-delay:0.4s;animation-fill-mode:both;font-family:Outfit,sans-serif">
								<?php echo esc_html( $content['label_conscious'] ); ?>
							</text>
							<text x="200" y="320" text-anchor="middle" font-size="18" fill="#FFF5EA" class="animate-fade-in" style="animation-delay:0.8s;animation-fill-mode:both;font-family:Outfit,sans-serif">
								<?php echo esc_html( $content['label_unconscious'] ); ?>
							</text>
						</svg>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>
</section>
