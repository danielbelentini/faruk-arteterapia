<?php
/**
 * Funções auxiliares do tema: opções do site, Google Tag Manager,
 * navegação e preload de fontes.
 *
 * @package faruk-arteterapia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------
 * Opções do site (painel "Configurações do site", via Carbon Fields)
 * ---------------------------------------------------------------- */

/**
 * Lê uma opção do tema. Retorna o padrão se o Carbon Fields não estiver
 * disponível ou se o campo estiver vazio.
 *
 * @param string $key     Chave do campo.
 * @param string $default Valor padrão.
 * @return string
 */
function faruk_option( string $key, string $default = '' ): string {
	if ( function_exists( 'carbon_get_theme_option' ) ) {
		$value = carbon_get_theme_option( $key );

		if ( is_string( $value ) && '' !== trim( $value ) ) {
			return trim( $value );
		}
	}

	return $default;
}

/**
 * URL do WhatsApp. O padrão é um placeholder (igual ao protótipo).
 */
function faruk_whatsapp_url(): string {
	return esc_url( faruk_option( 'faruk_whatsapp_url', 'https://wa.me/5500000000000' ) );
}

/**
 * URL do Instagram. O padrão é um placeholder (igual ao protótipo).
 */
function faruk_instagram_url(): string {
	return esc_url( faruk_option( 'faruk_instagram_url', 'https://instagram.com/faruk.arteterapia' ) );
}

/**
 * Texto do registro profissional. O padrão é um placeholder (igual ao protótipo).
 */
function faruk_registro_profissional(): string {
	return faruk_option( 'faruk_registro', 'n.º 000/00' );
}

/**
 * Telefone de exibição (texto livre, ex.: "(11) 99999-9999"). Vazio se ausente.
 */
function faruk_phone_display(): string {
	return faruk_option( 'faruk_phone' );
}

/**
 * Telefone no formato do link tel: (só dígitos e "+"). Vazio se ausente.
 */
function faruk_phone_href(): string {
	$digits = preg_replace( '/[^0-9+]/', '', faruk_phone_display() );

	return $digits ? 'tel:' . $digits : '';
}

/**
 * E-mail de contato. Vazio se ausente ou inválido.
 */
function faruk_contact_email(): string {
	$email = faruk_option( 'faruk_email' );

	return is_email( $email ) ? $email : '';
}

/**
 * Verdadeiro se o banner de cookies está ativo (padrão: ativo).
 */
function faruk_cookie_banner_enabled(): bool {
	if ( function_exists( 'carbon_get_theme_option' ) ) {
		return ! carbon_get_theme_option( 'faruk_cookie_banner' );
	}

	return true;
}

/**
 * URL de uma página legal: usa o campo do painel; se vazio, a página
 * criada com o slug padrão; se ela não existir, devolve vazio.
 *
 * @param string $type 'privacy' ou 'cookies'.
 */
function faruk_legal_url( string $type ): string {
	$map = array(
		'privacy' => array( 'faruk_privacy_url', 'politica-de-privacidade' ),
		'cookies' => array( 'faruk_cookies_url', 'politica-de-cookies' ),
	);

	if ( ! isset( $map[ $type ] ) ) {
		return '';
	}

	$custom = faruk_option( $map[ $type ][0] );

	if ( '' !== $custom ) {
		return faruk_resolve_link( $custom );
	}

	$page = get_page_by_path( $map[ $type ][1] );

	return $page ? esc_url( get_permalink( $page ) ) : '';
}

/* ------------------------------------------------------------------
 * Links e contexto de renderização
 * ---------------------------------------------------------------- */

/**
 * Converte o link digitado em um campo do bloco em uma URL segura.
 * Caminhos que começam com "/" são relativos ao endereço do site
 * (funciona também quando o WordPress está em uma subpasta).
 *
 * @param string $url Link informado.
 */
function faruk_resolve_link( string $url ): string {
	$url = trim( $url );

	if ( '' === $url ) {
		return '#';
	}

	if ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) {
		return esc_url( home_url( $url ) );
	}

	return esc_url( $url );
}

