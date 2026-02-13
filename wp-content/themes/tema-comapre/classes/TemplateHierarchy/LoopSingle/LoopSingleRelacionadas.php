<?php

namespace Classes\TemplateHierarchy\LoopSingle;


class LoopSingleRelacionadas extends LoopSingle
{
	private $id_post_atual;
	protected $args_relacionadas;
	protected $query_relacionadas;

	public function __construct($id_post_atual)
	{
		$this->id_post_atual = $id_post_atual;
		//$this->init();
		$this->my_related_posts();
	}


	public function getComplementosRelacionadas($id_post){
		$dt_post = get_the_date('d/m/Y g\hi');
		$categoria = get_the_category($id_post)[0]->name;

		return '<p class="fonte-doze font-italic mb-0">Publicado em: '.$dt_post.' - em '.$categoria.'</p>';


	}

	public function compareByTimeStamp($time1, $time2){ 
		if (strtotime($time1) < strtotime($time2)) 
			return -1; 
		else if (strtotime($time1) > strtotime($time2))  
			return 1; 
		else
			return 0; 
	}

	public function getDatesInRange($startDate, $endDate) {
		$dates = array();
		$currentDate = clone $startDate;

		while ($currentDate <= $endDate) {
			$dates[] = $currentDate->format('Y-m-d');
			$currentDate->add(new \DateInterval('P1D'));
		}

		return $dates;
	}

	public function compareDaysOfWeek($a, $b) {
		$daysOrder = array(
			'Segunda' => 1,
			'Terça' => 2,
			'Quarta' => 3,
			'Quinta' => 4,
			'Sexta' => 5,
			'Sábado' => 6,
			'Domingo' => 7
		);

		return $daysOrder[$a] - $daysOrder[$b];
	}

	public function convertData($data){ 
		$dataEvento = $data;

		$dataEvento = explode("-", $dataEvento);
		$mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
		$mes = translateMonth($mes);
		$data = $dataEvento[2] . " " . $mes . " " . $dataEvento[0];

		return $data;
	} 
	
