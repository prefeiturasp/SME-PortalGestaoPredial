<?php
require_once('wp-load.php');

function calcular_dias($data){
    // Converta a string da data para um objeto DateTime
    $data_recebida = DateTime::createFromFormat('d/m/Y', $data);

    // Obtenha a data atual
    $data_atual = new DateTime();

    // Calcule a diferença entre as duas datas
    $diferenca = $data_atual->diff($data_recebida);

    // Extraia o número de dias da diferença
    $dias_passados = $diferenca->days;

    return $dias_passados;
}

$args = array(
    'post_type' => 'unidade',
    'meta_query'     => array(
        array(
            'key'     => 'data_abertura', // Chave do campo personalizado
            'compare' => 'EXISTS', // Verifica se o campo existe
        ),
        array(
            'key'     => 'data_abertura', // Chave do campo personalizado novamente
            'value'   => '', // Valor do campo personalizado não deve ser vazio
            'compare' => '!=', // Verifica se o valor do campo é diferente de vazio
        ),
    ),

);

// The Query.
$the_query = new WP_Query( $args );

// The Loop.
if ( $the_query->have_posts() ) {
	//echo '<ul>';
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
        $infoVistoriador = '';
        $data_abertura = get_field('data_abertura');
        $tipo_unidade = get_field('tipo_unidade');
        $dias = calcular_dias($data_abertura);
        $validado = get_field('validado');
        //echo '<li>' . esc_html( get_the_title() ) . ' - ' . $dias . ' dias - ' . $tipo_unidade . ' - Validado: ' . $validado . '</li>';

        if( ($dias == 7 || $dias == 15) && !$validado){
            

            if($tipo_unidade == 'Administrativa'){
                
                global $wpdb;
								
                // Execute a consulta usando $wpdb
                    $results = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT DISTINCT u.ID, u.user_login, u.user_email
                            FROM {$wpdb->users} u
                            INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
                            WHERE um.meta_key = %s
                            AND um.meta_value = %s
                            AND u.ID IN (
                                SELECT user_id
                                FROM {$wpdb->usermeta}
                                WHERE meta_key = %s
                                AND meta_value LIKE %s
                            )",
                            'unidade_rel',
                            get_the_ID(),
                            $wpdb->prefix . 'capabilities',
                            '%administrativo%'
                        )
                    );
        
                    // Verifique se há resultados
                    if ( $results ) {
                        foreach ( $results as $result ) { 
                            $mensagem = "Prezado(a),<br><br>";
                            $mensagem .= "As informações institucionais da sua unidade precisam ser validadas para encaminharmos o processo de levantamento cadastral. Por favor, acesse o sistema <a href='" . get_home_url() . "'>Gestão Predial</a> utilizando seu RF e senha e realize a atualização/validação dos dados. Para isso:";
                            $mensagem .= "<ol>";
                                $mensagem .= '<li>Clique no ícone do botão "Ações"</li>';
                                $mensagem .= '<li>Em seguida, confira as informações da aba "Institucional" e faça as atualizações/correções, se necessário</li>';
                                $mensagem .= '<li>Após a conferência, clique no botão "Validar Informações" para confirmar</li>';                       
                            $mensagem .= "</ol>";
        
                            $mensagem .= "<br>Atenciosamente,<br>";
                            $mensagem .= "Equipe SME/COMAPRE";
                            // Envie o email para o autor do post
                            wp_mail( $result->user_email, '[Gestão Predial] Valide as Informações institucionais da sua unidade', $mensagem);
                        }
                    } else {
                        //echo 'Nenhum usuário encontrado.';
                    }

            } else {
                $eol = get_field('cod_eol', get_the_ID());
                if($eol){

                    global $wpdb;
                    // Execute a consulta usando $wpdb
                    $results = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT DISTINCT u.ID, u.user_login, u.user_email
                            FROM {$wpdb->users} u
                            INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
                            WHERE um.meta_key = %s
                            AND um.meta_value = %s
                            AND u.ID IN (
                                SELECT user_id
                                FROM {$wpdb->usermeta}
                                WHERE meta_key = %s
                                AND meta_value LIKE %s
                            )",
                            'cod_eol',
                            $eol,
                            $wpdb->prefix . 'capabilities',
                            '%educador%'
                        )
                    );
        
                    // Verifique se há resultados
                    if ( $results ) {
                        foreach ( $results as $result ) { 
                            $mensagem = "Prezado(a),<br><br>";
                            $mensagem .= "As informações institucionais da sua unidade precisam ser validadas para encaminharmos o processo de levantamento cadastral. Por favor, acesse o sistema <a href='" . get_home_url() . "'>Gestão Predial</a> utilizando seu RF e senha e realize a atualização/validação dos dados. Para isso:";
                            $mensagem .= "<ol>";
                                $mensagem .= '<li>Clique no ícone do botão "Ações"</li>';
                                $mensagem .= '<li>Em seguida, confira as informações da aba "Institucional" e faça as atualizações/correções, se necessário</li>';
                                $mensagem .= '<li>Após a conferência, clique no botão "Validar Informações" para confirmar</li>';                       
                            $mensagem .= "</ol>";
        
                            $mensagem .= "<br>Atenciosamente,<br>";
                            $mensagem .= "Equipe SME/COMAPRE";
                            // Envie o email para o autor do post
                            wp_mail( $result->user_email, '[Gestão Predial] Valide as Informações institucionais da sua unidade', $mensagem);
                        }
                    } else {
                        //echo 'Nenhum usuário encontrado.';
                    }
                }               

            }
        }
	}
	//echo '</ul>';
} else {
	//esc_html_e( 'Sorry, no posts matched your criteria.' );
}
// Restore original Post Data.
wp_reset_postdata();