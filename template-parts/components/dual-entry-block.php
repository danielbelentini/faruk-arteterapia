<?php
/**
 * Componente: Duas Portas de Entrada (bloco "Duas Portas de Entrada").
 *
 * Portado da seção "Duas portas de entrada" da HomePage.tsx do protótipo:
 * rótulo + título centralizados e dois cards (individual e grupos), cada um
 * com ícone, título, rótulo, descrição e link de texto com seta.
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

// Campos vazios usam o texto padrão do protótipo.
$defaults = faruk_dual_entry_defaults();
$content  = array();

foreach ( $defaults as $key => $default ) {
	$value           = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$content[ $key ] = '' !== $value ? $value : $default;
}

$extra_class = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<section class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'default' ) . $extra_class ); ?>">
	<div class="container-wide">

		<div class="mx-auto mb-14 max-w-2xl text-center">
			<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
				<?php echo esc_html( $content['eyebrow'] ); ?>
			</span>
			<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
				<?php echo esc_html( $content['heading'] ); ?>
			</h2>
		</div>

		<div class="grid gap-6 md:grid-cols-2">

			<!-- Card 1: atendimento individual -->
			<div class="group relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-terracota/10 to-creme-light p-10 ring-1 ring-terracota/20 transition-all duration-500 hover:shadow-xl hover:shadow-terracota-dark/5">
				<div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-terracota/20 blur-3xl transition-opacity group-hover:opacity-80"></div>
				<div class="relative z-10">
					<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-terracota text-white">
						<?php faruk_the_icon( 'message-circle', 'h-7 w-7' ); ?>
					</div>
					<h3 class="mt-6 font-serif text-2xl text-marrom">
						<?php echo esc_html( $content['card1_title'] ); ?>
					</h3>
					<p class="mt-1 text-sm font-medium text-terracota">
						<?php echo esc_html( $content['card1_label'] ); ?>
					</p>
					<p class="mt-4 text-sm leading-relaxed text-marrom/70">
						<?php echo esc_html( $content['card1_text'] ); ?>
					</p>
					<a
						href="<?php echo faruk_resolve_link( $content['card1_link_url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_resolve_link(). ?>"
						class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-terracota transition-all hover:gap-3"
						data-gtm-event="cta_click"
						data-gtm-location="dual_entry"
						data-gtm-item="<?php echo esc_attr( $content['card1_title'] ); ?>"
					>
						<?php echo esc_html( $content['card1_link_text'] ); ?>
						<?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?>
					</a>
				</div>
			</div>

			<!-- Card 2: grupos, projetos e vivências -->
			<div class="group relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-sage/10 to-creme-light p-10 ring-1 ring-sage/20 transition-all duration-500 hover:shadow-xl hover:shadow-sage-dark/5">
				<div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-sage/20 blur-3xl transition-opacity group-hover:opacity-80"></div>
				<div class="relative z-10">
					<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sage text-white">
						<?php faruk_the_icon( 'waves', 'h-7 w-7' ); ?>
					</div>
					<h3 class="mt-6 font-serif text-2xl text-marrom">
						<?php echo esc_html( $content['card2_title'] ); ?>
					</h3>
					<p class="mt-1 text-sm font-medium text-sage">
						<?php echo esc_html( $content['card2_label'] ); ?>
					</p>
					<p class="mt-4 text-sm leading-relaxed text-marrom/70">
						<?php echo esc_html( $content['card2_text'] ); ?>
					</p>
					<a
						href="<?php echo faruk_resolve_link( $content['card2_link_url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_resolve_link(). ?>"
						class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-sage transition-all hover:gap-3"
						data-gtm-event="cta_click"
						data-gtm-location="dual_entry"
						data-gtm-item="<?php echo esc_attr( $content['card2_title'] ); ?>"
					>
						<?php echo esc_html( $content['card2_link_text'] ); ?>
						<?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?>
					</a>
				</div>
			</div>

		</div>
	</div>
</section>
