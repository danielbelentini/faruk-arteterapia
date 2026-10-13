<?php
/**
 * Componente: Trajetória (bloco "Trajetória").
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

$defaults = faruk_trajectory_defaults();
$text     = array();

foreach ( array( 'traj_eyebrow', 'traj_heading', 'traj_text' ) as $key ) {
	$value        = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$text[ $key ] = '' !== $value ? $value : $defaults[ $key ];
}

$paragraphs = preg_split( '/\R{2,}/', trim( $text['traj_text'] ) );
?>
<section class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'default' ) . $extra ); ?>">
	<div class="container-wide">
		<div class="mx-auto max-w-3xl">
			<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
				<?php echo esc_html( $text['traj_eyebrow'] ); ?>
			</span>
			<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
				<?php echo esc_html( $text['traj_heading'] ); ?>
			</h2>
			<div class="mt-8 space-y-5 text-base leading-relaxed text-marrom/70">
				<?php foreach ( $paragraphs as $paragraph ) : ?>
					<p><?php echo esc_html( trim( $paragraph ) ); ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
