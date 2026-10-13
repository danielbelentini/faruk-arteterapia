<?php
/**
 * Template de reserva (fallback) do tema.
 *
 * O WordPress exige este arquivo. As páginas reais do site serão montadas
 * com os blocos do Gutenberg (Carbon Fields) nas próximas etapas; por ora,
 * este template apenas renderiza o conteúdo padrão.
 *
 * @package faruk-arteterapia
 */

get_header();
?>

<main id="main" class="flex-1">
	<div class="container-wide section-padding pt-32 md:pt-40">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'mx-auto max-w-3xl' ); ?>>
					<h1 class="text-4xl md:text-5xl"><?php the_title(); ?></h1>
					<div class="mt-8 space-y-4 text-base leading-relaxed text-marrom/75">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p class="mx-auto max-w-3xl text-base text-marrom/75">
				<?php esc_html_e( 'Nenhum conteúdo encontrado.', 'faruk-arteterapia' ); ?>
			</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
