<?php
        /* Template Name: Custom Search */        
        get_header();
        
            $argsConcurso = array(
                'post_type' => 'concurso',
                'posts_per_page' => 999999,
                'orderby' => 'title',
                'order' => 'ASC',                             
            );

            function getAno($anos) {

                $allyears = array();
                $returnYears = array();
        
                foreach($anos as $rdate){
                    $year = explode('-', $rdate);
                    $allyears[] = $year[0];
                }
    
                rsort($allyears);
    
                $arrlength = count($allyears);
    
                for($x = 0; $x < $arrlength; $x++) {
                    $returnYears[] = $allyears[$x];              
                } 
    
                $returnYears = array_unique($returnYears);
        
                return $returnYears;
            }

            $titleConc = array();
            $anoHomolog = array();
            $anoValidade = array();
            $status = array();

            $queryConcurso = new WP_Query( $argsConcurso );

            if ($queryConcurso->have_posts()) : while ($queryConcurso->have_posts()) : $queryConcurso->the_post();
			
                $titleConc[] = get_the_title();
                $anoHomolog[] = get_field( "homologacao");
                $anoValidade[] = get_field( "validade");
                $status[] = get_field( "status");

            endwhile;
            endif;
            wp_reset_postdata();
        
        ?>
        
        <section class="container">
            <?php
                global $post;
            ?>
            <article class="row">
                <article class="col-lg-12 col-xs-12">
                    <h1 class="mb-4"><?php echo get_the_title( 31818 );  ?> </h1>
                </article>
            </article>


            <article class="row">
                <article class="col-lg-9 col-xs-12">
                    <?php 
                        $post   = get_post( 31818 );
                        $output =  apply_filters( 'the_content', $post->post_content );                        
                        echo $output;
                    ?>
                </article>
            </article>
        </section>

        
        
        <section class="container">
		    <section class="row">
                <section class="col-lg-12 col-xs-12 d-flex align-content-start flex-wrap">
                <?php
                    
                ?>
                    <div class="shadow-sm col-md-10 p-3 mb-3 bg-white rounded">
                        <form class="needs-validation" novalidate action="<?php echo site_url('/'); ?>" method="get" id="searchform">
                            <div class="form-row">
                                <div class="col-md-12 my-3">
                                    
                                    <div class="input-group btn-busca">                            
                                        <input type="text" class="form-control" placeholder="Pesquisar" name='s'>
                                        <button type="submit" alt='Buscar por concursos' class="btn rounded-circle"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>     
                                            
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="validationTooltipUsername">Cargo/ Função</label>
                                    <div class="input-group">                            
                                        <select name='cargo' class="custom-select">
                                            <option selected value=''>Selecione um cargo</option>
                                            <?php 
                                                foreach($titleConc as $title){
                                                    echo "<option value='$title'>$title</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="validationTooltipUsername">Homologação</label>
                                    <div class="input-group">                            
                                        <select name="ano-hom" class="custom-select">
                                            <option selected value=''>Selecione ano</option>
                                            <?php
                                                $anosHomolog = getAno($anoHomolog);
                                                foreach($anosHomolog as $ano){
                                                    echo "<option value='$ano'>$ano</option>";
                                                }                                    
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="validationTooltipUsername">Validade</label>
                                    <div class="input-group">                            
                                        <select name="ano-val" class="custom-select">
                                            <option selected value=''>Selecione ano</option>
                                            <?php
                                                $anosValidade = getAno($anoValidade);
                                                foreach($anosValidade as $ano){
                                                    echo "<option value='$ano'>$ano</option>";
                                                }                                    
                                            ?> 
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="validationTooltipUsername">Status</label>
                                    <div class="input-group">                            
                                        <select name="status" class="custom-select">
                                            <option selected value=''>Selecione</option>
                                            <?php 
                                                $status = array_unique($status);
                                                foreach($status as $statu){
                                                    echo "<option value='$statu'>$statu</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            
                            <input type="hidden" name="post_type" value="concurso" />
                        </form>

                        <br>
                        
                        <?php

                        function convertDate($date){
                            $newDate = explode('-', $date);
                            
                            $dataPadrao = $newDate[2] . '/' . $newDate[1] . '/' . $newDate[0];
                            return $dataPadrao;
                        }

                        $args = array(
                            'post_type' => array( 'concurso' ),
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC',
                            'meta_query' => array(
                                'relation' => 'AND',                                
                            ),
                        );

                        if(isset($_GET['s']) && $_GET['s'] != ''){
                            $args['s'] = $_GET['s'];
                        }

                        if(isset($_GET['ano-hom']) && $_GET['ano-hom'] != ''){
                            $ano_hom = $_GET['ano-hom'];
                            
                            $args['meta_query'][] = array (
                                'key' => 'homologacao',
                                'value'     => $ano_hom . '-01-01',
                                'compare'   => '>=',
                                'type'      => 'DATE',
                            );
                            
                            $args['meta_query'][] = array (
                                'key' => 'homologacao',
                                'value'     => $ano_hom . '-12-31',
                                'compare'   => '<=',
                                'type'      => 'DATE',
                            );
                        }

                        if(isset($_GET['ano-val']) && $_GET['ano-val'] != ''){
                            $ano_val = $_GET['ano-val'];
                            
                            $args['meta_query'][] = array (
                                'key' => 'validade',
                                'value'     => $ano_val . '-01-01',
                                'compare'   => '>=',
                                'type'      => 'DATE',
                            );
                            
                            $args['meta_query'][] = array (
                                'key' => 'validade',
                                'value'     => $ano_val . '-12-31',
                                'compare'   => '<=',
                                'type'      => 'DATE',
                            );
                        }

                        
                        if(isset($_GET['cargo']) && $_GET['cargo'] != ''){
                            $cargo = $_GET['cargo'];
                            
                            $args['post_title_like'] = $cargo;
                        }

                        if(isset($_GET['status']) && $_GET['status'] != ''){
                            $status = $_GET['status'];
                            
                            $args['meta_query'][] = array (
                                'key' => 'status',
                                'value' => $status
                            );
                        }

                        //echo "<pre>";
                        //print_r($args);
                        //echo "</pre>";
                        
                        
                        $query = new WP_Query( $args );
                    ?>
                     
                     
                    <?php if ( $query->have_posts() ) : while (  $query->have_posts() ) :  $query->the_post(); ?>    
                            
                            <div class="row py-3 border-top">
                                <div class="col-12">
                                    <?php
                                        $titleurl = get_field( "link_noticias");
                                        if($titleurl) :
                                    ?>                                    
                                        <h3><a href="<?php echo $titleurl; ?>"><?php the_title(); ?></a></h3>
                                    <?php else: ?>
                                        <h3><?php the_title(); ?></h3>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12 col-md-6">
                                    <table class='table-conc'>

                                        
                                        <?php
                                            // Verifica data de hologacao
                                            $datahom = get_field("homologacao");
                                            if($datahom):
                                            
                                            $dtHomolog = convertDate($datahom);
                                        ?>

                                            <tr class='pb-2'>
                                                <td class='pr-3 pb-1'>Data Homologação:</td>
                                                <?php
                                                    // Verifica se tem DOC
                                                    $dochom = get_field( "doc_homologacao");
                                                    if($dochom):
                                                ?>
                                                    <td class='pb-1'><strong><a href="<?php echo $dochom; ?>">DOC - <?php echo $dtHomolog; ?></a></strong></td>
                                                <?php else: ?>
                                                    <td class='pb-1'><?php echo $dtHomolog; ?></td>
                                                <?php endif; ?>
                                            </tr>
                                        
                                        <?php endif; ?>

                                        <?php
                                            // Verifica data de Validade
                                            $validade = get_field("validade");
                                    
                                            if($validade):
                                            
                                            $dtValidade = convertDate($validade);
                                        ?>

                                            <tr class='pb-2'>
                                                <td class='pr-3 pb-1'>Validade:</td>
                                                <td class='pb-1'><?php echo $dtValidade; ?></td>                                                
                                            </tr>
                                        
                                        <?php endif; ?>                                        

                                        <?php
                                            // Verifica data Ultima Chamada
                                            $ultima_chamada = get_field("ultima_chamada");
                                            if($ultima_chamada):
                                            
                                            $dtUltima = convertDate($ultima_chamada);
                                        ?>

                                            <tr class='pb-2'>
                                                <td class='pr-3 pb-1'>Última Chamada:</td>
                                                <?php
                                                    // Verifica se tem DOC
                                                    $doc_ultima_chamada = get_field( "doc_ultima_chamada");
                                                    if($doc_ultima_chamada):
                                                ?>
                                                    <td class='pb-1'><strong><a href="<?php echo $doc_ultima_chamada; ?>">DOC - <?php echo $dtUltima; ?></a></strong></td>
                                                <?php else: ?>
                                                    <td class='pb-1'><?php echo $dtUltima; ?></td>
                                                <?php endif; ?>
                                            </tr>
                                        
                                        <?php endif; ?>

                                        <?php
                                            // Verifica Status
                                            $status = get_field("status");
                                            if($status):
                                        ?>

                                            <tr class='pb-2'>
                                                <td class='pr-3 pb-1'>Status:</td>
                                                <td class='pb-1'><?php echo get_field( "status"); ?></td>                                                
                                            </tr>
                                        
                                        <?php endif; ?> 

                                        
                                    </table>
                                    
                                </div>

                                

                                <div class="col-12 col-md-6 ultimos-convocados">
                                    <p><strong>Últimos Convocados</strong></p>
                                    <?php
                                        // Verifica Ultimos Convocados
                                        $ultimos_convocados = get_field("ultimos_convocados");

                                        if($ultimos_convocados):
                                            echo $ultimos_convocados;
                                        endif;
                                    ?> 

                                </div>
                            </div>
                           
                        <?php endwhile; ?>
                    <?php endif; ?>

                    </div>

                    <div class="shadow-sm col-md-10 p-3 mb-1 bg-white rounded concurso-legendas">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <p><strong>LEGENDA:</strong></p>
                                <?php echo get_field( "legenda_coluna_1", 31818); ?>
                            </div>
                            <div class="col-md-6">
                                <br>
                                <?php echo get_field( "legenda_coluna_1", 31818); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-10 mb-3 text-atualiza">
                        <p class="text-right">Ultima Atualização: <?php echo get_field( "ultima_atualizacao", 31818); ?></p>
                    </div>

                    <div class="col-md-10 mb-5 text-center">
                        <h3><a href="?view=lista">Ver todos os consursos SME</a></h3>              
                    </div>
                    
                </section>
            </section>
        </section>

         
        <?php get_footer(); ?>