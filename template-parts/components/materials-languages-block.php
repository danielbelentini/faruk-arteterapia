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

$extra = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<section class="section-padding bg-sage-dark text-white<?php echo esc_attr( $extra ); ?>">
	<div class="container-wide">

		<div class="mx-auto mb-14 max-w-2xl text-center">
			<span class="text-sm font-semibold uppercase tracking-wider text-laranja">
				<?php echo esc_html( $text['eyebrow'] ); ?>
			</span>
			<h2 class="mt-3 font-serif text-4xl text-white md:text-5xl">
				<?php echo esc_html( $text['heading'] ); ?>
			</h2>
			<p class="mt-5 text-base leading-relaxed text-creme/70">
				<?php echo esc_html( $text['intro'] ); ?>
			</p>
		</div>

		<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7">
			<?php foreach ( $items as $item ) : ?>
				<div class="group flex flex-col items-center rounded-2xl bg-white/5 p-6 text-center ring-1 ring-white/10 transition-colors hover:bg-white/10">
					<span class="inline-flex text-sage-light transition-all group-hover:scale-110 group-hover:text-laranja">
						<?php faruk_the_icon( $item['icon'], 'h-8 w-8' ); ?>
					</span>
					<span class="mt-4 text-sm font-medium text-creme/80">
						<?php echo esc_html( $item['name'] ); ?>
					</span>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
