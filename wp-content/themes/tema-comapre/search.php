<?php get_header(); ?>

	<div class="container-fluid">

		<div class="slide-principal mt-3 mb-3">
			<div class="container">
				<div class="row">
					<?php 
						$slides = get_field('slide', 30834);
						$today = date('Y-m-d'); // Data de hoje
                        $first = date("Y-m-d", strtotime("first day of this month")); // Primeiro da do mes atual
                        $last = date("Y-m-d", strtotime("last day of this month")); // Ultimo da do mes atual

                        foreach ($slides as $key => $slide) {
                            
                            $tipoEvento = get_field('tipo_de_evento_tipo', $slide->ID);

                            

                            if($tipoEvento != 'serie'){
                                $tipoData = get_field('data_tipo_de_data', $slide->ID);
                                
                                if($tipoData == 'data'){
                                    
                                    $data = get_field('data_data', $slide->ID);
                                    if(!$data){
                                        $mesPubli = get_the_date('Y-m-d', $slide->ID);                                        
                                        if($mesPubli < $first || $mesPubli > $last){
                                            unset($slides[$key]); 
                                        }
                                        
                                    } elseif($data < $today) {
                                        unset($slides[$key]);
                                    }

                                } elseif($tipoData == 'periodo') {
                                    $data = get_field('data_data_final', $slide->ID);
                                    if(!$data){
                                        $mesPubli = get_the_date('Y-m-d', $slide->ID);
                                        if($mesPubli < $first || $mesPubli > $last){
                                            unset($slides[$key]); 
                                        }
                                        
                                    } elseif($data < $today) {
                                        unset($slides[$key]);
                                    }
                                }
                                
                            } else {
                                $ceus = get_field('ceus_participantes', $slide->ID);
                                $verify = 0;
                                foreach($ceus as $ceu){
                                    $tipoData = $ceu['data_serie']['tipo_de_data'];
                                
                                    if($tipoData == 'data'){
                                        
                                        $data = $ceu['data_serie']['data'];
                                        if(!$data){
                                            $mesPubli = get_the_date('Y-m-d', $slide->ID);                                            
                                            if($mesPubli > $first || $mesPubli < $last){
                                                $verify++; 
                                            }
                                            
                                        } elseif($data > $today){
                                            $verify++;
                                        }

                                    } elseif($tipoData == 'periodo') {
                                        $data = $ceu['data_serie']['data_final'];
                                        if(!$data){
                                            $mesPubli = get_the_date('Y-m-d', $slide->ID);                                            
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
						//echo $qtSlide;
						
					?>
					<div id="carouselExampleIndicators" class="carousel slide col-sm-12" data-ride="carousel">
						<ol class="carousel-indicators">
						
						
							<?php while($m < $qtSlide) : ?>
								<li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $m; ?>" class="<?php if($m == 0){echo 'active';} ?>"></li>
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
												$imgSelect = get_field('capa_do_evento', $slide->ID);
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
													$tipoEvento = get_field('tipo_de_evento_tipo', $slide->ID);
													$atividades = get_the_terms( $slide->ID, 'atividades_categories' );
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
												<p><a href="<?php echo get_permalink( $slide->ID ); ?>"><?php echo $slide->post_title; ?></a></p>
											</div>
											<?php 
												$subTitle = get_field('subtitulo', $slide->ID);
												if($subTitle):
											?>
												<div class="carousel-subtitle">
													<p>- <?php echo $subTitle; ?></p>
												</div>

											<?php endif; ?>

											<div class="carousel-data">
												<?php
													$campos = get_field('data', $slide->ID);
													
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
																$dataEvento = get_the_date('Y-m-d', $slide->ID);
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
																	$dataEvento = get_the_date('Y-m-d', $slide->ID);
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
																$dataEvento = get_the_date('Y-m-d', $slide->ID);
																$dataEvento = explode("-", $dataEvento);
																$mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
																$mes = translateMonth($mes);                                                                        
																$dias = $mes;
															}

															$dataFinal = $dias; 
														}

													}
													if($tipoEvento == 'serie'){
														$participantes = get_field('ceus_participantes',  $slide->ID);
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
														$horario = get_field('horario', $slide->ID);

														

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
													$local = get_field('localizacao', $slide->ID);                                                        
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
		</div>

		<div class="search-home py-4" id='programacao'>
            <div class="container">
                
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h1>Encontre atividades que você goste</h1>
                        <p>As melhores atividades diretamente dos CEUs para a comunidade</p>
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
														<?php if(in_array($categoria->slug, $_GET['atividades'])) :?>
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
					
											// The Loop
											if ( $todasUnidades->have_posts() ) {
												
												while ( $todasUnidades->have_posts() ) {
													$todasUnidades->the_post();

													$titulo = htmlentities(get_the_title());
													$seletor = explode (" &amp;", $titulo);

													if(in_array( get_the_ID(), $_GET['unidades']) ) {
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

		<?php if($_GET['tipo_atividade'] && $_GET['tipo_atividade'] != '') : ?>

			<?php
				
				$paged = 1;
				if ( get_query_var('paged') ) $paged = get_query_var('paged');
				if ( get_query_var('page') ) $paged = get_query_var('page');
				
				$args = array(
					'post_type' => 'post',
					'paged' => $paged,
					'posts_per_page' => 20,			
					'tax_query' => array(
						'relation' => 'AND',
					),
								
				);

				if( isset($_GET['s']) && $_GET['s'] != ''){
					$s = $_GET['s'];

					$s = str_replace('-', '', $s);

					$args['s'] = $s;
				}

				if( isset($_GET['tipo_atividade']) && $_GET['tipo_atividade'] != '' ){
					
					$tipo = $_GET['tipo_atividade'];
					$today = date('Ymd');
					
					if($tipo == 'proxima'){
						// Proximas Atividades
						$args['orderby'] = 'meta_value_num';
						$args['order'] = 'ASC';
						
						$args['meta_query'] = array(
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
						);
						
					}

					elseif($tipo == 'encerrada'){
						// Proximas Atividades
						//$args['meta_key'] = 'data_data';
						$args['orderby'] = 'meta_value_num';
						$args['order'] = 'DESC';
						
						$args['meta_query'] = array(
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
								array(
									'key'     => 'ceus_participantes_$_data_serie_data',
									//'compare' => '>=', // depois ou igual a data de hoje
									'compare' => '<', // antes da data de hoje
									'value'   => $today,
								),
							)
						);
						
					}

					elseif($tipo == 'permanente'){					
						
						$args['meta_query'] = array(
							'relation' => 'AND',                            
							array(
								'key'   => 'data_tipo_de_data',
								'value' => 'semana',
							),
						);
						
					}
				}

				if( (isset($_GET['atividades']) && $_GET['atividades'] != '') || (isset($_GET['atividadesInternas']) && $_GET['atividadesInternas'] != '') ){
					$atividades = $_GET['atividades'];
					
					$args['tax_query'][] = array (
						'taxonomy' => 'atividades_categories',
						'field'    => 'slug',
						'terms'    => $atividades,
					);
				}

				if( isset($_GET['atividadesInternas']) && $_GET['atividadesInternas'] != ''){
					$atividadesInternas = $_GET['atividadesInternas'];
					
					$args['tax_query'][] = array (
						'taxonomy' => 'atividades_categories',
						'field'    => 'slug',
						'terms'    => $atividadesInternas,
					);
				}

				if( isset($_GET['publico']) && $_GET['publico'] != ''){
					$publico = $_GET['publico'];
					
					$args['tax_query'][] = array (
						'taxonomy' => 'publico_categories',
						'field'    => 'slug',
						'terms'    => $publico,
					);

					//echo "<pre>";
					//print_r($args);
					//echo "</pre>";
				}

				if( isset($_GET['faixaEtaria']) && $_GET['faixaEtaria'] != ''){
					$faixaEtaria = $_GET['faixaEtaria'];
					
					$args['tax_query'][] = array (
						'taxonomy' => 'faixa_categories',
						'field'    => 'slug',
						'terms'    => $faixaEtaria,
					);
				}

				if( isset($_GET['unidades']) && $_GET['unidades'] != ''){
					$unidades = $_GET['unidades'];
					
					foreach($unidades as $unidade){
						$unidadesBusca = array(
							'key'	 	=> 'localizacao',
							'value'	  	=> $unidade
						);
						$unidadesBusca2 = array(
							'key' => 'ceus_participantes_$_localizacao_serie',
							'value'	  	=> $unidade
						);
					}

					$args['meta_query'][] = array(
						'relation'	=> 'OR',
						array(
							'key'	 	=> 'localizacao',
							'value'	  	=> 31675
						),
						array(
							'key'	 	=> 'localizacao',
							'value'	  	=> 31675
						),
						$unidadesBusca,
						$unidadesBusca2					
					);
				}

				if( isset($_GET['periodos']) && $_GET['periodos'] != ''){
					$periodos = $_GET['periodos'];					

					if(in_array('manha', $periodos)){
						$args['meta_query'][] = array(
							'relation' => 'AND',
							array(
								'relation' => 'OR',
								array(
									'key'	 	=> 'horario_hora',
									'value' => '00:00:00',
									'compare' => '>='
								),
								array(
									'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
									'value' => '00:00:00',
									'compare' => '>='
								),
							),
		
							array(
								'relation' => 'OR',
								array(
									'key'	 	=> 'horario_hora',
									'value' => '11:59:59',
									'compare' => '<='
								),
								array(
									'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
									'value' => '11:59:59',
									'compare' => '<='
								),	
							),
						);
					}

					if(in_array('tarde', $periodos)){
						$args['meta_query'][] = array(
							'relation' => 'AND',
							array(
								'relation' => 'OR',
								array(
									'key'	 	=> 'horario_hora',
									'value' => '12:00:00',
									'compare' => '>='
								),
								array(
									'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
									'value' => '12:00:00',
									'compare' => '>='
								),
							),
		
							array(
								'relation' => 'OR',
								array(
									'key'	 	=> 'horario_hora',
									'value' => '18:59:59',
									'compare' => '<='
								),
								array(
									'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
									'value' => '18:59:59',
									'compare' => '<='
								),	
							),
						);
					}

					if(in_array('noite', $periodos)){
						$args['meta_query'][] = array(
							'relation' => 'AND',
							array(
								'relation' => 'OR',
								array(
									'key'	 	=> 'horario_hora',
									'value' => '19:00:00',
									'compare' => '>='
								),
								array(
									'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
									'value' => '19:00:00',
									'compare' => '>='
								),
							),
		
							array(
								'relation' => 'OR',
								array(
									'key'	 	=> 'horario_hora',
									'value' => '23:59:59',
									'compare' => '<='
								),
								array(
									'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
									'value' => '23:59:59',
									'compare' => '<='
								),	
							),
						);
					}

					
									
									
				}

				//if( isset($_GET['tipoData']) && $_GET['tipoData'] != ''){
					$tipoData = $_GET['tipoData'];

					// Dia da semana
					//if($tipoData == 'dia_semana'){

						if( isset($_GET['data']) && $_GET['data'] != ''){
							$diasSemana = $_GET['data'];

							$diasBusca = array();

							foreach($diasSemana as $dia){
								$diasBusca = array(
									'key'	 	=> 'data_dia_da_semana_$_selecione_os_dias',
									'value' => '"'.$dia.'"',
									'compare' 	=> 'LIKE',
								);
							}

							$args['meta_query'] = array(
								'relation'	=> 'OR',
								$diasBusca						
							);	
						}
					//}

					// Intervalo de data
					//if($tipoData == 'intervalo'){

						if( isset($_GET['start']) && $_GET['start'] != ''){
							$dtInicial = $_GET['start'];
							$dtFinal = $_GET['end'];
	
							$dtInicial = explode("/", $dtInicial);
							$dtFinal = explode("/", $dtFinal);
	
							$compareIni = $dtInicial[2] . $dtInicial[1] . $dtInicial[0];
							$compareFin = $dtFinal[2] . $dtFinal[1] . $dtFinal[0];	
							
							$args['meta_query'] = array(								
								array(
									'relation' => 'OR',
									array(
										'key'     => 'data_data',
										'compare' => '>=',
										'value'   => $compareIni,
									),
									array(
										'key'     => 'data_data_final',
										'compare' => '>=',
										'value'   => $compareIni,
									),
									array(
										'key'     => 'ceus_participantes_$_data_serie_data',
										'compare' => '>=',
										'value'   => $compareIni,
									),
								),
								array(
									'relation' => 'OR',
									array(
										'key'     => 'data_data',
										'compare' => '<=',
										'value'   => $compareFin,
									),
									array(
										'key'     => 'data_data_final',
										'compare' => '<=',
										'value'   => $compareFin,
									),
									array(
										'key'     => 'ceus_participantes_$_data_serie_data',
										'compare' => '<=',
										'value'   => $compareFin,
									),
								),								
							);												
						}
					//}

					
				//}

				$query = new WP_Query( $args );

				?>
				<?php if( isset($_GET['tipo_atividade']) && $_GET['tipo_atividade'] != ''): ?>
					<?php				
						$title = '';

						if($_GET['tipo_atividade'] == 'proxima'){
							$title = 'Próximas Atividades';                                    
						} elseif($_GET['tipo_atividade'] == 'permanente'){
							$title = 'Atividades Permanentes';                                    
						} else {
							$title = 'Atividades Encerradas';                                    
						}
					?>

					<div class="container mt-5">
						<div class="row">
							<div class="col-12">
								<div class="title-ativi">
									<h2><?= $title; ?></h2>
									<hr>
									<?php
										$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
										$newlink = removeParam($actual_link, 'tipo_atividade');
									?>
									<a href="<?= $newlink; ?>">Voltar ao Início</a>
								</div>
							</div>
						</div>
					</div>
					
				<?php else: ?>
					<div class="container">
						<div class="row">
							<div class="col-sm-12 mt-4 atividades-found">
								<?php 
									$count = $query->found_posts;
									if($count == 1){						 					
										echo '<p class="mb-0"><span>' . $count . '</span> ATIVIDADE ENCONTRADA</p>';
									} else {
										echo '<p class="mb-0"><span>' . $count . '</span> ATIVIDADES ENCONTRADAS</p>';
									}
								?>
							</div>
						</div>
					</div>
				<?php endif; ?>
				
				<?php

				// The Loop
				if ( $query->have_posts() ) {
					echo '<div class="tema-eventos my-4">';
					echo '<div class="container">';
					echo '<div class="row">';
						while ( $query->have_posts() ) {
							$query->the_post();
						?>
							<div class="col-sm-3">
								<div class="card-eventos mb-4">
									<div class="card-eventos-img aaaa">
										<?php 
											$imgSelect = get_field('capa_do_evento');
											$tipo = get_field('tipo_de_evento_selecione_o_evento');
											$online = get_field('tipo_de_evento_online');

											$featured_img_url = wp_get_attachment_image_src($imgSelect, 'recorte-eventos');
											if($featured_img_url){
												$imgEvento = $featured_img_url[0];
												//$thumbnail_id = get_post_thumbnail_id( $eventoInterno->ID );
												$alt = get_post_meta($imgSelect, '_wp_attachment_image_alt', true);  
											} else {
												$imgEvento = get_template_directory_uri().'/img/placeholder_portal_ceus.jpg';;
												$alt = get_the_title();
											}
										?>
										<a href="<?php echo get_the_permalink(); ?>"><img src="<?php echo $imgEvento; ?>" class="img-fluid d-block" alt="<?php echo $alt; ?>"></a>
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
											<h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
											<?php
																
												$campos = get_field('data', $eventoID);
												$tipoEvento = get_field('tipo_de_evento_tipo', $eventoID);
												
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
														$show = array();
														
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
												<?php if($hora) : ?>                                           
													<i class="fa fa-clock-o" aria-hidden="true"><span>icone horario</span></i> <?php echo convertHour($hora); ?>
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
					echo '</div>';
					echo '</div>';
				} else {
				
				}
				/* Restore original Post Data */
				
				wp_reset_postdata();

				

				//print_r($query);
			?>

			<div class="container mt-4">
				<div class="row">
					<div class="col-sm-12">
						<div class="pagination-prog text-center">
							<?php wp_pagenavi( array( 'query' => $query ) ); ?>
						</div>
					</div>
				</div>
			</div>
		
		<?php else : ?>

			<div class="container"> 
				<?php
					$resultsCount = 0;
					$today = date('Ymd');

					// Proximas Atividades
					$s = str_replace('-', '', $_GET['s']);
					//$s = str_replace('–', '', $_GET['s']);
					$args_next = array(
						'posts_per_page' => 8,
						'post_type' => 'post',
						'orderby'   => 'meta_value_num',
						'order'     => 'ASC',
						's' => $s,
						'meta_query' => array(
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
						)
					);
					if( (isset($_GET['atividades']) && $_GET['atividades'] != '') || (isset($_GET['atividadesInternas']) && $_GET['atividadesInternas'] != '') ){
						$atividades = $_GET['atividades'];
						
						$args_next['tax_query'][] = array (
							'taxonomy' => 'atividades_categories',
							'field'    => 'slug',
							'terms'    => $atividades,
						);
					}
		
					if( isset($_GET['atividadesInternas']) && $_GET['atividadesInternas'] != ''){
						$atividadesInternas = $_GET['atividadesInternas'];
						
						$args_next['tax_query'][] = array (
							'taxonomy' => 'atividades_categories',
							'field'    => 'slug',
							'terms'    => $atividadesInternas,
						);
					}
		
					if( isset($_GET['publico']) && $_GET['publico'] != ''){
						$publico = $_GET['publico'];
						
						$args_next['tax_query'][] = array (
							'taxonomy' => 'publico_categories',
							'field'    => 'slug',
							'terms'    => $publico,
						);
					}
		
					if( isset($_GET['faixaEtaria']) && $_GET['faixaEtaria'] != ''){
						$faixaEtaria = $_GET['faixaEtaria'];
						
						$args_next['tax_query'][] = array (
							'taxonomy' => 'faixa_categories',
							'field'    => 'slug',
							'terms'    => $faixaEtaria,
						);
					}
		
					if( isset($_GET['unidades']) && $_GET['unidades'] != ''){
						$unidades = $_GET['unidades'];
						
						foreach($unidades as $unidade){
							$unidadesBusca = array(
								'key'	 	=> 'localizacao',
								'value'	  	=> $unidade
							);
							$unidadesBusca2 = array(
								'key' => 'ceus_participantes_$_localizacao_serie',
								'value'	  	=> $unidade
							);
						}
		
						$args_next['meta_query'][] = array(
							'relation'	=> 'OR',
							array(
								'key'	 	=> 'localizacao',
								'value'	  	=> 31675
							),
							array(
								'key'	 	=> 'localizacao',
								'value'	  	=> 31675
							),
							$unidadesBusca,
							$unidadesBusca2					
						);
					}
		
					if( isset($_GET['periodos']) && $_GET['periodos'] != ''){
						$periodos = $_GET['periodos'];
						
		
						if(in_array('manha', $periodos)){
							$args_next['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '00:00:00',
										'compare' => '>='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '00:00:00',
										'compare' => '>='
									),
								),
			
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '11:59:59',
										'compare' => '<='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '11:59:59',
										'compare' => '<='
									),	
								),
							);
						}
		
						if(in_array('tarde', $periodos)){
							$args_next['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '12:00:00',
										'compare' => '>='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '12:00:00',
										'compare' => '>='
									),
								),
			
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '18:59:59',
										'compare' => '<='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '18:59:59',
										'compare' => '<='
									),	
								),
							);
						}
		
						if(in_array('noite', $periodos)){
							$args_next['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '19:00:00',
										'compare' => '>='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '19:00:00',
										'compare' => '>='
									),
								),
			
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '23:59:59',
										'compare' => '<='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '23:59:59',
										'compare' => '<='
									),	
								),
							);
						}			
										
					}

					if( isset($_GET['start']) && $_GET['start'] != ''){
						$dtInicial = $_GET['start'];
						$dtFinal = $_GET['end'];

						$dtInicial = explode("/", $dtInicial);
						$dtFinal = explode("/", $dtFinal);

						$compareIni = $dtInicial[2] . $dtInicial[1] . $dtInicial[0];
						$compareFin = $dtFinal[2] . $dtFinal[1] . $dtFinal[0];

						if($today < $compareFin){
							$args_next['meta_query'] = array(								
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'     => 'data_data',
										'compare' => '>=',
										'value'   => $compareIni,
									),
									array(
										'key'     => 'data_data_final',
										'compare' => '>=',
										'value'   => $compareIni,
									),
									array(
										'key'     => 'ceus_participantes_$_data_serie_data',
										'compare' => '>=',
										'value'   => $compareIni,
									),
								),
								array(
									'relation' => 'OR',
									array(
										'key'     => 'data_data',
										'compare' => '<=',
										'value'   => $compareFin,
									),
									array(
										'key'     => 'data_data_final',
										'compare' => '<=',
										'value'   => $compareFin,
									),
									array(
										'key'     => 'ceus_participantes_$_data_serie_data',
										'compare' => '<=',
										'value'   => $compareFin,
									),
								),															
							);	
							
						}
											
					}

					wp_enqueue_style('slick_css');
					wp_enqueue_style('slick_theme_css');		
					wp_enqueue_script('slick_min_js');
					wp_enqueue_script('slick_func_js');

					// The Query
					$the_query = new \WP_Query( $args_next );
					//echo "<pre>";
					//print_r($the_query);
					//echo "</pre>";
	
					// The Loop
					if ( $the_query->have_posts() ) {
						$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						echo '<div class="title-ativi mt-5">';
							echo '<h2>Próximas Atividades</h2><hr>';                                    
							echo '<a href="' . $actual_link . '&tipo_atividade=proxima">Ver todas</a>';
						echo '</div>';

						echo '<div class="row-slide" style="margin-left: -15px; margin-right: -15px;">';

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
					$resultsCount++;
					}
					/* Restore original Post Data */
					wp_reset_postdata();
					
				?>                    
			</div>

			<div class="container">

				<?php
					
					// Atividades Permanentes
					$s = str_replace('-', '', $_GET['s']);
					//$s = str_replace('–', '', $_GET['s']);
					$args = array(
						'posts_per_page' => 8,
						's' => $s,
						'meta_query' => array(
							'relation' => 'AND',                            
							array(
								'key'   => 'data_tipo_de_data',
								'value' => 'semana',
							),                            
						)
					);

					if( (isset($_GET['atividades']) && $_GET['atividades'] != '') || (isset($_GET['atividadesInternas']) && $_GET['atividadesInternas'] != '') ){
						$atividades = $_GET['atividades'];
						
						$args['tax_query'][] = array (
							'taxonomy' => 'atividades_categories',
							'field'    => 'slug',
							'terms'    => $atividades,
						);
					}
		
					if( isset($_GET['atividadesInternas']) && $_GET['atividadesInternas'] != ''){
						$atividadesInternas = $_GET['atividadesInternas'];
						
						$args['tax_query'][] = array (
							'taxonomy' => 'atividades_categories',
							'field'    => 'slug',
							'terms'    => $atividadesInternas,
						);
					}
		
					if( isset($_GET['publico']) && $_GET['publico'] != ''){
						$publico = $_GET['publico'];
						
						$args['tax_query'][] = array (
							'taxonomy' => 'publico_categories',
							'field'    => 'slug',
							'terms'    => $publico,
						);
					}
		
					if( isset($_GET['faixaEtaria']) && $_GET['faixaEtaria'] != ''){
						$faixaEtaria = $_GET['faixaEtaria'];
						
						$args['tax_query'][] = array (
							'taxonomy' => 'faixa_categories',
							'field'    => 'slug',
							'terms'    => $faixaEtaria,
						);
					}
		
					if( isset($_GET['unidades']) && $_GET['unidades'] != ''){
						$unidades = $_GET['unidades'];
						
						foreach($unidades as $unidade){
							$unidadesBusca = array(
								'key'	 	=> 'localizacao',
								'value'	  	=> $unidade
							);
							$unidadesBusca2 = array(
								'key' => 'ceus_participantes_$_localizacao_serie',
								'value'	  	=> $unidade
							);
						}
		
						$args['meta_query'][] = array(
							'relation'	=> 'OR',
							array(
								'key'	 	=> 'localizacao',
								'value'	  	=> 31675
							),
							array(
								'key'	 	=> 'localizacao',
								'value'	  	=> 31675
							),
							$unidadesBusca,
							$unidadesBusca2					
						);
					}
		
					if( isset($_GET['periodos']) && $_GET['periodos'] != ''){
						$periodos = $_GET['periodos'];
				
						if(in_array('manha', $periodos)){
							$args['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '00:00:00',
										'compare' => '>='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '00:00:00',
										'compare' => '>='
									),
								),
			
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '11:59:59',
										'compare' => '<='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '11:59:59',
										'compare' => '<='
									),	
								),
							);
						}
		
						if(in_array('tarde', $periodos)){
							$args['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '12:00:00',
										'compare' => '>='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '12:00:00',
										'compare' => '>='
									),
								),
			
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '18:59:59',
										'compare' => '<='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '18:59:59',
										'compare' => '<='
									),	
								),
							);
						}
		
						if(in_array('noite', $periodos)){
							$args['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '19:00:00',
										'compare' => '>='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '19:00:00',
										'compare' => '>='
									),
								),
			
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '23:59:59',
										'compare' => '<='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '23:59:59',
										'compare' => '<='
									),	
								),
							);
						}			
										
					}
					
					wp_enqueue_style('slick_css');
					wp_enqueue_style('slick_theme_css');		
					wp_enqueue_script('slick_min_js');
					wp_enqueue_script('slick_func_js');
					wp_enqueue_script('lightbox_js');

					// The Query
					$the_query = new \WP_Query( $args );

					// The Loop
					if ( $the_query->have_posts() ) {
						$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						echo '<div class="title-ativi mt-5">';
							echo '<h2>Atividades Permanentes</h2><hr>';                                    
							echo '<a href="' . $actual_link . '&tipo_atividade=permanente">Ver todas</a>';
						echo '</div>';

						echo '<div class="row">';

							while ( $the_query->have_posts() ) {
								$the_query->the_post();

								$tipo_data = get_field('data_tipo_de_data');
								$data = get_field('data_data');
								
							?>
								<div class="col-sm-12 col-md-6 col-lg-3">                                    
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
					$resultsCount++;
					}
					/* Restore original Post Data */
					wp_reset_postdata();
					
				?>
				
			</div>

			<div class="container">                    
				<?php
					// Atividades Encerradas
					$s = str_replace('-', '', $_GET['s']);
					//$s = str_replace('–', '', $_GET['s']);
					$args_before = array(
						'posts_per_page' => 8,
						'post_type' => 'post',
						's' => $s,
						//'meta_key'  => 'data_data',
						'orderby'   => 'meta_value_num',
						'order'     => 'DESC',
						'meta_query' => array(
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
								array(
									'key' => 'ceus_participantes_$_data_serie_data',
									//'compare' => '>=', // depois ou igual a data de hoje
									'compare' => '<', // antes da data de hoje
									'value'   => $today,
								),
							)							
													                         
						)
					);
					if( (isset($_GET['atividades']) && $_GET['atividades'] != '') || (isset($_GET['atividadesInternas']) && $_GET['atividadesInternas'] != '') ){
						$atividades = $_GET['atividades'];
						
						$args_before['tax_query'][] = array (
							'taxonomy' => 'atividades_categories',
							'field'    => 'slug',
							'terms'    => $atividades,
						);
					}
		
					if( isset($_GET['atividadesInternas']) && $_GET['atividadesInternas'] != ''){
						$atividadesInternas = $_GET['atividadesInternas'];
						
						$args_before['tax_query'][] = array (
							'taxonomy' => 'atividades_categories',
							'field'    => 'slug',
							'terms'    => $atividadesInternas,
						);
					}
		
					if( isset($_GET['publico']) && $_GET['publico'] != ''){
						$publico = $_GET['publico'];
						
						$args_before['tax_query'][] = array (
							'taxonomy' => 'publico_categories',
							'field'    => 'slug',
							'terms'    => $publico,
						);
					}
		
					if( isset($_GET['faixaEtaria']) && $_GET['faixaEtaria'] != ''){
						$faixaEtaria = $_GET['faixaEtaria'];
						
						$args_before['tax_query'][] = array (
							'taxonomy' => 'faixa_categories',
							'field'    => 'slug',
							'terms'    => $faixaEtaria,
						);
					}
		
					if( isset($_GET['unidades']) && $_GET['unidades'] != ''){
						$unidades = $_GET['unidades'];
						
						foreach($unidades as $unidade){
							$unidadesBusca = array(
								'key'	 	=> 'localizacao',
								'value'	  	=> $unidade
							);
							$unidadesBusca2 = array(
								'key' => 'ceus_participantes_$_localizacao_serie',
								'value'	  	=> $unidade
							);
						}
		
						$args_before['meta_query'][] = array(
							'relation'	=> 'OR',
							array(
								'key'	 	=> 'localizacao',
								'value'	  	=> 31675
							),
							array(
								'key'	 	=> 'localizacao',
								'value'	  	=> 31675
							),
							$unidadesBusca,
							$unidadesBusca2					
						);
					}
		
					if( isset($_GET['periodos']) && $_GET['periodos'] != ''){
						$periodos = $_GET['periodos'];
		
						if(in_array('manha', $periodos)){
							$args_before['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '00:00:00',
										'compare' => '>='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '00:00:00',
										'compare' => '>='
									),
								),
			
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '11:59:59',
										'compare' => '<='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '11:59:59',
										'compare' => '<='
									),	
								),
							);
						}
		
						if(in_array('tarde', $periodos)){
							$args_before['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '12:00:00',
										'compare' => '>='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '12:00:00',
										'compare' => '>='
									),
								),
			
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '18:59:59',
										'compare' => '<='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '18:59:59',
										'compare' => '<='
									),	
								),
							);
						}
		
						if(in_array('noite', $periodos)){
							$args_before['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '19:00:00',
										'compare' => '>='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '19:00:00',
										'compare' => '>='
									),
								),
			
								array(
									'relation' => 'OR',
									array(
										'key'	 	=> 'horario_hora',
										'value' => '23:59:59',
										'compare' => '<='
									),
									array(
										'key'	 	=> 'horario_hora_periodo_0_periodo_hora_inicio',
										'value' => '23:59:59',
										'compare' => '<='
									),	
								),
							);
						}			
										
					}

					if( isset($_GET['start']) && $_GET['start'] != ''){
						$dtInicial = $_GET['start'];
						$dtFinal = $_GET['end'];

						$dtInicial = explode("/", $dtInicial);
						$dtFinal = explode("/", $dtFinal);

						$compareIni = $dtInicial[2] . $dtInicial[1] . $dtInicial[0];
						$compareFin = $dtFinal[2] . $dtFinal[1] . $dtFinal[0];

						if($today > $compareFin){							
							
							$args_before['meta_query'] = array(								
								'relation' => 'OR',
								array(
									'relation' => 'OR', // Mantenha isso apenas se necessário
									'key' => 'data_data',
									'value' => array($compareIni, $compareFin),
									'compare' => 'BETWEEN',
									'type' => 'DATE',
								),
								array(
									'relation' => 'OR', // Mantenha isso apenas se necessário
									'key' => 'data_data_final',
									'value' => array($compareIni, $compareFin),
									'compare' => 'BETWEEN',
									'type' => 'DATE',
								),								
							);
						}
											
					}

					wp_enqueue_style('slick_css');
					wp_enqueue_style('slick_theme_css');		
					wp_enqueue_script('slick_min_js');
					wp_enqueue_script('slick_func_js');
					wp_enqueue_script('lightbox_js');

					$queryVerify = new \WP_Query( $args_before );

					$remove = array();

					if ( $queryVerify->have_posts() ) {
						while ( $queryVerify->have_posts() ) {
							$queryVerify->the_post();

							
							$campos = get_field('data', get_the_ID());
							
							// Verifica se possui campos
							if($campos){

								//print_r($campos);

								if($campos['tipo_de_data'] == 'data'){ // Se for do tipo data
									
									$dataEventoVer = $campos['data'];

									if(!$dataEventoVer){
										$dataEventoVer = get_the_date('Y-m-d');
									}

								} elseif($campos['tipo_de_data'] == 'periodo'){									
									
									$dataEventoVer = $campos['data_final'];

									if(!$dataEventoVer){ // Verifica se possui a data final
										$dataEventoVer = get_the_date('Y-m-d');
									}

								}

							}

							if($tipoEvento == 'serie'){
								$participantes = get_field('ceus_participantes',  get_the_ID());
								$countPart = count($participantes);
								$countPart = $countPart - 1;
								
								$dtInicial = $participantes[0]['data_serie'];
								$dtFinal = $participantes[$countPart]['data_serie'];

								if($dtFinal['tipo_de_data'] == 'data'){
									$dataEventoVer = $dtFinal['data'];
									if(!$dataEventoVer){ // Verifica se possui a data final
										$dataEventoVer = get_the_date('Y-m-d');
									}
								}											
							}

							if($dataEventoVer)
								$dataEventoVer = str_replace('-', '', $dataEventoVer);
												
							if($dataEventoVer >= $today){
								$remove[] = get_the_ID(); 
							}

						}

					}

					wp_reset_postdata();

					if($remove){
						$args_before['post__not_in'] = $remove;
					}

					// The Query
					$the_query = new \WP_Query( $args_before );

					// The Loop
					if ( $the_query->have_posts() ) {
						$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						echo '<div class="title-ativi">';
							echo '<h2>Atividades Encerradas</h2><hr>';                                    
							echo '<a href="' . $actual_link . '&tipo_atividade=encerrada">Ver todas</a>';
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
					$resultsCount++;
					}
					/* Restore original Post Data */
					wp_reset_postdata();
					
				?>                    
			</div>

		<?php endif; ?>

		<?php if($resultsCount == 3): ?>
			<div class="container">
				<div class="row">
					<div class="col-12 mt-5">
						<div class="no-results">
							<img src="<?= get_template_directory_uri();?>/img/search-empty.png" alt="">
							<p>Não há conteúdo disponível para o termo buscado. Por favor faça uma nova busca.</p>
						</div>							
					</div>
				</div>
			</div>				
		<?php endif; ?>

		<br><br>
	</div>

<?php get_footer();