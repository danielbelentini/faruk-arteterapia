<?php
/**
 * Componente: Supervisão para Arteterapeutas (bloco "Supervisão para Arteterapeutas").
 *
 * Duas colunas (imagem + texto), com as modalidades (grupo e individual)
 * em destaque e botão para o formulário externo. Evento GTM:
 * supervision_lead_click.
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

$defaults = faruk_supervision_defaults();
$text     = array();

foreach ( array( 'section_id', 'eyebrow', 'heading', 'text', 'button' ) as $key ) {
	$value        = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$text[ $key ] = '' !== $value ? $value : $defaults[ $key ];
}

$items = array();
if ( ! empty( $fields['items'] ) && is_array( $fields['items'] ) ) {
	foreach ( $fields['items'] as $row ) {
		if ( ! empty( $row['text'] ) ) {
			$items[] = array(
				'icon' => ! empty( $row['icon'] ) ? $row['icon'] : 'users',
				'text' => $row['text'],
			);
		}
	}
}
if ( empty( $items ) ) {
	$items = $defaults['items'];
}

$image_id   = ! empty( $fields['image'] ) ? absint( $fields['image'] ) : 0;
$image_left = ! isset( $fields['image_side'] ) || 'right' !== $fields['image_side'];
$url        = isset( $fields['url'] ) ? (string) $fields['url'] : '';
$section_id = sanitize_title( $text['section_id'] );
$extra      = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
$text_class = $image_left ? '' : ' lg:order-first';
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'default' ) . $extra ); ?>">
	<div class="container-wide">
		<div class="grid items-center gap-12 lg:grid-cols-2">

			<!-- Imagem -->
			<div class="relative">
				<div class="absolute -inset-4 rounded-[2.5rem] bg-gradient-to-br from-sage/20 to-terracota/20 opacity-50 blur-2xl"></div>
				<?php if ( $image_id ) : ?>
					<?php
					echo wp_get_attachment_image(
						$image_id,
						'large',
						false,
						array(
							'class'   => 'relative aspect-[4/3] w-full rounded-[2rem] object-cover shadow-lg',
							'loading' => 'lazy',
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup gerado pelo WordPress.
					?>
				<?php else : ?>
					<div class="relative flex aspect-[4/3] w-full items-center justify-center rounded-[2rem] bg-gradient-to-br from-sage to-sage-dark text-white/80 shadow-lg" role="img" aria-label="<?php echo esc_attr( $text['heading'] ); ?>">
						<?php faruk_the_icon( 'messages-square', 'h-20 w-20' ); ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Texto -->
			<div class="<?php echo esc_attr( trim( $text_class ) ); ?>">
				<span class="text-sm font-semibold uppercase tracking-wider text-sage">
					<?php echo esc_html( $text['eyebrow'] ); ?>
				</span>
				<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
					<?php echo esc_html( $text['heading'] ); ?>
				</h2>
				<p class="mt-6 text-base leading-relaxed text-marrom/75">
					<?php echo esc_html( $text['text'] ); ?>
				</p>

				<ul class="mt-6 space-y-3">
					<?php foreach ( $items as $item ) : ?>
						<li class="flex items-center gap-3 rounded-xl bg-white p-4 ring-1 ring-marrom/5">
							<span class="shrink-0 text-sage"><?php faruk_the_icon( $item['icon'], 'h-5 w-5' ); ?></span>
							<span class="text-sm text-marrom/75"><?php echo esc_html( $item['text'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>

				<a
					href="<?php echo faruk_resolve_link( $url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_resolve_link(). ?>"
					<?php echo 0 === strpos( trim( $url ), 'http' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
					class="btn-secondary mt-8"
					data-gtm-event="form_lead_click"
					data-gtm-location="supervision"
					data-gtm-lead-type="supervision"
					data-gtm-item="<?php echo esc_attr( $text['heading'] ); ?>"
				>
					<?php echo esc_html( $text['button'] ); ?>
					<?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?>
				</a>
			</div>

		</div>
	</div>
</section>