/**
 * Verdadeiro quando o bloco está sendo renderizado para a prévia do
 * editor (admin ou requisição REST), e não para o visitante do site.
 */
function faruk_is_editor_request(): bool {
	return is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST );
}

/* ------------------------------------------------------------------
 * Google Tag Manager + dataLayer
 * ---------------------------------------------------------------- */

/**
 * ID do contêiner do GTM, validado (formato GTM-XXXXXXX). Vazio se ausente.
 */
function faruk_gtm_id(): string {
	$id = strtoupper( faruk_option( 'faruk_gtm_id' ) );

	return preg_match( '/^GTM-[A-Z0-9]+$/', $id ) ? $id : '';
}

/**
 * <head>: inicializa o dataLayer (sempre) e carrega o GTM (se houver ID).
 */
function faruk_gtm_head(): void {
	$id = faruk_gtm_id();
	?>
	<?php // Google Consent Mode v2: tudo negado até o visitante aceitar (ver cookie-banner.php e main.js). ?>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('consent', 'default', {
		'ad_storage': 'denied',
		'ad_user_data': 'denied',
		'ad_personalization': 'denied',
		'analytics_storage': 'denied',
		'wait_for_update': 500
	});
	try {
		// Quem já aceitou em outra visita volta com o consentimento restaurado, antes do GTM carregar.
		if (window.localStorage.getItem('faruk_cookie_consent') === 'granted') {
			gtag('consent', 'update', {
				'ad_storage': 'granted',
				'ad_user_data': 'granted',
				'ad_personalization': 'granted',
				'analytics_storage': 'granted'
			});
		}
	} catch (e) {}
	</script>
	<?php if ( $id ) : ?>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo esc_js( $id ); ?>');</script>
	<?php endif; ?>
	<?php
}
add_action( 'wp_head', 'faruk_gtm_head', 1 );

/**
 * <body>: noscript do GTM (logo após a abertura do body).
 */
function faruk_gtm_body(): void {
	$id = faruk_gtm_id();

	if ( ! $id ) {
		return;
	}
	?>
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $id ); ?>" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>
	<?php
}
add_action( 'wp_body_open', 'faruk_gtm_body' );

/* ------------------------------------------------------------------
 * Fontes locais: preload das duas fontes críticas
 * ---------------------------------------------------------------- */

/**
 * Pré-carrega Cormorant Garamond (títulos) e Outfit Regular (corpo),
 * apenas se os arquivos existirem em assets/fonts/.
 */
function faruk_preload_fonts(): void {
	$fonts = array(
		'CormorantGaramond-Regular.woff2',
		'Outfit-Regular.woff2',
	);

	foreach ( $fonts as $font ) {
		if ( file_exists( FARUK_DIR . '/assets/fonts/' . $font ) ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( FARUK_URI . '/assets/fonts/' . $font )
			);
		}
	}
}
add_action( 'wp_head', 'faruk_preload_fonts', 2 );

/* ------------------------------------------------------------------
 * Navegação
 * ---------------------------------------------------------------- */

/**
 * Itens padrão do menu, usados enquanto nenhum menu for atribuído no
 * painel do WordPress. Os endereços assumem páginas com estes slugs.
 *
 * @return array<int,array{label:string,url:string,current:bool}>
 */
function faruk_default_nav_items(): array {
	return array(
		array(
			'label'   => 'Início',
			'url'     => home_url( '/' ),
			'current' => is_front_page(),
		),
		array(
			'label'   => 'Arteterapia Junguiana',
			'url'     => home_url( '/arteterapia-junguiana/' ),
			'current' => is_page( 'arteterapia-junguiana' ),
		),
		array(
			'label'   => 'Grupos, projetos e vivências',
			'url'     => home_url( '/grupos-projetos-e-vivencias/' ),
			'current' => is_page( 'grupos-projetos-e-vivencias' ),
		),
		array(
			'label'   => 'Sobre',
			'url'     => home_url( '/sobre/' ),
			'current' => is_page( 'sobre' ),
		),
		array(
			'label'   => 'Contato',
			'url'     => home_url( '/contato/' ),
			'current' => is_page( 'contato' ),
		),
	);
}

