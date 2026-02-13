<?php

namespace Classes\Usuarios;


class CamposAdicionais
{

	public function __construct()
	{
	
		add_filter('manage_users_columns', array($this, 'exibe_cols'));
		add_filter('manage_users_custom_column', array($this, 'cols_content'), 10, 3);
		add_filter('manage_users_sortable_columns', array($this, 'cols_sort'));
		//add_filter('request', array($this, 'orderby'));

		//add_action('restrict_manage_users', array($this, 'my_restrict_manage_posts'));

		//$this->exibeTodosUsuarios();

		add_action( 'restrict_manage_users', array($this,'add_grupo_filter' ));

		add_filter( 'pre_get_users', array($this,'filter_users_by_grupo' ));


	}

	public function exibeTodosUsuarios(){
		global $wpdb;
		$wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");
		$todos_usuarios_id = [];

		foreach ( $wp_user_search as $userid ) {
			$user_id       = (int) $userid->ID;
			$user_login    = stripslashes($userid->user_login);
			$display_name  = stripslashes($userid->display_name);
			$todos_usuarios_id[] = $user_id;
		}
		return $todos_usuarios_id;

	}

	public function getUsersgrupoUnique($users){

		$grupo_unico = [];
		foreach ($users as $user_id){
			$grupo_unico[] = get_user_meta($user_id, 'grupo', true);
		}

		return array_unique($grupo_unico, SORT_REGULAR);


	}

	function add_grupo_filter() {

		if ( isset( $_GET[ 'grupo' ]) && $_GET[ 'grupo' ][0] !== '0') {
			$section = $_GET[ 'grupo' ];
			$section = !empty( $section[ 0 ] ) ? $section[ 0 ] : $section[ 1 ];
		} else {
			$section = -1;
		}

		$users = $this->exibeTodosUsuarios();

		$grupos_unicos = $this->getUsersgrupoUnique($users);

		echo ' <select name="grupo[]" style="float:none;">';
		echo'<option value="0" selected="selected">Todos os grupos</option>';

		foreach ($grupos_unicos as $grupo){

			$selected = $grupo == $section ? ' selected="selected"' : '';

			if ($grupo) {
				echo '<option value="' . $grupo . '"' . $selected . '>' . $grupo . '</option>';
			}
		}
		echo '</select>';
		echo '<input type="submit" class="button" value="Filtrar">';
	}


	function filter_users_by_grupo( $query ) {
		global $pagenow;
    
		if ( is_admin() && 
			'users.php' == $pagenow && 
			isset( $_GET[ 'grupo_id' ] ) && 
			!empty( $_GET[ 'grupo_id' ] ) 
		) {
			$section = $_GET[ 'grupo_id' ];
			$meta_query = array(
				array(
					'key' => 'grupo', // name of custom field
					'value' => '"' . $section . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
					'compare' => 'LIKE'
				)
			);
			
			$query->set( 'meta_key', 'grupo' );
			$query->set( 'meta_query', $meta_query );
			
		}
	}


	// Funções necessária para exibir o filtro de categorias nos produtos no Dashboard
	public function my_restrict_manage_posts(){

		global $typenow;
		$taxonomy = $this->taxonomy; // taxonomia personalizada = categorias
		//if ($typenow == $this->cptSlug) { // custom post type = link
		$filters = array($taxonomy);

		foreach ($filters as $tax_slug) {
			//$tax_obj = get_taxonomy($tax_slug);
			//$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Ver todas as categorias</option>";
			foreach ($terms as $term) {
				echo '<option value=' . $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '', '>' . $term->name . ' (' . $term->count . ')</option>';
			}
			echo "</select>";
		}
		//}
	}

	//Exibindo as colunas no Dashboard
	public function exibe_cols($cols)
	{
		$cols['grupo'] = 'Grupos';
		return $cols;
	}

	//Exibindo as informações correspondentes de cada coluna
	public function cols_content($val, $column_name, $user_id)
	{
		$user_grupo = get_user_meta($user_id, 'grupo', true);

		switch ($column_name) {
			case 'grupo' :
				
				// pega o grupo que o usuario pertence
				$usergrupos = get_field('grupo', 'user_' . $user_id);
				
				$returngrupos = '';
				
				if($usergrupos && $usergrupos != ''){
					$b = 0;
					foreach($usergrupos as $usergrupo){
						if($b == 0){
							$returngrupos .= "<a href='" . admin_url('users.php?grupo_id=' . $usergrupo) . "'>" . get_the_title($usergrupo) . "</a>";
						} else {
							$returngrupos .= ", <a href='" . admin_url('users.php?grupo_id=' . $usergrupo) . "'>" . get_the_title($usergrupo) . "</a>";
						}
						$b++;				
					}
		
					//print_r($variable);
					return $returngrupos;
				}else{
					return "<p>Sem grupo atribuído</p>";
				}
			default:
		}

	}

}

new CamposAdicionais();