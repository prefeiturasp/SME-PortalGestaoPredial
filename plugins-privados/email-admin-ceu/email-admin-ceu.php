<?php

/*
Plugin Name: Emails Admin CEUs
Plugin URI: http://educacao.sme.prefeitura.sp.gov.br
Description: Envio de emails para admins em eventos e unidades pendentes.
Version: 1.0
Author: AMcom
Author URI: https://www.amcom.com.br
*/

function post_unpublished( $new_status, $old_status, $post ) {
    
    if ( ($old_status == 'publish' &&  $new_status == 'pending') || ($old_status == 'auto-draft' &&  $new_status == 'pending') || ($old_status == 'draft' &&  $new_status == 'pending') ) {
        
        if ( ! $post_type = get_post_type_object( $post->post_type ) )
        return;

        if($old_status == 'auto-draft' &&  $new_status == 'pending'){
            $idUnidade = $_POST['acf']['field_5fc7ce0712c45'];
        } elseif($post_type->labels->singular_name == 'Sobre o CEU' || $post_type->labels->singular_name == 'Destaque') {
            $idUnidade = $post->ID;
        } else {
            $idUnidade = get_field('localizacao', $post->ID);
            if($idUnidade == ''){
                $idUnidade = $_POST['acf']['field_5fc7ce0712c45'];
            }
        }
        $unidade = get_the_title($idUnidade);

        $gruposRel = array();
        $cc_args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'wporg_unidades',                    
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'destaques_grupo',
                        'value' => $idUnidade,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key' => 'unidades',
                        'value' => $idUnidade,
                        'compare' => 'LIKE',
                    ),
                )
                
            ),
        );
        $cc_query = new WP_Query( $cc_args );

        // The Loop
        if ( $cc_query->have_posts() ) {            
            while ( $cc_query->have_posts() ) {
                $cc_query->the_post();
                $gruposRel[] = get_the_ID();
            }
            
        } 
        /* Restore original Post Data */
        wp_reset_postdata();
        
        $emailto = array();
        
        $adminUsers = get_users('role=Administrator'); // Uuarios do tipo admin        

        $relation['relation'] = 'OR';

        if($gruposRel && $gruposRel != ''){
            foreach($gruposRel as $grupo){
                $relation[] = array(
                    'key' => 'grupo',
                    'value' => $grupo,
                    'compare' => 'LIKE'
                );                
            }  
            
            $args = array(
                'role' => 'editor', 
                'meta_query'=>array(
                    'relation' => 'AND',         
                        array(
                            $relation
                        ),       
                    
                )
            );     
            
            $editorUsers = get_users( $args ); // Uuarios do tipo Editor
            
            foreach($editorUsers as $key => $user){
                $grupos = get_field('grupo', 'user_' . $user->ID); // Grupo do usuario
                $unidades = array();
                
                foreach($grupos as $grupo){
                    $unidades[] = get_field('unidades', $grupo);
                }
    
                $unidades = call_user_func_array('array_merge', $unidades);
                $unidades = array_unique($unidades);
    
                if( !in_array($idUnidade, $unidades) ){
                    unset($editorUsers[$key]);
                }
            }
            
            
        } else {
            $editorUsers = '';
        }

                
        
        if($editorUsers && $editorUsers != ''){
            $portalusers = array_merge($adminUsers, $editorUsers); // todos os usuarios em um array
        } else {
            $portalusers = $adminUsers; 
        }
        
       
        foreach ($portalusers as $user) {
            $emailto[] = $user->user_email;
        }

        // usuarios que nao receberao email
        $removeUser = array('ollyver.ottoboni@amcom.com.br', 'ollyverottoboni@gmail.com', 'ascom.conteudo@sme.prefeitura.sp.gov.br');

        $emailto = array_diff($emailto, $removeUser);
        
        //Link para editar
        $link = get_edit_post_link( $post->ID );
        $link = str_replace('&amp;' , '&', $link);
       
        if($post_type->labels->singular_name == 'Evento'){            
            
            if($idUnidade == '31244' || $idUnidade == '31675'){
                // Assunto do email"
                $subject = 'O ' . $post_type->labels->singular_name . ' "' . get_the_title($post->ID) . '"' . ' que pertence a "' . $unidade . '" foi editado no portal.';
                
                // Corpo do email
                $message = 'O ' . $post_type->labels->singular_name . ' "' . get_the_title($post->ID) . '"' . ' que pertence a "' . $unidade . '"' . " foi editado no portal.\n\nPara visualizar as alterações acesse: " . get_permalink( $post->ID ) . "\nPara publicar acesse: " . $link;
            } else {
                // Assunto do email"
                $subject = 'O ' . $post_type->labels->singular_name . ' "' . get_the_title($post->ID) . '"' . ' que pertence a unidade "' . $unidade . '" foi editado no portal.';
                
                // Corpo do email
                $message = 'O ' . $post_type->labels->singular_name . ' "' . get_the_title($post->ID) . '"' . ' que pertence a unidade "' . $unidade . '"' . " foi editado no portal.\n\nPara visualizar as alterações acesse: " . get_permalink( $post->ID ) . "\nPara publicar acesse: " . $link;
                //$message .= ' ' . $old_status . "  " . $new_status;                
            }   

        } else {
            // Assunto do email"
            
            $subject = 'O ' . $post_type->labels->singular_name . ' "' . get_the_title($post->ID) . '"' . ' foi editado no portal.';
            // Corpo do email
            $message = 'O ' . $post_type->labels->singular_name . ' "' . get_the_title($post->ID) . '"' . " foi editado no portal.\nPara visualizar as alterações acesse: " . get_permalink( $post->ID ) . "\nPara publicar acesse: " . $link;
            //$message .= ' ' . $old_status . "  " . $new_status;            
        }
        
        // evia o email
        //$emailto2 = array('felipe.almeida@amcom.com.br', 'monica.tang@sme.prefeitura.sp.gov.br', 'jaqueline.sargi@amcom.com.br');
        wp_mail( $emailto, $subject, $message );
    }
}
add_action( 'transition_post_status', 'post_unpublished', 200, 3 );