<?php
/**
 * Cabeçalho do tema: abertura do documento, GTM e navegação responsiva.
 *
 * O contêiner do GTM (script no <head> e noscript após o <body>) é
 * impresso por wp_head() e wp_body_open(); ver inc/helpers.php.
 *
 * @package faruk-arteterapia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-terracota focus:px-5 focus:py-3 focus:text-sm focus:font-semibold focus:text-white">
	<?php esc_html_e( 'Pular para o conteúdo', 'faruk-arteterapia' ); ?>
</a>

<div id="page" class="flex min-h-screen flex-col">

	<header id="site-header" class="fixed inset-x-0 top-0 z-50 bg-creme/80 backdrop-blur-sm transition-all duration-500">
		<div class="mx-auto flex max-w-8xl items-center justify-between px-6 py-4 md:px-12 lg:px-16">

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="transition-transform hover:scale-105" aria-label="<?php esc_attr_e( 'Faruk — Arteterapia Junguiana, página inicial', 'faruk-arteterapia' ); ?>">
				<?php
				get_template_part(
					'template-parts/components/brand-logo',
					null,
					array( 'layout' => 'horizontal' )
				);
				?>
			</a>

			<nav class="hidden items-center gap-1 lg:flex" aria-label="<?php esc_attr_e( 'Navegação principal', 'faruk-arteterapia' ); ?>">
				<?php faruk_nav( 'desktop' ); ?>
			</nav>

			<div class="flex items-center gap-3">
				<a
					href="<?php echo faruk_whatsapp_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_whatsapp_url(). ?>"
					target="_blank"
					rel="noopener noreferrer"
					class="hidden items-center gap-2 rounded-full bg-[#25D366] px-5 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-[#1eb858] hover:shadow-lg hover:shadow-green-600/25 sm:inline-flex"
					data-gtm-event="whatsapp_click"
					data-gtm-location="header"
				>
					<?php faruk_the_icon( 'message-circle', 'h-4 w-4' ); ?>
					WhatsApp
				</a>

				<button
					type="button"
					id="menu-toggle"
					class="rounded-full p-2 text-marrom transition-colors hover:bg-creme-dark/60 lg:hidden"
					aria-label="<?php esc_attr_e( 'Abrir menu', 'faruk-arteterapia' ); ?>"
					aria-expanded="false"
					aria-controls="mobile-menu"
					data-label-open="<?php esc_attr_e( 'Abrir menu', 'faruk-arteterapia' ); ?>"
					data-label-close="<?php esc_attr_e( 'Fechar menu', 'faruk-arteterapia' ); ?>"
				>
					<span data-icon="open"><?php faruk_the_icon( 'menu', 'h-6 w-6' ); ?></span>
					<span data-icon="close" class="hidden"><?php faruk_the_icon( 'x', 'h-6 w-6' ); ?></span>
				</button>
			</div>
		</div>

		<!-- Menu mobile -->
		<div id="mobile-menu" class="invisible max-h-0 overflow-hidden backdrop-blur-md transition-all duration-300 lg:hidden">
			<nav class="flex flex-col gap-1 px-6 py-4" aria-label="<?php esc_attr_e( 'Navegação móvel', 'faruk-arteterapia' ); ?>">
				<?php faruk_nav( 'mobile' ); ?>
				<a
					href="<?php echo faruk_whatsapp_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_whatsapp_url(). ?>"
					target="_blank"
					rel="noopener noreferrer"
					class="mt-2 inline-flex items-center justify-center gap-2 rounded-full bg-[#25D366] px-5 py-3 text-sm font-semibold text-white"
					data-gtm-event="whatsapp_click"
					data-gtm-location="header_mobile"
				>
					<?php faruk_the_icon( 'message-circle', 'h-4 w-4' ); ?>
					<?php esc_html_e( 'Fale pelo WhatsApp', 'faruk-arteterapia' ); ?>
				</a>
			</nav>
		</div>
	</header>
