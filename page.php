<?php
/**
 * Template das páginas (Início, Arteterapia, Grupos, Sobre, Contato...).
 *
 * Renderiza só o conteúdo do editor, em LARGURA TOTAL: cada bloco do tema
 * controla o próprio espaçamento e largura (container-wide, section-padding).
 * Sem este arquivo, o WordPress usa o index.php, que limita o conteúdo a uma
 * coluna estreita de texto.
 *
 * @package faruk-arteterapia
 */

get_header();
?>

<main id="main" class="flex-1">
	<?php
	while ( have_posts() ) {
		the_post();
		the_content();
	}
	?>
</main>

<?php
get_footer();
