<?php

namespace Classes\TemplateHierarchy\LoopUnidades;

class LoopUnidadesSlide extends LoopUnidades{

	public function __construct()
	{
		$this->slideUnidade();
	}

	public function slideUnidade(){
    ?>

        
        <div class="container slide-principal mt-4 mb-4">
            <div class="row">
                <?php
                    $unidade = '';
                    $args = array(
                        'post_type' => 'destaque',
                        'post_status' => array('publish'),
                        'posts_per_page'   => -1,                        
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'unidade',
                                'value' => '' . get_the_ID() . '',
                                'compare' => 'LIKE'
                            )
                            
                        ),
                    );

                    // The Query.
                    $the_query = new \WP_Query( $args );
                    
                    // The Loop.
                    if ( $the_query->have_posts() ) {                       
                        while ( $the_query->have_posts() ) {
                            $the_query->the_post();
                            $unidade = get_the_ID();
                        }
                    } 
                    // Restore original Post Data.
                    wp_reset_postdata();

                    if($unidade != ''){
                        $newSlide = get_field('destaque', $unidade); 
                    } else {
                        $newSlide = array();
                    }              

                    $slides = $newSlide;
                    $today = date('Y-m-d'); // Data de hoje
                    $first = date("Y-m-d", strtotime("first day of this month")); // Primeiro da do mes atual
                    $last = date("Y-m-d", strtotime("last day of this month")); // Ultimo da do mes atual
                    
                    foreach ($slides as $key => $slide) {
                        
                        $tipoEvento = get_field('tipo_de_evento_tipo', $slide);

                        

                        if($tipoEvento != 'serie'){
                            $tipoData = get_field('data_tipo_de_data', $slide);
                            
                            if($tipoData == 'data'){
                                
                                $data = get_field('data_data', $slide);
                                if(!$data){
                                    $mesPubli = get_the_date('Y-m-d', $slide);                                        
                                    if($mesPubli < $first || $mesPubli > $last){
                                        unset($slides[$key]); 
                                    }
                                    
                                } elseif($data < $today) {
                                    unset($slides[$key]);
                                }

                            } elseif($tipoData == 'periodo') {
                                $data = get_field('data_data_final', $slide);
                                if(!$data){
                                    $mesPubli = get_the_date('Y-m-d', $slide);
                                    if($mesPubli < $first || $mesPubli > $last){
                                        unset($slides[$key]); 
                                    }
                                    
                                } elseif($data < $today) {
                                    unset($slides[$key]);
                                }
                            }
                            
                        } else {
                            $ceus = get_field('ceus_participantes', $slide);
                            $verify = 0;
                            foreach($ceus as $ceu){
                                $tipoData = $ceu['data_serie']['tipo_de_data'];
                            
                                if($tipoData == 'data'){
                                    
                                    $data = $ceu['data_serie']['data'];
                                    if(!$data){
                                        $mesPubli = get_the_date('Y-m-d', $slide);                                            
                                        if($mesPubli > $first || $mesPubli < $last){
                                            $verify++; 
                                        }
                                        
                                    } elseif($data > $today){
                                        $verify++;
                                    }

                                } elseif($tipoData == 'periodo') {
                                    $data = $ceu['data_serie']['data_final'];
                                    if(!$data){
                                        $mesPubli = get_the_date('Y-m-d', $slide);                                            
                                        if($mesPubli > $first || $mesPubli < $last){
                                            $verify++; 
                                        }
                                        
                                    } elseif($data > $today){
                                        $verify++;
                                    }
                                } elseif($tipoData == 'semana') {                                        
                                    $verify++;
                                }   
                                                            
                            }

                            if($verify == 0){
                                unset($slides[$key]);
                            }
                            
                        }
                    }
                                    
                    if($slides){
                        $qtSlide = count($slides);
                    } else {
                        $qtSlide = 0;
                    }
                    
                    $l = 0;
                    $m = 0;                    
                    
                ?>
                <div id="carouselExampleIndicators" class="carousel slide col-sm-12" data-ride="carousel">
                    <ol class="carousel-indicators">
                        
                        
                        <?php while($m < $qtSlide) : ?>
                            <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $m; ?>" class="<?php if($m == 0){echo 'active';} ?>"><span>bullet</span></li>
                        <?php 
                            $m++;
                            endwhile; ?>
                    </ol>
                    <div class="carousel-inner border">

                        <?php foreach($slides as $slide): ?>
                            <div class="carousel-item <?php if($l == 0){echo 'active';} ?>">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <?php 
                                            //$featured_img_url = get_the_post_thumbnail_url($slide, 'recorte-eventos');
                                            $imgSelect = get_field('capa_do_evento', $slide);
                                            $featured_img_url = wp_get_attachment_image_src($imgSelect, 'recorte-eventos');
                                            
                                            if($featured_img_url){
                                                $imgSlide = $featured_img_url[0];
                                            } else {
                                                $imgSlide = 'http://via.placeholder.com/640x350';
                                            }
                                        ?>
                                        <img class="d-block w-100" src="<?php echo  $imgSlide; ?>" alt="Slide ">
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="carousel-categ">
                                            <?php
                                                $tipoEvento = get_field('tipo_de_evento_tipo', $slide);
                                                $atividades = get_the_terms( $slide, 'atividades_categories' );
                                                $listaAtividades = array();
                                                foreach($atividades as $atividade){
                                                    if($atividade->parent != 0){
                                                        $listaAtividades[] = $atividade->term_id;
                                                    }
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
                                        
                                            <p><?php echo $showAtividades; ?></p> 
                                        </div>

                                        <div class="carousel-title">
                                            <p><a href="<?php echo get_permalink( $slide ); ?>"><?php echo get_the_title($slide); ?></a></p>
                                        </div>
                                        <?php 
                                            $subTitle = get_field('subtitulo', $slide);
                                            if($subTitle):
                                        ?>
                                            <div class="carousel-subtitle">
                                                <p>- <?php echo $subTitle; ?></p>
                                            </div>

                                        <?php endif; ?>

                                        <div class="carousel-data">
                                            <?php
                                                $campos = get_field('data', $slide);
                                                
                                                // Verifica se possui campos
                                                if($campos){

                                                    if($campos['tipo_de_data'] == 'data'){ // Se for do tipo data

                                                        $dataEvento = $campos['data'];

                                                        if($dataEvento){
                                                            $dataEvento = explode("-", $dataEvento);
                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                            $mes = translateMonth($mes);
                                                            $data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];

                                                            $dataFinal = $data;
                                                            
                                                        } else {
                                                            $dataEvento = get_the_date('Y-m-d', $slide);
                                                            $dataEvento = explode("-", $dataEvento);
                                                            $mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
                                                            $mes = translateMonth($mes);                                                                        
                                                            $dataFinal = $mes;
                                                        }

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
																$dataEvento = get_the_date('Y-m-d', $slide);
																$dataEvento = explode("-", $dataEvento);
																$mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
																$mes = translateMonth($mes);                                                                        
																$dataFinal = $mes;
															}
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
															$dataEvento = get_the_date('Y-m-d', $slide);
															$dataEvento = explode("-", $dataEvento);
															$mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
															$mes = translateMonth($mes);                                                                        
															$dias = $mes;
														}

                                                        $dataFinal = $dias; 
                                                    }

                                                }

                                                if($tipoEvento == 'serie'){
                                                    $participantes = get_field('ceus_participantes',  $slide);
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
                                                    $horario = get_field('horario', $slide);

                                                    

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
                                                    <i class="fa fa-clock-o" aria-hidden="true"><span>icone horario</span></i> <?php echo convertHour($hora); ?>
                                                <?php endif; ?>
                                                <?php if($tipoEvento == 'serie'): ?>
                                                    <i class="fa fa-clock-o" aria-hidden="true"><span>icone horario</span></i> Múltiplos Horários
                                                <?php endif; ?>
                                            </p>
                                            <?php
                                                $local = get_field('localizacao', $slide);                                                        
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
                            </div>
                        <?php 
                            $l++;
                            endforeach; ?>

                        


                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    </div>
            </div>
        </div>
        

    <?php
        
    }
}