<?php
/**
 * Carbon Fields: inicialização, painel de opções do tema e registro de blocos.
 *
 * O Carbon Fields pode vir de duas formas:
 *  1. Composer, dentro do tema (pasta vendor/): `composer install`.
 *  2. Plugin do Carbon Fields instalado e ativo no WordPress.
 *
 * @package faruk-arteterapia
 */

use Carbon_Fields\Block;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Carrega e inicializa o Carbon Fields quando ele vem do Composer.
 * Se o plugin já estiver ativo, ele mesmo cuida da inicialização.
 */
function faruk_boot_carbon_fields(): void {
	if ( class_exists( '\Carbon_Fields\Carbon_Fields' ) ) {
		return;
	}

	$autoload = FARUK_DIR . '/vendor/autoload.php';

	if ( file_exists( $autoload ) ) {
		require_once $autoload;
		\Carbon_Fields\Carbon_Fields::boot();
	}
}
add_action( 'after_setup_theme', 'faruk_boot_carbon_fields' );

/**
 * Avisa quem administra o site se o Carbon Fields não estiver disponível.
 */
function faruk_carbon_fields_notice(): void {
	if ( class_exists( '\Carbon_Fields\Carbon_Fields' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-error"><p><strong>%s</strong> %s</p></div>',
		esc_html__( 'Tema Faruk Arteterapia:', 'faruk-arteterapia' ),
		esc_html__( 'o Carbon Fields não foi encontrado. Rode "composer install" na pasta do tema ou ative o plugin Carbon Fields.', 'faruk-arteterapia' )
	);
}
add_action( 'admin_notices', 'faruk_carbon_fields_notice' );

/**
 * Painel "Configurações do site": contatos, registro profissional, GTM e privacidade (LGPD).
 * Valores lidos por faruk_option() em inc/helpers.php.
 */
function faruk_register_theme_options(): void {
	Container::make( 'theme_options', __( 'Configurações do site', 'faruk-arteterapia' ) )
		->set_page_menu_title( __( 'Configurações do site', 'faruk-arteterapia' ) )
		->add_tab(
			__( 'Contato', 'faruk-arteterapia' ),
			array(
				Field::make( 'text', 'faruk_whatsapp_url', __( 'Link do WhatsApp', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Exemplo: https://wa.me/5511999999999', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'faruk_instagram_url', __( 'Link do Instagram', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Exemplo: https://instagram.com/seu.perfil', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'faruk_phone', __( 'Telefone de exibição', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Aparece no rodapé, como link de ligação. Exemplo: (11) 99999-9999. Vazio = não aparece.', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'faruk_email', __( 'E-mail de contato', 'faruk-arteterapia' ) )
					->set_attribute( 'type', 'email' )
					->set_help_text( __( 'Aparece no rodapé. Vazio = não aparece.', 'faruk-arteterapia' ) ),
			)
		)
		->add_tab(
			__( 'Profissional', 'faruk-arteterapia' ),
			array(
				Field::make( 'text', 'faruk_registro', __( 'Registro profissional', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Aparece no rodapé. Exemplo: n.º 1234/00', 'faruk-arteterapia' ) ),
			)
		)
		->add_tab(
			__( 'Rastreamento', 'faruk-arteterapia' ),
			array(
				Field::make( 'text', 'faruk_gtm_id', __( 'ID do Google Tag Manager', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Exemplo: GTM-XXXXXXX. Deixe vazio para desativar.', 'faruk-arteterapia' ) ),
			)
		)
		->add_tab(
			__( 'Privacidade (LGPD)', 'faruk-arteterapia' ),
			array(
				Field::make( 'text', 'faruk_privacy_url', __( 'Link da Política de Privacidade', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Vazio = usa a página com o endereço /politica-de-privacidade/, se existir.', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'faruk_cookies_url', __( 'Link da Política de Cookies', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Vazio = usa a página com o endereço /politica-de-cookies/, se existir.', 'faruk-arteterapia' ) ),
				Field::make( 'checkbox', 'faruk_cookie_banner', __( 'Desativar o banner de cookies', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Só marque se o site não usar nenhum cookie de medição. Com o GTM ativo, mantenha o banner.', 'faruk-arteterapia' ) ),
			)
		);
}
add_action( 'carbon_fields_register_fields', 'faruk_register_theme_options' );

/**
 * Renderiza um componente de template-parts/components/ a partir de um bloco.
 *
 * Uso nos blocos (etapas seguintes):
 *   ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
 *       faruk_render_block( 'hero-carousel', $fields, $attributes, $inner_blocks );
 *   } );
 *
 * @param string $component    Nome do arquivo em template-parts/components/ (sem .php).
 * @param array  $fields       Campos do bloco.
 * @param array  $attributes   Atributos do bloco.
 * @param string $inner_blocks HTML dos blocos internos.
 */
function faruk_render_block( string $component, array $fields = array(), array $attributes = array(), $inner_blocks = '' ): void {
	get_template_part(
		'template-parts/components/' . $component,
		null,
		array(
			'fields'       => $fields,
			'attributes'   => $attributes,
			'inner_blocks' => $inner_blocks,
		)
	);
}

/* ------------------------------------------------------------------
 * Blocos do Gutenberg (Block::make)
 * Cada bloco tem um template em template-parts/components/.
 * ---------------------------------------------------------------- */

/**
 * Nome interno do bloco Hero (gerado pelo Carbon Fields a partir do título
 * "Hero Carrossel"). Usado só para carregar o Swiper nas páginas que o usam.
 */
function faruk_hero_block_name(): string {
	return 'carbon-fields/hero-carrossel';
}

/**
 * Slides padrão do carrossel (do protótipo). Usados enquanto o bloco não
 * tiver slides cadastrados, para a prévia e o primeiro teste.
 *
 * @return array<int,array<string,mixed>>
 */
function faruk_hero_default_slides(): array {
	return array(
		array(
			'badge'     => 'Atendimento individual',
			'title'     => 'Arteterapia Junguiana',
			'subtitle'  => 'Um espaço de escuta e criação para conhecer-se melhor e encontrar novos caminhos.',
			'image'     => 0,
			'cta_type'  => 'whatsapp',
			'cta_label' => 'Falar pelo WhatsApp',
			'cta_url'   => '',
		),
		array(
			'badge'     => 'Inscrições abertas',
			'title'     => 'Ciclo das Princesas',
			'subtitle'  => 'Vivência em grupo inspirada nos arquétipos femininos. Vagas limitadas.',
			'image'     => 0,
			'cta_type'  => 'link',
			'cta_label' => 'Ver projeto',
			'cta_url'   => '/grupos-projetos-e-vivencias/#ciclo-princesas',
		),
		array(
			'badge'     => 'Grupos e vivências',
			'title'     => 'Encontros que transformam',
			'subtitle'  => 'Grupos, projetos e vivências arteterapêuticas para diferentes momentos da vida.',
			'image'     => 0,
			'cta_type'  => 'link',
			'cta_label' => 'Conhecer grupos',
			'cta_url'   => '/grupos-projetos-e-vivencias/',
		),
		array(
			'badge'     => 'Para profissionais',
			'title'     => 'Supervisão para arteterapeutas',
			'subtitle'  => 'Encontros de supervisão individual e em grupo para a sua prática clínica.',
			'image'     => 0,
			'cta_type'  => 'link',
			'cta_label' => 'Saber mais',
			'cta_url'   => '/grupos-projetos-e-vivencias/#supervisao',
		),
	);
}

/**
 * Textos padrão do bloco "Duas Portas de Entrada" (do protótipo).
 *
 * @return array<string,string>
 */
function faruk_dual_entry_defaults(): array {
	return array(
		'eyebrow'         => 'Por onde começar',
		'heading'         => 'Duas portas de entrada',
		'card1_title'     => 'Atendimento individual',
		'card1_label'     => 'Arteterapia Junguiana',
		'card1_text'      => 'Um processo terapêutico pessoal, com encontros regulares e propostas de criação artística. Online ou presencial.',
		'card1_link_text' => 'Conhecer',
		'card1_link_url'  => '/arteterapia-junguiana/',
		'card2_title'     => 'Grupos, projetos e vivências',
		'card2_label'     => 'Encontros coletivos',
		'card2_text'      => 'Vivências em grupo, projetos temáticos, trabalhos para escolas e empresas, e supervisão para arteterapeutas.',
		'card2_link_text' => 'Explorar',
		'card2_link_url'  => '/grupos-projetos-e-vivencias/',
	);
}

/**
 * Ícones disponíveis nos seletores dos blocos (slug do Lucide => rótulo).
 * Os slugs precisam existir em inc/icons.php.
 *
 * @return array<string,string>
 */
function faruk_decorative_icon_options(): array {
	return array(
		'pen-tool'        => __( 'Caneta (desenho)', 'faruk-arteterapia' ),
		'palette'         => __( 'Paleta (pintura)', 'faruk-arteterapia' ),
		'scissors'        => __( 'Tesoura (colagem)', 'faruk-arteterapia' ),
		'box'             => __( 'Caixa (modelagem)', 'faruk-arteterapia' ),
		'pen-line'        => __( 'Caneta e linha (escrita)', 'faruk-arteterapia' ),
		'image'           => __( 'Imagem', 'faruk-arteterapia' ),
		'music'           => __( 'Música', 'faruk-arteterapia' ),
		'heart-handshake' => __( 'Coração e mãos (acolhimento)', 'faruk-arteterapia' ),
		'ear'             => __( 'Ouvido (escuta)', 'faruk-arteterapia' ),
		'feather'         => __( 'Pena (liberdade)', 'faruk-arteterapia' ),
		'messages-square' => __( 'Conversa (diálogo)', 'faruk-arteterapia' ),
		'award'           => __( 'Medalha', 'faruk-arteterapia' ),
	);
}

/**
 * Textos padrão (provisórios) do bloco "Conceito Iceberg" — versão da Início.
 *
 * @return array<string,string>
 */
function faruk_iceberg_defaults(): array {
	return array(
		'eyebrow'          => 'Compreensão',
		'heading'          => 'O que é a Arteterapia Junguiana?',
		'paragraph_1'      => 'É uma forma de terapia que usa a expressão artística como caminho de autoconhecimento. Inspirada na psicologia analítica de Jung, considera que cada pessoa carrega imagens e símbolos que revelam aspectos profundos da psique.',
		'paragraph_2'      => 'Através do contato com materiais artísticos, sentimentos e questões que estão além da palavra podem encontrar forma e significado — como um iceberg, onde a maior parte está sob a superfície.',
		'label_conscious'  => 'Consciente',
		'label_unconscious' => 'Inconsciente',
	);
}



/**
 * Textos padrão (provisórios) do bloco "Trajetória e Valores" — tela Sobre.
 *
 * @return array<string,mixed>
 */
function faruk_trajectory_defaults(): array {
	return array(
		'traj_eyebrow'  => 'Trajetória',
		'traj_heading'  => 'O caminho até aqui',
		'traj_text'     => "Minha jornada com a arteterapia começou a partir de uma percepção simples: havia coisas que as palavras não conseguiam alcançar, mas que apareciam com clareza quando havia tinta, argila ou papel nas mãos. Essa descoberta me levou a estudar a psicologia analítica de Jung e a me aprofundar no universo da arteterapia.\n\nAo longo dos anos, atendi pessoas em processos individuais, conduzi grupos e vivências, e levei a arteterapia para escolas e instituições. Cada encontro ensinou algo novo sobre a capacidade humana de transformar e ser transformado pela criação.\n\nHoje, meu trabalho reúne atendimento individual, projetos em grupo, formação para escolas e empresas, e supervisão para colegas arteterapeutas. Em todos eles, o fio condutor é o mesmo: a escuta atenta ao que emerge — nas palavras, nas imagens e nos silêncios.",
		'form_eyebrow'  => 'Formação',
		'form_heading'  => 'Formação e experiência',
		'form_items'    => array(
			'Graduação em Psicologia — Universidade [nome]',
			'Especialização em Arteterapia — Instituto [nome]',
			'Formação em Psicologia Analítica (Jung) — Instituto [nome]',
			'Atuação clínica há mais de 10 anos',
			'Experiência com grupos, escolas, empresas e supervisão',
		),
		'val_eyebrow'   => 'Como eu trabalho',
		'val_heading'   => 'Quatro valores',
		'val_items'     => array(
			array(
				'icon'        => 'heart-handshake',
				'title'       => 'Acolhimento',
				'description' => 'Um espaço seguro onde cada pessoa é recebida como está, sem pressa e sem expectativas prévias.',
			),
			array(
				'icon'        => 'ear',
				'title'       => 'Escuta ativa',
				'description' => 'Atenção genuína ao que é dito com palavras e ao que emerge através das imagens e dos materiais.',
			),
			array(
				'icon'        => 'feather',
				'title'       => 'Liberdade sem julgamentos',
				'description' => 'Liberdade para criar, experimentar e expressar — sem a preocupação com o resultado estético.',
			),
			array(
				'icon'        => 'messages-square',
				'title'       => 'Relação dialógica',
				'description' => 'O processo terapêutico como uma construção conjunta, em que terapeuta e cliente caminham juntos.',
			),
		),
	);
}

/**
 * Textos padrão (provisórios) do bloco "Como Funciona".
 *
 * @return array<string,mixed>
 */
function faruk_how_it_works_defaults(): array {
	return array(
		'eyebrow' => 'O processo',
		'heading' => 'Como funciona',
		'steps'   => array(
			array(
				'title'       => 'Primeiros encontros',
				'description' => 'Um espaço inicial de conhecimento mútuo, onde você compartilha suas questões e definimos juntos o caminho do processo.',
			),
			array(
				'title'       => 'Propostas com materiais artísticos',
				'description' => 'A cada sessão, surgem propostas de criação com diferentes linguagens — desenho, pintura, colagem, modelagem, escrita e mais.',
			),
			array(
				'title'       => 'Frequência semanal ou quinzenal',
				'description' => 'Os encontros acontecem regularmente, criando continuidade e profundidade para o processo terapêutico.',
			),
			array(
				'title'       => 'Online ou presencial',
				'description' => 'Você escolhe o formato que melhor se adequa à sua rotina. Ambos mantêm a mesma qualidade de escuta e cuidado.',
			),
		),
	);
}

/**
 * Textos padrão (provisórios) do bloco "Linguagens e Materiais".
 *
 * @return array<string,mixed>
 */
function faruk_materials_defaults(): array {
	return array(
		'eyebrow' => 'Recursos',
		'heading' => 'Linguagens e materiais',
		'intro'   => 'Cada encontro pode explorar diferentes formas de expressão. A escolha depende do momento do processo e do que emerge em cada sessão.',
		'items'   => array(
			array( 'icon' => 'pen-tool', 'name' => 'Desenho' ),
			array( 'icon' => 'palette', 'name' => 'Pintura' ),
			array( 'icon' => 'scissors', 'name' => 'Colagem' ),
			array( 'icon' => 'box', 'name' => 'Modelagem' ),
			array( 'icon' => 'pen-line', 'name' => 'Escrita' ),
			array( 'icon' => 'image', 'name' => 'Imagens' ),
			array( 'icon' => 'music', 'name' => 'Música' ),
		),
	);
}

/**
 * Ícones disponíveis nas listas do bloco "Escopo e Limites".
 *
 * @return array<string,string>
 */
function faruk_scope_icon_options(): array {
	return array(
		'users'        => __( 'Pessoas', 'faruk-arteterapia' ),
		'monitor'      => __( 'Tela (online)', 'faruk-arteterapia' ),
		'map-pin'      => __( 'Local (presencial)', 'faruk-arteterapia' ),
		'circle-check' => __( 'Check', 'faruk-arteterapia' ),
		'info'         => __( 'Informação', 'faruk-arteterapia' ),
	);
}

/**
 * Nome interno do bloco Depoimentos (carrega o Swiper só onde ele é usado).
 */
function faruk_testimonials_block_name(): string {
	return 'carbon-fields/depoimentos';
}

/**
 * Textos padrão (provisórios) do bloco "Escopo e Limites".
 *
 * @return array<string,mixed>
 */
function faruk_scope_defaults(): array {
	return array(
		'eyebrow'      => 'Escopo',
		'heading'      => 'Para quem é e limites do atendimento',
		'card1_title'  => 'Para quem é',
		'card1_items'  => array(
			array(
				'icon' => 'users',
				'text' => 'Atendimento para adolescentes e adultos que buscam autoconhecimento, elaboração emocional ou suporte em processos de transformação pessoal.',
			),
			array(
				'icon' => 'monitor',
				'text' => 'Disponível online (videochamada) ou presencial, conforme a preferência e a disponibilidade de cada pessoa.',
			),
		),
		'card2_title'  => 'Limites do atendimento',
		'card2_items'  => array(
			array(
				'icon' => 'info',
				'text' => 'A arteterapia não substitui acompanhamento psiquiátrico, psicológico ou médico quando necessário. Em casos de urgência, procure um serviço de saúde adequado.',
			),
		),
	);
}

/**
 * Textos padrão (provisórios) do bloco "FAQ".
 *
 * @return array<string,mixed>
 */
function faruk_faq_defaults(): array {
	return array(
		'eyebrow' => 'Dúvidas',
		'heading' => 'Perguntas frequentes',
		'items'   => array(
			array(
				'question' => 'Quanto custa uma sessão?',
				'answer'   => 'Os valores variam conforme o formato (individual, grupo ou supervisão) e a modalidade (online ou presencial). Entre em contato pelo WhatsApp para receber a tabela atual.',
			),
			array(
				'question' => 'Qual a frequência dos encontros?',
				'answer'   => 'O atendimento individual costuma ser semanal ou quinzenal. Grupos e vivências têm cronogramas específicos, divulgados em cada projeto.',
			),
			array(
				'question' => 'É online ou presencial?',
				'answer'   => 'Ambos. O atendimento individual pode ser online ou presencial. Grupos e vivências ocorrem majoritariamente de forma online, com alguns encontros presenciais.',
			),
			array(
				'question' => 'Preciso saber desenhar?',
				'answer'   => 'Não. A arteterapia não exige nenhuma habilidade artística prévia. O foco está no processo criativo e no que ele revela, não no resultado final.',
			),
			array(
				'question' => 'As sessões são confidenciais?',
				'answer'   => 'Sim. Tudo o que é compartilhado nos encontros — verbalmente ou através da produção artística — é confidencial e segue o código de ética profissional.',
			),
		),
	);
}

/**
 * Textos padrão (provisórios) do bloco "Depoimentos".
 *
 * @return array<string,mixed>
 */
function faruk_testimonials_defaults(): array {
	return array(
		'eyebrow' => 'Depoimentos',
		'heading' => 'Experiências reais',
		'items'   => array(
			array(
				'text'   => 'A arteterapia com o Faruk me ajudou a enxergar coisas que eu não conseguia colocar em palavras. O processo mudou minha forma de lidar com as emoções.',
				'author' => 'Mariana A.',
				'role'   => 'Atendimento individual',
			),
			array(
				'text'   => 'Participar do grupo foi um divisor de águas. O acolhimento e a liberdade para criar sem julgamentos me fizeram crescer muito.',
				'author' => 'Carlos Eduardo M.',
				'role'   => 'Grupo de vivência',
			),
			array(
				'text'   => 'Como arteterapeuta, a supervisão me deu segurança e profundidade no trabalho clínico. A escuta do Faruk é atenta e generosa.',
				'author' => 'Juliana R.',
				'role'   => 'Supervisão profissional',
			),
		),
	);
}

/**
 * Textos padrão (provisórios) do bloco "Canais de Contato" (ContatoPage.tsx).
 *
 * @return array<string,string>
 */
function faruk_contact_defaults(): array {
	return array(
		'wa_label'          => 'Canal principal',
		'wa_text'           => 'Resposta mais rápida. Ideal para tirar dúvidas sobre atendimento individual, valores e disponibilidade.',
		'wa_cta'            => 'Abrir conversa',
		'ig_handle'         => '@faruk.arteterapia',
		'ig_text'           => 'Conteúdos sobre arteterapia, junguiana, grupos e vivências. Um espaço de troca e inspiração.',
		'ig_cta'            => 'Seguir',
		'att_eyebrow'       => 'Atendimento',
		'att_heading'       => 'Online e presencial',
		'online_title'      => 'Online',
		'online_text'       => 'Atendimento por videochamada, com a mesma estrutura e cuidado do formato presencial. Você só precisa de um espaço tranquilo e acesso aos materiais.',
		'online_note'       => 'Disponível para todo o Brasil e exterior',
		'presencial_title'  => 'Presencial',
		'presencial_text'   => 'Atendimento em consultório, com ambiente preparado e materiais artísticos disponíveis. Para quem prefere o encontro face a face.',
		'presencial_address' => '[Espaço para endereço — Rua, número, bairro, cidade]',
		'others_eyebrow'    => 'Outros formatos',
		'others_heading'    => 'Grupos, escolas, empresas e supervisão',
		'others_text'       => 'Esses casos têm formulário próprio, com campos específicos para entender melhor a sua necessidade. Acesse a página de grupos e projetos para encontrar o formulário adequado.',
		'others_button'     => 'Ir para grupos e projetos',
		'others_url'        => '/grupos-projetos-e-vivencias/',
	);
}

/**
 * Textos padrão da chamada final (CTA.tsx).
 *
 * @return array<string,string>
 */
function faruk_final_cta_defaults(): array {
	return array(
		'title'    => 'Pronto para começar?',
		'subtitle' => 'Fale pelo WhatsApp e tire suas dúvidas. O primeiro passo é uma conversa.',
		'button'   => 'Falar pelo WhatsApp',
	);
}

/**
 * Textos padrão (provisórios) do bloco "Grupos e Vivências" (GruposPage.tsx).
 * Os projetos de exemplo não têm imagem (o card usa um degradê da marca).
 *
 * @return array<string,mixed>
 */
function faruk_groups_defaults(): array {
	return array(
		'eyebrow'         => 'Grupos e vivências',
		'heading'         => 'Projetos em andamento',
		'intro'           => 'Grupos terapêuticos e vivências com temáticas específicas, cada um com seu próprio percurso e ritmo.',
		'closed_note'     => 'Novas turmas em breve',
		'disclaimer'      => 'Novas turmas são formadas conforme a procura. Para entrar na lista de espera ou saber quando novas inscrições abrirem, fale pelo WhatsApp.',
		'disclaimer_cta'  => 'Entrar na lista de espera',
		'items'           => array(
			array(
				'title'       => 'Ciclo das Princesas',
				'tag'         => 'Grupo',
				'status'      => 'open',
				'format'      => 'Online',
				'dates'       => '',
				'description' => 'Vivência em grupo inspirada nos arquétipos femininos dos contos de fadas, explorando identidade, sombra e transformação.',
			),
			array(
				'title'       => 'Vivência arteterapêutica dos mitos',
				'tag'         => 'Vivência',
				'status'      => 'closed',
				'format'      => 'Online',
				'dates'       => '',
				'description' => 'Encontros que utilizam narrativas mitológicas como espelho para a jornada pessoal, com propostas de criação artística.',
			),
			array(
				'title'       => 'Flores que acolhem',
				'tag'         => 'Grupo',
				'status'      => 'closed',
				'format'      => 'Presencial',
				'dates'       => '',
				'description' => 'Grupo de arteterapia com elementos da natureza, trabalhando acolhimento, autocuidado e ciclos de vida através de flores.',
			),
			array(
				'title'       => 'Grupo de estudos sobre adolescência para pais',
				'tag'         => 'Estudos',
				'status'      => 'closed',
				'format'      => 'Online',
				'dates'       => '',
				'description' => 'Encontros para pais e responsáveis que desejam compreender a adolescência a partir da perspectiva junguiana e da arteterapia.',
			),
		),
	);
}

/**
 * Ícones disponíveis nos blocos da página de Grupos (âncoras, cards, modalidades).
 *
 * @return array<string,string>
 */
function faruk_groups_page_icon_options(): array {
	return array(
		'users'           => __( 'Pessoas (grupos)', 'faruk-arteterapia' ),
		'graduation-cap'  => __( 'Capelo (escolas)', 'faruk-arteterapia' ),
		'building-2'      => __( 'Prédio (empresas)', 'faruk-arteterapia' ),
		'messages-square' => __( 'Conversa (supervisão)', 'faruk-arteterapia' ),
		'message-circle'  => __( 'Balão (individual)', 'faruk-arteterapia' ),
		'waves'           => __( 'Ondas', 'faruk-arteterapia' ),
	);
}

/**
 * Padrões do bloco "Navegação por Âncoras" ("Nesta página:").
 *
 * @return array<string,mixed>
 */
function faruk_anchor_nav_defaults(): array {
	return array(
		'label' => 'Nesta página:',
		'items' => array(
			array( 'icon' => 'users', 'label' => 'Grupos e vivências', 'target' => 'grupos' ),
			array( 'icon' => 'graduation-cap', 'label' => 'Escolas e empresas', 'target' => 'escolas-empresas' ),
			array( 'icon' => 'messages-square', 'label' => 'Supervisão para arteterapeutas', 'target' => 'supervisao' ),
		),
	);
}

/**
 * Padrões do bloco "Escolas e Empresas" (GruposPage.tsx).
 *
 * @return array<string,mixed>
 */
function faruk_schools_companies_defaults(): array {
	return array(
		'section_id' => 'escolas-empresas',
		'eyebrow'    => 'Escolas e empresas',
		'heading'    => 'O mesmo trabalho, para públicos diferentes',
		'intro'      => 'A arteterapia pode ser levada para escolas, empresas e instituições, com propostas adaptadas a cada contexto e necessidade.',
		'items'      => array(
			array(
				'icon'        => 'graduation-cap',
				'title'       => 'Escolas',
				'description' => 'Vivências e projetos arteterapêuticos para alunos, educadores e famílias. Propostas que complementam o trabalho pedagógico com escuta, expressão e autoconhecimento.',
				'topics'      => "Alunos, educadores e famílias\nPropostas adaptadas ao contexto da escola\nEscuta, expressão e autoconhecimento",
				'button'      => 'Quero saber mais',
				'url'         => '',
			),
			array(
				'icon'        => 'building-2',
				'title'       => 'Empresas',
				'description' => 'Encontros de arteterapia para equipes, com foco em bem-estar, saúde mental e desenvolvimento humano. Uma experiência diferente de cuidado coletivo no ambiente corporativo.',
				'topics'      => "Encontros para equipes\nBem-estar e desenvolvimento humano\nCuidado coletivo no ambiente corporativo",
				'button'      => 'Quero saber mais',
				'url'         => '',
			),
		),
	);
}

/**
 * Padrões do bloco "Supervisão para Arteterapeutas" (GruposPage.tsx).
 *
 * @return array<string,mixed>
 */
function faruk_supervision_defaults(): array {
	return array(
		'section_id' => 'supervisao',
		'eyebrow'    => 'Para profissionais',
		'heading'    => 'Supervisão para arteterapeutas',
		'text'       => 'A supervisão é um espaço fundamental para a prática clínica do arteterapeuta. Um momento de aprofundamento teórico, troca de experiências e cuidado com o próprio fazer terapêutico.',
		'button'     => 'Quero saber mais',
		'url'        => '',
		'items'      => array(
			array( 'icon' => 'users', 'text' => 'Supervisão em grupo — encontros periódicos com pares' ),
			array( 'icon' => 'message-circle', 'text' => 'Supervisão individual — acompanhamento dedicado da sua prática' ),
		),
	);
}

/**
 * Registra os blocos do tema. Cada etapa seguinte adiciona seus blocos aqui.
 */
function faruk_register_blocks(): void {
	$category_slug  = 'faruk-arteterapia';
	$category_title = __( 'Faruk Arteterapia', 'faruk-arteterapia' );

	/* ---- Hero / Carrossel ------------------------------------------ */
	Block::make( __( 'Hero Carrossel', 'faruk-arteterapia' ) )
		->set_description( __( 'Destaque principal da página inicial, com slides em carrossel.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'format-gallery' )
		->add_fields(
			array(
				Field::make( 'select', 'height', __( 'Altura do Hero', 'faruk-arteterapia' ) )
					->set_options(
						array(
							'full'    => __( 'Alta (página inicial)', 'faruk-arteterapia' ),
							'compact' => __( 'Compacta (páginas internas)', 'faruk-arteterapia' ),
						)
					),
				Field::make( 'complex', 'slides', __( 'Slides', 'faruk-arteterapia' ) )
					->set_layout( 'tabbed-vertical' )
					->set_max( 6 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Slides', 'faruk-arteterapia' ),
							'singular_name' => __( 'Slide', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'text', 'badge', __( 'Selo (texto pequeno acima do título)', 'faruk-arteterapia' ) ),
							Field::make( 'text', 'title', __( 'Título', 'faruk-arteterapia' ) )
								->set_required( true ),
							Field::make( 'textarea', 'subtitle', __( 'Subtítulo', 'faruk-arteterapia' ) )
								->set_rows( 3 ),
							Field::make( 'image', 'image', __( 'Imagem de fundo', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Use uma imagem horizontal, com pelo menos 1600 px de largura. Sem imagem, o slide usa um degradê com as cores da marca.', 'faruk-arteterapia' ) ),
							Field::make( 'select', 'cta_type', __( 'Tipo do botão', 'faruk-arteterapia' ) )
								->set_options(
									array(
										'whatsapp' => __( 'WhatsApp', 'faruk-arteterapia' ),
										'link'     => __( 'Link (página ou âncora)', 'faruk-arteterapia' ),
									)
								),
							Field::make( 'text', 'cta_label', __( 'Texto do botão', 'faruk-arteterapia' ) ),
							Field::make( 'text', 'cta_url', __( 'Link do botão', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Exemplo: /grupos-projetos-e-vivencias/#ciclo-princesas', 'faruk-arteterapia' ) )
								->set_conditional_logic(
									array(
										array(
											'field' => 'cta_type',
											'value' => 'link',
										),
									)
								),
						)
					),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'hero-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Duas Portas de Entrada ------------------------------------ */
	$d = faruk_dual_entry_defaults();

	Block::make( __( 'Duas Portas de Entrada', 'faruk-arteterapia' ) )
		->set_description( __( 'Seção com dois cards: atendimento individual e grupos, projetos e vivências.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'columns' )
		->add_fields(
			array(
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $d['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título da seção', 'faruk-arteterapia' ) )
					->set_default_value( $d['heading'] ),

				Field::make( 'separator', 'sep_card1', __( 'Card 1 — Atendimento individual', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'card1_title', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $d['card1_title'] ),
				Field::make( 'text', 'card1_label', __( 'Rótulo (abaixo do título)', 'faruk-arteterapia' ) )
					->set_default_value( $d['card1_label'] ),
				Field::make( 'textarea', 'card1_text', __( 'Descrição', 'faruk-arteterapia' ) )
					->set_rows( 3 )
					->set_default_value( $d['card1_text'] ),
				Field::make( 'text', 'card1_link_text', __( 'Texto do link', 'faruk-arteterapia' ) )
					->set_default_value( $d['card1_link_text'] ),
				Field::make( 'text', 'card1_link_url', __( 'Link', 'faruk-arteterapia' ) )
					->set_default_value( $d['card1_link_url'] ),

				Field::make( 'separator', 'sep_card2', __( 'Card 2 — Grupos, projetos e vivências', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'card2_title', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $d['card2_title'] ),
				Field::make( 'text', 'card2_label', __( 'Rótulo (abaixo do título)', 'faruk-arteterapia' ) )
					->set_default_value( $d['card2_label'] ),
				Field::make( 'textarea', 'card2_text', __( 'Descrição', 'faruk-arteterapia' ) )
					->set_rows( 3 )
					->set_default_value( $d['card2_text'] ),
				Field::make( 'text', 'card2_link_text', __( 'Texto do link', 'faruk-arteterapia' ) )
					->set_default_value( $d['card2_link_text'] ),
				Field::make( 'text', 'card2_link_url', __( 'Link', 'faruk-arteterapia' ) )
					->set_default_value( $d['card2_link_url'] ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'dual-entry-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Conceito Iceberg (consciente / inconsciente) -------------- */
	$ib = faruk_iceberg_defaults();

	Block::make( __( 'Conceito Iceberg', 'faruk-arteterapia' ) )
		->set_description( __( 'Texto explicativo ao lado da ilustração do iceberg (consciente e inconsciente).', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'admin-site-alt3' )
		->add_fields(
			array(
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $ib['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $ib['heading'] ),
				Field::make( 'textarea', 'paragraph_1', __( 'Parágrafo 1', 'faruk-arteterapia' ) )
					->set_rows( 4 )
					->set_default_value( $ib['paragraph_1'] ),
				Field::make( 'textarea', 'paragraph_2', __( 'Parágrafo 2', 'faruk-arteterapia' ) )
					->set_rows( 4 )
					->set_default_value( $ib['paragraph_2'] ),
				Field::make( 'select', 'iceberg_side', __( 'Posição do iceberg (no desktop)', 'faruk-arteterapia' ) )
					->set_options(
						array(
							'right' => __( 'À direita do texto', 'faruk-arteterapia' ),
							'left'  => __( 'À esquerda do texto', 'faruk-arteterapia' ),
						)
					),
				Field::make( 'select', 'background', __( 'Fundo da seção', 'faruk-arteterapia' ) )
					->set_options(
						array(
							'default' => __( 'Padrão', 'faruk-arteterapia' ),
							'sand'    => __( 'Areia (creme mais escuro)', 'faruk-arteterapia' ),
						)
					),
				Field::make( 'text', 'label_conscious', __( 'Rótulo da ponta (visível)', 'faruk-arteterapia' ) )
					->set_default_value( $ib['label_conscious'] ),
				Field::make( 'text', 'label_unconscious', __( 'Rótulo da parte submersa', 'faruk-arteterapia' ) )
					->set_default_value( $ib['label_unconscious'] ),
				Field::make( 'image', 'custom_image', __( 'Imagem ou SVG próprio (opcional)', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Se preenchida, substitui a ilustração padrão do iceberg.', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'iceberg-concept-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Trajetória e Valores (tela Sobre) ------------------------- */
	$tv = faruk_trajectory_defaults();

	Block::make( __( 'Trajetória e Valores', 'faruk-arteterapia' ) )
		->set_description( __( 'Trajetória, formação e quatro valores do trabalho (tela Sobre).', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'awards' )
		->add_fields(
			array(
				Field::make( 'separator', 'sep_traj', __( 'Trajetória', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'traj_eyebrow', __( 'Rótulo', 'faruk-arteterapia' ) )
					->set_default_value( $tv['traj_eyebrow'] ),
				Field::make( 'text', 'traj_heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $tv['traj_heading'] ),
				Field::make( 'textarea', 'traj_text', __( 'Texto', 'faruk-arteterapia' ) )
					->set_rows( 10 )
					->set_help_text( __( 'Separe os parágrafos com uma linha em branco.', 'faruk-arteterapia' ) )
					->set_default_value( $tv['traj_text'] ),

				Field::make( 'separator', 'sep_form', __( 'Formação e experiência', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'form_eyebrow', __( 'Rótulo', 'faruk-arteterapia' ) )
					->set_default_value( $tv['form_eyebrow'] ),
				Field::make( 'text', 'form_heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $tv['form_heading'] ),
				Field::make( 'complex', 'form_items', __( 'Itens da lista', 'faruk-arteterapia' ) )
					->set_max( 12 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Itens', 'faruk-arteterapia' ),
							'singular_name' => __( 'Item', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'text', 'text', __( 'Texto', 'faruk-arteterapia' ) )
								->set_required( true ),
						)
					)
					->set_help_text( __( 'Vazio = usa a lista provisória do protótipo.', 'faruk-arteterapia' ) ),

				Field::make( 'separator', 'sep_val', __( 'Valores', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'val_eyebrow', __( 'Rótulo', 'faruk-arteterapia' ) )
					->set_default_value( $tv['val_eyebrow'] ),
				Field::make( 'text', 'val_heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $tv['val_heading'] ),
				Field::make( 'complex', 'val_items', __( 'Cards de valores', 'faruk-arteterapia' ) )
					->set_max( 6 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Valores', 'faruk-arteterapia' ),
							'singular_name' => __( 'Valor', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'select', 'icon', __( 'Ícone', 'faruk-arteterapia' ) )
								->set_options( faruk_decorative_icon_options() ),
							Field::make( 'text', 'title', __( 'Título', 'faruk-arteterapia' ) )
								->set_required( true ),
							Field::make( 'textarea', 'description', __( 'Descrição', 'faruk-arteterapia' ) )
								->set_rows( 3 ),
						)
					)
					->set_help_text( __( 'Vazio = usa os 4 valores provisórios do protótipo.', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'trajectory-values-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Como Funciona (passos 01-04) ------------------------------ */
	$hw = faruk_how_it_works_defaults();

	Block::make( __( 'Como Funciona', 'faruk-arteterapia' ) )
		->set_description( __( 'Etapas numeradas do processo (01, 02, 03...).', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'editor-ol' )
		->add_fields(
			array(
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $hw['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $hw['heading'] ),
				Field::make( 'complex', 'steps', __( 'Etapas', 'faruk-arteterapia' ) )
					->set_max( 8 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Etapas', 'faruk-arteterapia' ),
							'singular_name' => __( 'Etapa', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'text', 'title', __( 'Título', 'faruk-arteterapia' ) )
								->set_required( true ),
							Field::make( 'textarea', 'description', __( 'Descrição', 'faruk-arteterapia' ) )
								->set_rows( 3 ),
						)
					)
					->set_help_text( __( 'A numeração (01, 02...) é automática. Vazio = usa as 4 etapas provisórias do protótipo.', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'how-it-works-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Linguagens e Materiais ------------------------------------ */
	$ml = faruk_materials_defaults();

	Block::make( __( 'Linguagens e Materiais', 'faruk-arteterapia' ) )
		->set_description( __( 'Faixa escura com os recursos artísticos utilizados.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'art' )
		->add_fields(
			array(
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $ml['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $ml['heading'] ),
				Field::make( 'textarea', 'intro', __( 'Texto de introdução', 'faruk-arteterapia' ) )
					->set_rows( 3 )
					->set_default_value( $ml['intro'] ),
				Field::make( 'complex', 'items', __( 'Linguagens e materiais', 'faruk-arteterapia' ) )
					->set_max( 14 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Itens', 'faruk-arteterapia' ),
							'singular_name' => __( 'Item', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'select', 'icon', __( 'Ícone', 'faruk-arteterapia' ) )
								->set_options( faruk_decorative_icon_options() ),
							Field::make( 'text', 'name', __( 'Nome', 'faruk-arteterapia' ) )
								->set_required( true ),
						)
					)
					->set_help_text( __( 'Vazio = usa os 7 itens provisórios do protótipo.', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'materials-languages-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Escopo e Limites (para quem é / limites) ------------------- */
	$sc = faruk_scope_defaults();

	Block::make( __( 'Escopo e Limites', 'faruk-arteterapia' ) )
		->set_description( __( 'Dois cards: para quem é o atendimento e seus limites.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'yes-alt' )
		->add_fields(
			array(
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $sc['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $sc['heading'] ),

				Field::make( 'separator', 'sep_card1', __( 'Card 1 (ícones em verde)', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'card1_title', __( 'Título do card', 'faruk-arteterapia' ) )
					->set_default_value( $sc['card1_title'] ),
				Field::make( 'complex', 'card1_items', __( 'Itens do card 1', 'faruk-arteterapia' ) )
					->set_max( 8 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Itens', 'faruk-arteterapia' ),
							'singular_name' => __( 'Item', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'select', 'icon', __( 'Ícone', 'faruk-arteterapia' ) )
								->set_options( faruk_scope_icon_options() ),
							Field::make( 'textarea', 'text', __( 'Texto', 'faruk-arteterapia' ) )
								->set_rows( 3 )
								->set_required( true ),
						)
					)
					->set_help_text( __( 'Vazio = usa os itens provisórios do protótipo.', 'faruk-arteterapia' ) ),

				Field::make( 'separator', 'sep_card2', __( 'Card 2 (ressalvas e limites)', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'card2_title', __( 'Título do card', 'faruk-arteterapia' ) )
					->set_default_value( $sc['card2_title'] ),
				Field::make( 'complex', 'card2_items', __( 'Itens do card 2', 'faruk-arteterapia' ) )
					->set_max( 8 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Itens', 'faruk-arteterapia' ),
							'singular_name' => __( 'Item', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'select', 'icon', __( 'Ícone', 'faruk-arteterapia' ) )
								->set_options( faruk_scope_icon_options() ),
							Field::make( 'textarea', 'text', __( 'Texto', 'faruk-arteterapia' ) )
								->set_rows( 3 )
								->set_required( true ),
						)
					)
					->set_help_text( __( 'Vazio = usa o item provisório do protótipo.', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'scope-limits-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- FAQ (accordion reutilizável) ------------------------------ */
	$fq = faruk_faq_defaults();

	Block::make( __( 'FAQ Perguntas Frequentes', 'faruk-arteterapia' ) )
		->set_description( __( 'Perguntas e respostas em sanfona. Pode ser inserido em qualquer página, quantas vezes for preciso.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'editor-help' )
		->add_fields(
			array(
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $fq['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $fq['heading'] ),
				Field::make( 'select', 'background', __( 'Fundo da seção', 'faruk-arteterapia' ) )
					->set_options(
						array(
							'sand'    => __( 'Areia (creme mais escuro)', 'faruk-arteterapia' ),
							'default' => __( 'Padrão', 'faruk-arteterapia' ),
						)
					),
				Field::make( 'complex', 'items', __( 'Perguntas', 'faruk-arteterapia' ) )
					->set_layout( 'tabbed-vertical' )
					->set_max( 30 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Perguntas', 'faruk-arteterapia' ),
							'singular_name' => __( 'Pergunta', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'text', 'question', __( 'Pergunta', 'faruk-arteterapia' ) )
								->set_required( true ),
							Field::make( 'textarea', 'answer', __( 'Resposta', 'faruk-arteterapia' ) )
								->set_rows( 4 ),
						)
					)
					->set_help_text( __( 'Adicione quantas perguntas quiser. Vazio = usa as 5 perguntas provisórias do protótipo.', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'faq-accordion-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Depoimentos ------------------------------------------------ */
	$ts = faruk_testimonials_defaults();

	Block::make( __( 'Depoimentos', 'faruk-arteterapia' ) )
		->set_description( __( 'Relatos de pessoas atendidas. Até 3 relatos: grade no desktop. Mais de 3: carrossel. No celular é sempre carrossel.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'format-quote' )
		->add_fields(
			array(
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $ts['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $ts['heading'] ),
				Field::make( 'complex', 'items', __( 'Depoimentos', 'faruk-arteterapia' ) )
					->set_layout( 'tabbed-vertical' )
					->set_max( 20 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Depoimentos', 'faruk-arteterapia' ),
							'singular_name' => __( 'Depoimento', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'textarea', 'text', __( 'Depoimento', 'faruk-arteterapia' ) )
								->set_rows( 4 )
								->set_required( true ),
							Field::make( 'text', 'author', __( 'Nome ou iniciais', 'faruk-arteterapia' ) )
								->set_required( true ),
							Field::make( 'text', 'role', __( 'Modalidade', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Exemplo: Atendimento individual, Grupo de vivência.', 'faruk-arteterapia' ) ),
						)
					)
					->set_help_text( __( 'Vazio = usa os 3 depoimentos provisórios do protótipo. Use só relatos reais e autorizados.', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'testimonials-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Canais de Contato ----------------------------------------- */
	$ct = faruk_contact_defaults();

	Block::make( __( 'Canais de Contato', 'faruk-arteterapia' ) )
		->set_description( __( 'Cards de WhatsApp e Instagram, cards Online e Presencial e chamada para outros formatos (tela Contato).', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'phone' )
		->add_fields(
			array(
				Field::make( 'separator', 'sep_wa', __( 'Card do WhatsApp', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'wa_label', __( 'Subtítulo', 'faruk-arteterapia' ) )->set_default_value( $ct['wa_label'] ),
				Field::make( 'textarea', 'wa_text', __( 'Texto', 'faruk-arteterapia' ) )->set_rows( 3 )->set_default_value( $ct['wa_text'] ),
				Field::make( 'text', 'wa_cta', __( 'Texto do link', 'faruk-arteterapia' ) )->set_default_value( $ct['wa_cta'] ),

				Field::make( 'separator', 'sep_ig', __( 'Card do Instagram', 'faruk-arteterapia' ) ),
				Field::make( 'checkbox', 'hide_instagram', __( 'Ocultar o card do Instagram', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'ig_handle', __( 'Perfil (@)', 'faruk-arteterapia' ) )->set_default_value( $ct['ig_handle'] ),
				Field::make( 'textarea', 'ig_text', __( 'Texto', 'faruk-arteterapia' ) )->set_rows( 3 )->set_default_value( $ct['ig_text'] ),
				Field::make( 'text', 'ig_cta', __( 'Texto do link', 'faruk-arteterapia' ) )->set_default_value( $ct['ig_cta'] ),

				Field::make( 'separator', 'sep_att', __( 'Online e presencial', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'att_eyebrow', __( 'Rótulo', 'faruk-arteterapia' ) )->set_default_value( $ct['att_eyebrow'] ),
				Field::make( 'text', 'att_heading', __( 'Título', 'faruk-arteterapia' ) )->set_default_value( $ct['att_heading'] ),
				Field::make( 'text', 'online_title', __( 'Online — título', 'faruk-arteterapia' ) )->set_default_value( $ct['online_title'] ),
				Field::make( 'textarea', 'online_text', __( 'Online — texto', 'faruk-arteterapia' ) )->set_rows( 3 )->set_default_value( $ct['online_text'] ),
				Field::make( 'text', 'online_note', __( 'Online — observação', 'faruk-arteterapia' ) )->set_default_value( $ct['online_note'] ),
				Field::make( 'text', 'presencial_title', __( 'Presencial — título', 'faruk-arteterapia' ) )->set_default_value( $ct['presencial_title'] ),
				Field::make( 'textarea', 'presencial_text', __( 'Presencial — texto', 'faruk-arteterapia' ) )->set_rows( 3 )->set_default_value( $ct['presencial_text'] ),
				Field::make( 'text', 'presencial_address', __( 'Presencial — endereço', 'faruk-arteterapia' ) )->set_default_value( $ct['presencial_address'] ),

				Field::make( 'separator', 'sep_others', __( 'Outros formatos', 'faruk-arteterapia' ) ),
				Field::make( 'checkbox', 'hide_others', __( 'Ocultar esta chamada', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'others_eyebrow', __( 'Rótulo', 'faruk-arteterapia' ) )->set_default_value( $ct['others_eyebrow'] ),
				Field::make( 'text', 'others_heading', __( 'Título', 'faruk-arteterapia' ) )->set_default_value( $ct['others_heading'] ),
				Field::make( 'textarea', 'others_text', __( 'Texto', 'faruk-arteterapia' ) )->set_rows( 3 )->set_default_value( $ct['others_text'] ),
				Field::make( 'text', 'others_button', __( 'Texto do botão', 'faruk-arteterapia' ) )->set_default_value( $ct['others_button'] ),
				Field::make( 'text', 'others_url', __( 'Link do botão', 'faruk-arteterapia' ) )->set_default_value( $ct['others_url'] ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'contact-channels-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Chamada Final (WhatsApp) ---------------------------------- */
	$fc = faruk_final_cta_defaults();

	Block::make( __( 'Chamada Final WhatsApp', 'faruk-arteterapia' ) )
		->set_description( __( 'Faixa de fechamento com botão do WhatsApp ("Pronto para começar?").', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'megaphone' )
		->add_fields(
			array(
				Field::make( 'text', 'title', __( 'Título', 'faruk-arteterapia' ) )->set_default_value( $fc['title'] ),
				Field::make( 'textarea', 'subtitle', __( 'Subtítulo', 'faruk-arteterapia' ) )->set_rows( 2 )->set_default_value( $fc['subtitle'] ),
				Field::make( 'text', 'button', __( 'Texto do botão', 'faruk-arteterapia' ) )->set_default_value( $fc['button'] ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'final-cta-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Grupos e Vivências (cards de projetos) -------------------- */
	$gp = faruk_groups_defaults();

	Block::make( __( 'Grupos e Vivências', 'faruk-arteterapia' ) )
		->set_description( __( 'Cards dos grupos, projetos e vivências, com status de inscrição, botão e aviso de lista de espera.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'groups' )
		->add_fields(
			array(
				Field::make( 'text', 'section_id', __( 'Âncora da seção', 'faruk-arteterapia' ) )
					->set_default_value( 'grupos' )
					->set_help_text( __( 'Usada pelo bloco "Navegação por Âncoras" (sem o #).', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $gp['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $gp['heading'] ),
				Field::make( 'textarea', 'intro', __( 'Texto de introdução', 'faruk-arteterapia' ) )
					->set_rows( 3 )
					->set_default_value( $gp['intro'] ),

				Field::make( 'complex', 'items', __( 'Grupos, projetos e vivências', 'faruk-arteterapia' ) )
					->set_layout( 'tabbed-vertical' )
					->set_max( 24 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Projetos', 'faruk-arteterapia' ),
							'singular_name' => __( 'Projeto', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'text', 'title', __( 'Título do grupo/projeto', 'faruk-arteterapia' ) )
								->set_required( true ),
							Field::make( 'text', 'tag', __( 'Rótulo/categoria', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Exemplo: Grupo, Vivência, Estudos.', 'faruk-arteterapia' ) ),
							Field::make( 'select', 'status', __( 'Status', 'faruk-arteterapia' ) )
								->set_options(
									array(
										'open'   => __( 'Inscrições abertas', 'faruk-arteterapia' ),
										'closed' => __( 'Inscrições encerradas', 'faruk-arteterapia' ),
									)
								),
							Field::make( 'text', 'format', __( 'Formato', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Exemplo: Online, Presencial.', 'faruk-arteterapia' ) ),
							Field::make( 'text', 'dates', __( 'Datas / periodicidade', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Opcional. Exemplo: Quartas, 19h — início em março.', 'faruk-arteterapia' ) ),
							Field::make( 'textarea', 'description', __( 'Breve descrição', 'faruk-arteterapia' ) )
								->set_rows( 4 ),
							Field::make( 'image', 'image', __( 'Imagem de capa', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Formato 4:3. Sem imagem, o card usa um degradê com as cores da marca.', 'faruk-arteterapia' ) ),
							Field::make( 'text', 'cta_label', __( 'Texto do botão (inscrições abertas)', 'faruk-arteterapia' ) )
								->set_default_value( 'Quero me inscrever' ),
							Field::make( 'text', 'cta_url', __( 'Link do botão (formulário de inscrição)', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Link do formulário (com UTM). Só é usado com inscrições abertas.', 'faruk-arteterapia' ) ),
							Field::make( 'text', 'anchor', __( 'Âncora do card (opcional)', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Permite linkar direto para este card, ex.: /grupos/#ciclo-princesas. Vazio = gerada pelo título.', 'faruk-arteterapia' ) ),
						)
					)
					->set_help_text( __( 'Vazio = usa os 4 projetos provisórios do protótipo.', 'faruk-arteterapia' ) ),

				Field::make( 'separator', 'sep_notes', __( 'Avisos', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'closed_note', __( 'Nota abaixo do botão desativado', 'faruk-arteterapia' ) )
					->set_default_value( $gp['closed_note'] ),
				Field::make( 'checkbox', 'hide_disclaimer', __( 'Ocultar o aviso final (turmas e lista de espera)', 'faruk-arteterapia' ) ),
				Field::make( 'textarea', 'disclaimer', __( 'Aviso final', 'faruk-arteterapia' ) )
					->set_rows( 3 )
					->set_default_value( $gp['disclaimer'] ),
				Field::make( 'text', 'disclaimer_cta', __( 'Texto do link do WhatsApp no aviso', 'faruk-arteterapia' ) )
					->set_default_value( $gp['disclaimer_cta'] ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'groups-projects-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Navegação por Âncoras ("Nesta página:") ------------------- */
	$an = faruk_anchor_nav_defaults();

	Block::make( __( 'Navegação por Âncoras', 'faruk-arteterapia' ) )
		->set_description( __( 'Faixa "Nesta página:" com links de rolagem suave para as seções.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'admin-links' )
		->add_fields(
			array(
				Field::make( 'text', 'label', __( 'Rótulo', 'faruk-arteterapia' ) )
					->set_default_value( $an['label'] ),
				Field::make( 'complex', 'items', __( 'Links', 'faruk-arteterapia' ) )
					->set_max( 6 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Links', 'faruk-arteterapia' ),
							'singular_name' => __( 'Link', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'select', 'icon', __( 'Ícone', 'faruk-arteterapia' ) )
								->set_options( faruk_groups_page_icon_options() ),
							Field::make( 'text', 'label', __( 'Texto', 'faruk-arteterapia' ) )
								->set_required( true ),
							Field::make( 'text', 'target', __( 'Âncora de destino', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Sem o #. Precisa ser igual ao campo "Âncora da seção" do bloco de destino. Exemplo: grupos', 'faruk-arteterapia' ) )
								->set_required( true ),
						)
					)
					->set_help_text( __( 'Vazio = usa os 3 links padrão (grupos, escolas e empresas, supervisão).', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'anchor-nav-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Escolas e Empresas ---------------------------------------- */
	$sch = faruk_schools_companies_defaults();

	Block::make( __( 'Escolas e Empresas', 'faruk-arteterapia' ) )
		->set_description( __( 'Cards de apresentação para escolas e empresas, cada um com botão para o formulário.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'building' )
		->add_fields(
			array(
				Field::make( 'text', 'section_id', __( 'Âncora da seção', 'faruk-arteterapia' ) )
					->set_default_value( $sch['section_id'] )
					->set_help_text( __( 'Usada pelo bloco "Navegação por Âncoras" (sem o #).', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $sch['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $sch['heading'] ),
				Field::make( 'textarea', 'intro', __( 'Texto de introdução', 'faruk-arteterapia' ) )
					->set_rows( 3 )
					->set_default_value( $sch['intro'] ),
				Field::make( 'complex', 'items', __( 'Cards', 'faruk-arteterapia' ) )
					->set_layout( 'tabbed-vertical' )
					->set_max( 4 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Cards', 'faruk-arteterapia' ),
							'singular_name' => __( 'Card', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'select', 'icon', __( 'Ícone', 'faruk-arteterapia' ) )
								->set_options( faruk_groups_page_icon_options() ),
							Field::make( 'text', 'title', __( 'Título', 'faruk-arteterapia' ) )
								->set_required( true ),
							Field::make( 'textarea', 'description', __( 'Descrição', 'faruk-arteterapia' ) )
								->set_rows( 4 ),
							Field::make( 'textarea', 'topics', __( 'Tópicos (um por linha)', 'faruk-arteterapia' ) )
								->set_rows( 4 ),
							Field::make( 'text', 'button', __( 'Texto do botão', 'faruk-arteterapia' ) )
								->set_default_value( 'Quero saber mais' ),
							Field::make( 'text', 'url', __( 'Link do formulário', 'faruk-arteterapia' ) )
								->set_help_text( __( 'Link externo (Google Forms com UTM).', 'faruk-arteterapia' ) ),
						)
					)
					->set_help_text( __( 'Vazio = usa os cards provisórios de Escolas e Empresas.', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'schools-companies-block', $fields, $attributes, $inner_blocks );
			}
		);

	/* ---- Supervisão para Arteterapeutas ---------------------------- */
	$sp = faruk_supervision_defaults();

	Block::make( __( 'Supervisão para Arteterapeutas', 'faruk-arteterapia' ) )
		->set_description( __( 'Imagem de um lado e texto do outro, com as modalidades (grupo e individual) e botão.', 'faruk-arteterapia' ) )
		->set_category( $category_slug, $category_title, 'heart' )
		->set_icon( 'welcome-learn-more' )
		->add_fields(
			array(
				Field::make( 'text', 'section_id', __( 'Âncora da seção', 'faruk-arteterapia' ) )
					->set_default_value( $sp['section_id'] )
					->set_help_text( __( 'Usada pelo bloco "Navegação por Âncoras" (sem o #).', 'faruk-arteterapia' ) ),
				Field::make( 'image', 'image', __( 'Imagem', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Formato 4:3. Sem imagem, aparece uma ilustração com as cores da marca.', 'faruk-arteterapia' ) ),
				Field::make( 'select', 'image_side', __( 'Posição da imagem (desktop)', 'faruk-arteterapia' ) )
					->set_options(
						array(
							'left'  => __( 'À esquerda', 'faruk-arteterapia' ),
							'right' => __( 'À direita', 'faruk-arteterapia' ),
						)
					),
				Field::make( 'text', 'eyebrow', __( 'Rótulo da seção', 'faruk-arteterapia' ) )
					->set_default_value( $sp['eyebrow'] ),
				Field::make( 'text', 'heading', __( 'Título', 'faruk-arteterapia' ) )
					->set_default_value( $sp['heading'] ),
				Field::make( 'textarea', 'text', __( 'Texto', 'faruk-arteterapia' ) )
					->set_rows( 4 )
					->set_default_value( $sp['text'] ),
				Field::make( 'complex', 'items', __( 'Modalidades', 'faruk-arteterapia' ) )
					->set_max( 4 )
					->setup_labels(
						array(
							'plural_name'   => __( 'Modalidades', 'faruk-arteterapia' ),
							'singular_name' => __( 'Modalidade', 'faruk-arteterapia' ),
						)
					)
					->add_fields(
						array(
							Field::make( 'select', 'icon', __( 'Ícone', 'faruk-arteterapia' ) )
								->set_options( faruk_groups_page_icon_options() ),
							Field::make( 'text', 'text', __( 'Texto', 'faruk-arteterapia' ) )
								->set_required( true ),
						)
					)
					->set_help_text( __( 'Vazio = usa as modalidades Grupo e Individual.', 'faruk-arteterapia' ) ),
				Field::make( 'text', 'button', __( 'Texto do botão', 'faruk-arteterapia' ) )
					->set_default_value( $sp['button'] ),
				Field::make( 'text', 'url', __( 'Link do formulário', 'faruk-arteterapia' ) )
					->set_help_text( __( 'Link externo (Google Forms com UTM).', 'faruk-arteterapia' ) ),
			)
		)
		->set_render_callback(
			function ( $fields, $attributes, $inner_blocks ) {
				faruk_render_block( 'supervision-block', $fields, $attributes, $inner_blocks );
			}
		);
}
add_action( 'carbon_fields_register_fields', 'faruk_register_blocks' );
