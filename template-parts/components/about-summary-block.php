<?php
/**
 * Componente: Sobre da Home (bloco "Sobre da Home" — "Quem acompanha").
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

$defaults = faruk_about_summary_defaults();
$c        = array();

foreach ( $defaults as $key => $default ) {
	$value     = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$c[ $key ] = '' !== $value ? $value : $default;
}

$photo_id   = isset( $fields['photo'] ) ? absint( $fields['photo'] ) : 0;
$paragraphs = preg_split( '/\R{2,}/', trim( $c['bio_text'] ) );
?>
<section class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'default' ) . $extra ); ?>">
	<div class="container-wide">
		<div class="grid items-center gap-12 lg:grid-cols-5">

			<div class="lg:col-span-2">
				<div class="relative mx-auto max-w-sm">
					<div class="absolute -inset-4 rounded-[2.5rem] bg-gradient-to-br from-terracota-light to-sage-light opacity-40 blur-2xl"></div>
					<div class="relative overflow-hidden rounded-[2rem] shadow-lg">
						<?php
						if ( $photo_id ) {
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup gerado pelo WordPress.
								$photo_id,
								'large',
								false,
								array(
									'class'   => 'aspect-[4/5] w-full object-cover',
									'alt'     => trim( $c['title'] . ' — ' . $c['subtitle'] ),
									'sizes'   => '(min-width: 1024px) 384px, 90vw',
									'loading' => 'lazy',
								)
							);
						} else {
							echo '<div class="aspect-[4/5] w-full bg-gradient-to-br from-terracota/20 to-sage/20"></div>';
						}
						?>
					</div>
				</div>
			</div>

			<div class="lg:col-span-3">
				<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
					<?php echo esc_html( $c['label'] ); ?>
				</span>
				<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
					<?php echo esc_html( $c['title'] ); ?>
				</h2>
				<p class="mt-1 text-lg font-medium text-terracota">
					<?php echo esc_html( $c['subtitle'] ); ?>
				</p>

				<div class="mt-6 space-y-4 text-base leading-relaxed text-marrom/70">
					<?php foreach ( $paragraphs as $paragraph ) : ?>
						<p><?php echo esc_html( trim( $paragraph ) ); ?></p>
					<?php endforeach; ?>
				</div>

				<?php if ( '' !== $c['btn_text'] ) : ?>
					<a
						href="<?php echo faruk_resolve_link( $c['btn_url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_resolve_link(). ?>"
						class="btn-secondary mt-8"
						data-gtm-event="cta_click"
						data-gtm-location="about_summary"
					>
						<?php echo esc_html( $c['btn_text'] ); ?>
						<?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?>
					</a>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