	public function my_related_posts() {
		global $post;
		$tipoEvento = get_field('tipo_de_evento_tipo', $post->ID);
		$group_field = get_field( "tipo_de_evento", $post->ID );
		if($group_field['evento_principal'] == 'parte' || $group_field['tipo'] == 'singular') :

			$local = get_field('localizacao', $post->ID);							
			$infosBasicas = get_field('informacoes_basicas', $local);
			$zona = get_group_field( 'informacoes_basicas', 'zona_sp', $local );

			//print_r($group_field);

	?>
		<?php if($local == '31675' || $local == '31244' || ($group_field['tipo'] == 'serie' && $group_field['evento_principal'] == 'parte') ): ?>

		<?php else : ?>
			<div class="end-footer py-4 col-12 color-<?php echo $zona; ?>">
				<div class="container">
					<div class="row">
						<div class="col-md-6">

							<div class="end-title-unidade my-3">
								<p><?php echo get_the_title($local); ?></p>
							</div>
							
							<div class="end-infos">
								<p>
									<?php 
										if($infosBasicas['endereco'] && $infosBasicas['endereco'] != ''){
											echo $infosBasicas['endereco'];
										}

										if($infosBasicas['numero'] && $infosBasicas['numero'] != ''){
											echo ', ' . $infosBasicas['numero'];
										}

										if($infosBasicas['complemento'] && $infosBasicas['complemento'] != ''){
											echo ' - ' . $infosBasicas['complemento'];
										}

										if($infosBasicas['bairro'] && $infosBasicas['bairro'] != ''){
											echo ' - ' . $infosBasicas['bairro'];
										}

										if($infosBasicas['cep'] && $infosBasicas['cep'] != ''){
											echo ' - CEP: ' . $infosBasicas['cep'];
										}
									?>
								</p>

								<?php if($infosBasicas['email'] != ''): ?>								
									<p><i class="fa fa-envelope" aria-hidden="true"></i> 
									<?php 
										$email_primary = $infosBasicas['email']['email_principal'];
										$email_second = $infosBasicas['email']['email_second'];

										if($email_primary && $email_primary != ''){
											echo $email_primary;
										}
									
										if($email_second && $email_second != ''){
											foreach($email_second as $email){
												echo '<br>' . $email['email'];
											}
										}                        
									?>
									</p>
								<?php endif; ?>

								<?php if($infosBasicas['telefone'] != ''): ?>								
									<p><i class="fa fa-phone" aria-hidden="true"></i> 
										<?php 
											$tel_primary = $infosBasicas['telefone']['telefone_principal'];
											$tel_second = $infosBasicas['telefone']['tel_second'];

											if($tel_primary && $tel_primary != ''){
												echo $tel_primary;
											}
										
											if($tel_second && $tel_second != ''){
												foreach($tel_second as $tel){
													echo ' / ' . $tel['telefone_sec'];
												}
											}                        
										?>
									</p>
								<?php endif; ?>
								
							</div>
						</div>

						<div class="col-md-6">
							<div id="map" style="width: 100%; height: 350px;"></div>
							<a href="#map" class="story" data-point="<?php echo $infosBasicas['latitude']; ?>,<?php echo $infosBasicas['longitude']; ?>,<div class='marcador-unidade  color-<?php echo $infosBasicas['zona_sp']; ?>'><p class='marcador-title'><?php echo get_the_title($local); ?></p><p><?php echo $infosBasicas['endereco'];?> nº <?php echo $infosBasicas['numero']; ?> - <?php echo $infosBasicas['bairro']; ?></p><p>CEP: <?php echo $infosBasicas['cep']; ?></p></div>,<?php echo $infosBasicas['zona_sp']; ?>" style="display: none;"> &nbsp;destacar no mapa</a></span>                  
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>		
		<?php
		else:

			$args = array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'meta_query'	=> array(
					'relation'		=> 'AND',					
					array(
						'key'	  	=> 'tipo_de_evento_selecione_o_evento',
						'value'	  	=> $post->ID,
						'compare' 	=> '=',
					)
				),
			);

			

			$diasEventos = array();
			$atividadesEventos = array();
			$unidadesEventos = array();

			
			// The Query
			$the_query = new \WP_Query( $args );

