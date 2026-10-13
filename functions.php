<?php
/**
 * Funções e definições do tema Faruk Arteterapia.
 *
 * @package faruk-arteterapia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FARUK_VERSION', '0.1.0' );
define( 'FARUK_DIR', get_template_directory() );
define( 'FARUK_URI', get_template_directory_uri() );

/*
 * Inclusões do tema.
 * A ordem importa: o Carbon Fields é inicializado primeiro; os helpers
 * (opções, GTM, navegação) e os ícones são carregados em seguida.
 */
require_once FARUK_DIR . '/inc/carbon-fields.php';
require_once FARUK_DIR . '/inc/helpers.php';
require_once FARUK_DIR . '/inc/icons.php';

/**
 * Suporte do tema e localizações de menu.
 */
function faruk_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal (cabeçalho)', 'faruk-arteterapia' ),
			'footer'  => __( 'Menu do rodapé', 'faruk-arteterapia' ),
		)
	);
}
add_action( 'after_setup_theme', 'faruk_setup' );

/**
 * Versão de um arquivo do tema para cache busting (data de modificação).
 *
 * @param string $relative_path Caminho relativo à pasta do tema (com / inicial).
 */
function faruk_asset_version( string $relative_path ): string {
	$path = FARUK_DIR . $relative_path;

	return file_exists( $path ) ? (string) filemtime( $path ) : FARUK_VERSION;
}

/**
 * Estilos e scripts compilados.
 * O CSS é gerado pelo Tailwind CLI (npm run dev / npm run build).
 */
function faruk_enqueue_assets(): void {
	wp_enqueue_style(
		'faruk-main',
		FARUK_URI . '/assets/css/main.css',
		array(),
		faruk_asset_version( '/assets/css/main.css' )
	);

	wp_enqueue_script(
		'faruk-main',
		FARUK_URI . '/assets/js/main.js',
		array(),
		faruk_asset_version( '/assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'faruk_enqueue_assets' );

/**
 * Swiper (arquivos locais em assets/vendor/swiper/) e script do carrossel do Hero.
 *
 * Os handles são apenas REGISTRADOS aqui; só são carregados nas páginas que
 * usam o bloco "Hero Carrossel" (ver faruk_enqueue_hero_assets() e o próprio
 * template hero-block.php, que também os enfileira como garantia).
 */
function faruk_register_vendor_assets(): void {
	wp_register_style(
		'swiper',
		FARUK_URI . '/assets/vendor/swiper/swiper-bundle.min.css',
		array(),
		faruk_asset_version( '/assets/vendor/swiper/swiper-bundle.min.css' )
	);

	wp_register_script(
		'swiper',
		FARUK_URI . '/assets/vendor/swiper/swiper-bundle.min.js',
		array(),
		faruk_asset_version( '/assets/vendor/swiper/swiper-bundle.min.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_register_script(
		'faruk-hero',
		FARUK_URI . '/assets/js/hero-carousel.js',
		array( 'swiper' ),
		faruk_asset_version( '/assets/js/hero-carousel.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_register_script(
		'faruk-testimonials',
		FARUK_URI . '/assets/js/testimonials-carousel.js',
		array( 'swiper' ),
		faruk_asset_version( '/assets/js/testimonials-carousel.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'faruk_register_vendor_assets', 5 );

/**
 * Carrega o Swiper no <head>/rodapé apenas quando a página tem o bloco Hero.
 */
function faruk_enqueue_hero_assets(): void {
	if ( ! is_singular() ) {
		return;
	}

	$post = get_post();

	if ( $post && has_block( faruk_hero_block_name(), $post ) ) {
		wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'faruk-hero' );
	}

	if ( $post && has_block( faruk_testimonials_block_name(), $post ) ) {
		wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'faruk-testimonials' );
	}
}
add_action( 'wp_enqueue_scripts', 'faruk_enqueue_hero_assets', 20 );

/**
 * Editor de blocos: carrega o CSS compilado (Tailwind) para a prévia dos blocos.
 */
function faruk_enqueue_editor_assets(): void {
	if ( ! is_admin() ) {
		return;
	}

	wp_enqueue_style(
		'faruk-editor',
		FARUK_URI . '/assets/css/main.css',
		array(),
		faruk_asset_version( '/assets/css/main.css' )
	);
}
add_action( 'enqueue_block_assets', 'faruk_enqueue_editor_assets' );

/**
 * Avisa quem administra o site quando o CSS compilado ainda não existe.
 */
function faruk_missing_css_notice(): void {
	if ( file_exists( FARUK_DIR . '/assets/css/main.css' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p><strong>%s</strong> %s</p></div>',
		esc_html__( 'Tema Faruk Arteterapia:', 'faruk-arteterapia' ),
		esc_html__( 'o CSS ainda não foi compilado. Rode "npm install" e "npm run build" na pasta do tema.', 'faruk-arteterapia' )
	);
}
add_action( 'admin_notices', 'faruk_missing_css_notice' );

/**
 * Avisa quem administra o site quando o Swiper local ainda não foi copiado
 * (rode "npm install" ou "npm run vendor" na pasta do tema).
 */
function faruk_missing_swiper_notice(): void {
	$files = array(
		FARUK_DIR . '/assets/vendor/swiper/swiper-bundle.min.js',
		FARUK_DIR . '/assets/vendor/swiper/swiper-bundle.min.css',
	);

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	foreach ( $files as $file ) {
		if ( ! file_exists( $file ) ) {
			printf(
				'<div class="notice notice-warning"><p><strong>%s</strong> %s</p></div>',
				esc_html__( 'Tema Faruk Arteterapia:', 'faruk-arteterapia' ),
				esc_html__( 'o Swiper local não foi encontrado. Rode "npm install" (ou "npm run vendor") na pasta do tema para o carrossel funcionar.', 'faruk-arteterapia' )
			);

			return;
		}
	}
}
add_action( 'admin_notices', 'faruk_missing_swiper_notice' );
