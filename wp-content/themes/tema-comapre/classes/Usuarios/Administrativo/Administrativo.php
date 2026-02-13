<?php


namespace Classes\Usuarios\Administrativo;

class Administrativo
{

	public function __construct()
	{

		$this->addRole();
		$this->getRole();
		$this->removeCap();
		$this->addCap();
		//$this->removeRole();
	}

	public function removeRole(){
		remove_role('administrativo');
	}

	public function addRole(){

		add_role(
			'administrativo',
			__( 'Gestor Administrativo' ),
			array(
				'read'         => true,  // true allows this capability
				'edit_posts'   => true,
				'publish_posts' => false
			)
		);
	}

	public function getRole(){
		// get the the role object
		if (current_user_can('administrativo')) {
			$this->role_object = get_role('administrativo');
		}
	}

	public function removeCap(){

		if (current_user_can('administrativo')) {

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
		if (current_user_can('administrativo')) {
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

}

new Administrativo();