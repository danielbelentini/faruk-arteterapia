<?php
/**
 * Componente: Navegação por Âncoras (bloco "Navegação por Âncoras").
 *
 * Faixa "Nesta página:" com links que rolam suavemente até as seções
 * (o scroll suave vem do CSS: html { scroll-behavior: smooth }).
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

$defaults = faruk_anchor_nav_defaults();
$label    = isset( $fields['label'] ) && '' !== trim( (string) $fields['label'] ) ? trim( (string) $fields['label'] ) : $defaults['label'];

$items = array();
if ( ! empty( $fields['items'] ) && is_array( $fields['items'] ) ) {
	foreach ( $fields['items'] as $row ) {
		if ( ! empty( $row['label'] ) && ! empty( $row['target'] ) ) {
			$items[] = array(
				'icon'   => ! empty( $row['icon'] ) ? $row['icon'] : 'users',
				'label'  => $row['label'],
				'target' => sanitize_title( ltrim( (string) $row['target'], '#' ) ),
			);
		}
	}
}
if ( empty( $items ) ) {
	$items = $defaults['items'];
}

$extra = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<nav class="border-b border-creme-dark bg-white py-8<?php echo esc_attr( $extra ); ?>" aria-label="<?php echo esc_attr( $label ); ?>">
	<div class="container-wide box-content px-6 md:px-12 lg:px-16">
		<div class="flex flex-col items-center gap-4 md:flex-row md:justify-center md:gap-8">
			<span class="text-sm font-medium text-marrom/50"><?php echo esc_html( $label ); ?></span>
			<?php foreach ( $items as $item ) : ?>
				<a
					href="#<?php echo esc_attr( $item['target'] ); ?>"
					class="group flex items-center gap-2 text-sm font-semibold text-marrom/70 transition-colors hover:text-terracota"
					data-gtm-event="anchor_nav_click"
					data-gtm-location="anchor_nav"
				>
					<span class="text-terracota-light"><?php faruk_the_icon( $item['icon'], 'h-4 w-4' ); ?></span>
					<?php echo esc_html( $item['label'] ); ?>
					<span class="opacity-0 transition-opacity group-hover:opacity-100"><?php faruk_the_icon( 'arrow-right', 'h-3 w-3' ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</nav>
