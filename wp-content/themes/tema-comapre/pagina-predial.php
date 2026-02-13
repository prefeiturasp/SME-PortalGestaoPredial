<?php
/*
 * Template Name: Página Instalação Predial
 * Description: Pagina para a inclusão dos Prédios
 */

 if (isset($_GET['unidadeatb']) && isset($_GET['post_id'])) {
    // Define os dados do novo post

    $post_id = $_GET['post_id'];

    $new_title = get_field('nome_predio', $post_id);

    // Set the post data
    $new_post = array(
        'ID'           => $post_id,
        'post_title'   => $new_title,
    );                   
    
    // Update the post
    wp_update_post( $new_post );
    update_field('unidade_rel', $_GET['unidadeatb'], $post_id);

    // Se a inserção do post for bem-sucedida
    if ($post_id) {
        // Obtém o link da página recém-criada
        $post_link = get_permalink($post_id);

        $nome = get_field('predio_nome_predio', $post_id);

        if($nome){
            // Cria um array com os dados do post que você deseja atualizar
            $post_atualizado = array(
                'ID'         => $post_id,
                'post_title' => $nome,
            );

            // Usa a função wp_update_post para atualizar o post
            wp_update_post($post_atualizado);
        }

        // Redireciona para o link da página
        wp_redirect($post_link);
        exit;
    }
}

get_header();
acf_form_head();
?>
<div class="post-view">
<div class="container">
    <div class="row">
        <div class="col-12">
            
                <?php
                    acf_form(array(
                        'post_id' => 'new_post',
                        'post_title' => false,                    
                        'new_post' => array(
                            'post_status' => 'publish',
                            'post_type' => 'predio',
                            'post_title' => $_POST['acf']['field_663b8fdd0859e']
                        ),
                        'field_groups' => array(17353), // ID do grupo de campos do ACF
                        'return' => '?unidadeatb=' . $_GET['unidadeatb'] . '&post_id=%post_id%',
                        'submit_value' => 'Salvar',
                        'html_submit_button'  => '<input type="submit" class="acf-button acf-save button button-secondary button-large" value="%s" />',
                    ));
                    
                ?>
        </div>
    </div>
</div>

</div>

<?php

get_footer();