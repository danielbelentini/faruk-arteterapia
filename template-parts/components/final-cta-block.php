<?php
/**
 * Componente: Chamada Final WhatsApp (bloco "Chamada Final WhatsApp").
 *
 * Portado do CTA.tsx do protótipo ("Pronto para começar?").
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

$defaults = faruk_final_cta_defaults();
$c        = array();

foreach ( $defaults as $key => $default ) {
	$value     = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$c[ $key ] = '' !== $value ? $value : $default;
}

$extra = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<section class="relative flex items-center justify-center overflow-hidden bg-gradient-to-br from-terracota via-terracota-dark to-sage-dark px-6 py-16 text-center md:min-h-[480px] md:px-12 md:py-20 lg:px-16<?php echo esc_attr( $extra ); ?>">
	<div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-white/5 blur-2xl"></div>
	<div class="absolute -bottom-20 -right-20 h-64 w-64 rounded-full bg-laranja/10 blur-2xl"></div>
	<div class="absolute right-10 top-10 h-32 w-32 rounded-full border border-white/10"></div>

	<div class="relative z-10 mx-auto w-full max-w-3xl">
		<h2 class="font-serif text-4xl text-white md:text-5xl">
			<?php echo esc_html( $c['title'] ); ?>
		</h2>
		<p class="mx-auto mt-4 max-w-xl text-lg text-creme/80">
			<?php echo esc_html( $c['subtitle'] ); ?>
		</p>
		<a
			href="<?php echo faruk_whatsapp_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_whatsapp_url(). ?>"
			target="_blank"
			rel="noopener noreferrer"
			class="mt-8 inline-flex items-center gap-2.5 rounded-full bg-[#25D366] px-8 py-4 text-base font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#1eb858] hover:shadow-xl hover:shadow-green-600/30"
			data-gtm-event="whatsapp_click"
			data-gtm-location="final_cta"
		>
			<?php faruk_the_icon( 'message-circle', 'h-5 w-5' ); ?>
			<?php echo esc_html( $c['button'] ); ?>
		</a>
	</div>
</section>
