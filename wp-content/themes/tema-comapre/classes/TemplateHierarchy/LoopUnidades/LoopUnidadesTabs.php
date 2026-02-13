<?php

namespace Classes\TemplateHierarchy\LoopUnidades;

class LoopUnidadesTabs extends LoopUnidades{

	public function __construct()
	{
		$this->tabUnidade();
	}

	public function tabUnidade(){
        $infoBasicas = get_field('informacoes_basicas');
        //echo "<pre>";
        //print_r($infoBasicas['horario']);
        //echo "</pre>";
    ?>

        <div class="container unidade-tab color-<?php echo $infoBasicas['zona_sp']; ?>">
        
                <button type="button" class="btn-submenu d-lg-none d-xl-none b-0" data-toggle="modal" data-target="#filtroBusca">
					<i class="fa fa-ellipsis-v" aria-hidden="true"></i> <span>Subpáginas</span>					
				</button>

				<hr class='d-lg-none d-xl-none'>

				<!-- Modal -->
				<div class="modal left fade" id="filtroBusca" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
					<div class="modal-dialog" role="document">
						<div class="modal-content">

							<div class="modal-header">
								<p class="modal-title" id="myModalLabel2">Subpáginas</p>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>				
							</div>

							<div class="modal-body">
                                <ul class="nav nav-tabs d-flex">
                                    <li class="active"><a data-toggle="tab" href="#programacao-ceu" class="active tab-mobile">Programação</a></li>
                                    <li><a data-toggle="tab" href="#servicos" class="tab-mobile">Serviços e Instalações</a></li>
                                    <li><a data-toggle="tab" href="#sobre" class="tab-mobile">Sobre a Unidade </a></li>
                                    <li><a data-toggle="tab" href="#chegar" class="tab-mobile">Como Chegar</a></li>
                                </ul>
							</div>

						</div><!-- modal-content -->
					</div><!-- modal-dialog -->
				</div><!-- modal -->

            <ul class="nav nav-tabs d-none d-lg-flex">
                <li class="active"><a data-toggle="tab" href="#programacao-ceu" class="active">Programação</a></li>
                <li><a data-toggle="tab" href="#servicos">Serviços e Instalações</a></li>
                <li><a data-toggle="tab" href="#sobre">Sobre a Unidade </a></li>
                <li><a data-toggle="tab" href="#chegar">Como Chegar</a></li>
            </ul>

            <div class="tab-content">

                <div id="programacao-ceu" class="tab-pane fade in active show">
                    <p class="d-lg-none d-xl-none mob-tab-title">PROGRAMAÇÃO</p>
                    <p class='unidade-title'>Confira a programação no <?php echo get_the_title(); ?></p>

                    <div class="search-home py-4" id='programacao'>
                        <div class="container">
                            
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    
                                    <?php 
                                        
                                        // Atividades
                                        $terms = get_terms( array( 
                                            'taxonomy' => 'atividades_categories',
                                            'parent'   => 0,                                
                                            'hide_empty' => false
                                        ) );

                                        // Publico Alvo
                                        $publicos = get_terms( array( 
                                            'taxonomy' => 'publico_categories',
                                            'parent'   => 0,                                
                                            'hide_empty' => false
                                        ) );

                                        // Faixa Etaria
                                        $faixas = get_terms( array( 
                                            'taxonomy' => 'faixa_categories',
                                            //'parent'   => 0,                                
                                            'hide_empty' => false
                                        ) );

                                        // Unidades
                                        $unidades = get_terms( array( 
                                            'taxonomy' => 'category',
                                            'parent'   => 0,                                
                                            'hide_empty' => false,
                                            'exclude' => 1
                                        ) );

                                        // Periodo
                                        $periodos = get_terms( array( 
                                            'taxonomy' => 'periodo_categories',
                                            'parent'   => 0,                                
                                            'hide_empty' => false,
                                            'exclude' => 1
                                        ) );
                                        
                                    ?>
                                </div>
                                
                                <div class="col-12">
                                    <form action="<?php echo home_url( '/' ); ?>"  id="searchform" class="row form-prog">
                                        <input id="prodId" name="tipo" type="hidden" value="programacao">
                                        <?php if( isset($_GET['tipo_atividade']) && $_GET['tipo_atividade'] != '' ): ?>
                                            <input name="tipo_atividade" type="hidden" value="<?= $_GET['tipo_atividade']; ?>">
                                        <?php endif; ?>

                                        <div class="col-sm-6 mt-3">
                                            <input type="text" name="s" class="form-control" placeholder="Busque por palavra-chave" value="<?= $_GET['s']; ?>">
                                        </div>

                                        <div class="col-sm-6 mt-3">
                                            <select name="atividades[]" multiple="multiple" class="ms-list-1" style="">
                                                <?php if ( !empty( $terms ) && !is_wp_error( $terms ) ): ?>
                                                    <?php foreach( get_terms( 'atividades_categories', array( 'hide_empty' => false, 'parent' => 0 ) ) as $parent_term ) : ?>
                                                        <?php
                                                            $term_children = get_term_children($parent_term->term_id, 'atividades_categories');
                                                            if($term_children):
                                                        ?>
                                                            <optgroup label="<?= $parent_term->name; ?>">
                                                                <?php foreach($term_children as $term): 
                                                                    $categoria = get_term( $term, 'atividades_categories' );
                                                                ?>
                                                                    <?php if(isset($_GET['atividades']) && is_array($_GET['atividades']) && in_array($categoria->slug, $_GET['atividades'])) :?>
                                                                        <option value="<?= $categoria->slug; ?>" selected><?= $categoria->name; ?></option>
                                                                    <?php else: ?>
                                                                        <option value="<?= $categoria->slug; ?>"><?= $categoria->name; ?></option>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </optgroup>
                                                        <?php else: ?>
                                                            <option value="<?= $parent_term->slug; ?>"><?= $parent_term->name; ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>                                                   
                                            </select>
                                        </div>

                                        <div class="col-sm-6 mt-3">
                                            <select name="faixaEtaria[]" multiple="multiple" class="ms-list-4" style="">                        
                                                <?php if ( !empty( $faixas ) && !is_wp_error( $faixas ) ): ?>
                                                    <?php foreach( get_terms( 'faixa_categories', array( 'hide_empty' => false, 'parent' => 0 ) ) as $parent_term ) : ?>
                                                        <?php
                                                            $term_children = get_term_children($parent_term->term_id, 'faixa_categories');
                                                            if($term_children):
                                                        ?>
                                                            <optgroup label="<?= $parent_term->name; ?>">
                                                                <?php foreach($term_children as $term): 
                                                                    $faixa_etaria = get_term( $term, 'faixa_categories' );
                                                                ?>
                                                                    <?php if(in_array($faixa_etaria->slug, $_GET['faixaEtaria'])) :?>                                                    	
                                                                        <option value="<?= $faixa_etaria->slug; ?>" selected><?= $faixa_etaria->name; ?></option>
                                                                    <?php else: ?>
                                                                        <option value="<?= $faixa_etaria->slug; ?>"><?= $faixa_etaria->name; ?></option>
                                                                    <?php endif; ?>
                                                                    
                                                                <?php endforeach; ?>
                                                            </optgroup>
                                                        <?php else: ?>
                                                            <option value="<?= $parent_term->slug; ?>"><?= $parent_term->name; ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>                      
                                            </select>
                                        </div>

                                        <div class="col-sm-3 mt-3">
                                            <select name="publico[]" multiple="multiple" class="ms-list-3" style="">                        
                                                <?php foreach ($publicos as $publico): ?>
                                                    <?php if(in_array($publico->slug, $_GET['publico'])) :?>										
                                                        <option value="<?php echo $publico->slug; ?>" selected><?php echo $publico->name; ?></option>
                                                    <?php else: ?>
                                                        <option value="<?php echo $publico->slug; ?>"><?php echo $publico->name; ?></option>
                                                    <?php endif; ?>                                    
                                                <?php endforeach; ?>                    
                                            </select>
                                        </div>

                                        <div class="col-sm-3 mt-3">
                                            <select name="unidades[]" multiple="multiple" class="ms-list-5" style="">
                                                <?php
                                                        $currentID = get_the_id();
                                                        $argsUnidades = array(
                                                            'post_type' => 'unidade',
                                                            'posts_per_page' => -1,
                                                            'post__not_in' => array(31675, 31244),
                                                            'orderby' => 'title',
                                                            'order'   => 'ASC',
                                                            'post_status' => array('publish', 'pending'),
                                                        );

                                                        $todasUnidades = new \WP_Query( $argsUnidades );
                                                        $atual = get_the_ID();
                                
                                                        // The Loop
                                                        if ( $todasUnidades->have_posts() ) {
                                                            
                                                            while ( $todasUnidades->have_posts() ) {
                                                                $todasUnidades->the_post();

                                                                $titulo = htmlentities(get_the_title());
                                                                $seletor = explode (" &amp;", $titulo);

                                                                if( in_array( get_the_ID(), $_GET['unidades']) || get_the_ID() == $atual) {
                                                                    echo '<option selected value="' . get_the_id() .'">' . $seletor[0] .'</option>';
                                                                } else {
                                                                    echo '<option value="' . get_the_id() .'">' . $seletor[0] .'</option>';
                                                                }
                                                                
                                                            }
                                                        
                                                        }
                                                        wp_reset_postdata();
                                                    ?>      
                                            </select>
                                        </div>

                                        <div class="col-sm-6 mt-3">
                                            <div id='date-range'>
                                                <div class="input-daterange input-group" id="datepicker">
                                                    <input type="text" class="input-sm form-control" name="start" value="<?= $_GET['start']; ?>" placeholder="Data" />
                                                    <span class="input-group-addon px-2">Até</span>
                                                    <input type="text" class="input-sm form-control" name="end" value="<?= $_GET['end']; ?>" placeholder="Data" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-3 mt-3">
                                            <select name="data[]" multiple="multiple" class="ms-list-9" style=""> 
                                                <optgroup label="Dia da semana">
                                                    <option value="segunda" <?= in_array('segunda', $_GET['data']) ? "selected" : ""; ?>>Segunda</option>
                                                    <option value="terca" <?= in_array('terca', $_GET['data']) ? "selected" : ""; ?>>Terça</option>
                                                    <option value="quarta" <?= in_array('quarta', $_GET['data']) ? "selected" : ""; ?>>Quarta</option>
                                                    <option value="quinta" <?= in_array('quinta', $_GET['data']) ? "selected" : ""; ?>>Quinta</option>
                                                    <option value="sexta" <?= in_array('sexta', $_GET['data']) ? "selected" : ""; ?>>Sexta</option>
                                                    <option value="sabado" <?= in_array('sabado', $_GET['data']) ? "selected" : ""; ?>>Sábado</option>
                                                    <option value="domingo" <?= in_array('domingo', $_GET['data']) ? "selected" : ""; ?>>Domingo</option>
                                                </optgroup>
                                            </select>
                                        </div>

                                        <div class="col-sm-3 mt-3">
                                            <select name="periodos[]" multiple="multiple" class="ms-list-8" style="">                        
                                                <option value='manha' <?= in_array('manha', $_GET['periodos']) ? "selected" : ""; ?>>Manhã</option>
                                                <option value='tarde' <?= in_array('tarde', $_GET['periodos']) ? "selected" : ""; ?>>Tarde</option>
                                                <option value='noite' <?= in_array('noite', $_GET['periodos']) ? "selected" : ""; ?>>Noite</option>                        
                                            </select>
                                        </div>

                                        <div class="col-sm-12 text-right mt-3">
                                            <a href="<?= get_home_url();?>/programacao" class="btn btn-outline-primary mr-3">Limpar busca</a>
                                            <button type="submit" class="btn btn-search">Buscar</button>
                                        </div>

                                    </form>
                                </div>

                            </div> <!-- end row -->
                        </div>
                    </div>
                    
                    <?php if( isset($_GET['tipo_atividade']) && $_GET['tipo_atividade'] != ''): ?>
                        <div class="unidade-eventos">

                            <?php
                                $id = get_the_ID();
                                $today = date('Ymd');
                                $title = '';

                                if($_GET['tipo_atividade'] == 'proxima'){
                                    $title = 'Próximas Atividades';
                                    $rd_args = array(
                                        'post_type' => 'post',
                                        'posts_per_page' => 16,
                                        'paged' => $paged,
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'relation' => 'AND',
                                                array(
                                                    'relation' => 'OR',
                                                    array(
                                                        'key'     => 'data_data',
                                                        'compare' => '>=', // depois ou igual a data de hoje
                                                        //'compare' => '<', // antes da data de hoje
                                                        'value'   => $today,
                                                    ),
                                                    array(
                                                        'key'     => 'data_data_final',
                                                        'compare' => '>=', // depois ou igual a data de hoje
                                                        //'compare' => '<', // antes da data de hoje
                                                        'value'   => $today,
                                                    ),
                                                    array(
                                                        'key'     => 'ceus_participantes_$_data_serie_data',
                                                        'compare' => '>=', // depois ou igual a data de hoje
                                                        //'compare' => '<', // antes da data de hoje
                                                        'value'   => $today,
                                                    ),
                                                ),
                                                array(
                                                    'relation' => 'OR',
                                                    array(
                                                        'key' => 'localizacao',
                                                        'value' => $id
                                                    ),
                                                    array(
                                                        'key' => 'localizacao',
                                                        'value' => 31675
                                                    ),
                                                    array(
                                                        'key' => 'ceus_participantes_$_localizacao_serie',
                                                        'value' => $id
                                                    ),
                                                )                                   
                                                
                                            ),                                                                             
                                        )
                                    );
                                } elseif($_GET['tipo_atividade'] == 'permanente'){
                                    $title = 'Atividades Permanentes';
                                    $rd_args = array(
                                        'posts_per_page' => 16,
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'relation' => 'OR',
                                                array(
                                                    'key' => 'localizacao',
                                                    'value' => $id
                                                ),
                                                array(
                                                    'key' => 'localizacao',
                                                    'value' => 31675
                                                ),
                                                array(
                                                    'key' => 'localizacao',
                                                    'value' => 31224
                                                ),
                                                array(
                                                    'key' => 'ceus_participantes_$_localizacao_serie',
                                                    'value' => $id
                                                ),
                                            ),                          
                                            array(
                                                'key'   => 'data_tipo_de_data',
                                                'value' => 'semana',
                                            ),                            
                                        )
                                    );
                                } else {
                                    $title = 'Atividades Encerradas';
                                    $rd_args = array(
                                        'post_type' => 'post',
                                        'posts_per_page' => 16,
                                        'paged' => $paged,
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'relation' => 'AND',
                                                array(
                                                    'relation' => 'OR',
                                                    array(
                                                        'key'     => 'data_data',
                                                        //'compare' => '>=', // depois ou igual a data de hoje
                                                        'compare' => '<', // antes da data de hoje
                                                        'value'   => $today,
                                                    ),
                                                    array(
                                                        'key'     => 'data_data_final',
                                                        //'compare' => '>=', // depois ou igual a data de hoje
                                                        'compare' => '<', // antes da data de hoje
                                                        'value'   => $today,
                                                    ), 
                                                ),
                                                array(
                                                    'relation' => 'OR',
                                                    array(
                                                        'key' => 'localizacao',
                                                        'value' => $id
                                                    ),
                                                    array(
                                                        'key' => 'localizacao',
                                                        'value' => 31675
                                                    ),
                                                    array(
                                                        'key' => 'ceus_participantes_$_localizacao_serie',
                                                        'value' => $id
                                                    ),
                                                )                                   
                                                
                                            ),
                                            array(
                                                'key'   => 'tipo_de_evento_tipo',
                                                'value' => 'serie',
                                                'compare' => '!='
                                            ),
                                            array(
                                                'key'   => 'data_tipo_de_data',
                                                'value' => 'semana',
                                                'compare' => '!='
                                            ),                                    
                                        )
                                    );
                                }
                            ?>

                            <div class="title-ativi">
                                <h2><?= $title; ?></h2>
                                <hr>
                                <a href="<?= get_the_permalink(); ?>">Voltar ao Início</a>
                            </div>

                            <?php
                                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                                $today = date('Ymd');
                                $id = get_the_ID();
                                

                                $rd_query = new \WP_Query( $rd_args );

                                // The Loop
                                if ( $rd_query->have_posts() ) {
                                    echo '<div class="tema-eventos my-4">';
                                    echo '<div class="container p-0">';
                                    echo '<div class="row">';
                                        while ( $rd_query->have_posts() ) {
                                            $rd_query->the_post();
                                            $eventoID = get_the_id();
                                        ?>
                                            <div class="col-sm-3">
                                                <div class="card-eventos mb-3">
                                                    <div class="card-eventos-img">
                                                        <?php 
                                                            $imgSelect = get_field('capa_do_evento', $eventoID);
                                                            $tipo = get_field('tipo_de_evento_selecione_o_evento', $eventoID);
                                                            $online = get_field('tipo_de_evento_online', $eventoID);
                                                            $tipoEvento = get_field('tipo_de_evento_tipo', $eventoID);

                                                            $featured_img_url = wp_get_attachment_image_src($imgSelect, 'recorte-eventos');
                                                            if($featured_img_url){
                                                                $imgEvento = $featured_img_url[0];
                                                                //$thumbnail_id = get_post_thumbnail_id( $eventoID );
                                                                $alt = get_post_meta($imgSelect, '_wp_attachment_image_alt', true);  
                                                            } else {
                                                                $imgEvento = get_template_directory_uri().'/img/placeholder_portal_ceus.jpg';
                                                                $alt = get_the_title($eventoID);
                                                            }
                                                        ?>
                                                        <a href="<?= get_the_permalink(); ?>"><img src="<?php echo $imgEvento; ?>" class="img-fluid d-block" alt="<?php echo $alt; ?>"></a>
                                                        
                                                        <?php if($tipo && $tipo != '') : 
                                                            echo '<span class="flag-pdf-full">';
                                                                echo get_the_title($tipo);
                                                            echo '</span>';
                                                        endif; ?>
                                                        <?php if($online && $online != '') : 
                                                            if($tipo && $tipo != ''){
                                                                $customClass = 'mt-tags';
                                                            }
                                                            echo '<span class="flag-pdf-full ' . $customClass . '">';
                                                                echo "Evento on-line";
                                                            echo '</span>';
                                                        endif; ?>
                                                    </div>
                                                    <div class="card-eventos-content p-2">
                                                    <div class="evento-categ border-bottom pb-1">
                                                            <?php
                                                                    $atividades = get_the_terms( get_the_id(), 'atividades_categories' );
                                                                    
                                                                    $listaAtividades = array();

                                                                    $atividadesTotal = count($atividades);

                                                                    if($atividadesTotal > 1){
                                                                        foreach($atividades as $atividade){
                                                                            if($atividade->parent != 0){
                                                                                $listaAtividades[] = $atividade->term_id;
                                                                            } 
                                                                        }
                                                                    } else {
                                                                        $listaAtividades[] = $atividades[0]->term_id;
                                                                    }

                                                                    $total = count($listaAtividades); 
                                                                    $k = 0;
                                                                    $showAtividades = '';

                                                                    foreach($listaAtividades as $atividade){
                                                                        $k++;
                                                                        if($k == 1){
                                                                            $showAtividades .= '<a href="' . get_home_url() . '?s=&atividades[]=' . get_term( $atividade )->slug . '">' . get_term( $atividade )->name . "</a>";
                                                                        } else {
                                                                            $showAtividades .= ' ,<a href="' . get_home_url() . '?s=&atividades[]=' . get_term( $atividade )->slug . '">' . get_term( $atividade )->name . "</a>";
                                                                        }
                                                                    }
                                                                ?>
                                                            <?php echo $showAtividades; ?>
                                                            
                                                        </div>
                                                        <p class='unidade-title'><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></p>
                                                        <?php
                                                            
                                                            $campos = get_field('data', $eventoID);
                                                            
                                                            // Verifica se possui campos
                                                            if($campos){
                
                                                                //print_r($campos);
                
                                                                if($campos['tipo_de_data'] == 'data'){ // Se for do tipo data
                                                                    
                                                                    $dataEvento = $campos['data'];
                
                                                                    if($dataEvento){
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);
                                                                        $data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];

                                                                        $dataFinal = $data;
                                                                        
                                                                    } else {
                                                                        $dataEvento = get_the_date('Y-m-d');
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);                                                                        
                                                                        $dataFinal = $mes;
                                                                    }
                
                                                                } elseif($campos['tipo_de_data'] == 'semana'){ // se for do tipo semana
                                                                    
                                                                    $semana = $campos['dia_da_semana'];													
                                                    
                                                                    $diasSemana = array();

                                                                    foreach($semana as $dias){

                                                                        $total = count($dias['selecione_os_dias']); 
                                                                        $i = 0;
                                                                        $diasShow = '';
                                                                        
                                                                        foreach($dias['selecione_os_dias'] as $diaS){
                                                                            $i++;
                                                                            //echo $dia . "<br>";
                                                                            if($total - $i == 1){
                                                                                $diasShow .= $diaS . " ";
                                                                            } elseif($total != $i){
                                                                                $diasShow .= $diaS . ", ";
                                                                            } elseif($total == 1){
                                                                                $diasShow = $diaS;
                                                                            } else {
                                                                                $diasShow .= "e " . $diaS;
                                                                            }	
                                                                                                                                    
                                                                        }

                                                                        $show[] = $diasShow;
                                                                        
                                                                    }
                                                                    
                                                                    $totalDias = count($show);
                                                                    $j = 0;	
                                                                    
                                                                    $dias = '';

                                                                    foreach($show as $diaShow){
                                                                        $j++;
                                                                        if($j == 1){
                                                                            $dias .= $diaShow . " ";                                                        
                                                                        } else {
                                                                            $dias .= "/ " . $diaShow;
                                                                        }
                                                                    }

                                                                    if(!$dias){
                                                                        $dataEvento = get_the_date('Y-m-d');
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);                                                                        
                                                                        $dias = $mes;
                                                                    }

                                                                    $dataFinal = $dias;  
                
                                                                    $dias = '';
                                                                    $show = array();
                                                                    
                                                                } elseif($campos['tipo_de_data'] == 'periodo'){
                                                                    
                                                                    $dataInicial = $campos['data'];
                                                                    $dataFinal = $campos['data_final'];
                
                                                                    if($dataFinal){ // Verifica se possui a data final
                                                                        $dataInicial = explode("-", $dataInicial);
                                                                        $dataFinal = explode("-", $dataFinal);
                                                                        if($dataInicial[1] != $dataFinal[1]){
                                                                            $mesIni = date('M', mktime(0, 0, 0, $dataInicial[1], 10));
                                                                            $mesIni = translateMonth($mesIni);
                
                                                                            $mesFinal = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                            $mesFinal = translateMonth($mesFinal);
                
                                                                            $data = $dataInicial[2] . ' '. $mesIni . " a " .  $dataFinal[2] . " " . $mesFinal . " " . $dataFinal[0];
                                                                        } else {
                                                                            $mes = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                            $mes = translateMonth($mes);
                
                                                                            $data = $dataInicial[2] . " a " .  $dataFinal[2] . " " . $mes . " " . $dataFinal[0];
                                                                        }
                
                                                                        $dataFinal = $data;
                                                                    } else { // Se nao tiver a final mostra apenas a inicial
                                                                        if($dataInicial){
                                                                            $dataEvento = explode("-", $dataInicial);
                                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                            $mes = translateMonth($mes);
                                                                            $data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];
                
                                                                            $dataFinal = $data;
                                                                            
                                                                        } else {
                                                                            $dataEvento = get_the_date('Y-m-d');
                                                                            $dataEvento = explode("-", $dataEvento);
                                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                            $mes = translateMonth($mes);                                                                        
                                                                            $dataFinal = $mes;
                                                                        }
                                                                    }
                
                                                                }
                
                                                            }

                                                            if($tipoEvento == 'serie'){
                                                                $participantes = get_field('ceus_participantes',  $eventoID);
                                                                $countPart = count($participantes);
                                                                $countPart = $countPart - 1;
                                                                
                                                                $dtInicial = $participantes[0]['data_serie'];
                                                                $dtFinal = $participantes[$countPart]['data_serie'];

                                                                if($dtInicial['tipo_de_data'] == 'data' && $dtFinal['tipo_de_data'] == 'data'){
                                                                    
                                                                    $dataInicial = explode("-", $dtInicial['data']);
                                                                    $dataFinal = explode("-", $dtFinal['data']);
                                                                    $mes = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                    $mes = translateMonth($mes);
            
                                                                    $data = $dataInicial[2] . " a " .  $dataFinal[2] . " " . $mes . " " . $dataFinal[0];
            
                                                                    $dataFinal = $data;

                                                                } else {
                                                                    $dataFinal = 'Múltiplas Datas';
                                                                }

                                                                
                                                                
                                                            }
                                                        ?>
                                                        <p class="mb-0">
                                                            <i class="fa fa-calendar" aria-hidden="true"><span>icone calendario</span></i> <?php echo $dataFinal; ?>
                                                            <br>
                                                            <?php
                                                                // Exibe os horários
                                                                $horario = get_field('horario', $eventoID);

                                                                

                                                                if($horario['selecione_o_horario'] == 'horario'){
                                                                    $hora = $horario['hora'];
                                                                } elseif($horario['selecione_o_horario'] == 'periodo'){
                                                                    
                                                                    $hora = '';
                                                                    $k = 0;
                                                                    
                                                                    foreach($horario['hora_periodo'] as $periodo){
                                                                        //print_r($periodo['periodo_hora_final']);
                                                                        
                                                                        if($periodo['periodo_hora_inicio']){

                                                                            if($k > 0){
                                                                                $hora .= ' / ';
                                                                            }

                                                                            $hora .= $periodo['periodo_hora_inicio'];

                                                                        } 
                                                                        
                                                                        if ($periodo['periodo_hora_final']){

                                                                            $hora .= ' às ' . $periodo['periodo_hora_final'];

                                                                        }
                                                                        
                                                                        $k++;
                                                                        
                                                                    }

                                                                }else {
                                                                    $hora = '';
                                                                }
                                                            ?>
                                                            <?php if($hora && $tipoEvento != 'serie') : ?>                                           
                                                                <i class="fa fa-clock-o" aria-hidden="true"><span>icone horario</span></i> <?php echo convertHour($hora); ?>
                                                            <?php endif; ?>
                                                            <?php if($tipoEvento == 'serie'): ?>
                                                                <i class="fa fa-clock-o" aria-hidden="true"><span>icone horario</span></i> Múltiplos Horários
                                                            <?php endif; ?>
                                                        </p>
                                                        <?php
                                                            $local = get_field('localizacao', $eventoID);                                                        
                                                            if($local == '31675' || $local == '31244'):
                                                        ?>
                                                            <p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></p>
                                                        <?php elseif($tipoEvento == 'serie') : ?>
                                                            <p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> Múltiplas Unidades</p>
                                                        <?php else: ?>
                                                            <p class="mb-0 mt-1 evento-unidade"><a href="<?php echo get_the_permalink($local); ?>"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></a></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                        </div>

                                        <?php
                                        }
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                } else {
                                
                                }
                                /* Restore original Post Data */
                                
                                wp_reset_postdata();

                                
                            ?>
                        </div>

                        <div class="container my-5">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="pagination-prog text-center">
                                        <?php wp_pagenavi( array( 'query' => $rd_query ) ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>

                        <div class="">                            
                            <?php
                                $today = date('Ymd');
                                $id = get_the_ID();
                                // Proximas Atividades
                                $args_next = array(
                                    'posts_per_page' => 8,
                                    'orderby'   => 'meta_value_num',
                                    'order'     => 'ASC',
                                    'meta_query' => array(
                                        //'relation' => 'AND',
                                        array(
                                            'relation' => 'AND',
                                            array(
                                                'relation' => 'OR',
                                                array(
                                                    'key'     => 'data_data',
                                                    'compare' => '>=', // depois ou igual a data de hoje
                                                    //'compare' => '<', // antes da data de hoje
                                                    'value'   => $today,
                                                ),
                                                array(
                                                    'key'     => 'data_data_final',
                                                    'compare' => '>=', // depois ou igual a data de hoje
                                                    //'compare' => '<', // antes da data de hoje
                                                    'value'   => $today,
                                                ),
                                                array(
                                                    'key'     => 'ceus_participantes_$_data_serie_data',
                                                    'compare' => '>=', // depois ou igual a data de hoje
                                                    //'compare' => '<', // antes da data de hoje
                                                    'value'   => $today,
                                                ),
                                            ),
                                            array(
                                                'relation' => 'OR',
                                                array(
                                                    'key' => 'localizacao',
                                                    'value' => $id
                                                ),
                                                array(
                                                    'key' => 'localizacao',
                                                    'value' => 31675
                                                ),
                                                array(
                                                    'key' => 'ceus_participantes_$_localizacao_serie',
                                                    'value' => $id
                                                ),
                                            )                                   
                                            
                                        ),
                                                                          
                                    )
                                );
                                wp_enqueue_style('slick_css');
                                wp_enqueue_style('slick_theme_css');		
                                wp_enqueue_script('slick_min_js');
                                wp_enqueue_script('slick_func_js');
                                wp_enqueue_script('lightbox_js');

                                // The Query
                                $the_query = new \WP_Query( $args_next );

                                // The Loop
                                if ( $the_query->have_posts() ) {

                                    echo '<div class="title-ativi">';
                                        echo '<h2>Próximas Atividades</h2><hr>';                                    
                                        echo '<a href="' . get_home_url() . '/?s=&tipo_atividade=proxima&unidades[]=' . get_the_ID() . '">Ver todas</a>';
                                    echo '</div>';

                                    echo '<div class="row-slide mb-5" style="margin-left: -15px; margin-right: -15px;">';

                                        echo '<section class="regular slider">';

                                            while ( $the_query->have_posts() ) {
                                                $the_query->the_post();

                                                $tipo_data = get_field('data_tipo_de_data');
                                                $data = get_field('data_data');
                                                
                                            ?>
                                                <div class="card-eventos">
                                                    <div class="card-eventos-img">
                                                        <?php 
                                                            //$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'recorte-unidades');
                                                            $imgSelect = get_field('capa_do_evento', get_the_ID());
                                                            $tipo = get_field('tipo_de_evento_selecione_o_evento', get_the_ID());
                                                            $online = get_field('tipo_de_evento_online', get_the_ID());
                                                            $tipoEvento = get_field('tipo_de_evento_tipo', get_the_ID());

                                                            $featured_img_url = wp_get_attachment_image_src($imgSelect, 'recorte-eventos');
                                                            if($featured_img_url){
                                                                $imgEvento = $featured_img_url[0];
                                                                //$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                                                                $alt = get_post_meta($imgSelect, '_wp_attachment_image_alt', true);  
                                                            } else {
                                                                $imgEvento = get_template_directory_uri().'/img/placeholder_portal_ceus.jpg';
                                                                $alt = get_the_title(get_the_ID());
                                                            }
                                                        ?>
                                                        <a href="<?php echo get_the_permalink(get_the_ID()); ?>"><img src="<?php echo $imgEvento; ?>" class="img-fluid d-block" alt="<?php echo $alt; ?>"></a>
                                                        <?php if($tipo && $tipo != '') : 
                                                            echo '<span class="flag-pdf-full">';
                                                                echo get_the_title($tipo);
                                                            echo '</span>';                                           
                                                        endif; ?>                                      

                                                        <?php if($online && $online != '') : 
                                                            if($tipo && $tipo != ''){
                                                                $customClass = 'mt-tags';
                                                            }
                                                            echo '<span class="flag-pdf-full ' . $customClass . '">';
                                                                echo "Evento on-line";
                                                            echo '</span>';
                                                        endif; ?>
                                                    </div>
                                                    <div class="card-eventos-content p-2">
                                                        <div class="evento-categ border-bottom pb-1">
                                                            <?php
                                                                $atividades = get_the_terms( get_the_ID(), 'atividades_categories' );
                                                                $listaAtividades = array();

                                                                $atividadesTotal = count($atividades);

                                                                if($atividadesTotal > 1){
                                                                    foreach($atividades as $atividade){
                                                                        if($atividade->parent != 0){
                                                                            $listaAtividades[] = $atividade->term_id;
                                                                        } 
                                                                    }
                                                                } else {
                                                                    $listaAtividades[] = $atividades[0]->term_id;
                                                                }

                                                                $total = count($listaAtividades); 
                                                                $k = 0;
                                                                $showAtividades = '';

                                                                foreach($listaAtividades as $atividade){
                                                                    $k++;
                                                                    if($k == 1){
                                                                        $showAtividades .= '<a href="' . get_home_url() . '?s=&atividades[]=' . get_term( $atividade )->slug . '">' . get_term( $atividade )->name . "</a>";
                                                                    } else {
                                                                        $showAtividades .= ' ,<a href="' . get_home_url() . '?s=&atividades[]=' . get_term( $atividade )->slug . '">' . get_term( $atividade )->name . "</a>";
                                                                    }
                                                                }
                                                            ?>
                                                            <?php echo $showAtividades; ?>
                                                        </div>
                                                        <h3><a href="<?php echo get_the_permalink(get_the_ID()); ?>"><?php echo get_the_title(); ?></a></h3>
                                                        <?php
                                                            $campos = get_field('data', get_the_ID());
                                                            
                                                            // Verifica se possui campos
                                                            if($campos){

                                                                //print_r($campos);

                                                                if($campos['tipo_de_data'] == 'data'){ // Se for do tipo data
                                                                    
                                                                    $dataEvento = $campos['data'];

                                                                    if($dataEvento){
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);
                                                                        $data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];

                                                                        $dataFinal = $data;
                                                                        
                                                                    } else {
                                                                        $dataEvento = get_the_date('Y-m-d');
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);                                                                        
                                                                        $dataFinal = $mes;
                                                                    }

                                                                } elseif($campos['tipo_de_data'] == 'semana'){ // se for do tipo semana
                                                                    
                                                                    $semana = $campos['dia_da_semana'];
                                                                    $diasSemana = array();
                                                                    $show = array();

                                                                    foreach($semana as $dias){

                                                                        $total = count($dias['selecione_os_dias']); 
                                                                        $i = 0;
                                                                        $diasShow = '';
                                                                        
                                                                        foreach($dias['selecione_os_dias'] as $diaS){
                                                                            $i++;
                                                                            //echo $dia . "<br>";
                                                                            if($total - $i == 1){
                                                                                $diasShow .= $diaS . " ";
                                                                            } elseif($total != $i){
                                                                                $diasShow .= $diaS . ", ";
                                                                            } elseif($total == 1){
                                                                                $diasShow = $diaS;
                                                                            } else {
                                                                                $diasShow .= "e " . $diaS;
                                                                            }	
                                                                                                                                    
                                                                        }

                                                                        $show[] = $diasShow;
                                                                        
                                                                    }
                                                                    
                                                                    $totalDias = count($show);
                                                                    $j = 0;	
                                                                    
                                                                    $dias = '';

                                                                    foreach($show as $diaShow){
                                                                        $j++;
                                                                        if($j == 1){
                                                                            $dias .= $diaShow . " ";                                                        
                                                                        } else {
                                                                            $dias .= "/ " . $diaShow;
                                                                        }
                                                                    }

                                                                    if(!$dias){
                                                                        $dataEvento = get_the_date('Y-m-d');
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);                                                                        
                                                                        $dias = $mes;
                                                                    }

                                                                    $dataFinal = $dias; 

                                                                    $dias = '';
                                                                    $show = '';
                                                                    
                                                                } elseif($campos['tipo_de_data'] == 'periodo'){
                                                                    
                                                                    $dataInicial = $campos['data'];
                                                                    $dataFinal = $campos['data_final'];

                                                                    if($dataFinal){ // Verifica se possui a data final
                                                                        $dataInicial = explode("-", $dataInicial);
                                                                        $dataFinal = explode("-", $dataFinal);
                                                                        if($dataInicial[1] != $dataFinal[1]){
                                                                            $mesIni = date('M', mktime(0, 0, 0, $dataInicial[1], 10));
                                                                            $mesIni = translateMonth($mesIni);
                
                                                                            $mesFinal = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                            $mesFinal = translateMonth($mesFinal);
                
                                                                            $data = $dataInicial[2] . ' '. $mesIni . " a " .  $dataFinal[2] . " " . $mesFinal . " " . $dataFinal[0];
                                                                        } else {
                                                                            $mes = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                            $mes = translateMonth($mes);
                
                                                                            $data = $dataInicial[2] . " a " .  $dataFinal[2] . " " . $mes . " " . $dataFinal[0];
                                                                        }

                                                                        $dataFinal = $data;
                                                                    } else { // Se nao tiver a final mostra apenas a inicial
                                                                        if($dataInicial){
                                                                            $dataEvento = explode("-", $dataInicial);
                                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                            $mes = translateMonth($mes);
                                                                            $data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];
                
                                                                            $dataFinal = $data;
                                                                            
                                                                        } else {
                                                                            $dataEvento = get_the_date('Y-m-d');
                                                                            $dataEvento = explode("-", $dataEvento);
                                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                            $mes = translateMonth($mes);                                                                        
                                                                            $dataFinal = $mes;
                                                                        }
                                                                    }

                                                                }

                                                            }

                                                            if($tipoEvento == 'serie'){
                                                                $participantes = get_field('ceus_participantes',  get_the_ID());
                                                                $countPart = count($participantes);
                                                                $countPart = $countPart - 1;
                                                                
                                                                $dtInicial = $participantes[0]['data_serie'];
                                                                $dtFinal = $participantes[$countPart]['data_serie'];

                                                                if($dtInicial['tipo_de_data'] == 'data' && $dtFinal['tipo_de_data'] == 'data'){
                                                                    
                                                                    $dataInicial = explode("-", $dtInicial['data']);
                                                                    $dataFinal = explode("-", $dtFinal['data']);
                                                                    $mes = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                    $mes = translateMonth($mes);

                                                                    $data = $dataInicial[2] . " a " .  $dataFinal[2] . " " . $mes . " " . $dataFinal[0];

                                                                    $dataFinal = $data;

                                                                } else {
                                                                    $dataFinal = 'Múltiplas Datas';
                                                                }											
                                                            }
                                                        ?>
                                                        <p class="mb-0">
                                                            <i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $dataFinal; ?>
                                                            <br>
                                                            <?php
                                                            // Exibe os horários
                                                                        $horario = get_field('horario', get_the_ID());

                                                                        

                                                                        if($horario['selecione_o_horario'] == 'horario'){
                                                                            $hora = $horario['hora'];
                                                                        } elseif($horario['selecione_o_horario'] == 'periodo'){
                                                                            
                                                                            $hora = '';
                                                                            $k = 0;
                                                                            
                                                                            foreach($horario['hora_periodo'] as $periodo){
                                                                                //print_r($periodo['periodo_hora_final']);
                                                                                
                                                                                if($periodo['periodo_hora_inicio']){

                                                                                    if($k > 0){
                                                                                        $hora .= ' / ';
                                                                                    }

                                                                                    $hora .= $periodo['periodo_hora_inicio'];

                                                                                } 
                                                                                
                                                                                if ($periodo['periodo_hora_final']){

                                                                                    $hora .= ' às ' . $periodo['periodo_hora_final'];

                                                                                }
                                                                                
                                                                                $k++;
                                                                                
                                                                            }

                                                                        }else {
                                                                            $hora = '';
                                                                        }
                                                            ?>
                                                            <?php if($hora) : ?>                                           
                                                                <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo convertHour($hora); ?>
                                                            <?php endif; ?>
                                                            <?php if($tipoEvento == 'serie'): ?>
                                                                <i class="fa fa-clock-o" aria-hidden="true"><span>icone horario</span></i> Múltiplos Horários
                                                            <?php endif; ?>
                                                        </p>
                                                        <?php
                                                            $local = get_field('localizacao', get_the_ID());                                                        
                                                            if($local == '31675' || $local == '31244'):
                                                        ?>
                                                            <p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></p>
                                                        <?php elseif($tipoEvento == 'serie') : ?>
                                                            <p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> Múltiplas Unidades</p>
                                                        <?php else: ?>
                                                            <p class="mb-0 mt-1 evento-unidade"><a href="<?php echo get_the_permalink($local); ?>"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></a></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php
                                                
                                            }

                                        echo '</section>';
                                    echo '</div>';
                                } else {
                                // no posts found
                                }
                                /* Restore original Post Data */
                                wp_reset_postdata();
                                
                            ?>                            
                        </div>

                        <div class="">                            
                            <?php
                                
                                // Atividades Permanentes
                                $args = array(
                                    'posts_per_page' => 8,
                                    'meta_query' => array(
                                        'relation' => 'AND',
                                        array(
                                            'relation' => 'OR',
                                            array(
                                                'key' => 'localizacao',
                                                'value' => $id
                                            ),
                                            array(
                                                'key' => 'localizacao',
                                                'value' => 31675
                                            ),
                                            array(
                                                'key' => 'localizacao',
                                                'value' => 31224
                                            ),
                                            array(
                                                'key' => 'ceus_participantes_$_localizacao_serie',
                                                'value' => $id
                                            ),
                                        ),                          
                                        array(
                                            'key'   => 'data_tipo_de_data',
                                            'value' => 'semana',
                                        ),                            
                                    )
                                );
                                wp_enqueue_style('slick_css');
                                wp_enqueue_style('slick_theme_css');		
                                wp_enqueue_script('slick_min_js');
                                wp_enqueue_script('slick_func_js');
                                wp_enqueue_script('lightbox_js');

                                // The Query
                                $the_query = new \WP_Query( $args );

                                // The Loop
                                if ( $the_query->have_posts() ) {

                                    echo '<div class="title-ativi">';
                                        echo '<h2>Atividades Permanentes</h2><hr>';                                    
                                        echo '<a href="' . get_home_url() . '/?s=&tipo_atividade=permanente&unidades[]=' . get_the_ID() . '">Ver todas</a>';
                                    echo '</div>';

                                    echo '<div class="row mb-4">';

                                        while ( $the_query->have_posts() ) {
                                            $the_query->the_post();

                                            $tipo_data = get_field('data_tipo_de_data');
                                            $data = get_field('data_data');
                                            
                                        ?>
                                            <div class="col-sm-12 col-md-6 col-lg-3 mb-4">                                    
                                                <div class="card-eventos">
                                                    <div class="card-eventos-img">
                                                        <?php 
                                                            //$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'recorte-unidades');
                                                            $imgSelect = get_field('capa_do_evento', get_the_ID());
                                                            $tipo = get_field('tipo_de_evento_selecione_o_evento', get_the_ID());
                                                            $online = get_field('tipo_de_evento_online', get_the_ID());
                                                            $tipoEvento = get_field('tipo_de_evento_tipo', get_the_ID());

                                                            $featured_img_url = wp_get_attachment_image_src($imgSelect, 'recorte-eventos');
                                                            if($featured_img_url){
                                                                $imgEvento = $featured_img_url[0];
                                                                //$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                                                                $alt = get_post_meta($imgSelect, '_wp_attachment_image_alt', true);  
                                                            } else {
                                                                $imgEvento = get_template_directory_uri().'/img/placeholder_portal_ceus.jpg';
                                                                $alt = get_the_title(get_the_ID());
                                                            }
                                                        ?>
                                                        <a href="<?php echo get_the_permalink(get_the_ID()); ?>"><img src="<?php echo $imgEvento; ?>" class="img-fluid d-block" alt="<?php echo $alt; ?>"></a>
                                                        <?php if($tipo && $tipo != '') : 
                                                            echo '<span class="flag-pdf-full">';
                                                                echo get_the_title($tipo);
                                                            echo '</span>';                                           
                                                        endif; ?>                                      

                                                        <?php if($online && $online != '') : 
                                                            if($tipo && $tipo != ''){
                                                                $customClass = 'mt-tags';
                                                            }
                                                            echo '<span class="flag-pdf-full ' . $customClass . '">';
                                                                echo "Evento on-line";
                                                            echo '</span>';
                                                        endif; ?>
                                                    </div>
                                                    <div class="card-eventos-content p-2">
                                                        <div class="evento-categ border-bottom pb-1">
                                                            <?php
                                                                $atividades = get_the_terms( get_the_ID(), 'atividades_categories' );
                                                                $listaAtividades = array();

                                                                $atividadesTotal = count($atividades);

                                                                if($atividadesTotal > 1){
                                                                    foreach($atividades as $atividade){
                                                                        if($atividade->parent != 0){
                                                                            $listaAtividades[] = $atividade->term_id;
                                                                        } 
                                                                    }
                                                                } else {
                                                                    $listaAtividades[] = $atividades[0]->term_id;
                                                                }

                                                                $total = count($listaAtividades); 
                                                                $k = 0;
                                                                $showAtividades = '';

                                                                foreach($listaAtividades as $atividade){
                                                                    $k++;
                                                                    if($k == 1){
                                                                        $showAtividades .= '<a href="' . get_home_url() . '?s=&atividades[]=' . get_term( $atividade )->slug . '">' . get_term( $atividade )->name . "</a>";
                                                                    } else {
                                                                        $showAtividades .= ' ,<a href="' . get_home_url() . '?s=&atividades[]=' . get_term( $atividade )->slug . '">' . get_term( $atividade )->name . "</a>";
                                                                    }
                                                                }
                                                            ?>
                                                            <?php echo $showAtividades; ?>
                                                        </div>
                                                        <h3><a href="<?php echo get_the_permalink(get_the_ID()); ?>"><?php echo get_the_title(); ?></a></h3>
                                                        <?php
                                                            $campos = get_field('data', get_the_ID());
                                                            
                                                            // Verifica se possui campos
                                                            if($campos){

                                                                //print_r($campos);

                                                                if($campos['tipo_de_data'] == 'data'){ // Se for do tipo data
                                                                    
                                                                    $dataEvento = $campos['data'];

                                                                    if($dataEvento){
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);
                                                                        $data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];

                                                                        $dataFinal = $data;
                                                                        
                                                                    } else {
                                                                        $dataEvento = get_the_date('Y-m-d');
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);                                                                        
                                                                        $dataFinal = $mes;
                                                                    }

                                                                } elseif($campos['tipo_de_data'] == 'semana'){ // se for do tipo semana
                                                                    
                                                                    $semana = $campos['dia_da_semana'];
                                                                    $diasSemana = array();
                                                                    $show = array();

                                                                    foreach($semana as $dias){

                                                                        $total = count($dias['selecione_os_dias']); 
                                                                        $i = 0;
                                                                        $diasShow = '';
                                                                        
                                                                        foreach($dias['selecione_os_dias'] as $diaS){
                                                                            $i++;
                                                                            //echo $dia . "<br>";
                                                                            if($total - $i == 1){
                                                                                $diasShow .= $diaS . " ";
                                                                            } elseif($total != $i){
                                                                                $diasShow .= $diaS . ", ";
                                                                            } elseif($total == 1){
                                                                                $diasShow = $diaS;
                                                                            } else {
                                                                                $diasShow .= "e " . $diaS;
                                                                            }	
                                                                                                                                    
                                                                        }

                                                                        $show[] = $diasShow;
                                                                        
                                                                    }
                                                                    
                                                                    $totalDias = count($show);
                                                                    $j = 0;	
                                                                    
                                                                    $dias = '';

                                                                    foreach($show as $diaShow){
                                                                        $j++;
                                                                        if($j == 1){
                                                                            $dias .= $diaShow . " ";                                                        
                                                                        } else {
                                                                            $dias .= "/ " . $diaShow;
                                                                        }
                                                                    }

                                                                    if(!$dias){
                                                                        $dataEvento = get_the_date('Y-m-d');
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);                                                                        
                                                                        $dias = $mes;
                                                                    }

                                                                    $dataFinal = $dias; 

                                                                    $dias = '';
                                                                    $show = '';
                                                                    
                                                                } elseif($campos['tipo_de_data'] == 'periodo'){
                                                                    
                                                                    $dataInicial = $campos['data'];
                                                                    $dataFinal = $campos['data_final'];

                                                                    if($dataFinal){ // Verifica se possui a data final
                                                                        $dataInicial = explode("-", $dataInicial);
                                                                        $dataFinal = explode("-", $dataFinal);
                                                                        if($dataInicial[1] != $dataFinal[1]){
                                                                            $mesIni = date('M', mktime(0, 0, 0, $dataInicial[1], 10));
                                                                            $mesIni = translateMonth($mesIni);
                
                                                                            $mesFinal = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                            $mesFinal = translateMonth($mesFinal);
                
                                                                            $data = $dataInicial[2] . ' '. $mesIni . " a " .  $dataFinal[2] . " " . $mesFinal . " " . $dataFinal[0];
                                                                        } else {
                                                                            $mes = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                            $mes = translateMonth($mes);
                
                                                                            $data = $dataInicial[2] . " a " .  $dataFinal[2] . " " . $mes . " " . $dataFinal[0];
                                                                        }

                                                                        $dataFinal = $data;
                                                                    } else { // Se nao tiver a final mostra apenas a inicial
                                                                        if($dataInicial){
                                                                            $dataEvento = explode("-", $dataInicial);
                                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                            $mes = translateMonth($mes);
                                                                            $data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];
                
                                                                            $dataFinal = $data;
                                                                            
                                                                        } else {
                                                                            $dataEvento = get_the_date('Y-m-d');
                                                                            $dataEvento = explode("-", $dataEvento);
                                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                            $mes = translateMonth($mes);                                                                        
                                                                            $dataFinal = $mes;
                                                                        }
                                                                    }

                                                                }

                                                            }

                                                            if($tipoEvento == 'serie'){
                                                                $participantes = get_field('ceus_participantes',  get_the_ID());
                                                                $countPart = count($participantes);
                                                                $countPart = $countPart - 1;
                                                                
                                                                $dtInicial = $participantes[0]['data_serie'];
                                                                $dtFinal = $participantes[$countPart]['data_serie'];

                                                                if($dtInicial['tipo_de_data'] == 'data' && $dtFinal['tipo_de_data'] == 'data'){
                                                                    
                                                                    $dataInicial = explode("-", $dtInicial['data']);
                                                                    $dataFinal = explode("-", $dtFinal['data']);
                                                                    $mes = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                    $mes = translateMonth($mes);

                                                                    $data = $dataInicial[2] . " a " .  $dataFinal[2] . " " . $mes . " " . $dataFinal[0];

                                                                    $dataFinal = $data;

                                                                } else {
                                                                    $dataFinal = 'Múltiplas Datas';
                                                                }											
                                                            }
                                                        ?>
                                                        <p class="mb-0">
                                                            <i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $dataFinal; ?>
                                                            <br>
                                                            <?php
                                                            // Exibe os horários
                                                                        $horario = get_field('horario', get_the_ID());

                                                                        

                                                                        if($horario['selecione_o_horario'] == 'horario'){
                                                                            $hora = $horario['hora'];
                                                                        } elseif($horario['selecione_o_horario'] == 'periodo'){
                                                                            
                                                                            $hora = '';
                                                                            $k = 0;
                                                                            
                                                                            foreach($horario['hora_periodo'] as $periodo){
                                                                                //print_r($periodo['periodo_hora_final']);
                                                                                
                                                                                if($periodo['periodo_hora_inicio']){

                                                                                    if($k > 0){
                                                                                        $hora .= ' / ';
                                                                                    }

                                                                                    $hora .= $periodo['periodo_hora_inicio'];

                                                                                } 
                                                                                
                                                                                if ($periodo['periodo_hora_final']){

                                                                                    $hora .= ' às ' . $periodo['periodo_hora_final'];

                                                                                }
                                                                                
                                                                                $k++;
                                                                                
                                                                            }

                                                                        }else {
                                                                            $hora = '';
                                                                        }
                                                            ?>
                                                            <?php if($hora) : ?>                                           
                                                                <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo convertHour($hora); ?>
                                                            <?php endif; ?>
                                                            <?php if($tipoEvento == 'serie'): ?>
                                                                <i class="fa fa-clock-o" aria-hidden="true"><span>icone horario</span></i> Múltiplos Horários
                                                            <?php endif; ?>
                                                        </p>
                                                        <?php
                                                            $local = get_field('localizacao', get_the_ID());                                                        
                                                            if($local == '31675' || $local == '31244'):
                                                        ?>
                                                            <p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></p>
                                                        <?php elseif($tipoEvento == 'serie') : ?>
                                                            <p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> Múltiplas Unidades</p>
                                                        <?php else: ?>
                                                            <p class="mb-0 mt-1 evento-unidade"><a href="<?php echo get_the_permalink($local); ?>"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></a></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                            
                                        }
                                    echo '</div>';
                                    
                                } else {
                                // no posts found
                                }
                                /* Restore original Post Data */
                                wp_reset_postdata();
                                
                            ?>                            
                        </div>

                        <div class="">                            
                            <?php
                                $today = date('Ymd');
                                $id = get_the_ID();
                                // Proximas Atividades
                                $args_next = array(
                                    'posts_per_page' => 8,
                                    'meta_key'  => 'data_data',
                                    'orderby'   => 'meta_value_num',
                                    'order'     => 'ASC',
                                    'meta_query' => array(
                                        'relation' => 'AND',
                                        array(
                                            'relation' => 'AND',
                                            array(
                                                'relation' => 'OR',
                                                array(
                                                    'key'     => 'data_data',
                                                    //'compare' => '>=', // depois ou igual a data de hoje
                                                    'compare' => '<', // antes da data de hoje
                                                    'value'   => $today,
                                                ),
                                                array(
                                                    'key'     => 'data_data_final',
                                                    //'compare' => '>=', // depois ou igual a data de hoje
                                                    'compare' => '<', // antes da data de hoje
                                                    'value'   => $today,
                                                ), 
                                            ),
                                            array(
                                                'relation' => 'OR',
                                                array(
                                                    'key' => 'localizacao',
                                                    'value' => $id
                                                ),
                                                array(
                                                    'key' => 'localizacao',
                                                    'value' => 31675
                                                ),
                                                array(
                                                    'key' => 'ceus_participantes_$_localizacao_serie',
                                                    'value' => $id
                                                ),
                                            )                                   
                                            
                                        ),
                                        array(
                                            'key'   => 'tipo_de_evento_tipo',
                                            'value' => 'serie',
                                            'compare' => '!='
                                        ),
                                        array(
                                            'key'   => 'data_tipo_de_data',
                                            'value' => 'semana',
                                            'compare' => '!='
                                        ),                                    
                                    )
                                );
                                wp_enqueue_style('slick_css');
                                wp_enqueue_style('slick_theme_css');		
                                wp_enqueue_script('slick_min_js');
                                wp_enqueue_script('slick_func_js');

                                // The Query
                                $the_query = new \WP_Query( $args_next );

                                // The Loop
                                if ( $the_query->have_posts() ) {

                                    echo '<div class="title-ativi">';
                                        echo '<h2>Atividades Encerradas</h2><hr>';                                    
                                        echo '<a href="' . get_home_url() . '/?s=&tipo_atividade=encerrada&unidades[]=' . get_the_ID() . '">Ver todas</a>';
                                    echo '</div>';

                                    echo '<div class="row-slide mb-5" style="margin-left: -15px; margin-right: -15px;">';
                                        echo '<section class="regular slider">';

                                            while ( $the_query->have_posts() ) {
                                                $the_query->the_post();

                                                $tipo_data = get_field('data_tipo_de_data');
                                                $data = get_field('data_data');
                                                
                                            ?>
                                                <div class="card-eventos">
                                                    <div class="card-eventos-img">
                                                        <?php 
                                                            //$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'recorte-unidades');
                                                            $imgSelect = get_field('capa_do_evento', get_the_ID());
                                                            $tipo = get_field('tipo_de_evento_selecione_o_evento', get_the_ID());
                                                            $online = get_field('tipo_de_evento_online', get_the_ID());
                                                            $tipoEvento = get_field('tipo_de_evento_tipo', get_the_ID());

                                                            $featured_img_url = wp_get_attachment_image_src($imgSelect, 'recorte-eventos');
                                                            if($featured_img_url){
                                                                $imgEvento = $featured_img_url[0];
                                                                //$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                                                                $alt = get_post_meta($imgSelect, '_wp_attachment_image_alt', true);  
                                                            } else {
                                                                $imgEvento = get_template_directory_uri().'/img/placeholder_portal_ceus.jpg';
                                                                $alt = get_the_title(get_the_ID());
                                                            }
                                                        ?>
                                                        <a href="<?php echo get_the_permalink(get_the_ID()); ?>"><img src="<?php echo $imgEvento; ?>" class="img-fluid d-block" alt="<?php echo $alt; ?>"></a>
                                                        <?php if($tipo && $tipo != '') : 
                                                            echo '<span class="flag-pdf-full">';
                                                                echo get_the_title($tipo);
                                                            echo '</span>';                                           
                                                        endif; ?>                                      

                                                        <?php if($online && $online != '') : 
                                                            if($tipo && $tipo != ''){
                                                                $customClass = 'mt-tags';
                                                            }
                                                            echo '<span class="flag-pdf-full ' . $customClass . '">';
                                                                echo "Evento on-line";
                                                            echo '</span>';
                                                        endif; ?>
                                                    </div>
                                                    <div class="card-eventos-content p-2">
                                                        <div class="evento-categ border-bottom pb-1">
                                                            <?php
                                                                $atividades = get_the_terms( get_the_ID(), 'atividades_categories' );
                                                                $listaAtividades = array();

                                                                if($atividades){
                                                                    $atividadesTotal = count($atividades);
                                                                } else {
                                                                    $atividadesTotal = 0;
                                                                }  

                                                                if($atividadesTotal > 1){
                                                                    foreach($atividades as $atividade){
                                                                        if($atividade->parent != 0){
                                                                            $listaAtividades[] = $atividade->term_id;
                                                                        } 
                                                                    }
                                                                } else {
                                                                    $listaAtividades[] = $atividades[0]->term_id;
                                                                }

                                                                $total = count($listaAtividades); 
                                                                $k = 0;
                                                                $showAtividades = '';

                                                                foreach($listaAtividades as $atividade){
                                                                    $k++;
                                                                    if($k == 1){
                                                                        $showAtividades .= '<a href="' . get_home_url() . '?s=&atividades[]=' . get_term( $atividade )->slug . '">' . get_term( $atividade )->name . "</a>";
                                                                    } else {
                                                                        $showAtividades .= ' ,<a href="' . get_home_url() . '?s=&atividades[]=' . get_term( $atividade )->slug . '">' . get_term( $atividade )->name . "</a>";
                                                                    }
                                                                }
                                                            ?>
                                                            <?php echo $showAtividades; ?>
                                                        </div>
                                                        <h3><a href="<?php echo get_the_permalink(get_the_ID()); ?>"><?php echo get_the_title(); ?></a></h3>
                                                        <?php
                                                            $campos = get_field('data', get_the_ID());
                                                            
                                                            // Verifica se possui campos
                                                            if($campos){

                                                                //print_r($campos);

                                                                if($campos['tipo_de_data'] == 'data'){ // Se for do tipo data
                                                                    
                                                                    $dataEvento = $campos['data'];

                                                                    if($dataEvento){
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);
                                                                        $data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];

                                                                        $dataFinal = $data;
                                                                        
                                                                    } else {
                                                                        $dataEvento = get_the_date('Y-m-d');
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);                                                                        
                                                                        $dataFinal = $mes;
                                                                    }                                                                    

                                                                } elseif($campos['tipo_de_data'] == 'semana'){ // se for do tipo semana
                                                                    
                                                                    $semana = $campos['dia_da_semana'];
                                                                    $diasSemana = array();
                                                                    $show = array();

                                                                    foreach($semana as $dias){

                                                                        $total = count($dias['selecione_os_dias']); 
                                                                        $i = 0;
                                                                        $diasShow = '';
                                                                        
                                                                        foreach($dias['selecione_os_dias'] as $diaS){
                                                                            $i++;
                                                                            //echo $dia . "<br>";
                                                                            if($total - $i == 1){
                                                                                $diasShow .= $diaS . " ";
                                                                            } elseif($total != $i){
                                                                                $diasShow .= $diaS . ", ";
                                                                            } elseif($total == 1){
                                                                                $diasShow = $diaS;
                                                                            } else {
                                                                                $diasShow .= "e " . $diaS;
                                                                            }	
                                                                                                                                    
                                                                        }

                                                                        $show[] = $diasShow;
                                                                        
                                                                    }
                                                                    
                                                                    $totalDias = count($show);
                                                                    $j = 0;	
                                                                    
                                                                    $dias = '';

                                                                    foreach($show as $diaShow){
                                                                        $j++;
                                                                        if($j == 1){
                                                                            $dias .= $diaShow . " ";                                                        
                                                                        } else {
                                                                            $dias .= "/ " . $diaShow;
                                                                        }
                                                                    }

                                                                    if(!$dias){
                                                                        $dataEvento = get_the_date('Y-m-d');
                                                                        $dataEvento = explode("-", $dataEvento);
                                                                        $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                        $mes = translateMonth($mes);                                                                        
                                                                        $dias = $mes;
                                                                    }

                                                                    $dataFinal = $dias; 

                                                                    $dias = '';
                                                                    $show = '';
                                                                    
                                                                } elseif($campos['tipo_de_data'] == 'periodo'){
                                                                    
                                                                    $dataInicial = $campos['data'];
                                                                    $dataFinal = $campos['data_final'];

                                                                    if($dataFinal){ // Verifica se possui a data final
                                                                        $dataInicial = explode("-", $dataInicial);
                                                                        $dataFinal = explode("-", $dataFinal);
                                                                        if($dataInicial[1] != $dataFinal[1]){
                                                                            $mesIni = date('M', mktime(0, 0, 0, $dataInicial[1], 10));
                                                                            $mesIni = translateMonth($mesIni);
                
                                                                            $mesFinal = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                            $mesFinal = translateMonth($mesFinal);
                
                                                                            $data = $dataInicial[2] . ' '. $mesIni . " a " .  $dataFinal[2] . " " . $mesFinal . " " . $dataFinal[0];
                                                                        } else {
                                                                            $mes = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                            $mes = translateMonth($mes);
                
                                                                            $data = $dataInicial[2] . " a " .  $dataFinal[2] . " " . $mes . " " . $dataFinal[0];
                                                                        }

                                                                        $dataFinal = $data;
                                                                    } else { // Se nao tiver a final mostra apenas a inicial
                                                                        if($dataInicial){
                                                                            $dataEvento = explode("-", $dataInicial);
                                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                            $mes = translateMonth($mes);
                                                                            $data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];
                
                                                                            $dataFinal = $data;
                                                                            
                                                                        } else {
                                                                            $dataEvento = get_the_date('Y-m-d');
                                                                            $dataEvento = explode("-", $dataEvento);
                                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                                            $mes = translateMonth($mes);                                                                        
                                                                            $dataFinal = $mes;
                                                                        }
                                                                    }

                                                                }

                                                            }

                                                            if($tipoEvento == 'serie'){
                                                                $participantes = get_field('ceus_participantes',  get_the_ID());
                                                                $countPart = count($participantes);
                                                                $countPart = $countPart - 1;
                                                                
                                                                $dtInicial = $participantes[0]['data_serie'];
                                                                $dtFinal = $participantes[$countPart]['data_serie'];

                                                                if($dtInicial['tipo_de_data'] == 'data' && $dtFinal['tipo_de_data'] == 'data'){
                                                                    
                                                                    $dataInicial = explode("-", $dtInicial['data']);
                                                                    $dataFinal = explode("-", $dtFinal['data']);
                                                                    $mes = date('M', mktime(0, 0, 0, $dataFinal[1], 10));
                                                                    $mes = translateMonth($mes);

                                                                    $data = $dataInicial[2] . " a " .  $dataFinal[2] . " " . $mes . " " . $dataFinal[0];

                                                                    $dataFinal = $data;

                                                                } else {
                                                                    $dataFinal = 'Múltiplas Datas';
                                                                }											
                                                            }
                                                        ?>
                                                        <p class="mb-0">
                                                            <i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $dataFinal; ?>
                                                            <br>
                                                            <?php
                                                            // Exibe os horários
                                                                        $horario = get_field('horario', get_the_ID());

                                                                        

                                                                        if($horario['selecione_o_horario'] == 'horario'){
                                                                            $hora = $horario['hora'];
                                                                        } elseif($horario['selecione_o_horario'] == 'periodo'){
                                                                            
                                                                            $hora = '';
                                                                            $k = 0;
                                                                            
                                                                            foreach($horario['hora_periodo'] as $periodo){
                                                                                //print_r($periodo['periodo_hora_final']);
                                                                                
                                                                                if($periodo['periodo_hora_inicio']){

                                                                                    if($k > 0){
                                                                                        $hora .= ' / ';
                                                                                    }

                                                                                    $hora .= $periodo['periodo_hora_inicio'];

                                                                                } 
                                                                                
                                                                                if ($periodo['periodo_hora_final']){

                                                                                    $hora .= ' às ' . $periodo['periodo_hora_final'];

                                                                                }
                                                                                
                                                                                $k++;
                                                                                
                                                                            }

                                                                        }else {
                                                                            $hora = '';
                                                                        }
                                                            ?>
                                                            <?php if($hora) : ?>                                           
                                                                <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo convertHour($hora); ?>
                                                            <?php endif; ?>
                                                            <?php if($tipoEvento == 'serie'): ?>
                                                                <i class="fa fa-clock-o" aria-hidden="true"><span>icone horario</span></i> Múltiplos Horários
                                                            <?php endif; ?>
                                                        </p>
                                                        <?php
                                                            $local = get_field('localizacao', get_the_ID());                                                        
                                                            if($local == '31675' || $local == '31244'):
                                                        ?>
                                                            <p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></p>
                                                        <?php elseif($tipoEvento == 'serie') : ?>
                                                            <p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> Múltiplas Unidades</p>
                                                        <?php else: ?>
                                                            <p class="mb-0 mt-1 evento-unidade"><a href="<?php echo get_the_permalink($local); ?>"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></a></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php
                                                
                                            }

                                        echo '</section>';
                                    echo '</div>';
                                } else {
                                // no posts found
                                }
                                /* Restore original Post Data */
                                wp_reset_postdata();
                                
                            ?>                            
                        </div>

                    <?php endif; ?>

                </div>

                <div id="servicos" class="tab-pane fade">
                    <p class="d-lg-none d-xl-none mob-tab-title">SERVIÇOS E INSTALAÇÕES</p>                
                    <p class='unidade-title'>Confira os serviços disponíveis e Instalações no <?php echo get_the_title(); ?></p>

                    <div class="row pt-4">
                        <div class="col-sm-12">
                            <p>A Carta de Serviços da Prefeitura de São Paulo, disponível no Portal SP 156, traz a ficha técnica detalhada de oito serviços relacionados relacionados aos Centros Educacionais Unificados:</p>
                            <ul>
                                <li>CEU – Emprestar livros em bibliotecas (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&amp;a=NTgx&amp;conteudo=1139">ir para Emprestar livros em bibliotecas</a>)</li>
                                <li>CEU – Fazer inscrição de crianças e adolescentes para atividades de férias (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&amp;a=NTgx&amp;conteudo=1145">ir para Fazer inscrição de crianças e adolescentes</a>)</li>
                                <li>CEU – Fazer inscrição em atividades de extensão de jornada escolar (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&amp;a=NTgx&amp;conteudo=1144">ir para Fazer inscrição em atividades de extensão</a>)</li>
                                <li>CEU – Fazer inscrição em cursos UniCEU (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&amp;a=NTgx&amp;conteudo=1146">ir para Fazer inscrição em cursos</a>)</li>
                                <li>CEU – Usar piscina (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&amp;a=NTgx&amp;conteudo=1143">ir para Usar piscina</a>)</li>
                                <li>CEU – Usar quadra (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&amp;a=NTgx&amp;conteudo=1142">ir para Usar quadra</a>)</li>
                                <li>CEU – Consultar Programação (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?conteudo=1759">ir para Consultar Programação</a>)</li>
                                <li>CEU – Fazer inscrição de crianças e adolescentes para atividades de férias (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&amp;a=NzAx&amp;conteudo=1145">ir para Fazer inscrição para atividades de férias</a>)</li>
                            </ul>
                        </div>
                    </div>

                    <?php
                        $servicos = get_field('servicos_disponiveis');
                        $s = 0;
                        
                        if($servicos && $servicos != ''):
                    ?>
                    
                        <div id="accordion" class='pb-4 sss'>

                            <?php foreach($servicos as $servico): ?>

                                <div class="card">
                                    <div class="services-header" id="headingOne">
                                    <p class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse<?php echo $s; ?>" aria-expanded="false" aria-controls="collapse<?php echo $s; ?>">
                                        <i class="fa fa-chevron-right" aria-hidden="true"></i> <?php echo $servico['titulo_servico']; ?>
                                        </button>
                                    </p>
                                    </div>

                                    <div id="collapse<?php echo $s; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <?php if($servico['descricao'] && $servico['descricao'] != ''): ?>
                                                <p><?php echo $servico['descricao']; ?></p>
                                            <?php endif; ?>

                                            <?php if($servico['foto_descricao'] && $servico['foto_descricao'] != ''): 
                                                    $imgurl = wp_get_attachment_image_url( $servico['foto_descricao'], 'recorte-eventos' );
                                                    $image_alt = get_post_meta($servico['foto_descricao'], '_wp_attachment_image_alt', TRUE);
                                                ?>
                                                <p><img src="<?php echo  $imgurl; ?>" alt='<?php echo $image_alt; ?>'></p>
                                            <?php endif; ?>

                                            <?php if($servico['telefone'] && $servico['telefone'] != ''): ?>
                                                <p><strong>Telefone:</strong> <?php echo $servico['telefone']; ?></p>
                                            <?php endif; ?>

                                            <?php if($servico['email'] && $servico['email'] != ''): ?>
                                                <p><strong>E-mail:</strong> <a href="mailto:<?php echo $servico['email']; ?>"><?php echo $servico['email']; ?></a></p>
                                            <?php endif; ?>

                                            <?php if($servico['horario_serv'] && $servico['horario_serv'] != ''): ?>
                                                <p class='mb-0'><strong>Horário de Funcionamento</strong></p>
                                                <p class='mb-0'><?php echo $servico['horario_serv']['dia_abertura'] . ' a ' . $servico['horario_serv']['dia_fechamento']; ?> - <?php echo convertHour($servico['horario_serv']['horario_abertura']) . ' às ' . convertHour($servico['horario_serv']['horario_fechamento']); ?></p>

                                                <?php
                                                    $horaAdicional = $servico['horario_serv']['horario_de_funcionamento'];
                                                    if($horaAdicional && $horaAdicional != ''):
                                                        foreach($horaAdicional as $hora) :
                                                ?>
                                                        <p class='mb-0'>
                                                            <?php 
                                                                echo $hora['data_inicial'];
                                                                if($hora['data_final'] && $hora['data_final'] != ''){
                                                                    echo " e " . $hora['data_final'];
                                                                }
                                                                $horaInicial = convertHour($hora['hora_abertura']);
                                                                $horaFinal = convertHour($hora['hora_fechamento']);
                                                                
                                                                echo " - " . $horaInicial . " às " . $horaFinal;
                                                            ?>
                                                        </p>
                                                <?php
                                                        endforeach;
                                                    endif; 
                                                ?>

                                            <?php endif; ?>

                                            <hr>

                                            <?php if($servico['tipo_de_servico'] == 'biblioteca') : ?>
                                                <p class='mb-0'>CEU – Emprestar livros em bibliotecas (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&a=NTgx&conteudo=1139">ir para Emprestar livros em bibliotecas</a>)</p>
                                            <?php endif; ?>

                                            <?php if($servico['tipo_de_servico'] == 'ferias') : ?>
                                                <p class='mb-0'>CEU – Fazer inscrição de crianças e adolescentes para atividades de férias (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&a=NTgx&conteudo=1145">ir para Fazer inscrição de crianças e adolescentes</a>)</p>
                                            <?php endif; ?>

                                            <?php if($servico['tipo_de_servico'] == 'jornada') : ?>
                                                <p class='mb-0'>CEU – Fazer inscrição em atividades de extensão de jornada escolar (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&a=NTgx&conteudo=1144">ir para Fazer inscrição em atividades de extensão</a>)</p>
                                            <?php endif; ?>

                                            <?php if($servico['tipo_de_servico'] == 'uni') : ?>
                                                <p class='mb-0'>CEU – Fazer inscrição em cursos UniCEU (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&a=NTgx&conteudo=1146">ir para Fazer inscrição em cursos</a>)</p>
                                            <?php endif; ?>

                                            <?php if($servico['tipo_de_servico'] == 'piscina') : ?>
                                                <p class='mb-0'>CEU – Usar piscina (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&a=NTgx&conteudo=1143">ir para Usar piscina</a>)</p>
                                            <?php endif; ?>

                                            <?php if($servico['tipo_de_servico'] == 'quadra') : ?>
                                                <p class='mb-0'>CEU – Usar quadra (<a href="https://sp156.prefeitura.sp.gov.br/portal/servicos/informacao?t=&a=NTgx&conteudo=1143">ir para Usar quadra</a>)</p>
                                            <?php endif; ?>

                                            <?php if($servico['tipo_de_servico'] == 'perso' && $servico['link_url'] != '') : ?>
                                                <p class='mb-0'><?php echo $servico['texto_link']; ?> (<a href="<?php echo $servico['link_url']; ?>">ir para <?php echo $servico['texto_link']; ?></a>)</p>
                                            <?php endif; ?>
                                            
                                        </div>
                                    </div>
                                </div>

                            <?php
                                $s++;
                                endforeach; ?>
                            
                        </div>
                    <?php endif; ?>
                    
                </div>
               
                <div id="sobre" class="tab-pane fade">
                    <p class="d-lg-none d-xl-none mob-tab-title">SOBRE A UNIDADE</p>
                    <p class='unidade-title'>Saiba mais sobre o <?php echo get_the_title(); ?></p>
                    <?php
                        $gestor = $infoBasicas['gestor'];
                        if($gestor && $gestor != ''){
                            echo "<p class='m-0'><strong>Gestor: </strong>" . $gestor . "</p>";
                        }
                    ?>
                    
                    <div class="row py-4">
                        <div class="col-sm-12 col-md-6 about-text">
                            <?php
                                $descri = get_field('descricao');
                                if($descri && $descri != ''){
                                    echo "<p>" . $descri . "</p>";
                                }
                            ?>

                            <?php
                                $redes = get_field('redes_sociais');
                                if($redes && $redes != '') :
                            ?>
                                <div class='about-redes'>

                                    <?php if($redes['facebook'] && $redes['facebook'] != '') : ?>
                                        <a href="<?php echo $redes['facebook']; ?>"><i class="fa fa-facebook-square" title="Ir para facebook" aria-hidden="true"></i><span>Ir para Facebook</span></a>
                                    <?php endif; ?>

                                    <?php if($redes['instagram'] && $redes['instagram'] != '') : ?>
                                        <a href="<?php echo $redes['instagram']; ?>"><i class="fa fa-instagram" title="Ir para Instagram" aria-hidden="true"></i><span>Ir para Instagram</span></a>
                                    <?php endif; ?>

                                    <?php if($redes['twitter'] && $redes['twitter'] != '') : ?>
                                        <a href="<?php echo $redes['twitter']; ?>"><i class="fa fa-twitter-square" title="Ir para Twitter" aria-hidden="true"></i><span>Ir para Twitter</span></a>
                                    <?php endif; ?>

                                    <?php if($redes['youtube'] && $redes['youtube'] != '') : ?>
                                        <a href="<?php echo $redes['youtube']; ?>"><i class="fa fa-youtube-square" title="Ir para YouTube" aria-hidden="true"></i><span>Ir para YouTube</span></a>
                                    <?php endif; ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-sm-12 col-md-6">

                            <?php                                
                                $princial[] = get_field('foto_principal_do_ceu');
                                $seconds = get_field('outras_fotos_da_unidade');
                                if(!$seconds){
                                    $seconds = array();
                                }
                                $todasFotos = array_merge($princial, $seconds);
                                $todasFotos = array_filter($todasFotos);                                

                                $count_fotos = count($todasFotos);
                                
                                $j = 0;
                            ?>

                            <div id="carouselAbout" class="carousel slide mb-4" data-ride="carousel">
                                <?php if($count_fotos > 0): ?>
                                    <ol class="carousel-indicators">
                                        <?php for($i = 0; $i < $count_fotos; $i++): ?>                                            
                                            <li data-target="#carouselAbout" data-slide-to="<?php echo $i; ?>" class="<?php echo $i == 0 ? 'active' : ''; ?>"><span>bullet</span></li>                                            
                                        <?php endfor; ?>
                                    </ol>
                                    <div class="carousel-inner">

                                        <?php foreach($todasFotos as $foto):
                                                $featured_img_url = wp_get_attachment_image_src($foto, 'recorte-eventos');
                                                if($featured_img_url){
                                                    $imgEvento = $featured_img_url[0];
                                                    //$thumbnail_id = get_post_thumbnail_id( $eventoID );
                                                    $alt = get_post_meta($foto, '_wp_attachment_image_alt', true);  
                                                } else {
                                                    $imgEvento = get_template_directory_uri().'/img/placeholder_portal_ceus.jpg';
                                                    $alt = get_the_title($eventoID);
                                                }
                                        ?>
                                            <div class="carousel-item <?php echo $j == 0 ? 'active' : ''; ?>">
                                                <img src="<?php echo $imgEvento; ?>" class="d-block w-100" alt="<?php echo $alt; ?>">
                                            </div>
                                        <?php 
                                            $j++;
                                            endforeach; ?>

                                        
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                    
                </div>

                <div id="chegar" class="tab-pane fade">
                    <p class="d-lg-none d-xl-none mob-tab-title">COMO CHEGAR</p>
                    <p class='unidade-title'>Como chegar ao <?php echo get_the_title(); ?></p>
                        
                        <div class="row pb-4">
                            <div class="col-sm-12 col-md-6 about-text">
                                <?php
                                   $publico = get_field('via_transporte_publico');
                                   if($publico && $publico != ''){
                                        echo '<p class="about-title">Via Transporte Público</p>';

                                        foreach($publico as $chegar){
                                            echo "<div class='como-chegar'>";
                                                echo "<p class='chegar-title mb-0'>" . $chegar['ponto_de_partida'] . "</p>";
                                                echo "<p>" . $chegar['descricao'] . "</p>";
                                            echo "</div>";
                                        }
                                   } 
                                ?>

                                <?php
                                    $link_sp = get_field('link_sptrans');
                                    if($link_sp && $link_sp != ''){
                                        echo "<div class='como-chegar'>";
                                            echo "<p class='chegar-title mb-0'>Veja mais</p>";
                                            echo "<p>Planeje sua visita conferindo rotas da SPTrans. <br><i class='fa fa-bus' aria-hidden='true'></i> <a href='" . $link_sp . "'>Clique aqui</a>.</p>";
                                        echo "</div>";
                                    }
                                ?>

                                <?php
                                   $pessoal = get_field('via_transporte_individual');
                                   if($pessoal && $pessoal != ''){
                                        echo '<p class="about-title">Via transporte individual</p>';

                                        foreach($pessoal as $chegar){
                                            echo "<div class='como-chegar'>";
                                                echo "<p class='chegar-title mb-0'>" . $chegar['ponto_de_partida'] . "</p>";
                                                echo "<p>" . $chegar['descricao'] . "</p>";
                                            echo "</div>";
                                        }
                                   } 
                                ?>
                            </div>

                            <div class="col-sm-12 col-md-6 pt-4">
                                <?php
                                    $infoBasicas = get_field('informacoes_basicas');
                                    if($infoBasicas && $infoBasicas != ''): 
                                ?>
                                    <div id="map" style="width: 100%; height: 450px;"></div>
                                    <a href="#map" class="story" data-point="<?php echo $infoBasicas['latitude']; ?>,<?php echo $infoBasicas['longitude']; ?>,<div class='marcador-unidade  color-<?php echo $infoBasicas['zona_sp']; ?>'><p class='marcador-title'><?php the_title(); ?></p><p><?php echo $infoBasicas['endereco'];?> nº <?php echo $infoBasicas['numero']; ?> - <?php echo $infoBasicas['bairro']; ?></p><p>CEP: <?php echo $infoBasicas['cep']; ?></p></div>,<?php echo $infoBasicas['zona_sp']; ?>" style="display: none;"> &nbsp;destacar no mapa</a></span>
                                <?php endif; ?>
                            </div>
                        </div>

                    
                </div>

            </div>
        </div>

        <?php $alertas = get_field('alertas'); ?>
        <script>
			jQuery(document).ready(function ($) {
				// Condição ACF para ativar modal
				var ativo_modal = "<?php echo $alertas['ativar_o_alerta']; ?>"
				
				console.log(ativo_modal);
                console.log("Aqui");
                
                if(ativo_modal == '1'){
				   		jQuery('#modal-content').modal({ show: true });
				   }else{
					 	jQuery('#modal-content').modal({ show: false });
				   }
			});
		</script>
		
        <div id="modal-content" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					
						<button type="button" class="close" data-dismiss="modal">×</button>
					
					<div class="modal-body">
					<?php
						if($alertas['titulo_alerta'] && $alertas['titulo_alerta'] != ''){
							?><p><h1><strong><?php echo $alertas['titulo_alerta']; ?></strong></h1></p><?php
						}
					?>
					<?php
						if($alertas['tipo_de_alerta'] == 'imagem' && $alertas['imagem_alerta'] != ''){
							?><p><img src="<?php echo $alertas['imagem_alerta'] ?>" width="100%"></p><?php
						}
					?>
					<?php
						if($alertas['tipo_de_alerta'] == 'texto' && $alertas['descricao'] != ''){
							?><p><?php echo $alertas['descricao']; ?></p><?php
						}
					?>					
					</div>
				</div>
			</div>
		</div>
        
        
    <?php
        
    }
}