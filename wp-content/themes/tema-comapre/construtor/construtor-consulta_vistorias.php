<?php
    $titulo = get_sub_field('titulo_principal');
    $quantidade = get_sub_field('quantidade');
    if(!$quantidade){
        $quantidade = 10;
    }
?>

<div class="container">
    <div class="busca-unidades busca-result visao-geral">
        <div class="row">
            <div class="col-12 p-0 border-bottom mb-3">
                <?php if($titulo): ?>
                    <p class="titulo"><?= $titulo; ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ( !current_user_can( 'educador' ) && !current_user_can( 'administrativo' ) ): ?>
            <div class="row">
                <div class="col-12">
                    <form action="<?= get_the_permalink(); ?>" class="consulta-vistorias">
                        <div class="form-row">

                            <div class="col">
                                <div class="form-group">
                                    <label for="nome_eol">Nome da unidade</label>
                                    <input type="text" name="nome_eol" class="form-control" id="nome_eol" placeholder="Informar" value="<?= $_GET['nome_eol']; ?>">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="nomeDre">Nome da DRE</label>
                                    <select class="form-control" name="nomeDre" id="nomeDre">
                                        <option value="">Selecionar</option>
                                        <?php
                                        $opcoes = array(
                                            "DRE Butantã",
                                            "DRE Campo Limpo",
                                            "DRE Capela do Socorro",
                                            "DRE Freguesia/Brasilândia",
                                            "DRE Guaianases",
                                            "DRE Ipiranga",
                                            "DRE Itaquera",
                                            "DRE Jaçanã/Tremembé",
                                            "DRE Penha",
                                            "DRE Pirituba/Jaraguá",
                                            "DRE Santo Amaro",
                                            "DRE São Mateus",
                                            "DRE São Miguel"
                                        );

                                        foreach ($opcoes as $opcao) {
                                            echo '<option value="' . $opcao . '"';
                                            if ($_GET['nomeDre'] == $opcao) {
                                                echo ' selected';
                                            }
                                            echo '>' . $opcao . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="flatpickr">
                                    <label for="intervalo_datas">Período</label>
                                    <input name="intervalo_datas" type="text" id="intervalo_datas" class="form-control input" placeholder="Data início Até Data final" value="<?= $_GET['intervalo_datas']; ?>" autocomplete="off" data-input> <!-- input is mandatory -->

                                    <a class="input-button" title="toggle" data-toggle>
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </a>

                                    <a class="input-button" title="clear" data-clear>
                                        <i class="icon-close"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Selecionar</option>
                                        <?php
                                            $opcoes_status = array(
                                                "nao-iniciada" => "Não iniciada",
                                                "andamento" => "Em andamento",
                                                "analise-fiscal" => "Em análise: Fiscal Setorial",
                                                "correcao-fiscal" => "Solicitação de correções Fiscal Setorial",
                                                "analise-sme" => "Em análise: SME/COMAPRE",
                                                "correcao-sme" => "Solicitação de correções SME/COMAPRE",
                                                "concluida" => "Concluída"
                                            );

                                            foreach ($opcoes_status as $key => $opcao_status) {
                                                echo '<option value="' . $key . '"';
                                                if ($_GET['status'] == $key) {
                                                    echo ' selected';
                                                }
                                                echo '>' . $opcao_status . '</option>';
                                            }
                                        ?>                                    
                                    </select>
                                </div>
                            </div>

                            <div class="col align-self-end">
                                <button type="submit" class="btn btn-consulta mb-2">Pesquisar</button>
                            </div>

                        </div>
                        
                    </form>
                </div>

                
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                
                <?php
                if(current_user_can( 'educador' )){
                    $currentUser = get_current_user_id();
                    $paged = 1;
                    if ( get_query_var('paged') ) $paged = get_query_var('paged');
                    if ( get_query_var('page') ) $paged = get_query_var('page');
                    $unidadeEol = get_user_meta( $currentUser, 'cod_eol', true);
                    if(!$unidadeEol){
                        $unidadeEol = 0;
                    }
                    
                    $args = array(
                        'post_type' => 'unidade',
                        'posts_per_page' => $quantidade,
                        'relation' => 'AND',
                        'meta_query' => array(
                            array(
                                'key' => 'cod_eol', // Substitua 'nome_do_campo_de_relacionamento' pelo nome do seu campo de relacionamento ACF
                                'value' => $unidadeEol, // ID do usuário ao qual você deseja filtrar os posts
                                'compare' => '=', // Verifica se o valor está contido no campo de relacionamento (pode variar dependendo do tipo do campo)
                            ),
                        ),
                    );
                } elseif(current_user_can( 'administrativo' )){

                    $currentUser = get_current_user_id();
                    $paged = 1;
                    if ( get_query_var('paged') ) $paged = get_query_var('paged');
                    if ( get_query_var('page') ) $paged = get_query_var('page');
                    $unidade = get_user_meta( $currentUser, 'unidade_rel', true);
                    if(!$unidade){
                        $unidade = 1;
                    }
                    
                    $args = array(
                        'post_type' => 'unidade',
                        'p' => $unidade,
                    );

                } else {
                    $currentUser = get_current_user_id();
                    $paged = 1;
                    if ( get_query_var('paged') ) $paged = get_query_var('paged');
                    if ( get_query_var('page') ) $paged = get_query_var('page');
                    $args = array(
                        'post_type' => 'unidade',
                        'paged' => $paged,
                        'posts_per_page' => $quantidade,
                        'relation' => 'AND',
                        'meta_query' => array(
                            array(
                                'key' => 'vistoriador', // Substitua 'nome_do_campo_de_relacionamento' pelo nome do seu campo de relacionamento ACF
                                'value' => $currentUser, // ID do usuário ao qual você deseja filtrar os posts
                                'compare' => '=', // Verifica se o valor está contido no campo de relacionamento (pode variar dependendo do tipo do campo)
                            ),
                        ),
                    );
                }
                

                if($_GET['nome_eol'] && $_GET['nome_eol'] != ''){
                    $args['meta_query'][] = array(
                        'relation' => 'OR',
                        array(
                            'key' => 'cod_eol', // Substitua 'nome_do_campo_de_relacionamento' pelo nome do seu campo de relacionamento ACF
                            'value' => $_GET['nome_eol'], // ID do usuário ao qual você deseja filtrar os posts
                            'compare' => '=', // Verifica se o valor está contido no campo de relacionamento (pode variar dependendo do tipo do campo)
                        ),
                        array(
                            'key' => 'nome_oficial', // Substitua 'nome_do_campo_de_relacionamento' pelo nome do seu campo de relacionamento ACF
                            'value' => $_GET['nome_eol'], // ID do usuário ao qual você deseja filtrar os posts
                            'compare' => 'LIKE', // Verifica se o valor está contido no campo de relacionamento (pode variar dependendo do tipo do campo)
                        ),
                        array(
                            'key' => 'nome_nao_oficial', // Substitua 'nome_do_campo_de_relacionamento' pelo nome do seu campo de relacionamento ACF
                            'value' => $_GET['nome_eol'], // ID do usuário ao qual você deseja filtrar os posts
                            'compare' => 'LIKE', // Verifica se o valor está contido no campo de relacionamento (pode variar dependendo do tipo do campo)
                        )
                    );
                }

                if($_GET['status'] && $_GET['status'] != ''){
                    $args['meta_query'][] = array(
                        'key' => 'status_vistoria', // Substitua 'nome_do_campo_de_relacionamento' pelo nome do seu campo de relacionamento ACF
                        'value' => $_GET['status'], // ID do usuário ao qual você deseja filtrar os posts
                        'compare' => '=', // Verifica se o valor está contido no campo de relacionamento (pode variar dependendo do tipo do campo)
                    );
                }

                if($_GET['nomeDre'] && $_GET['nomeDre'] != ''){
                    $args['meta_query'][] = array(
                        'key' => 'dre', // Substitua 'nome_do_campo_de_relacionamento' pelo nome do seu campo de relacionamento ACF
                        'value' => $_GET['nomeDre'], // ID do usuário ao qual você deseja filtrar os posts
                        'compare' => '=', // Verifica se o valor está contido no campo de relacionamento (pode variar dependendo do tipo do campo)
                    );
                }

                if($_GET['intervalo_datas'] && $_GET['intervalo_datas'] != ''){
                    
                    $string_datas = $_GET['intervalo_datas'];

                    // Divide a string em duas partes com base no texto " até "
                    $partes = explode(" até ", $string_datas);

                    // Cria um objeto DateTime usando a data fornecida
                    $dataIni = DateTime::createFromFormat('d/m/Y', $partes[0]);                    
                    $dataCompareIni = $dataIni->format('Ymd');
                    $dataFin = DateTime::createFromFormat('d/m/Y', $partes[1]);                    
                    $dataCompareFin = $dataFin->format('Ymd');
                    
                    $args['meta_query'][] = array(
                        'key' => 'data_abertura', // Substitua 'nome_do_campo_de_relacionamento' pelo nome do seu campo de relacionamento ACF
                        'value' => array($dataCompareIni, $dataCompareFin), // Intervalo de datas
                        'compare' => 'BETWEEN', // Compara se a data está entre o intervalo
                        'type' => 'NUMERIC', // Tipo de comparação
                    );
                }
                
                // the query.
                $the_query = new WP_Query( $args ); ?>

                <?php if ( $the_query->have_posts() ) : ?>
                    <div class="table-results border-0 m-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="align-middle">Código EOL</th>
                                    <th scope="col" class="align-middle">Nome da unidade</th>
                                    <th scope="col" class="align-middle">DRE</th>
                                    <th scope="col" class="align-middle">Data abertura da vistoria</th>
                                    <th scope="col" class="align-middle">Status</th>
                                    <th scope="col" class="align-middle">Ações</th>
                                </tr>
                            </thead>
                            
                        
                            <!-- pagination here -->
                            <?php
                                $total_posts = $the_query->found_posts;
                                
                                $posts_exibidos = $the_query->post_count; // Obtém o número de posts exibidos atualmente
                                $total_pages = $the_query->max_num_pages; // Obtém o número total de páginas disponíveis

                                // Exibe a mensagem "Exibindo X de Y"
                                if($paged == $total_pages){
                                    echo '<p class="total_posts">Exibindo <span>' . $total_posts . '</span> de <span>' . $total_posts . '</span></p>';
                                } else {
                                    $show_qtd = $posts_exibidos * $paged;
                                    echo '<p class="total_posts last">Exibindo <span>' . $show_qtd . '</span> de <span>' . $total_posts . '</span></p>';
                                }
                                
                            ?>
                            <!-- the loop -->
                            <?php
                            while ( $the_query->have_posts() ) :
                                $the_query->the_post();
                                ?>
                                <tbody>
                                    <tr>
                                        <td class="align-middle">
                                            <?php
                                                $cod_eol = get_field('cod_eol');
                                                echo $cod_eol ? $cod_eol : '-';
                                            ?>
                                        </td>
                                        <td class="align-middle"><?= get_the_title(); ?></td>
                                        <td class="align-middle">
                                            <?php
                                                $dre = get_field('dre');
                                                echo $dre ? $dre : '-';
                                            ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                                $data_abertura = get_field('data_abertura');
                                                echo $data_abertura ? $data_abertura : '-';
                                            ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                                $status = get_field('status_vistoria');
                                                $class = 'badge-warning';
                                                if($status == 'Em andamento'){
                                                    $class = 'badge-primary';
                                                } elseif($status == 'Concluída'){
                                                    $class = 'badge-success';
                                                }
                                                echo $status ? '<span class="px-2 py-1 badge ' . $class . '">' . $status . '</span>' : '-';
                                            ?>
                                            
                                        </td>
                                        <td class="align-middle text-center"><a href="<?= get_the_permalink(); ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                    </tr>
                                    
                                </tbody>
                                
                            <?php endwhile; ?>
                            <!-- end of the loop -->

                        </table>
                    </div>

                    <!-- pagination here -->
                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="pagination-prog text-center">
                                    <?php wp_pagenavi( array( 'query' => $the_query ) ); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php wp_reset_postdata(); ?>

                <?php else : ?>
                    <p><?php esc_html_e( 'Desculpe, nenhuma unidade encontrada.' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
</div>