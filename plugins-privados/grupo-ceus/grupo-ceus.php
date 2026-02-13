<?php
/*
Plugin Name: Grupos Ceus
Description:  Plugin Criado para controlar os editores dos CEUs
Author: Felipe Viana
Version: 1.0.0
Author URI: https://amcom.com.br/
*/


// Excluir Categorias
add_action('init', 'wporg_custom_post_type');

// Controle de paginas permitidas
function wpse_user_can_edit( $user_id, $page_id ) {

    $eventos = array();

    global $pagenow;

    if($pagenow == 'post-new.php'){
        return true;
    }

    // Id da pagina corrente da lista
    $page = get_post( $page_id );
 	
    // pega as informacoes do usuario logado
    $user = wp_get_current_user($user_id);

    // usuarios que ficam foram da regra
	$allowed_roles = array( 'administrator' );
    
    if ( array_intersect( $allowed_roles, $user->roles ) ) {
        // se estiverem na lista todas as paginas sao permitidas para edicao
        return true;
    }

    //return true;

 
    // pega o grupo que o usuario pertence
    $variable = get_field('grupo', 'user_' . $user_id);    

    // verifica se esta liberado para editar todas paginas
	$todos = get_field('todas_as_paginas', $variable);

	if($todos && $todos != ''){
		return true;
	} else {	
        
        $permitidas = array();

        if($variable && $variable != ''){
            foreach($variable as $permitido){
                // Verifica se esta ativo a selecao manual de unidades
                $select_manu = get_post_meta($permitido, 'select_manu', true); // Selecao manual
                if($select_manu){                    
                    $permitidas[] = get_field('unidades', $permitido);
                }
                
            }
        }

        $unidades = array_flatten($permitidas);
        $unidades = array_unique($unidades);
        

        // se a pagina corrente esta na lista de paginas do grupo libera para edicao
        if( in_array($page_id, $unidades)  ){
			return true;
		} else {
			//return false;
            return true;
		}

        
	} 
	
}


//
add_filter( 'map_meta_cap', function ( $caps, $cap, $user_id, $args ) {

    global $pagenow;

    if($pagenow == 'post-new.php'){
        return $caps;
    }

    if (( $pagenow == 'post-new.php' ) || ($pagenow == 'post.php')) {       
        // capability removida
        $to_filter = [ 'delete_post', 'edit_page', 'delete_page', 'edit_concurso', 'edit_unidade' ];
        
    } else {
        // capability removida
        $to_filter = [ 'edit_post','delete_post', 'edit_page', 'delete_page', 'edit_concurso', 'edit_unidade' ];
       
    }

    $user_meta = get_userdata($user_id); // Pega as informacoes do usuario
    $user_roles = $user_meta->roles; // Pega o tipo do usuario    

    if($user_roles != ''){
        if ( in_array( 'editor', $user_roles, true ) ) {
            if($pagenow == 'upload.php' || 'admin-ajax.php' == $pagenow || $_REQUEST['action'] == 'query-attachments'){
                $to_filter = [ 'edit_page', 'delete_page', 'edit_concurso', 'edit_unidade' ];
            }
        }
    }
    
    //echo "<pre>";
    //print_r($cap);
    //echo "</pre>";

    // If the capability being filtered isn't of our interest, just return current value
    if ( ! in_array( $cap, $to_filter, true ) ) {
        return $caps;
    }

    //print_r($args);

    // First item in $args array should be page ID
    if ( ! $args || empty( $args[0] ) || ! wpse_user_can_edit( $user_id, $args[0] ) ) {
        // User is not allowed, let's tell that to WP
        return [ 'do_not_allow' ];
    }     
    
    $variable = get_field('grupo', 'user_' . $user_id);   
    // se não for de nenhum grupo
    if($variable == '' && !current_user_can( 'manage_options' )) {
        return [ 'do_not_allow' ];
    }
    // Otherwise just return current value
    return $caps;

}, 10, 4 );


// Post Type Grupos
function wporg_custom_post_type() {
    register_post_type('wporg_unidades',
        array(
            'labels'      => array(
                'name'          => __( 'Macrorregiões', 'textdomain' ),
                'singular_name' => __( 'Macrorregião', 'textdomain' ),
            ),            
            'has_archive' => true,
			'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
            'publicly_queryable' => true,  // you should be able to query it
            'show_ui' => true,  // you should be able to edit it in wp-admin
            'exclude_from_search' => true,  // you should exclude it from search results
            'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
            'has_archive' => false,  // it shouldn't have archive page
            'rewrite' => false,  // it shouldn't have rewrite rules
            'menu_position' => 12,
            'menu_icon'   => 'dashicons-location-alt',
			'capabilities' => array(
				'edit_post'          => 'remove_users',
				'read_post'          => 'remove_users',
				'delete_post'        => 'remove_users',
				'edit_posts'         => 'remove_users',
				'edit_others_posts'  => 'remove_users',
				'delete_posts'       => 'remove_users',
				'publish_posts'      => 'remove_users',
				'read_private_posts' => 'remove_users'
			),
        )
    );
}

add_action('init', 'wporg_custom_post_type');