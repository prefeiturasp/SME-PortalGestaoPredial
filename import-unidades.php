<?php

require_once('wp-load.php');

function import_unidades(){

    $api_token = getenv('SMEINTEGRACAO_API_PREPROD_TOKEN');

    $i = 0; // Contador de insercoes
    $j = 0; // Atualizar status

    $listaDres = array(
        109300, // DRE Sao Miguel
        109100, // DRE Santo Amaro
        108100, // DRE Butanta
        108500, // DRE Guaianases
        108400, // DRE Freguesia/Brasilandia
        108900, // DRE Penha
        108700, // DRE Itaquera
        109200, // DRE DRE São Mateus
        108600, // DRE Ipiranga
        109000, // DRE Pirituba/Jaragua
        108200, // DRE Campo Limpo
        108300, // DRE Capela do Socorro
        108800, // DRE DRE Jacana/Tremembe
    );

    global $wpdb;
    
    // Prefixo da tabela
    $prefixo_tabela = $wpdb->prefix;

    // Nome da tabela com prefixo
    $tabela = $prefixo_tabela . 'import_unidades';

    // Obtendo a data e hora atual
    $data_hora_atual = current_time( 'mysql' );

    // Definindo os valores para inserção na tabela
    $dados_inicio = array(
        'data_ini'    => $data_hora_atual,
        'data_fin'   => '',
        'cadastros'   => 0, // Valor numérico para a coluna 'cadastros'
        'atualizados' => 0   // Valor numérico para a coluna 'atualizados'
    );        

    // Inserir dados iniciais na tabela
    $resultado_insercao = $wpdb->insert( $tabela, $dados_inicio );

    if ( $resultado_insercao ) {

        // ID do registro inserido
        $id_inserido = $wpdb->insert_id;

        foreach($listaDres as $dre){
            $api_url =  getenv('SMEINTEGRACAO_API_PREPROD_URL') . '/api/DREs/' . $dre . '/unidades';
            
            $response = wp_remote_get( $api_url ,
                array( 
                    'headers' => array( 
                        'x-api-eol-key' => $api_token,							
                    )
                )
            );

            // Verificar se ocorreu um erro
            if ( is_wp_error( $response ) ) {
                // Se ocorreu um erro, exibir uma mensagem de erro ou registrar no log de erros
                echo 'Ocorreu um erro: ' . $response->get_error_message();

                $dados_erro = array(
                    'erro' => $response->get_error_message(),               
                );

                $where = array( 'ID' => $id_inserido );
    
                // Atualizar a linha na tabela
                $wpdb->update( $tabela, $dados_erro, $where );
            } else {
                // Se não ocorreu erro, acessar as propriedades ou métodos do resultado
                $unidades = json_decode($response['body']);
            }
            

            if($unidades[0]->codigoEol){

                foreach($unidades as $unidade){
    
                    $posts = get_posts(array(
                        'posts_per_page'    => 1,
                        'fields'         => 'ids',
                        'post_type'     => 'unidade',
                        'meta_key'      => 'cod_eol',
                        'meta_value'    => $unidade->codigoEol,
                        'meta_compare'   => '=',
                    ));
    
                    if(!$posts){
                        // Array com os dados do post
                        $post_data = array(
                            'post_title'    => $unidade->nomeOficial,
                            'post_status'   => 'publish',  // Status do post (publicado)
                            'post_type'     => 'unidade'   // Tipo de post (CPT)
                        );
    
                        // Inserir o post no banco de dados
                        $post_id = wp_insert_post($post_data);
    
                        if($post_id){
                            $i++;
                            $dre = convert_dre($unidade->nomeDre);
                            $tipoUeValor = trim($unidade->tipoUE);
                            $tipoUe = convert_tipoUe($tipoUeValor);
                            $telefone1 = padrao_tel($unidade->telefone1);
                            $telefone2 = padrao_tel($unidade->telefone2);
    
                            update_field( 'cod_eol', $unidade->codigoEol, $post_id );
                            update_field( 'nome_oficial', $unidade->nomeOficial, $post_id );
                            update_field( 'nome_nao_oficial', $unidade->nomeNaoOficial, $post_id );
                            update_field( 'tipologia', $tipoUe, $post_id );
                            update_field( 'logradouro', $unidade->logadouro, $post_id );
                            update_field( 'numero', $unidade->numero, $post_id );
                            update_field( 'bairro', $unidade->bairro, $post_id );
                            update_field( 'cep', $unidade->cep, $post_id );
                            update_field( 'distrito', $unidade->distrito, $post_id );
                            update_field( 'sub_prefeitura', $unidade->subPrefeitura, $post_id );
                            update_field( 'dre', $dre, $post_id );
                            update_field( 'email', $unidade->email, $post_id );
                            update_field( 'telefone_1', $telefone1, $post_id );
                            update_field( 'telefone_2', $telefone2, $post_id );
                            update_field( 'ano_construcao', $unidade->anoConstrucao, $post_id );
                            update_field( 'propriedade', $unidade->propriedade, $post_id );
                            update_field( 'vagas_matutino', $unidade->capacidadeVagasMatutino, $post_id );
                            update_field( 'vagas_vespertino', $unidade->capacidadeVagasVespertino, $post_id );
                            update_field( 'vagas_noturno', $unidade->capacidadeVagasNoturno, $post_id );
                            update_field( 'vagas_intermediario', $unidade->capacidadeVagasIntermediario, $post_id );
                            update_field( 'vagas_integral', $unidade->capacidadeVagasIntegral, $post_id );
                            update_field( 'vagas_total', $unidade->capacidadeVagasTotal, $post_id );
                            update_field( 'status', $unidade->status, $post_id );
                            update_field( 'funcionarios', $unidade->quantidadeDeFuncionarios, $post_id );
                        }
                    } else {
                        $status = get_field('status', $posts[0]);
                        $statusEol = $unidade->status;
    
                        if($status != $statusEol && $status != ''){
                            $j++;
                            update_field( 'status', $statusEol, $posts[0] );
                        }
                    }
    
                }

            }
        }

        // Verificações concluídas, atualizar a linha com a data e hora de conclusão do processo
        $dados_fim = array(
            'data_fin' => current_time( 'mysql' ), // Data e hora de conclusão do processo
            'cadastros' => $i, // Valor numérico final
            'atualizados' => $j  // Valor numérico final
        );
    
        // Condição para atualizar a linha
        $where = array( 'ID' => $id_inserido );
    
        // Atualizar a linha na tabela
        $wpdb->update( $tabela, $dados_fim, $where );
    
        echo "Processo concluído e registros atualizados com sucesso.";
    } else {
        // Ocorreu um erro durante a inserção
        echo "Erro ao inserir dados na tabela: " . $wpdb->last_error;

        $dados_erro = array(
            'erro' => $wpdb->last_error,               
        );

        $where = array( 'ID' => $id_inserido );

        // Atualizar a linha na tabela
        $wpdb->update( $tabela, $dados_erro, $where );
    }

}

import_unidades();