<?php
/**
 * Componente: Linguagens e Materiais (bloco "Linguagens e Materiais").
 *
 * Faixa escura (verde sage) com os recursos artísticos em grade de cards.
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

$defaults = faruk_materials_defaults();
$text     = array();

foreach ( array( 'eyebrow', 'heading', 'intro' ) as $key ) {
	$value        = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$text[ $key ] = '' !== $value ? $value : $defaults[ $key ];
}

$items = array();
if ( ! empty( $fields['items'] ) && is_array( $fields['items'] ) ) {
	foreach ( $fields['items'] as $row ) {
		if ( ! empty( $row['name'] ) ) {
			$items[] = array(
				'icon' => ! empty( $row['icon'] ) ? $row['icon'] : 'palette',
				'name' => $row['name'],
			);
		}
	}
}
if ( empty( $items ) ) {
	$items = $defaults['items'];
}

$is_dark = ! isset( $fields['background'] ) || '' === $fields['background'] || 'dark' === $fields['background'];
$bg_cls  = $is_dark ? ' bg-sage-dark text-white' : faruk_section_bg_class( $fields );
$c_label = $is_dark ? 'text-laranja' : 'text-terracota';
$c_title = $is_dark ? 'text-white' : 'text-marrom';
$c_intro = $is_dark ? 'text-creme/70' : 'text-marrom/70';
$c_card  = $is_dark ? 'bg-white/5 ring-white/10 hover:bg-white/10' : 'bg-white ring-marrom/5 hover:shadow-md';
$c_icon  = $is_dark ? 'text-sage-light group-hover:text-laranja' : 'text-sage group-hover:text-terracota';
$c_name  = $is_dark ? 'text-creme/80' : 'text-marrom/80';
$extra = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<section class="section-padding<?php echo esc_attr( $bg_cls . $extra ); ?>">
	<div class="container-wide">

		<div class="mx-auto mb-14 max-w-2xl text-center">
			<span class="text-sm font-semibold uppercase tracking-wider <?php echo esc_attr( $c_label ); ?>">
				<?php echo esc_html( $text['eyebrow'] ); ?>
			</span>
			<h2 class="mt-3 font-serif text-4xl <?php echo esc_attr( $c_title ); ?> md:text-5xl">
				<?php echo esc_html( $text['heading'] ); ?>
			</h2>
			<p class="mt-5 text-base leading-relaxed <?php echo esc_attr( $c_intro ); ?>">
				<?php echo esc_html( $text['intro'] ); ?>
			</p>
		</div>

		<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7">
			<?php foreach ( $items as $item ) : ?>
				<div class="group flex flex-col items-center rounded-2xl p-6 text-center ring-1 transition-colors <?php echo esc_attr( $c_card ); ?>">
					<span class="inline-flex transition-all group-hover:scale-110 <?php echo esc_attr( $c_icon ); ?>">
						<?php faruk_the_icon( $item['icon'], 'h-8 w-8' ); ?>
					</span>
					<span class="mt-4 text-sm font-medium <?php echo esc_attr( $c_name ); ?>">
						<?php echo esc_html( $item['name'] ); ?>
					</span>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