			// The Loop
			if ( $the_query->have_posts() ) {
				
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$tipoEventoFiltro = get_field('tipo_de_evento_tipo', $post->ID);					

					$atividadesEventos[] = get_the_terms( $post->ID, 'atividades_categories' );
					
					if($tipoEventoFiltro == 'serie'){						
						$participantes = get_field('ceus_participantes', $post->ID);
						
						if($participantes && $participantes != ''){
							foreach($participantes as $participante){
								
								$unidadesEventos[] = $participante['localizacao_serie'];
										
								if($participante['data_serie']['tipo_de_data'] == 'data'){ // Se for do tipo data
								
									$diasEventos[] = $participante['data_serie']['data'];
	
								}elseif($participante['data_serie']['tipo_de_data'] == 'periodo'){
	
									$startDate = new \DateTime($participante['data_serie']['data']); // data inicio
									$endDate = new \DateTime($participante['data_serie']['data_final']); // data final
	
									$datesInRange = $this->getDatesInRange($startDate, $endDate);
	
									foreach ($datesInRange as $date) {
										$diasEventos[] = $date;
									}
	
								} elseif($participante['data_serie']['tipo_de_data'] == 'semana'){
									//$total = count($participante['selecione_os_dias']);
									$semana = $participante['data_serie']['dia_da_semana'];
									if($semana != ''){
										foreach($semana as $dias){
											foreach($dias['selecione_os_dias'] as $dia){
												$diasSemana[] = $dia;
											}									
										}
									}
	
	
								}		
							}
						}
					} else {

						$unidadesEventos[] = get_field('localizacao', $post->ID);

						$campos = get_field('data', get_the_ID());
					
						if($campos){
							if($campos['tipo_de_data'] == 'data'){ // Se for do tipo data
								
								$diasEventos[] = $campos['data'];

							}elseif($campos['tipo_de_data'] == 'periodo'){

								$startDate = new \DateTime($campos['data']); // data inicio
								$endDate = new \DateTime($campos['data_final']); // data final

								$datesInRange = $this->getDatesInRange($startDate, $endDate);

								foreach ($datesInRange as $date) {
									$diasEventos[] = $date;
								}

							} elseif($campos['tipo_de_data'] == 'semana'){
								//$total = count($campos['selecione_os_dias']);
								$semana = $campos['dia_da_semana'];
								if($semana != ''){
									foreach($semana as $dias){
										foreach($dias['selecione_os_dias'] as $dia){
											$diasSemana[] = $dia;
										}									
									}
								}
							}
						}

					}
									
				}
				
			}

			$filtroAtividades = array();

			foreach($atividadesEventos as $atividades){

				if($atividades != ''){
					foreach($atividades as $atividade){
						if($atividade->parent == 0){
							//$filtroAtividades[] = $atividade->term_id;
						} else {
							$filtroAtividades[$atividade->parent][] = $atividade->term_id;
						}
					}
				}				

			}
										

			$filtroUnidades = array_unique($unidadesEventos);

			$unidadesFiltro = array();
			if($filtroUnidades){
				foreach($filtroUnidades as $unidade){
					$unidadesFiltro[$unidade] = get_the_title($unidade);
				}
			}
			asort($unidadesFiltro, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

			
			wp_reset_postdata();

			if($diasSemana != ''){
				$diasSemana = array_unique($diasSemana); // remove datas iguais
				usort($diasSemana, array($this, 'compareDaysOfWeek')); // Ordena por dia da semana
			}
			
			usort($diasEventos, array($this, 'compareByTimeStamp')); // Ondena por data
			$diasEventos = array_unique($diasEventos); // remove datas iguais
		?>

		<?php if($tipoEvento != 'serie'): ?>

			<div class="container mt-3 px-0">
			
				<div class="search-home search-event py-4 col-12" id='programacao'>
					<div class="">
						
						<div class="">
							<div class="col-sm-12 text-center">							
								<?php 

									// Unidades
									$unidades = get_terms( array( 
										'taxonomy' => 'category',
										'parent'   => 0,                                
										'hide_empty' => false,
										'exclude' => 1
									) );								
								?>
							</div>
							<form action="<?php echo get_the_permalink(); ?>" class="row">
								
								<div class="col-sm-12 col-md-6">
									<label for="atividades">Atividade(s) de interesse</label>
									<select name="atividades[]" multiple="multiple" class="ms-list-1" id="atividades">
										
										<?php 
											foreach($filtroAtividades as $chave => $filtro): 
												$catPrincipal = get_term( $chave, 'atividades_categories' );
												$catSubs = array_unique($filtro);
											?>
												<optgroup label="<?= $catPrincipal->name; ?>">
													<?php foreach($catSubs as $term):
														$categoria = get_term( $term, 'atividades_categories' );
													?>
														<?php if(in_array($categoria->slug, $_GET['atividades'])) :?>
															<option value="<?= $categoria->slug; ?>" selected><?= $categoria->name; ?></option>
														<?php else: ?>
															<option value="<?= $categoria->slug; ?>"><?= $categoria->name; ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												</optgroup>

											<?php
											endforeach;
										?>

									</select>
								</div>

								<div class="col-sm-12 col-md-6">
									<label for="unidades">CEUs</label>
									<select name="unidades[]" multiple="multiple" class="ms-list-5" id="unidades">
									<?php foreach($unidadesFiltro as $key => $value): ?>

										<?php 
											if(in_array( $key, $_GET['unidades']) ) {
														echo '<option selected value="' . $key .'">' . $value .'</option>';
											} else {
												echo '<option value="' . $key .'">' . $value .'</option>';
											}
										?>											
										<?php endforeach; ?> 

									</select>
								</div>

								<div class="col-sm-12 col-md-6 mt-3">
									<label for="tipoData">Data</label>									
									<?php if(!empty($diasEventos)): ?>
										<select name='data' class="form-control" id="tipoData">
											<option value="" disabled selected>Selecione a data</option>
											
											<?php foreach($diasEventos as $dia) : ?>
												<?php if($_GET['data'] == $dia): ?>
													<option value="<?php echo $dia; ?>" selected><?php echo $this->convertData($dia); ?></option>
												<?php else : ?>
													<option value="<?php echo $dia; ?>"><?php echo $this->convertData($dia); ?></option>
												<?php endif; ?>
											<?php endforeach; ?>  
										</select>
									<?php else: ?>
										<select name='data'  class="form-control" id="tipoData" disabled>
											<option value="" disabled selected>Selecione a data</option>
										</select> 
									<?php endif; ?>
									
								</div>

								<div class="col-sm-12 col-md-6 mt-3">
									<label for="semana">Dias da semana</label>
									<?php if(!empty($diasSemana)): ?>
										<select name="semana[]" multiple="multiple" class="ms-list-9"> 
											<optgroup label="Dia da semana">
												<?php 
													foreach($diasSemana as $dia):														
														$valor = str_replace('ç', 'c', $dia); // Remover caracteres especiais
														$valor = str_replace('á', 'a', $valor); // Remover caracteres especiais
														$valor = strtolower($valor); // Converter para minúsculas
												?>
													<option value="<?= $valor; ?>" <?= in_array($valor, $_GET['semana']) ? "selected" : ""; ?>><?= $dia; ?></option>
												<?php endforeach; ?>
											</optgroup>
										</select>
									<?php else: ?>
										<select name="semana[]" multiple="multiple" class="ms-list-9" disabled></select> 
									<?php endif; ?>
								</div>
								
								<div class="col-12 text-right mt-3" style="align-self: flex-end;">
									<a href="<?= get_the_permalink(); ?>" class="btn btn-light btn-clear mr-2">Limpar busca</a>
									<button type="submit" class="btn btn-search rounded-0">Buscar</button>
								</div>
								
							</form> <!-- end form -->
						</div> <!-- end row -->
					</div>
				</div>

			</div>

		<?php endif; ?>

		<?php
						
			if( isset($_GET['data']) && $_GET['data'] != ''){
				$diaEvento = $_GET['data'];

				$diaEvento = str_replace('-', '', $diaEvento);

				$args['meta_query'][] = array(

					array(
						'relation' => 'OR',
						array(
							'key'     => 'data_data',
							'compare' => '=', // depois ou igual a data de hoje
							//'compare' => '<', // antes da data de hoje
							'value'   => $diaEvento,
						),
						array(
							'key'     => 'data_data_final',
							'compare' => '=', // depois ou igual a data de hoje
							//'compare' => '<', // antes da data de hoje
							'value'   => $diaEvento,
						),
						array(
							'key'     => 'ceus_participantes_$_data_serie_data',
							'compare' => '=', // depois ou igual a data de hoje
							//'compare' => '<', // antes da data de hoje
							'value'   => $diaEvento,
						),
					),		
				);
			}

			if( isset($_GET['semana']) && $_GET['semana'] != ''){
				$diasSemana = $_GET['semana'];

				$diasBusca = array(
					'relation'	=> 'OR',
				);

				foreach($diasSemana as $dia){
					$diasBusca[] = array(
						'key'	 	=> 'data_dia_da_semana_$_selecione_os_dias',
						'value' => '"'.$dia.'"',
						'compare' 	=> 'LIKE',
					);
				}

				$args['meta_query'][] = array(
					$diasBusca						
				);	
			}

			if( isset($_GET['atividades']) && $_GET['atividades'] != ''){
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

			if( isset($_GET['unidades']) && $_GET['unidades'] != ''){
				$unidades = $_GET['unidades'];
				
				$unidades = $_GET['unidades'];
				$unidadesBusca = array();

				$unidadesBusca['relation'] = 'OR';

				foreach($unidades as $unidade){
					$unidadesBusca[] = array(
						'key'	 	=> 'localizacao',
						'value'	  	=> $unidade
					);
					$unidadesBusca[] = array(
						'key' => 'ceus_participantes_$_localizacao_serie',
						'value'	  	=> $unidade
					);
				}

				

				$args['meta_query'][] = array(
					//'relation'	=> 'OR',				
					$unidadesBusca				
				);				
			}
			
			// The Query
			$the_query = new \WP_Query( $args );
			
			// The Loop
			if ( $the_query->have_posts() ) {

				echo '<div class="tema-eventos my-4 col-12">';
                	echo '<div class="container px-0">';
						echo '<div class="row">';
						
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
							?>
								<div class="col-sm-3">
									<div class="card-eventos">
										<div class="card-eventos-img">
											<?php 
												
												$imgSelect = get_field('capa_do_evento', get_the_ID());
												$tipo_evento = get_field('tipo_de_evento');
												$tipo = get_field('tipo_de_evento_selecione_o_evento', get_the_ID());
																							
												$featured_img_url = wp_get_attachment_image_src($imgSelect, 'recorte-eventos');
												if($featured_img_url){
													$imgEvento = $featured_img_url[0];													
													$alt = get_post_meta($imgSelect, '_wp_attachment_image_alt', true);  
												} else {
													$imgEvento = get_template_directory_uri().'/img/placeholder_portal_ceus.jpg';
													$alt = get_the_title(get_the_ID());
												}
											?>
											<a href="<?php echo get_the_permalink(); ?>"><img src="<?php echo $imgEvento; ?>" class="img-fluid d-block" alt="<?php echo $alt; ?>"></a>
											
											<?php if($tipo && $tipo != '') : 
												echo '<span class="flag-pdf-full">';
													echo get_the_title($tipo);
												echo '</span>';
											endif; ?>

										</div>
										<div class="card-eventos-content p-2">
											<div class="evento-categ border-bottom pb-1">
												<?php
													$atividades = get_the_terms( get_the_ID(), 'atividades_categories' );
													
													$listaAtividades = array();
													$atividadesTotal = 0;
													if($atividades){
														$atividadesTotal = count($atividades);
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
											<h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
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
														
														
                                                    } else if($tipoEvento == 'outro'){
														$dataFinal = 'Múltiplas Datas';
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
												//print_r($tipo_evento);
												$local = get_field('localizacao', get_the_ID());                                                        
												if($local == '31675' || $local == '31244'):
											?>
												<p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></p>
											
											<?php elseif($tipo_evento['tipo'] == 'serie'): ?>
												<p class="mb-0 mt-1 evento-unidade no-link"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> Múltiplas Unidades</p>
											<?php else: ?>
												<p class="mb-0 mt-1 evento-unidade"><a href="<?php echo get_the_permalink($local); ?>"><i class="fa fa-map-marker" aria-hidden="true"><span>icone unidade</span></i> <?php echo get_the_title($local); ?></a></p>
											<?php endif; ?>
										</div>
									</div>
									<?php 
										
										$term_obj_list = get_the_terms( get_the_ID(), 'atividades_categories' );
										$terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
										
									?>
								</div>

							<?php
								
							}

						echo '</div>';
					echo '</div>';
				echo '</div>';

			} else {
				// no posts found
			}
			/* Restore original Post Data */
			wp_reset_postdata();
			
		endif;
		
	}
}