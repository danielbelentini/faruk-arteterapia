<?php
/**
 * Componente: Perfil Sobre (bloco "Perfil Sobre" — topo da página Sobre).
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

$defaults = faruk_about_hero_defaults();
$c        = array();

foreach ( $defaults as $key => $default ) {
	$value     = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$c[ $key ] = '' !== $value ? $value : $default;
}

$photo_id = isset( $fields['photo'] ) ? absint( $fields['photo'] ) : 0;
?>
<section class="relative overflow-hidden bg-gradient-to-br from-marrom via-terracota-dark to-sage-dark pt-32 pb-20 text-white md:pt-40 md:pb-28<?php echo esc_attr( $extra ); ?>">
	<div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-terracota-light/10 blur-3xl"></div>
	<div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-laranja/10 blur-3xl"></div>

	<div class="container-wide relative z-10 px-6 md:px-12 lg:px-16">
		<div class="grid items-center gap-12 lg:grid-cols-5">

			<div class="lg:col-span-2">
				<div class="relative mx-auto max-w-sm">
					<div class="absolute -inset-4 rounded-[2.5rem] bg-gradient-to-br from-terracota-light/30 to-sage-light/30 blur-2xl"></div>
					<div class="relative overflow-hidden rounded-[2rem] shadow-2xl">
						<?php
						if ( $photo_id ) {
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup gerado pelo WordPress.
								$photo_id,
								'large',
								false,
								array(
									'class'         => 'aspect-[4/5] w-full object-cover',
									'alt'           => trim( $c['title'] . ' — ' . $c['subtitle'] ),
									'sizes'         => '(min-width: 1024px) 384px, 90vw',
									'loading'       => 'eager',
									'fetchpriority' => 'high',
								)
							);
						} else {
							echo '<div class="aspect-[4/5] w-full bg-gradient-to-br from-white/10 to-white/5"></div>';
						}
						?>
					</div>
				</div>
			</div>

			<div class="lg:col-span-3">
				<span class="inline-block rounded-full bg-laranja/20 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-laranja-light ring-1 ring-laranja/30">
					<?php echo esc_html( $c['badge'] ); ?>
				</span>
				<h1 class="mt-5 font-serif text-5xl leading-tight md:text-6xl">
					<?php echo esc_html( $c['title'] ); ?>
				</h1>
				<p class="mt-2 text-xl font-medium text-laranja-light">
					<?php echo esc_html( $c['subtitle'] ); ?>
				</p>
				<p class="mt-6 max-w-xl text-base leading-relaxed text-creme/70">
					<?php echo esc_html( $c['text'] ); ?>
				</p>
			</div>

		</div>
	</div>
</section>