/**
 * Classes dos links do menu, por variante (desktop, mobile, footer).
 *
 * @param string $variant Variante do menu.
 * @param bool   $current Se o item é a página atual.
 */
function faruk_nav_link_classes( string $variant, bool $current ): string {
	switch ( $variant ) {
		case 'mobile':
			$base   = 'rounded-xl px-4 py-3 text-left text-sm font-medium transition-all';
			$active = 'bg-terracota/10 text-terracota';
			$idle   = 'text-marrom/70 hover:bg-creme-dark/60';
			break;

		case 'footer':
			$base   = 'text-sm transition-colors';
			$active = 'text-creme/60 hover:text-laranja';
			$idle   = 'text-creme/60 hover:text-laranja';
			break;

		case 'desktop':
		default:
			$base   = 'rounded-full px-4 py-2 text-sm font-medium transition-all duration-200';
			$active = 'bg-terracota/10 text-terracota';
			$idle   = 'text-marrom/70 hover:bg-creme-dark/60 hover:text-terracota';
			break;
	}

	return $base . ' ' . ( $current ? $active : $idle );
}

/**
 * Classes da lista <ul> do menu, por variante.
 *
 * @param string $variant Variante do menu.
 */
function faruk_nav_list_classes( string $variant ): string {
	switch ( $variant ) {
		case 'mobile':
			return 'flex flex-col gap-1';
		case 'footer':
			return 'space-y-2.5';
		case 'desktop':
		default:
			return 'flex items-center gap-1';
	}
}

/**
 * Valor de link_location do GTM para cada variante do menu.
 *
 * @param string $variant 'desktop', 'mobile' ou 'footer'.
 */
function faruk_nav_location( string $variant ): string {
	return 'footer' === $variant ? 'footer_nav' : 'header_nav';
}

/**
 * Imprime o menu. Usa o menu atribuído no painel (localizações "primary"
 * e "footer"); se não houver, usa os itens padrão do protótipo.
 *
 * @param string $variant 'desktop', 'mobile' ou 'footer'.
 */
function faruk_nav( string $variant = 'desktop' ): void {
	$location = 'footer' === $variant ? 'footer' : 'primary';

	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'menu_class'     => faruk_nav_list_classes( $variant ),
				'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
				'depth'          => 1,
				'faruk_variant'  => $variant, // Lido pelos filtros abaixo.
			)
		);

		return;
	}

	echo '<ul class="' . esc_attr( faruk_nav_list_classes( $variant ) ) . '">';

	foreach ( faruk_default_nav_items() as $item ) {
		printf(
			'<li><a href="%1$s" class="%2$s"%3$s data-gtm-event="nav_click" data-gtm-location="%4$s">%5$s</a></li>',
			esc_url( $item['url'] ),
			esc_attr( faruk_nav_link_classes( $variant, $item['current'] ) ),
			$item['current'] ? ' aria-current="page"' : '',
			esc_attr( faruk_nav_location( $variant ) ),
			esc_html( $item['label'] )
		);
	}

	echo '</ul>';
}

/**
 * Aplica as classes do tema aos links dos menus do WordPress.
 */
function faruk_nav_link_attributes( array $atts, $item, $args ): array {
	if ( empty( $args->faruk_variant ) ) {
		return $atts;
	}

	$current        = ! empty( $item->current );
	$atts['class']  = faruk_nav_link_classes( $args->faruk_variant, $current );

	if ( $current ) {
		$atts['aria-current'] = 'page';
	}

	// Rastreamento (GTM): clique no menu.
	$atts['data-gtm-event']    = 'nav_click';
	$atts['data-gtm-location'] = faruk_nav_location( $args->faruk_variant );

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'faruk_nav_link_attributes', 10, 3 );

/**
 * Remove as classes padrão dos <li> dos menus do tema.
 */
function faruk_nav_item_classes( array $classes, $item, $args ): array {
	if ( empty( $args->faruk_variant ) ) {
		return $classes;
	}

	return array();
}
add_filter( 'nav_menu_css_class', 'faruk_nav_item_classes', 10, 3 );
