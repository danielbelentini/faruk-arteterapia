<?php
/**
 * Página legal (Política de Privacidade, Política de Cookies etc.).
 *
 * Mostra o título e o conteúdo escrito no editor do WordPress, com
 * tipografia limpa (classe .legal-content em assets/css/input.css).
 * O texto jurídico NÃO vem pronto no tema: deve ser redigido/validado
 * pelo responsável e colado no editor da página.
 *
 * @package faruk-arteterapia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="flex-1">
	<?php while ( have_posts() ) : the_post(); ?>
		<section class="bg-gradient-to-br from-sage-dark via-sage-dark to-terracota-dark pb-16 pt-32 text-white md:pb-20 md:pt-40">
			<div class="container-wide box-content px-6 md:px-12 lg:px-16">
				<div class="mx-auto max-w-3xl">
					<span class="inline-block rounded-full bg-laranja/20 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-laranja-light ring-1 ring-laranja/30">
						<?php esc_html_e( 'Documento legal', 'faruk-arteterapia' ); ?>
					</span>
					<h1 class="mt-5 text-4xl leading-tight md:text-5xl">
						<?php the_title(); ?>
					</h1>
					<p class="mt-4 text-sm text-creme/70">
						<?php
						printf(
							/* translators: %s: data da última atualização */
							esc_html__( 'Última atualização: %s', 'faruk-arteterapia' ),
							esc_html( get_the_modified_date() )
						);
						?>
					</p>
				</div>
			</div>
		</section>

		<section class="section-padding">
			<div class="container-wide">
				<article class="mx-auto max-w-3xl">
					<?php if ( '' === trim( get_the_content() ) ) : ?>
						<?php if ( current_user_can( 'edit_pages' ) ) : ?>
							<p class="rounded-2xl bg-laranja/20 p-5 text-sm text-marrom/80">
								<?php esc_html_e( 'Esta página está sem conteúdo. Cole o texto do documento no editor da página (visível só para administradores).', 'faruk-arteterapia' ); ?>
							</p>
						<?php endif; ?>
					<?php else : ?>
						<div class="legal-content">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>
				</article>
			</div>
		</section>
	<?php endwhile; ?>
</main>

<?php
get_footer();
