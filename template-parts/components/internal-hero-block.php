<?php
/**
 * Componente: Hero de Página Interna (bloco "Hero de Página Interna").
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

$defaults = faruk_internal_hero_defaults();
$c        = array();

foreach ( array( 'badge', 'title', 'subtitle', 'cta_label' ) as $key ) {
	$value     = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$c[ $key ] = '' !== $value ? $value : $defaults[ $key ];
}

$image_id = isset( $fields['image'] ) ? absint( $fields['image'] ) : 0;
$cta_type = isset( $fields['cta_type'] ) ? (string) $fields['cta_type'] : 'none';
$gradient = ( isset( $fields['gradient'] ) && 'sage' === $fields['gradient'] )
	? 'bg-gradient-to-br from-sage-dark via-sage-dark to-terracota-dark'
	: 'bg-gradient-to-br from-terracota-dark via-terracota-dark to-sage-dark';

// Sem botão escolhido, o texto do botão fica vazio por padrão.
$show_cta = false;
if ( 'whatsapp' === $cta_type ) {
	$show_cta  = true;
	$cta_href  = faruk_whatsapp_url();
	$cta_event = 'whatsapp_click';
} elseif ( 'link' === $cta_type && ! empty( $fields['cta_url'] ) ) {
	$show_cta  = true;
	$cta_href  = faruk_resolve_link( (string) $fields['cta_url'] );
	$cta_event = 'cta_click';
}

$title_lines = preg_split( '/\R/', trim( $c['title'] ) );
?>
<section class="relative overflow-hidden pt-32 pb-20 text-white md:pt-40 md:pb-28<?php echo esc_attr( ( $image_id ? '' : ' ' . $gradient ) . $extra ); ?>">

	<?php if ( $image_id ) : ?>
		<div class="absolute inset-0">
			<?php
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup gerado pelo WordPress.
				$image_id,
				'full',
				false,
				array(
					'class'         => 'h-full w-full object-cover',
					'alt'           => '',
					'sizes'         => '100vw',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
				)
			);
			?>
		</div>
		<div class="absolute inset-0 bg-gradient-to-r from-marrom/80 via-marrom/60 to-marrom/30"></div>
	<?php else : ?>
		<div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-laranja/10 blur-3xl"></div>
		<div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-terracota-light/10 blur-3xl"></div>
		<div class="absolute right-10 top-32 h-24 w-24 rounded-full border border-white/10"></div>
	<?php endif; ?>

	<div class="container-wide relative z-10 px-6 md:px-12 lg:px-16">
		<div class="max-w-3xl">
			<span class="inline-block rounded-full bg-laranja/20 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-laranja-light ring-1 ring-laranja/30">
				<?php echo esc_html( $c['badge'] ); ?>
			</span>

			<h1 class="mt-5 font-serif text-5xl leading-tight md:text-6xl lg:text-7xl">
				<?php
				foreach ( $title_lines as $line_index => $line ) {
					if ( $line_index > 0 ) {
						echo '<br />';
					}
					echo esc_html( trim( $line ) );
				}
				?>
			</h1>

			<p class="mt-5 max-w-2xl text-lg leading-relaxed text-creme/80 md:text-xl">
				<?php echo esc_html( $c['subtitle'] ); ?>
			</p>

			<?php if ( $show_cta ) : ?>
				<a
					href="<?php echo $cta_href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado (faruk_whatsapp_url / faruk_resolve_link). ?>"
					<?php echo 'whatsapp_click' === $cta_event ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
					class="<?php echo 'whatsapp_click' === $cta_event ? 'bg-[#25D366] hover:bg-[#1eb858] hover:shadow-green-600/30' : 'bg-terracota-light hover:bg-terracota hover:shadow-terracota/30'; ?> mt-8 inline-flex items-center gap-2.5 rounded-full px-8 py-4 text-base font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl"
					data-gtm-event="<?php echo esc_attr( $cta_event ); ?>"
					data-gtm-location="internal_hero"
					data-gtm-item="<?php echo esc_attr( $c['title'] ); ?>"
				>
					<?php if ( 'whatsapp_click' === $cta_event ) : ?>
						<?php faruk_the_icon( 'message-circle', 'h-5 w-5' ); ?>
					<?php endif; ?>
					<?php echo esc_html( $c['cta_label'] ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
