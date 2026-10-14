<?php
/**
 * Rodapé do tema: navegação, contatos, aviso, botão flutuante do WhatsApp
 * e fechamento do documento (abre em header.php).
 *
 * @package faruk-arteterapia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

	<footer class="bg-marrom text-creme">
		<div class="mx-auto box-content max-w-8xl px-6 py-16 md:px-12 lg:px-16">
			<div class="grid gap-12 lg:grid-cols-4">

				<!-- Logo + identificação -->
				<div class="lg:col-span-1">
					<?php
					get_template_part(
						'template-parts/components/brand-logo',
						null,
						array(
							'layout' => 'vertical',
							'dark'   => true,
							'class'  => 'mb-6',
						)
					);
					?>
					<p class="text-sm leading-relaxed text-creme/70">
						Faruk — Arteterapeuta e Analista Junguiano
					</p>
					<p class="mt-1 text-xs text-creme/50">
						<?php echo esc_html( 'Registro profissional ' . faruk_registro_profissional() ); ?>
					</p>
				</div>

				<!-- Atalhos de navegação -->
				<div class="lg:col-span-1">
					<h3 class="mb-4 font-serif text-lg text-creme/90">
						<?php esc_html_e( 'Navegação', 'faruk-arteterapia' ); ?>
					</h3>
					<nav aria-label="<?php esc_attr_e( 'Navegação do rodapé', 'faruk-arteterapia' ); ?>">
						<?php faruk_nav( 'footer' ); ?>
					</nav>
				</div>

				<!-- Contato -->
				<div class="lg:col-span-1">
					<h3 class="mb-4 font-serif text-lg text-creme/90">
						<?php esc_html_e( 'Contato', 'faruk-arteterapia' ); ?>
					</h3>
					<div class="space-y-3">
						<a
							href="<?php echo faruk_whatsapp_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_whatsapp_url(). ?>"
							target="_blank"
							rel="noopener noreferrer"
							class="group flex items-center gap-2 text-sm text-creme/60 transition-colors hover:text-[#25D366]"
							data-gtm-event="whatsapp_click"
							data-gtm-location="footer"
						>
							<?php faruk_the_icon( 'message-circle', 'h-4 w-4' ); ?>
							WhatsApp
							<?php faruk_the_icon( 'arrow-up-right', 'h-3 w-3 opacity-0 transition-opacity group-hover:opacity-100' ); ?>
						</a>
						<a
							href="<?php echo faruk_instagram_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_instagram_url(). ?>"
							target="_blank"
							rel="noopener noreferrer"
							class="group flex items-center gap-2 text-sm text-creme/60 transition-colors hover:text-laranja"
							data-gtm-event="instagram_click"
							data-gtm-location="footer"
						>
							<?php faruk_the_icon( 'instagram', 'h-4 w-4' ); ?>
							Instagram
							<?php faruk_the_icon( 'arrow-up-right', 'h-3 w-3 opacity-0 transition-opacity group-hover:opacity-100' ); ?>
						</a>
						<?php if ( faruk_phone_href() ) : ?>
							<a
								href="<?php echo esc_url( faruk_phone_href() ); ?>"
								class="flex items-center gap-2 text-sm text-creme/60 transition-colors hover:text-laranja"
								data-gtm-event="phone_click"
								data-gtm-location="footer"
							>
								<?php echo esc_html( faruk_phone_display() ); ?>
							</a>
						<?php endif; ?>
						<?php if ( faruk_contact_email() ) : ?>
							<a
								href="mailto:<?php echo esc_attr( faruk_contact_email() ); ?>"
								class="flex items-center gap-2 break-all text-sm text-creme/60 transition-colors hover:text-laranja"
							>
								<?php echo esc_html( faruk_contact_email() ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<!-- Aviso -->
				<div class="lg:col-span-1">
					<h3 class="mb-4 font-serif text-lg text-creme/90">
						<?php esc_html_e( 'Aviso importante', 'faruk-arteterapia' ); ?>
					</h3>
					<p class="text-xs leading-relaxed text-creme/50">
						A arteterapia não substitui acompanhamento de outros profissionais
						de saúde e não é indicada para situações de urgência.
					</p>
					<p class="mt-3 text-xs leading-relaxed text-creme/50">
						Em caso de sofrimento emocional intenso, ligue para o CVV:
						<a href="tel:188" class="font-semibold text-creme/70 underline underline-offset-2 transition-colors hover:text-laranja" data-gtm-event="phone_click" data-gtm-location="footer">188</a>
						(24 horas, gratuito).
					</p>
				</div>
			</div>

			<div class="mt-12 border-t border-creme/10 pt-6">
				<?php
				$privacy_url = faruk_legal_url( 'privacy' );
				$cookies_url = faruk_legal_url( 'cookies' );
				?>
				<ul class="mb-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs text-creme/50">
					<?php if ( $privacy_url ) : ?>
						<li><a href="<?php echo $privacy_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_legal_url(). ?>" class="transition-colors hover:text-laranja"><?php esc_html_e( 'Política de Privacidade', 'faruk-arteterapia' ); ?></a></li>
					<?php endif; ?>
					<?php if ( $cookies_url ) : ?>
						<li><a href="<?php echo $cookies_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_legal_url(). ?>" class="transition-colors hover:text-laranja"><?php esc_html_e( 'Política de Cookies', 'faruk-arteterapia' ); ?></a></li>
					<?php endif; ?>
					<?php if ( faruk_cookie_banner_enabled() ) : ?>
						<li><button type="button" class="transition-colors hover:text-laranja" data-cookie-settings><?php esc_html_e( 'Gerenciar cookies', 'faruk-arteterapia' ); ?></button></li>
					<?php endif; ?>
				</ul>
				<p class="text-center text-xs text-creme/40">
					© <?php echo esc_html( wp_date( 'Y' ) ); ?> Faruk — Arteterapia Junguiana. Todos os
					direitos reservados.
				</p>
			</div>
		</div>
	</footer>

	<!-- Botão flutuante do WhatsApp -->
	<a
		href="<?php echo faruk_whatsapp_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_whatsapp_url(). ?>"
		target="_blank"
		rel="noopener noreferrer"
		class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg shadow-green-600/30 transition-all duration-300 hover:scale-110 hover:shadow-xl hover:shadow-green-600/40"
		aria-label="<?php esc_attr_e( 'Falar pelo WhatsApp', 'faruk-arteterapia' ); ?>"
		data-gtm-event="whatsapp_click"
		data-gtm-location="floating_button"
	>
		<?php faruk_the_icon( 'message-circle', 'h-7 w-7' ); ?>
		<span class="absolute inset-0 animate-ping rounded-full bg-[#25D366] opacity-20 motion-reduce:animate-none" aria-hidden="true"></span>
	</a>

</div><!-- #page -->

<?php
// Banner de cookies (LGPD / Consent Mode v2).
if ( faruk_cookie_banner_enabled() ) {
	get_template_part( 'template-parts/components/cookie-banner' );
}
?>

<?php wp_footer(); ?>
</body>
</html>
