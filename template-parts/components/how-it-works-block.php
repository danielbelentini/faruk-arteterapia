<?php
/**
 * Componente: Como Funciona (bloco "Como Funciona").
 *
 * Etapas numeradas (01, 02, 03...) em cards.
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

$defaults = faruk_how_it_works_defaults();

$eyebrow = isset( $fields['eyebrow'] ) && '' !== trim( (string) $fields['eyebrow'] ) ? trim( (string) $fields['eyebrow'] ) : $defaults['eyebrow'];
$heading = isset( $fields['heading'] ) && '' !== trim( (string) $fields['heading'] ) ? trim( (string) $fields['heading'] ) : $defaults['heading'];

$steps = array();
if ( ! empty( $fields['steps'] ) && is_array( $fields['steps'] ) ) {
	foreach ( $fields['steps'] as $row ) {
		if ( ! empty( $row['title'] ) ) {
			$steps[] = array(
				'title'       => $row['title'],
				'description' => isset( $row['description'] ) ? $row['description'] : '',
			);
		}
	}
}
if ( empty( $steps ) ) {
	$steps = $defaults['steps'];
}

$extra = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<section class="section-padding bg-creme-dark/50<?php echo esc_attr( $extra ); ?>">
	<div class="container-wide">

		<div class="mx-auto mb-14 max-w-2xl text-center">
			<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
				<?php echo esc_html( $eyebrow ); ?>
			</span>
			<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
				<?php echo esc_html( $heading ); ?>
			</h2>
		</div>

		<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
			<?php foreach ( $steps as $index => $step ) : ?>
				<div class="group rounded-3xl bg-white p-8 ring-1 ring-marrom/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-terracota-dark/10">
					<span class="font-serif text-5xl text-terracota-light transition-colors group-hover:text-terracota">
						<?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>
					</span>
					<h3 class="mt-4 font-serif text-xl text-marrom">
						<?php echo esc_html( $step['title'] ); ?>
					</h3>
					<?php if ( ! empty( $step['description'] ) ) : ?>
						<p class="mt-3 text-sm leading-relaxed text-marrom/70">
							<?php echo esc_html( $step['description'] ); ?>
						</p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
