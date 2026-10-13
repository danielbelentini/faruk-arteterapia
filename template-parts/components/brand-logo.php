<?php
/**
 * Componente: logotipo da marca (mandala em SVG inline + texto).
 *
 * Uso:
 *   get_template_part( 'template-parts/components/brand-logo', null, array(
 *       'layout' => 'horizontal', // 'horizontal' (padrão) ou 'vertical'
 *       'dark'   => false,        // true para fundos escuros (rodapé)
 *       'animate' => true,        // false para a mandala parada
 *       'class'  => '',           // classes extras no contêiner
 *   ) );
 *
 * Mandala: o anel externo gira no sentido anti-horário (animate-rotate-ccw)
 * e as pétalas no sentido horário (animate-rotate-cw). O anel interno fica parado.
 *
 * @package faruk-arteterapia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	isset( $args ) ? $args : array(),
	array(
		'layout'  => 'horizontal',
		'dark'    => false,
		'animate' => true,
		'class'   => '',
	)
);

$layout  = 'vertical' === $args['layout'] ? 'vertical' : 'horizontal';
$is_dark = (bool) $args['dark'];
$animate = (bool) $args['animate'];

// Cores: no fundo escuro, usam-se as variações claras da identidade.
// As classes ficam escritas por inteiro (nada de concatenar), para o Tailwind detectá-las.
$stroke_terracota = $is_dark ? 'stroke-terracota-light' : 'stroke-terracota';
$stroke_sage      = $is_dark ? 'stroke-sage-light' : 'stroke-sage';
$fill_terracota   = $is_dark ? 'fill-terracota-light' : 'fill-terracota';
$fill_sage        = $is_dark ? 'fill-sage-light' : 'fill-sage';

$text_color = $is_dark ? 'text-creme' : 'text-marrom';
$sub_color  = $is_dark ? 'text-creme/60' : 'text-marrom/50';

// Rotação em torno do centro do viewBox (100,100). Respeita "reduzir movimento".
$spin_base = 'origin-[100px_100px] [transform-box:view-box] motion-reduce:animate-none';
$ring_anim = $animate ? $spin_base . ' animate-rotate-ccw' : '';
$petal_anim = $animate ? $spin_base . ' animate-rotate-cw' : '';

$mandala_size = 'horizontal' === $layout ? 'h-11 w-11 flex-shrink-0' : 'h-20 w-20';

$petal_path = 'M 100 82 C 78 65, 82 32, 100 24 C 118 32, 122 65, 100 82 Z';
?>
<div class="<?php echo esc_attr( trim( ( 'horizontal' === $layout ? 'flex items-center gap-3' : 'flex flex-col items-center text-center' ) . ' ' . $args['class'] ) ); ?>">

	<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="<?php echo esc_attr( $mandala_size ); ?>" aria-hidden="true" focusable="false">
		<g class="outer-ring-group <?php echo esc_attr( $ring_anim ); ?>">
			<circle class="<?php echo esc_attr( $stroke_terracota ); ?>" cx="100" cy="100" r="92" stroke-width="2.5" stroke-dasharray="64.2566 8 64.2566 8 64.2566 8 64.2566 8 64.2566 8 64.2566 8 64.2566 8 64.2566 8" opacity="0.4" />
		</g>
		<circle class="<?php echo esc_attr( $stroke_sage ); ?>" cx="100" cy="100" r="78" stroke-width="1.5" opacity="0.35" />
		<g class="petals-group <?php echo esc_attr( $petal_anim ); ?>">
			<?php for ( $i = 0; $i < 8; $i++ ) : ?>
				<?php $even = ( 0 === $i % 2 ); ?>
				<g transform="rotate(<?php echo (int) ( $i * 45 ); ?> 100 100)"><path class="<?php echo esc_attr( $even ? $fill_terracota : $fill_sage ); ?>" d="<?php echo esc_attr( $petal_path ); ?>" opacity="<?php echo $even ? '0.85' : '0.7'; ?>" /></g>
			<?php endfor; ?>
		</g>
		<circle class="<?php echo esc_attr( $fill_terracota ); ?>" cx="100" cy="100" r="16" />
		<circle class="fill-laranja" cx="100" cy="100" r="10" opacity="0.5" />
		<circle class="fill-creme" cx="100" cy="100" r="5" opacity="0.9" />
	</svg>

	<?php if ( 'horizontal' === $layout ) : ?>
		<div class="flex flex-col leading-none">
			<span class="font-serif text-xl tracking-tight <?php echo esc_attr( $text_color ); ?>">Faruk</span>
			<span class="font-sans text-[10px] uppercase tracking-[0.22em] <?php echo esc_attr( $sub_color ); ?>">Arteterapia Junguiana</span>
		</div>
	<?php else : ?>
		<span class="mt-3 font-serif text-2xl leading-none tracking-tight <?php echo esc_attr( $text_color ); ?>">Faruk</span>
		<span class="mt-1.5 font-sans text-[10px] uppercase leading-tight tracking-[0.2em] <?php echo esc_attr( $sub_color ); ?>">Arteterapia<br>Junguiana</span>
	<?php endif; ?>

</div>
