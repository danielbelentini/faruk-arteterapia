<?php
/**
 * Componente: Grupos e Vivências (bloco "Grupos e Vivências").
 *
 * Portado do ProjectCard.tsx / GruposPage.tsx do protótipo:
 *  - cards com imagem 4:3, selo de status, selo de formato e rótulo;
 *  - inscrições abertas: botão com link (formulário); encerradas: botão
 *    desativado + nota "Novas turmas em breve";
 *  - aviso final sobre novas turmas e lista de espera pelo WhatsApp.
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

$defaults = faruk_groups_defaults();
$text     = array();

foreach ( array( 'eyebrow', 'heading', 'intro', 'closed_note', 'disclaimer', 'disclaimer_cta' ) as $key ) {
	$value        = isset( $fields[ $key ] ) ? trim( (string) $fields[ $key ] ) : '';
	$text[ $key ] = '' !== $value ? $value : $defaults[ $key ];
}

$items = array();
if ( ! empty( $fields['items'] ) && is_array( $fields['items'] ) ) {
	foreach ( $fields['items'] as $row ) {
		if ( empty( $row['title'] ) ) {
			continue;
		}
		$items[] = array(
			'title'       => $row['title'],
			'tag'         => isset( $row['tag'] ) ? $row['tag'] : '',
			'status'      => ( isset( $row['status'] ) && 'open' === $row['status'] ) ? 'open' : 'closed',
			'format'      => isset( $row['format'] ) ? $row['format'] : '',
			'dates'       => isset( $row['dates'] ) ? $row['dates'] : '',
			'description' => isset( $row['description'] ) ? $row['description'] : '',
			'image'       => isset( $row['image'] ) ? absint( $row['image'] ) : 0,
			'cta_label'   => ! empty( $row['cta_label'] ) ? $row['cta_label'] : 'Quero me inscrever',
			'cta_url'     => isset( $row['cta_url'] ) ? (string) $row['cta_url'] : '',
			'anchor'      => isset( $row['anchor'] ) ? sanitize_title( $row['anchor'] ) : '',
		);
	}
}
if ( empty( $items ) ) {
	$items = array();
	foreach ( $defaults['items'] as $demo ) {
		$items[] = array_merge(
			$demo,
			array(
				'image'     => 0,
				'cta_label' => 'Quero me inscrever',
				'cta_url'   => '',
				'anchor'    => '',
			)
		);
	}
}

// Degradês de reserva (sem imagem), com as cores da marca, alternados.
$fallbacks = array(
	'bg-gradient-to-br from-terracota-light to-terracota-dark',
	'bg-gradient-to-br from-sage-light to-sage-dark',
	'bg-gradient-to-br from-laranja to-terracota',
	'bg-gradient-to-br from-sage to-terracota-dark',
);

$section_id      = ! empty( $fields['section_id'] ) ? sanitize_title( $fields['section_id'] ) : 'grupos';
$show_disclaimer = empty( $fields['hide_disclaimer'] );
$extra           = ! empty( $attributes['className'] ) ? ' ' . $attributes['className'] : '';
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="section-padding<?php echo esc_attr( faruk_section_bg_class( $fields, 'default' ) . $extra ); ?>">
	<div class="container-wide">

		<div class="mx-auto mb-14 max-w-2xl text-center">
			<span class="text-sm font-semibold uppercase tracking-wider text-terracota">
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

		<div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$is_open   = ( 'open' === $item['status'] );
				$anchor    = '' !== $item['anchor'] ? $item['anchor'] : sanitize_title( $item['title'] );
				$cta_url   = faruk_resolve_link( $item['cta_url'] );
				$is_extern = 0 === strpos( trim( $item['cta_url'] ), 'http' );
				$fallback  = $fallbacks[ $index % count( $fallbacks ) ];
				$img_class = 'h-full w-full object-cover transition-transform duration-700 ' . ( $is_open ? 'group-hover:scale-105' : 'grayscale-[40%]' );
				?>
				<article
					id="<?php echo esc_attr( $anchor ); ?>"
					class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-marrom/5 transition-all duration-300 hover:shadow-lg hover:shadow-terracota-dark/5"
				>
					<!-- Imagem + selos -->
					<div class="relative aspect-[4/3] overflow-hidden">
						<?php if ( $item['image'] ) : ?>
							<?php
							echo wp_get_attachment_image(
								$item['image'],
								'large',
								false,
								array(
									'class'   => $img_class,
									'alt'     => '',
									'loading' => 'lazy',
								)
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup gerado pelo WordPress.
							?>
						<?php else : ?>
							<div class="h-full w-full <?php echo esc_attr( $fallback . ( $is_open ? '' : ' grayscale-[40%]' ) ); ?>"></div>
						<?php endif; ?>
						<div class="absolute inset-0 bg-gradient-to-t from-marrom/30 to-transparent"></div>

						<div class="absolute left-4 top-4">
							<?php if ( $is_open ) : ?>
								<span class="inline-flex items-center gap-1.5 rounded-full bg-sage/90 px-3 py-1.5 text-xs font-semibold text-white backdrop-blur-sm">
									<?php faruk_the_icon( 'calendar-check', 'h-3.5 w-3.5' ); ?>
									<?php esc_html_e( 'Inscrições abertas', 'faruk-arteterapia' ); ?>
								</span>
							<?php else : ?>
								<span class="inline-flex items-center gap-1.5 rounded-full bg-creme/90 px-3 py-1.5 text-xs font-semibold text-terracota-dark backdrop-blur-sm">
									<?php faruk_the_icon( 'lock', 'h-3.5 w-3.5' ); ?>
									<?php esc_html_e( 'Inscrições encerradas', 'faruk-arteterapia' ); ?>
								</span>
							<?php endif; ?>
						</div>

						<?php if ( '' !== $item['format'] ) : ?>
							<div class="absolute right-4 top-4">
								<span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-3 py-1.5 text-xs font-medium text-marrom/70 backdrop-blur-sm">
									<?php faruk_the_icon( 'map-pin', 'h-3 w-3' ); ?>
									<?php echo esc_html( $item['format'] ); ?>
								</span>
							</div>
						<?php endif; ?>

						<?php if ( '' !== $item['tag'] ) : ?>
							<span class="absolute bottom-4 left-4 rounded-full bg-laranja/95 px-3 py-1 text-xs font-semibold text-marrom backdrop-blur-sm">
								<?php echo esc_html( $item['tag'] ); ?>
							</span>
						<?php endif; ?>
					</div>

					<!-- Conteúdo -->
					<div class="flex flex-1 flex-col p-6">
						<h3 class="font-serif text-xl text-marrom">
							<?php echo esc_html( $item['title'] ); ?>
						</h3>

						<?php if ( '' !== $item['dates'] ) : ?>
							<p class="mt-2 flex items-center gap-2 text-xs font-medium text-sage">
								<?php faruk_the_icon( 'calendar', 'h-3.5 w-3.5' ); ?>
								<?php echo esc_html( $item['dates'] ); ?>
							</p>
						<?php endif; ?>

						<p class="mt-2 flex-1 text-sm leading-relaxed text-marrom/65">
							<?php echo esc_html( $item['description'] ); ?>
						</p>

						<?php if ( $is_open ) : ?>
							<a
								href="<?php echo $cta_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_resolve_link(). ?>"
								<?php echo $is_extern ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
								class="mt-5 inline-flex items-center justify-center gap-2 rounded-full bg-terracota px-6 py-3 text-sm font-semibold text-white transition-all duration-300 hover:bg-terracota-dark hover:shadow-lg hover:shadow-terracota/25"
								data-gtm-event="form_lead_click"
								data-gtm-location="groups_projects"
								data-gtm-lead-type="project_signup"
								data-gtm-item="<?php echo esc_attr( $item['title'] ); ?>"
							>
								<?php echo esc_html( $item['cta_label'] ); ?>
								<?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?>
							</a>
						<?php else : ?>
							<button
								type="button"
								disabled
								class="mt-5 inline-flex cursor-not-allowed items-center justify-center gap-2 rounded-full bg-creme-dark px-6 py-3 text-sm font-semibold text-marrom/40"
							>
								<?php esc_html_e( 'Inscrições encerradas', 'faruk-arteterapia' ); ?>
							</button>
							<?php if ( '' !== $text['closed_note'] ) : ?>
								<p class="mt-3 text-center text-xs font-medium text-terracota">
									<?php echo esc_html( $text['closed_note'] ); ?>
								</p>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( $show_disclaimer && '' !== $text['disclaimer'] ) : ?>
			<div class="mx-auto mt-12 flex max-w-3xl flex-col items-start gap-4 rounded-2xl bg-creme-dark/60 p-6 ring-1 ring-marrom/5 sm:flex-row sm:items-center sm:gap-6">
				<span class="shrink-0 text-terracota">
					<?php faruk_the_icon( 'info', 'h-6 w-6' ); ?>
				</span>
				<p class="flex-1 text-sm leading-relaxed text-marrom/70">
					<?php echo esc_html( $text['disclaimer'] ); ?>
				</p>
				<a
					href="<?php echo faruk_whatsapp_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- já escapado em faruk_whatsapp_url(). ?>"
					target="_blank"
					rel="noopener noreferrer"
					class="inline-flex shrink-0 items-center gap-2 text-sm font-semibold text-terracota transition-all hover:gap-3"
					data-gtm-event="whatsapp_click"
					data-gtm-location="groups_waitlist"
				>
					<?php echo esc_html( $text['disclaimer_cta'] ); ?>
					<?php faruk_the_icon( 'arrow-right', 'h-4 w-4' ); ?>
				</a>
			</div>
		<?php endif; ?>

	</div>
</section>
