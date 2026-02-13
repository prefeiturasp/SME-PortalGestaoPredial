<?php
// Contador de visualizações de noticias
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count.'';
}
// conta as visitas.
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// Adiciona uma coluna no Admin
add_filter('manage_pages_columns', 'posts_column_views');
add_action('manage_pages_custom_column', 'posts_custom_column_views',10,2);
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',10,2);
function posts_column_views($defaults){
$defaults['post_views'] = __('<span class="dashicons dashicons-visibility"></span>');
return $defaults;
}
function posts_custom_column_views($column_name, $id){
if($column_name === 'post_views'){
echo '<h3><strong>'.getPostViews(get_the_ID()).'</strong></h3>';
}
}


// Funcao para ativar ordenacao
function ws_sortable_manufacturer_column( $columns )    {
    $columns['post_views'] =  'post_views';
    return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'ws_sortable_manufacturer_column' ); // Noticias
add_filter( 'manage_edit-card_sortable_columns', 'ws_sortable_manufacturer_column' ); // Cards
add_filter( 'manage_edit-agenda_sortable_columns', 'ws_sortable_manufacturer_column' ); // Agenda Secretario
add_filter( 'manage_edit-contato_sortable_columns', 'ws_sortable_manufacturer_column' ); // Contatos SME
add_filter( 'manage_edit-organograma_sortable_columns', 'ws_sortable_manufacturer_column' ); // Organograma
add_filter( 'manage_edit-aba_sortable_columns', 'ws_sortable_manufacturer_column' ); // Cadastro Aba
add_filter( 'manage_edit-botao_sortable_columns', 'ws_sortable_manufacturer_column' ); // Cadastro Botoes
add_filter( 'manage_edit-curriculo-da-cidade_sortable_columns', 'ws_sortable_manufacturer_column' ); // Curriculo da cidade
add_filter( 'manage_edit-programa-projeto_sortable_columns', 'ws_sortable_manufacturer_column' ); // Programas Projetos
add_filter( 'manage_edit-page_sortable_columns', 'ws_sortable_manufacturer_column' ); // Paginas

// Funcao para ordernar
function ws_orderby_custom_column( $query ) {
    global $pagenow;

    if ( ! is_admin() || 'edit.php' != $pagenow || ! $query->is_main_query()  )  {
        return;
    }

    $orderby = $query->get( 'orderby' );

    print_r($orderby);

    switch ( $orderby ) {
        case 'post_views':
            $query->set( 'meta_key', 'post_views_count' );
            $query->set( 'orderby', 'meta_value_num' );
            break;

        default:
            break;
    }

}
add_action( 'pre_get_posts', 'ws_orderby_custom_column' );