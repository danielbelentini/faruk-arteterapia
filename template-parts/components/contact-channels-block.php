<?php
/**
 * Componente: Canais de Contato (bloco "Canais de Contato").
 *
 * Portado da ContatoPage.tsx do protótipo, em três partes:
 *  1. cards de WhatsApp (canal principal) e Instagram;
 *  2. cards Online e Presencial;
 *  3. chamada "Outros formatos" (grupos, escolas, empresas e supervisão).
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

$defaults = faruk_contact_defaults();
$c        = array();

foreach ( $defaults as $key => $default ) {
	$value     = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$c[ $key ] = '' !== $value ? $value : $default;
}

$show_instagram = empty( $fields['hide_instagram'] );
$show_others    = empty( $fields['hide_others'] );
$extra          = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
$channels_grid  = $show_instagram ? 'grid gap-6 md:grid-cols-2' : 'mx-auto grid max-w-2xl gap-6';
?>
<div class="<?php echo esc_attr( trim( $extra ) ); ?>">

	<!-- Canais: WhatsApp e Instagram -->
	<section class="section-padding">
		<div class="container-wide">
			<div class="<?php echo esc_attr( $channels_grid ); ?>">

				<a
					href="<?php echo faruk_whatsapp_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_whatsapp_url(). ?>"
					target="_blank"
					rel="noopener noreferrer"
					class="group relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#25D366] to-[#1eb858] p-10 text-white transition-all duration-500 hover:-translate-y-1 hover:shadow-xl hover:shadow-green-600/20"
					data-gtm-event="whatsapp_click"
					data-gtm-location="contact_section"
				>
					<div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
					<div class="relative z-10">
						<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm">
							<?php faruk_the_icon( 'message-circle', 'h-7 w-7' ); ?>
						</div>
						<h3 class="mt-6 font-serif text-2xl">WhatsApp</h3>
						<p class="mt-1 text-sm font-medium text-white/80"><?php echo esc_html( $c['wa_label'] ); ?></p>
						<p class="mt-4 text-sm leading-relaxed text-white/90"><?php echo esc_html( $c['wa_text'] ); ?></p>
						<div class="mt-6 inline-flex items-center gap-2 text-sm font-semibold">
							<?php echo esc_html( $c['wa_cta'] ); ?>
							<span class="transition-transform group-hover:translate-x-1"><?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?></span>
						</div>
					</div>
				</a>

				<?php if ( $show_instagram ) : ?>
					<a
						href="<?php echo faruk_instagram_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_instagram_url(). ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="group relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-sage to-sage-dark p-10 text-white transition-all duration-500 hover:-translate-y-1 hover:shadow-xl hover:shadow-sage-dark/20"
						data-gtm-event="instagram_click"
						data-gtm-location="contact_section"
					>
						<div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-laranja/20 blur-2xl"></div>
						<div class="relative z-10">
							<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm">
								<?php faruk_the_icon( 'instagram', 'h-7 w-7' ); ?>
							</div>
							<h3 class="mt-6 font-serif text-2xl">Instagram</h3>
							<p class="mt-1 text-sm font-medium text-white/80"><?php echo esc_html( $c['ig_handle'] ); ?></p>
							<p class="mt-4 text-sm leading-relaxed text-white/90"><?php echo esc_html( $c['ig_text'] ); ?></p>
							<div class="mt-6 inline-flex items-center gap-2 text-sm font-semibold">
								<?php echo esc_html( $c['ig_cta'] ); ?>
								<span class="transition-transform group-hover:translate-x-1"><?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?></span>
							</div>
						</div>
					</a>
				<?php endif; ?>

			</div>
		</div>
	</section>

	<!-- Atendimento: online e presencial -->
	<section class="section-padding bg-creme-dark/50">
		<div class="container-wide">
			<div class="mx-auto mb-12 max-w-2xl text-center">
				<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
					<?php echo esc_html( $c['att_eyebrow'] ); ?>
				</span>
				<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
					<?php echo esc_html( $c['att_heading'] ); ?>
				</h2>
			</div>

			<div class="grid gap-6 md:grid-cols-2">

				<div class="rounded-[2rem] bg-white p-10 ring-1 ring-marrom/5">
					<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sage/10 text-sage">
						<?php faruk_the_icon( 'monitor', 'h-7 w-7' ); ?>
					</div>
					<h3 class="mt-6 font-serif text-2xl text-marrom"><?php echo esc_html( $c['online_title'] ); ?></h3>
					<p class="mt-3 text-sm leading-relaxed text-marrom/70"><?php echo esc_html( $c['online_text'] ); ?></p>
					<?php if ( '' !== $c['online_note'] ) : ?>
						<div class="mt-6 flex items-center gap-2 text-sm text-marrom/50">
							<?php faruk_the_icon( 'info', 'h-4 w-4' ); ?>
							<?php echo esc_html( $c['online_note'] ); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="rounded-[2rem] bg-white p-10 ring-1 ring-marrom/5">
					<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-terracota/10 text-terracota">
						<?php faruk_the_icon( 'map-pin', 'h-7 w-7' ); ?>
					</div>
					<h3 class="mt-6 font-serif text-2xl text-marrom"><?php echo esc_html( $c['presencial_title'] ); ?></h3>
					<p class="mt-3 text-sm leading-relaxed text-marrom/70"><?php echo esc_html( $c['presencial_text'] ); ?></p>
					<div class="mt-6 rounded-xl bg-creme-dark/60 p-4">
						<p class="text-xs font-medium uppercase tracking-wider text-marrom/40">
							<?php esc_html_e( 'Endereço', 'faruk-arteterapia' ); ?>
						</p>
						<p class="mt-1 text-sm text-marrom/60"><?php echo esc_html( $c['presencial_address'] ); ?></p>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- Outros formatos -->
	<?php if ( $show_others ) : ?>
		<section class="section-padding">
			<div class="container-wide">
				<div class="mx-auto max-w-3xl">
					<div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-terracota/10 to-sage/10 p-10 text-center ring-1 ring-marrom/5 md:p-14">
						<div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-laranja/30 blur-2xl"></div>
						<div class="relative z-10">
							<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
								<?php echo esc_html( $c['others_eyebrow'] ); ?>
							</span>
							<h2 class="mt-3 font-serif text-3xl text-marrom md:text-4xl">
								<?php echo esc_html( $c['others_heading'] ); ?>
							</h2>
							<p class="mt-4 text-base leading-relaxed text-marrom/70">
								<?php echo esc_html( $c['others_text'] ); ?>
							</p>
							<a
								href="<?php echo faruk_resolve_link( $c['others_url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_resolve_link(). ?>"
								class="btn-primary mt-8"
								data-gtm-event="cta_click"
								data-gtm-location="other_formats"
								data-gtm-item="<?php echo esc_attr( $c['others_heading'] ); ?>"
							>
								<?php echo esc_html( $c['others_button'] ); ?>
								<?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

</div>
