<?php
/**
 * Componente: Formação e Valores (bloco "Formação e Valores").
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

foreach ( array( 'form_eyebrow', 'form_heading', 'val_eyebrow', 'val_heading' ) as $key ) {
	$value        = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$text[ $key ] = '' !== $value ? $value : $defaults[ $key ];
}

// Formação: lista de textos.
$form_items = array();
if ( ! empty( $fields['form_items'] ) && is_array( $fields['form_items'] ) ) {
	foreach ( $fields['form_items'] as $row ) {
		$item = isset( $row['text'] ) ? trim( (string) $row['text'] ) : '';
		if ( '' !== $item ) {
			$form_items[] = $item;
		}
	}
}
if ( empty( $form_items ) ) {
	$form_items = $defaults['form_items'];
}

// Valores: cards.
$val_items = array();
if ( ! empty( $fields['val_items'] ) && is_array( $fields['val_items'] ) ) {
	foreach ( $fields['val_items'] as $row ) {
		if ( empty( $row['title'] ) ) {
			continue;
		}
		$val_items[] = array(
			'icon'        => ! empty( $row['icon'] ) ? $row['icon'] : 'heart-handshake',
			'title'       => $row['title'],
			'description' => isset( $row['description'] ) ? $row['description'] : '',
		);
	}
}
if ( empty( $val_items ) ) {
	$val_items = $defaults['val_items'];
}
?>
<section class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'sand' ) . $extra ); ?>">
	<div class="container-wide">
		<div class="grid gap-12 lg:grid-cols-2">

			<!-- Formação e experiência -->
			<div>
				<span class="text-sm font-semibold uppercase tracking-wider text-sage">
					<?php echo esc_html( $text['form_eyebrow'] ); ?>
				</span>
				<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
					<?php echo esc_html( $text['form_heading'] ); ?>
				</h2>
				<ul class="mt-8 space-y-4">
					<?php foreach ( $form_items as $item ) : ?>
						<li class="flex items-start gap-3 rounded-2xl bg-white p-5 ring-1 ring-marrom/5 transition-shadow hover:shadow-sm">
							<span class="mt-0.5 shrink-0 text-laranja-dark">
								<?php faruk_the_icon( 'award', 'h-5 w-5' ); ?>
							</span>
							<span class="text-sm leading-relaxed text-marrom/70">
								<?php echo esc_html( $item ); ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- Valores -->
			<div>
				<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
					<?php echo esc_html( $text['val_eyebrow'] ); ?>
				</span>
				<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
					<?php echo esc_html( $text['val_heading'] ); ?>
				</h2>
				<div class="mt-8 grid gap-4 sm:grid-cols-2">
					<?php foreach ( $val_items as $value_item ) : ?>
						<div class="group rounded-2xl bg-white p-6 ring-1 ring-marrom/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-terracota-dark/10">
							<div class="flex h-12 w-12 items-center justify-center rounded-xl bg-terracota/10 text-terracota transition-colors group-hover:bg-terracota group-hover:text-white">
								<?php faruk_the_icon( $value_item['icon'], 'h-6 w-6' ); ?>
							</div>
							<h3 class="mt-4 font-serif text-lg text-marrom">
								<?php echo esc_html( $value_item['title'] ); ?>
							</h3>
							<?php if ( ! empty( $value_item['description'] ) ) : ?>
								<p class="mt-2 text-sm leading-relaxed text-marrom/65">
									<?php echo esc_html( $value_item['description'] ); ?>
								</p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</section>
