<?php
    $titulo = get_sub_field('titulo_principal');
    if ( !current_user_can( 'educador' ) && !current_user_can( 'administrativo' ) ):
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
        <div class="form-row">
            <div class="col-12 col-md-3 mb-3">
                <a href="<?= get_the_permalink() . '?status=nao-iniciada'; ?>">
                    <div class="card">
                        <div class="card-body">
                            <?php
                                $current_user = get_current_user_id();
                                $args = array(
                                    'post_type'      => 'unidade', // Tipo de post que você deseja contar
                                    'posts_per_page' => -1, // Define -1 para retornar todos os posts encontrados
                                    'meta_query'     => array(
                                        'relation' => 'AND', // Considera ambas as condições
                                        array(
                                            'key'     => 'status_vistoria',
                                            'value'   => 'nao-iniciada',
                                            'compare' => '=',
                                        ),
                                        array(
                                            'key'     => 'vistoriador',
                                            'value'   => $current_user,
                                            'compare' => '=',
                                        ),
                                    ),
                                    'fields'         => 'ids', // Limita a consulta a recuperar apenas os IDs dos posts correspondentes
                                    'cache_results'  => true, // Ativa o cache de resultados da consulta
                                );

                                // Executa a consulta
                                $query = new WP_Query($args);

                                // Obtém o total de posts encontrados
                                $total_posts = $query->found_posts;

                                // Exibe o total de posts encontrados
                                //echo 'Total de posts com status "concluida" e vistoriador 1: ' . $total_posts;

                            ?>
                            <p class="valor"><?= $total_posts; ?></p>
                            Não iniciada
                        </div>
                    </div>
                </a>
            </div>            

            <div class="col-12 col-md-3 mb-3">
                <a href="<?= get_the_permalink() . '?status=andamento'; ?>">
                    <div class="card">
                        <div class="card-body">
                            <?php
                                $args = array(
                                    'post_type'      => 'unidade', // Tipo de post que você deseja contar
                                    'posts_per_page' => -1, // Define -1 para retornar todos os posts encontrados
                                    'meta_query'     => array(
                                        'relation' => 'AND', // Considera ambas as condições
                                        array(
                                            'key'     => 'status_vistoria',
                                            'value'   => 'andamento',
                                            'compare' => '=',
                                        ),
                                        array(
                                            'key'     => 'vistoriador',
                                            'value'   => $current_user,
                                            'compare' => '=',
                                        ),
                                    ),
                                    'fields'         => 'ids', // Limita a consulta a recuperar apenas os IDs dos posts correspondentes
                                    'cache_results'  => true, // Ativa o cache de resultados da consulta
                                );

                                // Executa a consulta
                                $query = new WP_Query($args);

                                // Obtém o total de posts encontrados
                                $total_posts = $query->found_posts;

                                // Exibe o total de posts encontrados
                                //echo 'Total de posts com status "concluida" e vistoriador 1: ' . $total_posts;

                            ?>
                            <p class="valor"><?= $total_posts; ?></p>
                            Em andamento
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <a href="<?= get_the_permalink() . '?status=analise-fiscal'; ?>">
                    <div class="card">
                        <div class="card-body">
                            <?php
                                $args = array(
                                    'post_type'      => 'unidade', // Tipo de post que você deseja contar
                                    'posts_per_page' => -1, // Define -1 para retornar todos os posts encontrados
                                    'meta_query'     => array(
                                        'relation' => 'AND', // Considera ambas as condições
                                        array(
                                            'key'     => 'status_vistoria',
                                            'value'   => 'analise-fiscal',
                                            'compare' => '=',
                                        ),
                                        array(
                                            'key'     => 'vistoriador',
                                            'value'   => $current_user,
                                            'compare' => '=',
                                        ),
                                    ),
                                    'fields'         => 'ids', // Limita a consulta a recuperar apenas os IDs dos posts correspondentes
                                    'cache_results'  => true, // Ativa o cache de resultados da consulta
                                );

                                // Executa a consulta
                                $query = new WP_Query($args);

                                // Obtém o total de posts encontrados
                                $total_posts = $query->found_posts;

                                // Exibe o total de posts encontrados
                                //echo 'Total de posts com status "concluida" e vistoriador 1: ' . $total_posts;

                            ?>
                            <p class="valor"><?= $total_posts; ?></p>
                            Em análise: Fiscal Setorial
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <a href="<?= get_the_permalink() . '?status=correcao-fiscal'; ?>">
                    <div class="card">
                        <div class="card-body">
                            <?php
                                $args = array(
                                    'post_type'      => 'unidade', // Tipo de post que você deseja contar
                                    'posts_per_page' => -1, // Define -1 para retornar todos os posts encontrados
                                    'meta_query'     => array(
                                        'relation' => 'AND', // Considera ambas as condições
                                        array(
                                            'key'     => 'status_vistoria',
                                            'value'   => 'correcao-fiscal',
                                            'compare' => '=',
                                        ),
                                        array(
                                            'key'     => 'vistoriador',
                                            'value'   => $current_user,
                                            'compare' => '=',
                                        ),
                                    ),
                                    'fields'         => 'ids', // Limita a consulta a recuperar apenas os IDs dos posts correspondentes
                                    'cache_results'  => true, // Ativa o cache de resultados da consulta
                                );

                                // Executa a consulta
                                $query = new WP_Query($args);

                                // Obtém o total de posts encontrados
                                $total_posts = $query->found_posts;

                                // Exibe o total de posts encontrados
                                //echo 'Total de posts com status "concluida" e vistoriador 1: ' . $total_posts;

                            ?>
                            <p class="valor"><?= $total_posts; ?></p>
                            Solicitação de correções Fiscal Setorial
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <a href="<?= get_the_permalink() . '?status=analise-sme'; ?>">
                    <div class="card">
                        <div class="card-body">
                            <?php
                                $args = array(
                                    'post_type'      => 'unidade', // Tipo de post que você deseja contar
                                    'posts_per_page' => -1, // Define -1 para retornar todos os posts encontrados
                                    'meta_query'     => array(
                                        'relation' => 'AND', // Considera ambas as condições
                                        array(
                                            'key'     => 'status_vistoria',
                                            'value'   => 'analise-sme',
                                            'compare' => '=',
                                        ),
                                        array(
                                            'key'     => 'vistoriador',
                                            'value'   => $current_user,
                                            'compare' => '=',
                                        ),
                                    ),
                                    'fields'         => 'ids', // Limita a consulta a recuperar apenas os IDs dos posts correspondentes
                                    'cache_results'  => true, // Ativa o cache de resultados da consulta
                                );

                                // Executa a consulta
                                $query = new WP_Query($args);

                                // Obtém o total de posts encontrados
                                $total_posts = $query->found_posts;

                                // Exibe o total de posts encontrados
                                //echo 'Total de posts com status "concluida" e vistoriador 1: ' . $total_posts;

                            ?>
                            <p class="valor"><?= $total_posts; ?></p>
                            Em análise: SME/COMAPRE
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <a href="<?= get_the_permalink() . '?status=correcao-sme'; ?>">
                    <div class="card">
                        <div class="card-body">
                            <?php
                                $args = array(
                                    'post_type'      => 'unidade', // Tipo de post que você deseja contar
                                    'posts_per_page' => -1, // Define -1 para retornar todos os posts encontrados
                                    'meta_query'     => array(
                                        'relation' => 'AND', // Considera ambas as condições
                                        array(
                                            'key'     => 'status_vistoria',
                                            'value'   => 'correcao-sme',
                                            'compare' => '=',
                                        ),
                                        array(
                                            'key'     => 'vistoriador',
                                            'value'   => $current_user,
                                            'compare' => '=',
                                        ),
                                    ),
                                    'fields'         => 'ids', // Limita a consulta a recuperar apenas os IDs dos posts correspondentes
                                    'cache_results'  => true, // Ativa o cache de resultados da consulta
                                );

                                // Executa a consulta
                                $query = new WP_Query($args);

                                // Obtém o total de posts encontrados
                                $total_posts = $query->found_posts;

                                // Exibe o total de posts encontrados
                                //echo 'Total de posts com status "concluida" e vistoriador 1: ' . $total_posts;

                            ?>
                            <p class="valor"><?= $total_posts; ?></p>
                            Solicitação de correções SME/COMAPRE
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <a href="<?= get_the_permalink() . '?status=concluida'; ?>">
                    <div class="card">
                        <div class="card-body">
                            <?php
                                $args = array(
                                    'post_type'      => 'unidade', // Tipo de post que você deseja contar
                                    'posts_per_page' => -1, // Define -1 para retornar todos os posts encontrados
                                    'meta_query'     => array(
                                        'relation' => 'AND', // Considera ambas as condições
                                        array(
                                            'key'     => 'status_vistoria',
                                            'value'   => 'concluida',
                                            'compare' => '=',
                                        ),
                                        array(
                                            'key'     => 'vistoriador',
                                            'value'   => $current_user,
                                            'compare' => '=',
                                        ),
                                    ),
                                    'fields'         => 'ids', // Limita a consulta a recuperar apenas os IDs dos posts correspondentes
                                    'cache_results'  => true, // Ativa o cache de resultados da consulta
                                );

                                // Executa a consulta
                                $query = new WP_Query($args);

                                // Obtém o total de posts encontrados
                                $total_posts = $query->found_posts;

                                // Exibe o total de posts encontrados
                                //echo 'Total de posts com status "concluida" e vistoriador 1: ' . $total_posts;

                            ?>
                            <p class="valor"><?= $total_posts; ?></p>
                            Concluída
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
    
</div>

<?php endif; ?>