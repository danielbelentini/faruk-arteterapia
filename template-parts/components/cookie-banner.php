<?php
/**
 * Componente: banner de cookies (LGPD + Google Consent Mode v2).
 *
 * Nasce escondido (atributo hidden) e é exibido por assets/js/main.js
 * somente se o visitante ainda não escolheu (localStorage
 * "faruk_cookie_consent": "granted" ou "denied"). Isso evita que o banner
 * apareça "preso" em páginas servidas por cache.
 *
 * @package faruk-arteterapia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$privacy_url = faruk_legal_url( 'privacy' );
$cookies_url = faruk_legal_url( 'cookies' );
?>
<div
	id="cookie-banner"
	class="fixed inset-x-4 bottom-4 z-[55] mx-auto max-w-3xl translate-y-6 rounded-2xl bg-marrom p-5 text-creme opacity-0 shadow-2xl ring-1 ring-white/10 transition-all duration-500 data-[open=true]:translate-y-0 data-[open=true]:opacity-100 motion-reduce:transition-none md:p-6"
	role="region"
	aria-label="<?php esc_attr_e( 'Aviso de cookies', 'faruk-arteterapia' ); ?>"
	data-cookie-banner
	data-open="false"
	hidden
>
	<div class="flex flex-col gap-5 md:flex-row md:items-center md:gap-8">
		<p class="flex-1 text-sm leading-relaxed text-creme/80">
			<?php esc_html_e( 'Usamos cookies essenciais para o site funcionar e, com a sua permissão, cookies de medição para entender como ele é usado e melhorá-lo.', 'faruk-arteterapia' ); ?>
			<?php if ( $privacy_url ) : ?>
				<a href="<?php echo $privacy_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_legal_url(). ?>" class="whitespace-nowrap font-medium text-laranja underline underline-offset-2 hover:text-laranja-light">
					<?php esc_html_e( 'Política de Privacidade', 'faruk-arteterapia' ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $cookies_url ) : ?>
				<span aria-hidden="true">·</span>
				<a href="<?php echo $cookies_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_legal_url(). ?>" class="whitespace-nowrap font-medium text-laranja underline underline-offset-2 hover:text-laranja-light">
					<?php esc_html_e( 'Política de Cookies', 'faruk-arteterapia' ); ?>
				</a>
			<?php endif; ?>
		</p>

		<div class="flex flex-col gap-3 sm:flex-row md:shrink-0">
			<button
				type="button"
				class="inline-flex items-center justify-center rounded-full border border-creme/30 px-6 py-3 text-sm font-semibold text-creme transition-colors hover:bg-white/10"
				data-cookie-decline
			>
				<?php esc_html_e( 'Apenas essenciais', 'faruk-arteterapia' ); ?>
			</button>
			<button
				type="button"
				class="inline-flex items-center justify-center rounded-full bg-terracota px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-terracota-light"
				data-cookie-accept
			>
				<?php esc_html_e( 'Aceitar cookies', 'faruk-arteterapia' ); ?>
			</button>
		</div>
	</div>
</div>
