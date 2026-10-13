<?php
/**
 * Componente: FAQ (bloco "FAQ Perguntas Frequentes").
 *
 * Accordion: ao abrir uma pergunta, a anterior fecha; abre e fecha com
 * animação suave. A lógica está em assets/js/main.js (seção 4) e o estado
 * visual vem do atributo data-open (variantes data-* do Tailwind).
 * Pode ser inserido em qualquer página, quantas vezes for preciso.
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

$defaults = faruk_faq_defaults();

$eyebrow = isset( $fields['eyebrow'] ) && '' !== trim( (string) $fields['eyebrow'] ) ? trim( (string) $fields['eyebrow'] ) : $defaults['eyebrow'];
$heading = isset( $fields['heading'] ) && '' !== trim( (string) $fields['heading'] ) ? trim( (string) $fields['heading'] ) : $defaults['heading'];

$items = array();
if ( ! empty( $fields['items'] ) && is_array( $fields['items'] ) ) {
	foreach ( $fields['items'] as $row ) {
		if ( ! empty( $row['question'] ) ) {
			$items[] = array(
				'question' => $row['question'],
				'answer'   => isset( $row['answer'] ) ? $row['answer'] : '',
			);
		}
	}
}
if ( empty( $items ) ) {
	$items = $defaults['items'];
}

$is_sand = ! isset( $fields['background'] ) || 'default' !== $fields['background'];
$extra   = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
$class   = 'section-padding' . ( $is_sand ? ' bg-creme-dark/50' : '' ) . $extra;
$uid     = wp_unique_id( 'faq-' );
?>
<section class="<?php echo esc_attr( $class ); ?>">
	<div class="container-wide">

		<div class="mx-auto mb-14 max-w-2xl text-center">
			<span class="text-sm font-semibold uppercase tracking-wider text-sage">
				<?php echo esc_html( $eyebrow ); ?>
			</span>
			<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
				<?php echo esc_html( $heading ); ?>
			</h2>
		</div>

		<div class="mx-auto max-w-3xl space-y-3" data-faq>
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$is_open  = ( 0 === $index );
				$trigger  = $uid . '-q-' . $index;
				$panel    = $uid . '-a-' . $index;
				?>
				<div
					class="group overflow-hidden rounded-2xl bg-white/60 ring-1 ring-marrom/5 transition-all duration-300 hover:bg-white hover:shadow-sm data-[open=true]:bg-white data-[open=true]:shadow-md data-[open=true]:ring-terracota/20 motion-reduce:transition-none"
					data-faq-item
					data-open="<?php echo $is_open ? 'true' : 'false'; ?>"
				>
					<h3 class="font-sans">
						<button
							type="button"
							id="<?php echo esc_attr( $trigger ); ?>"
							class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left"
							aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
							aria-controls="<?php echo esc_attr( $panel ); ?>"
							data-faq-trigger
							data-gtm-event="faq_toggle"
							data-gtm-location="faq"
							data-gtm-item="<?php echo esc_attr( $item['question'] ); ?>"
						>
							<span class="text-base font-semibold text-marrom transition-colors group-data-[open=true]:text-terracota-dark">
								<?php echo esc_html( $item['question'] ); ?>
							</span>
							<span class="shrink-0 rounded-full p-1 text-marrom/40 transition-all group-data-[open=true]:bg-terracota/10 group-data-[open=true]:text-terracota">
								<span class="group-data-[open=true]:hidden"><?php faruk_the_icon( 'plus', 'h-4 w-4' ); ?></span>
								<span class="hidden group-data-[open=true]:block"><?php faruk_the_icon( 'minus', 'h-4 w-4' ); ?></span>
							</span>
						</button>
					</h3>
					<div
						id="<?php echo esc_attr( $panel ); ?>"
						role="region"
						aria-labelledby="<?php echo esc_attr( $trigger ); ?>"
						class="grid grid-rows-[0fr] opacity-0 transition-all duration-300 group-data-[open=true]:grid-rows-[1fr] group-data-[open=true]:opacity-100 motion-reduce:transition-none"
						data-faq-panel
						<?php echo $is_open ? '' : 'inert'; ?>
					>
						<div class="overflow-hidden">
							<p class="px-6 pb-5 text-sm leading-relaxed text-marrom/70">
								<?php echo esc_html( $item['answer'] ); ?>
							</p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
