<?php
/**
 * Componente: Trajetória e Valores (bloco "Trajetória e Valores").
 *
 * Três partes da tela Sobre: Trajetória (texto), Formação e experiência
 * (lista com ícone) e Valores (cards com ícone).
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

$defaults = faruk_trajectory_defaults();
$text     = array();

foreach ( array( 'traj_eyebrow', 'traj_heading', 'traj_text', 'form_eyebrow', 'form_heading', 'val_eyebrow', 'val_heading' ) as $key ) {
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

$paragraphs = preg_split( '/\R{2,}/', trim( $text['traj_text'] ) );
$extra      = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<div class="<?php echo esc_attr( trim( $extra ) ); ?>">

	<!-- Trajetória -->
	<section class="section-padding">
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

	<!-- Formação e experiência -->
	<section class="section-padding bg-creme-dark/50">
		<div class="container-wide">
			<div class="mx-auto max-w-3xl">
				<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
					<?php echo esc_html( $text['form_eyebrow'] ); ?>
				</span>
				<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
					<?php echo esc_html( $text['form_heading'] ); ?>
				</h2>
				<ul class="mt-8 space-y-4">
					<?php foreach ( $form_items as $item ) : ?>
						<li class="flex items-start gap-4 rounded-2xl bg-white p-5 ring-1 ring-marrom/5">
							<span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-terracota/10 text-terracota">
								<?php faruk_the_icon( 'award', 'h-5 w-5' ); ?>
							</span>
							<span class="pt-2 text-sm leading-relaxed text-marrom/80">
								<?php echo esc_html( $item ); ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>

	<!-- Valores -->
	<section class="section-padding">
		<div class="container-wide">
			<div class="mx-auto mb-14 max-w-2xl text-center">
				<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
					<?php echo esc_html( $text['val_eyebrow'] ); ?>
				</span>
				<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
					<?php echo esc_html( $text['val_heading'] ); ?>
				</h2>
			</div>

			<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
				<?php foreach ( $val_items as $value_item ) : ?>
					<div class="group rounded-2xl bg-white p-8 ring-1 ring-marrom/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-terracota-dark/10">
						<div class="flex h-12 w-12 items-center justify-center rounded-xl bg-terracota/10 text-terracota transition-colors group-hover:bg-terracota group-hover:text-white">
							<?php faruk_the_icon( $value_item['icon'], 'h-6 w-6' ); ?>
						</div>
						<h3 class="mt-5 font-serif text-xl text-marrom">
							<?php echo esc_html( $value_item['title'] ); ?>
						</h3>
						<?php if ( ! empty( $value_item['description'] ) ) : ?>
							<p class="mt-3 text-sm leading-relaxed text-marrom/70">
								<?php echo esc_html( $value_item['description'] ); ?>
							</p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

</div>
