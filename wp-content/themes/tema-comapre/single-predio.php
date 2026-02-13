<?php
use Classes\TemplateHierarchy\LoopUnidades\LoopUnidades;
get_header();
acf_form_head();
//$loop_unidades = new LoopUnidades();
//contabiliza visualizações de noticias
//setPostViews(get_the_ID()); /*echo getPostViews(get_the_ID());*/
$unidade = get_field('unidade_rel');
?>

<div class="post-view">
    <div class="info-topo pt-4 mb-2">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <p class="m-0"><strong><a href="<?= get_permalink($unidade); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar a aba de Instalação predial</a></strong></p>
                </div>
                <div class="col-12 col-md-8 text-right">
                    <p class="post-modified-info">
                        <?= get_the_last_modified_info(); ?>
                        <?php
                            $author_id = get_post_meta( get_the_ID(), '_edit_last', false );
                            if($author_id[0]){
                                $user = get_user_by( 'id', $author_id[0] );
                                if($user->display_name){
                                    echo "por " . $user->display_name;
                                }

                                $user_rf = get_user_meta( $author_id[0], 'rf');
                                if($user_rf[0]){
                                    echo " - RF " . $user_rf[0];
                                }
                            }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">        
        <div class="row">
            <div class="col-12">
                <?php
                
                    $user = wp_get_current_user();
                    if ( in_array( 'educador', (array) $user->roles ) || in_array( 'administrativo', (array) $user->roles )) {
                        $btnValidar = "Validar informações";
                    } else {
                        $btnValidar = "Validar";
                    }
			
                    if (have_posts()) {
                        // Loop de posts
                        while (have_posts()) {

                            the_post();

                            // Obter as configurações do grupo de campos com base na chave
                            $field_group = acf_get_field_group('group_1234567890');
                
                            // Exibe o formulário de edição dos campos personalizados usando acf_form()
                            $args = array(
                                'post_id'   => get_the_ID(), // ID do post atual
                                'form'      => true, // Exibe o formulário
                                'field_groups' => array(17300),
                                'fields'    => array(), // Renderiza todos os campos do post
                                'uploader' => 'basic',
                                'form_attributes' => array( // Atributos adicionais para o formulário
                                    'class' => 'custom-acf-form'
                                ), 
                                'html_submit_button' => '<input type="submit" class="acf-form-submit" value="%s" />', // Primeiro botão de envio
                                'submit_value' => $btnValidar, // Texto do botão de envio
                                // Inclui um segundo botão de ação
                                //'html_submit_button_secondary' => '<input type="submit" class="acf-form-submit" name="second_submit" value="Segundo botão" />',
                            );

                            if ( in_array( 'educador', (array) $user->roles ) || in_array( 'administrativo', (array) $user->roles )) {
                                $args['html_after_fields'] = '<input type="hidden" name="info_validadas" value="1"/>';
                            }

                            acf_form($args);
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>
    
<?php
get_footer();