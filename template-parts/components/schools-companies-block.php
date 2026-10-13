<?php
/**
 * Componente: Escolas e Empresas (bloco "Escolas e Empresas").
 *
 * Cards de apresentação (título, descrição, tópicos) com botão que leva
 * ao formulário externo (Google Forms com UTM). Evento GTM:
 * school_company_lead_click.
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

$defaults = faruk_schools_companies_defaults();
$text     = array();

foreach ( array( 'section_id', 'eyebrow', 'heading', 'intro' ) as $key ) {
	$value        = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$text[ $key ] = '' !== $value ? $value : $defaults[ $key ];
}

$items = array();
if ( ! empty( $fields['items'] ) && is_array( $fields['items'] ) ) {
	foreach ( $fields['items'] as $row ) {
		if ( ! empty( $row['title'] ) ) {
			$items[] = array(
				'icon'        => ! empty( $row['icon'] ) ? $row['icon'] : 'users',
				'title'       => $row['title'],
				'description' => isset( $row['description'] ) ? $row['description'] : '',
				'topics'      => isset( $row['topics'] ) ? $row['topics'] : '',
				'button'      => ! empty( $row['button'] ) ? $row['button'] : 'Quero saber mais',
				'url'         => isset( $row['url'] ) ? (string) $row['url'] : '',
			);
		}
	}
}
if ( empty( $items ) ) {
	$items = $defaults['items'];
}

// Cada card tem um par de cores da identidade (alternados).
$themes = array(
	array(
		'blob' => 'bg-laranja/20',
		'icon' => 'bg-laranja text-marrom',
		'btn'  => 'bg-terracota hover:bg-terracota-dark hover:shadow-terracota/25',
		'tick' => 'text-terracota',
	),
	array(
		'blob' => 'bg-sage/20',
		'icon' => 'bg-sage text-white',
		'btn'  => 'bg-sage hover:bg-sage-dark hover:shadow-sage/25',
		'tick' => 'text-sage',
	),
);

$section_id = sanitize_title( $text['section_id'] );
$extra      = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'sand' ) . $extra ); ?>">
	<div class="container-wide">

		<div class="mx-auto mb-12 max-w-2xl text-center">
			<span class="text-sm font-semibold uppercase tracking-wider text-sage">
				<?php echo esc_html( $text['eyebrow'] ); ?>
			</span>
			<h2 class="mt-3 font-serif text-4xl text-marrom md:text-5xl">
				<?php echo esc_html( $text['heading'] ); ?>
			</h2>
			<?php if ( '' !== $text['intro'] ) : ?>
				<p class="mt-4 text-base leading-relaxed text-marrom/65">
					<?php echo esc_html( $text['intro'] ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="grid gap-6 md:grid-cols-2">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$theme  = $themes[ $index % count( $themes ) ];
				$topics    = array_filter( array_map( 'trim', preg_split( '/\R/', (string) $item['topics'] ) ) );
				// lead_type do GTM, conforme o ícone do card (capelo = escola, prédio = empresa).
				$lead_type = 'graduation-cap' === $item['icon'] ? 'school' : ( 'building-2' === $item['icon'] ? 'company' : 'institution' );
				?>
				<div class="group relative overflow-hidden rounded-[2rem] bg-white p-10 ring-1 ring-marrom/5 transition-all duration-500 hover:shadow-xl hover:shadow-terracota-dark/5">
					<div class="absolute -right-12 -top-12 h-40 w-40 rounded-full blur-2xl <?php echo esc_attr( $theme['blob'] ); ?>"></div>
					<div class="relative z-10 flex h-full flex-col">
						<div class="flex h-14 w-14 items-center justify-center rounded-2xl <?php echo esc_attr( $theme['icon'] ); ?>">
							<?php faruk_the_icon( $item['icon'], 'h-7 w-7' ); ?>
						</div>
						<h3 class="mt-6 font-serif text-2xl text-marrom">
							<?php echo esc_html( $item['title'] ); ?>
						</h3>
						<p class="mt-3 text-sm leading-relaxed text-marrom/70">
							<?php echo esc_html( $item['description'] ); ?>
						</p>

						<?php if ( ! empty( $topics ) ) : ?>
							<ul class="mt-5 space-y-2">
								<?php foreach ( $topics as $topic ) : ?>
									<li class="flex items-start gap-2 text-sm text-marrom/75">
										<span class="mt-0.5 shrink-0 <?php echo esc_attr( $theme['tick'] ); ?>"><?php faruk_the_icon( 'circle-check', 'h-4 w-4' ); ?></span>
										<?php echo esc_html( $topic ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<div class="mt-auto pt-6">
							<a
								href="<?php echo faruk_resolve_link( $item['url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_resolve_link(). ?>"
								<?php echo 0 === strpos( trim( $item['url'] ), 'http' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
								class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold text-white transition-all hover:shadow-lg <?php echo esc_attr( $theme['btn'] ); ?>"
								data-gtm-event="form_lead_click"
								data-gtm-location="schools_companies"
								data-gtm-lead-type="<?php echo esc_attr( $lead_type ); ?>"
								data-gtm-item="<?php echo esc_attr( $item['title'] ); ?>"
							>
								<?php echo esc_html( $item['button'] ); ?>
								<?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?>
							</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
