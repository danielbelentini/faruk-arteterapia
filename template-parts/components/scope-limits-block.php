<?php
/**
 * Componente: Escopo e Limites (bloco "Escopo e Limites").
 *
 * Dois cards: "Para quem é" (ícones em verde) e "Limites do atendimento"
 * (ícones sutis de informação). Textos do protótipo (ArteterapiaPage.tsx).
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

$defaults = faruk_scope_defaults();
$text     = array();

foreach ( array( 'eyebrow', 'heading', 'card1_title', 'card2_title' ) as $key ) {
	$value        = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$text[ $key ] = '' !== $value ? $value : $defaults[ $key ];
}

/**
 * Lê uma lista de itens (ícone + texto), com padrão do protótipo se vazia.
 */
$read_items = static function ( $raw, array $fallback ): array {
	$items = array();

	if ( is_array( $raw ) ) {
		foreach ( $raw as $row ) {
			if ( ! empty( $row['text'] ) ) {
				$items[] = array(
					'icon' => ! empty( $row['icon'] ) ? $row['icon'] : 'circle-check',
					'text' => $row['text'],
				);
			}
		}
	}

	return empty( $items ) ? $fallback : $items;
};

$card1 = $read_items( isset( $fields['card1_items'] ) ? $fields['card1_items'] : array(), $defaults['card1_items'] );
$card2 = $read_items( isset( $fields['card2_items'] ) ? $fields['card2_items'] : array(), $defaults['card2_items'] );

$extra = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<section class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'default' ) . $extra ); ?>">
	<div class="container-wide">

		<div class="mx-auto mb-14 max-w-2xl text-center">
			<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
				<?php echo esc_html( $text['eyebrow'] ); ?>
			</span>
			<h2 class="mt-3 font-serif text-3xl text-marrom md:text-4xl">
				<?php echo esc_html( $text['heading'] ); ?>
			</h2>
		</div>

		<div class="mx-auto grid max-w-5xl gap-6 md:grid-cols-2">

			<!-- Card 1: para quem é -->
			<div class="rounded-[2rem] bg-gradient-to-br from-creme-dark to-creme-light p-10 ring-1 ring-marrom/5 md:p-12">
				<h3 class="font-serif text-2xl text-marrom">
					<?php echo esc_html( $text['card1_title'] ); ?>
				</h3>
				<ul class="mt-6 space-y-4">
					<?php foreach ( $card1 as $item ) : ?>
						<li class="flex items-start gap-3">
							<span class="mt-0.5 shrink-0 text-sage">
								<?php faruk_the_icon( $item['icon'], 'h-5 w-5' ); ?>
							</span>
							<p class="text-sm leading-relaxed text-marrom/75">
								<?php echo esc_html( $item['text'] ); ?>
							</p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- Card 2: ressalvas e limites -->
			<div class="rounded-[2rem] bg-white p-10 ring-1 ring-marrom/5 md:p-12">
				<h3 class="font-serif text-2xl text-marrom">
					<?php echo esc_html( $text['card2_title'] ); ?>
				</h3>
				<ul class="mt-6 space-y-4">
					<?php foreach ( $card2 as $item ) : ?>
						<li class="flex items-start gap-3">
							<span class="mt-0.5 shrink-0 text-laranja-dark">
								<?php faruk_the_icon( $item['icon'], 'h-5 w-5' ); ?>
							</span>
							<p class="text-sm leading-relaxed text-marrom/75">
								<?php echo esc_html( $item['text'] ); ?>
							</p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

		</div>
	</div>
</section>
