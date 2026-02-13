<?php

namespace Classes\Usuarios\Colaborador;

class Colaborador
{
	const ROLE = 'contributor';
	private $role_object;

	public function __construct()
	{
		$this->getRole();
		$this->removeCap();
		$this->addCap();

		add_action( 'admin_menu', array($this, 'remove_menus' ));
	}

	public function getRole(){
		// get the the role object
		if (current_user_can('contributor')) {
			$this->role_object = get_role('contributor');
		}
	}

	public function removeCap(){

		if (current_user_can('contributor')) {



			$caps = array(
				//'upload_files',
				'edit_files',
				'edit_posts',
				'edit_others_posts',
				'edit_published_posts',
				'edit_private_posts',
				'manage_options',
				'edit_pages',
				'edit_published_pages',
				'edit_others_pages',
				'edit_private_pages',
				'delete_others_pages',
				'delete_private_pages',
				'delete_published_pages',
				'manage_links',
				'delete_pages',
				'delete_posts',
				'delete_published_posts',
				'publish_posts',
				'read_card',
				'edit_cards',
				'delete_cards',
				'delete_card',
				'manage_cards',
				'assign_cards',
			);

			foreach ($caps as $cap){
				$this->role_object->remove_cap($cap);
			}

		}

	}

	public function addCap(){
		// add $cap capability to this role object
		if (current_user_can('contributor')) {
			$this->role_object->add_cap('upload_files');
			//midias para colaborador
			$this->role_object->add_cap(' unfiltered_upload ');
			$this->role_object->add_cap('edit_files');
			$this->role_object->add_cap('edit_posts');
			$this->role_object->add_cap('edit_post');


			$this->role_object->add_cap('edit_others_posts');
			$this->role_object->add_cap('edit_private_posts');
			$this->role_object->add_cap('edit_published_posts');

			//$this->role_object->add_cap('publish_posts');
			$this->role_object->add_cap('read_private_posts');
			$this->role_object->add_cap('delete_posts');

			$this->role_object->add_cap('edit_pages');
			$this->role_object->add_cap('delete_pages');
			
			// Ativar permissoes de edicao/criacao de paginas
			//$this->role_object->add_cap('edit_pages');
			//$this->role_object->add_cap('delete_pages');
			//$this->role_object->add_cap('delete_published_pages');
			//$this->role_object->add_cap('edit_published_pages');
			//$this->role_object->add_cap('read_published_pages');
			//$this->role_object->add_cap('edit_private_pages');

			$this->role_object->add_cap( 'read_card');
			$this->role_object->add_cap( 'read_private_cards');
			$this->role_object->add_cap( 'edit_card' );
			$this->role_object->add_cap( 'edit_cards' );
			$this->role_object->add_cap( 'edit_published_cards' );
			$this->role_object->add_cap( 'delete_card' );
			$this->role_object->add_cap( 'delete_published_cards' );
			$this->role_object->add_cap( 'delete_published_cards' );
			$this->role_object->add_cap( 'edit_cards' );
			$this->role_object->add_cap( 'delete_cards' );
			$this->role_object->add_cap( 'assign_cards' );


			$this->role_object->add_cap( 'read_aba');
			$this->role_object->add_cap( 'edit_abas' );
			$this->role_object->add_cap( 'delete_abas' );
			$this->role_object->add_cap( 'manage_abas' );
			$this->role_object->add_cap( 'assign_abas' );
			$this->role_object->add_cap( 'edit_published_abas' );
			$this->role_object->add_cap( 'delete_published_aba' );
			$this->role_object->add_cap( 'manage_abas' );
			$this->role_object->add_cap( 'edit_abas' );
			$this->role_object->add_cap( 'delete_abas' );
			$this->role_object->add_cap( 'assign_abas' );

			$this->role_object->add_cap( 'edit_unidade');
			$this->role_object->add_cap( 'edit_unidades' );
			$this->role_object->add_cap( 'edit_published_unidades');
			$this->role_object->add_cap( 'read_unidade' );
			$this->role_object->add_cap( 'read_private_unidades');			
			$this->role_object->add_cap( 'edit_others_unidades' );
			$this->role_object->add_cap( 'edit_private_unidades');	

			$this->role_object->add_cap( 'read_botao');
			$this->role_object->add_cap( 'edit_botoes' );
			$this->role_object->add_cap( 'delete_botoes' );
			$this->role_object->add_cap( 'manage_botoes' );
			$this->role_object->add_cap( 'assign_botoes' );
			$this->role_object->add_cap( 'edit_published_botoes' );
			$this->role_object->add_cap( 'delete_published_botao' );


			$this->role_object->add_cap( 'read_imagem');
			$this->role_object->add_cap( 'edit_imagens' );
			$this->role_object->add_cap( 'delete_imagens' );
			$this->role_object->add_cap( 'manage_imagens' );
			$this->role_object->add_cap( 'assign_imagens' );

			$this->role_object->add_cap( 'edit_published_imagens' );
			$this->role_object->add_cap( 'delete_published_imagens' );
			



		}
	}

	/* Escondendo o Botão  Add Gallery do plugin Responsive Lightbox & Gallery */
	public function remove_menus(){

		$usuario = wp_get_current_user();

		if ($usuario->roles[0] === 'contributor') {

			//remove_menu_page('edit.php');
			remove_menu_page('edit.php?post_type=agenda');
			remove_menu_page('edit.php?post_type=contato');
			remove_menu_page('edit.php?post_type=organograma');
			remove_menu_page('edit.php?post_type=curriculo-da-cidade');
			remove_menu_page('edit.php?post_type=page');
			//remove_menu_page('edit.php?post_type=aba');
			//remove_menu_page('edit.php?post_type=botao');

			//remove_menu_page('admin.php');
			remove_menu_page( 'wpcf7' );
			
			global $submenu;			
			unset($submenu['edit.php?post_type=unidade'][10]);
			unset($submenu['edit.php?post_type=destaque'][10]);
			if ( isset($_GET['post_type']) && ($_GET['post_type'] == 'unidade' || $_GET['post_type'] == 'destaque') ) {
				echo '<style type="text/css">
				.wrap .wp-heading-inline+.page-title-action { display:none; }
				</style>';
			}


			remove_menu_page('edit-comments.php');
			remove_menu_page('tools.php');

			remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
			remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
			remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=atividades_categories');
			remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=publico_categories');
			remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=faixa_categories');
			remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=localidade');
		}
	}

}

new Colaborador();