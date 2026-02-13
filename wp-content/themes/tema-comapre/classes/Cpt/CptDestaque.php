<?php

namespace Classes\Cpt;


class CptDestaque extends Cpt
{

	public function __construct()
	{
		$this->cptSlug = self::getCptSlugExtend();
		$this->name = self::getNameExtend();
		$this->todosOsItens = self::getTodosOsItensExtend();
		$this->dashborarIcon = self::getDashborarIconExtendExtend();

		add_action('init', array($this, 'register'));
		add_filter('manage_posts_columns', array($this, 'exibe_cols'), 10, 2);
		add_action( 'manage_posts_custom_column' , array($this, 'cols_content_posts'), 10, 2 );
	}

	// add featured thumbnail to admin post columns
	public function exibe_cols($cols, $post_type) {
		if ($post_type === 'destaque') {
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => 'Unidade',								
				//'date' => 'Data',
				//'modified' => 'Modificado por',
				'lastmodified' => 'Modificado por',
				//'grupo' => 'Grupo',
				'unidade_ceu' => 'Unidade CEU',
			);

			return $columns;
		}else{
			return $cols;
		}

	}

	public function cols_content_posts($column) {

		switch ( $column ) {
			case 'featured_thumb':
				echo '<a href="' . get_edit_post_link() . '">';
				echo the_post_thumbnail( 'admin-list-thumb' );
				echo '</a>';
				break;

			case 'grupo':
				$localizacao = get_the_ID();

				$paginas = get_posts(array(
					'post_type' => 'wporg_unidades',
					'orderby' => 'title',
    				'order'   => 'ASC',
					'post_status'    => 'publish',
					'meta_query' => array(
						array(
							'key' => 'unidades', // name of custom field
							'value' => '' . $localizacao . '', // matches exaclty "123", not just 123. This prevents a match for "1234"
							'compare' => 'LIKE'
						)
					)
				));

				
				if($paginas && $paginas != ''){
					$a = 0;
					foreach($paginas as $pagina){
						if($a == 0){
							echo "<a href='" . admin_url('edit.php?post_type=unidade&filter=grupo&grupo_id=' . $pagina->ID) . "'>" . get_the_title($pagina->ID) . "</a>";
							
						} else {
							echo ", <a href='" . admin_url('edit.php?post_type=unidade&filter=grupo&grupo_id=' . $pagina->ID) . "'>" . get_the_title($pagina->ID) . "</a>";
						}
						
						$a++;
					}
				} else {
					if($_GET['grupo_id'] && $_GET['grupo_id'] != ''){
						echo "<a href='https://educacao.sme.prefeitura.sp.gov.br/wp-admin/edit.php?post_type=unidade&filter=grupo&grupo_id=" . $_GET['grupo_id'] . "'>". get_the_title($_GET['grupo_id']) . "</a>";
					}
				}
				
				break;

			case 'lastmodified':				
				$last_id = get_post_meta( get_the_ID(), '_edit_last', true );							
				echo "<a href='" . get_home_url() . "/wp-admin/user-edit.php?user_id=" . $last_id . "'>" . get_the_modified_author() . "</a>";		
				break;

			case 'unidade_ceu':				
				$unidade = get_field('unidade', get_the_ID()); // Pegar a localizacao atribuida
				if($unidade){
					echo get_the_title($unidade);
				}
				break;

		}
	}


	/**
	 * Alterando as configurações que vem por padrão na classe CPT (Adicionando suporte a thumbnail)
	 */
	public function register()
	{

		$labels = array(
			'name' => _x($this->name, 'post type general name'),
			'singular_name' => _x($this->name, 'post type singular name'),
			'all_items' => _x( $this->todosOsItens, 'Admin Menu todos os itens'),
			'add_new' => _x('Adicionar novo ', 'Novo item'),
			'add_new_item' => __('Novo Item'),
			'edit_item' => __('Editar Item'),
			'new_item' => __('Novo Item'),
			'view_item' => __('Ver Item'),
			'search_items' => __('Procurar Itens'),
			'not_found' => __('Nenhum registro encontrado'),
			'not_found_in_trash' => __('Nenhum registro encontrado na lixeira'),
			'parent_item_colon' => '',
			'menu_name' => $this->name
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'public_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => array('destaque','destaques'),
			'capabilities' => array(
				'edit_post' => 'edit_destaque',
				'edit_posts' => 'edit_destaques',
				'edit_published_posts ' => 'edit_published_destaques',
				'read_post' => 'read_destaque',
				'read_private_posts' => 'read_private_destaques',
				'delete_post' => 'delete_destaque',
				'delete_published_posts' => 'delete_published_destaques',
			),
			'map_meta_cap'        => true,
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => 10,
			'menu_icon'   => 'dashicons-table-row-before',
			'exclude_from_search' => true,
			'show_in_rest' => true,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'supports' => array('title', 'editor', 'excerpt',  'author'),
		);

		register_post_type($this->cptSlug, $args);
		flush_rewrite_rules();
		
	}

}