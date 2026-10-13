<?php
/**
 * Componente: Definição (bloco "Definição" — "O que é arteterapia?").
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
$extra      = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';

$defaults = faruk_definition_defaults();
$c        = array();

foreach ( $defaults as $key => $default ) {
	$value     = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$c[ $key ] = '' !== $value ? $value : $default;
}

$image_id = isset( $fields['image'] ) ? absint( $fields['image'] ) : 0;
?>
<section class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'default' ) . $extra ); ?>">
	<div class="container-wide">
		<div class="grid items-center gap-12 lg:grid-cols-2">

			<div>
				<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
					<?php echo esc_html( $c['eyebrow'] ); ?>
				</span>
				<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
					<?php echo esc_html( $c['heading'] ); ?>
				</h2>
				<p class="mt-6 text-base leading-relaxed text-marrom/70">
					<?php echo esc_html( $c['text'] ); ?>
				</p>

				<div class="mt-8 rounded-2xl border-l-4 border-laranja bg-laranja/10 p-6">
					<div class="flex items-start gap-3">
						<span class="shrink-0 text-laranja-dark">
							<?php faruk_the_icon( 'sparkles', 'h-6 w-6' ); ?>
						</span>
						<div>
							<p class="font-serif text-xl text-marrom">
								<?php echo esc_html( $c['highlight_title'] ); ?>
							</p>
							<p class="mt-1 text-sm leading-relaxed text-marrom/70">
								<?php echo esc_html( $c['highlight_text'] ); ?>
							</p>
						</div>
					</div>
				</div>
			</div>

			<div class="relative">
				<div class="absolute -inset-4 rounded-[2.5rem] bg-gradient-to-br from-terracota-light to-laranja-light opacity-50 blur-2xl"></div>
				<?php
				if ( $image_id ) {
					echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup gerado pelo WordPress.
						$image_id,
						'large',
						false,
						array(
							'class'   => 'relative aspect-[4/3] w-full rounded-[2rem] object-cover shadow-lg',
							'alt'     => __( 'Materiais e processo de criação em arteterapia', 'faruk-arteterapia' ),
							'sizes'   => '(min-width: 1024px) 560px, 90vw',
							'loading' => 'lazy',
						)
					);
				} else {
					echo '<div class="relative aspect-[4/3] w-full rounded-[2rem] bg-gradient-to-br from-terracota/20 to-sage/20 shadow-lg"></div>';
				}
				?>
			</div>

		</div>
	</div>
</section>
