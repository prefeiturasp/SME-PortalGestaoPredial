<?php
namespace Classes\TemplateHierarchy\LoopSingle;
class LoopSingleNoticiaPrincipal extends LoopSingle
{
	public function __construct()
	{
		$this->init();
	}
	public function init()
	{
		$this->montaHtmlNoticiaPrincipal();
	}
	public function montaHtmlNoticiaPrincipal(){
		if (have_posts()):
			while (have_posts()): the_post();
				echo '<article class="col-sm-12 border-bottom">';
			?>

				<?php 
					$categories = get_the_category();
					$category_id = $categories[0]->cat_ID;

					$classi = get_field('faixa_etaria');
					$publico = get_field('publico');
					$espaco = get_field('local_espaco');
					$inscri = get_field('inscricoes');
					$datas = get_field('data'); // Datas
					$horario = get_field('horario');
					$tipo_evento = get_field('tipo_de_evento_tipo');
					$turmas = get_field('turmas');
					$sobre = get_field('tipo_de_evento');
					
					$tipo = get_field('tipo_de_evento_selecione_o_evento', get_the_ID());
				?>

				<div class="col-md-5 evento-details d-block d-md-none">
					<table class="table border-right border-left border-bottom">                            
						<tbody>

							<?php if( $tipo && $tipo != '' && $sobre['evento_principal'] == 'parte' ): ?>
								<tr>
									<th scope="row" class="align-middle bg-tipo"><i class="fa fa-globe" aria-hidden="true"></i></th>
									<td class="py-4 bg-tipo">
										<p class='m-0'>	
											Esse evento pertence a: “<strong><?php echo get_the_title($tipo); ?></strong>”.<br>
											<strong><a href="<?php echo get_the_permalink($tipo); ?>">Clique aqui</a></strong> para conferir o restante da programação
										</p></td>                                    
								</tr>
							<?php endif; ?>

							<?php //print_r($tipo_evento); ?>

							<?php
								// Verifica se possui campos
								if($datas){

									if($datas['tipo_de_data'] == 'data'){ // Se for do tipo data
										
										$dataEvento = $datas['data'];

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
										
									} else if($datas['tipo_de_data'] == 'periodo'){
										
										$dataInicial = $datas['data'];
										$dataFinal = $datas['data_final'];

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
											$dataEvento = $dataInicial;
															
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

									} elseif($datas['tipo_de_data'] == 'semana'){ // se for do tipo semana
										$semana = $datas['dia_da_semana'];
										
										
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
										
									}

								}
							?>

							<?php if($tipo_evento != 'serie') : ?>

								<tr>
									<th scope="row" class="align-middle"><i class="fa fa-calendar" aria-hidden="true"></i></th>
									<td><?php echo $dataFinal; ?></td>                                    
								</tr>

							<?php endif; // Fim tipo_evento ?>

							<?php
								// Exibe os horários

								if($horario['selecione_o_horario'] == 'horario'){
									$hora = $horario['hora'];
								} elseif($horario['selecione_o_horario'] == 'periodo'){
									
									$hora = '';
									$k = 0;
									
									foreach($horario['hora_periodo'] as $periodo){
										
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
								<tr>
									<th scope="row" class="align-middle"><i class="fa fa-clock-o" aria-hidden="true"></i></th>
									<td><?php echo convertHour($hora); ?></td> 
								</tr>
							<?php endif; ?>

							<?php if($classi != '') : ?>
								<tr>
									<th scope="row" class="align-middle"><i class="fa fa-star" aria-hidden="true"></i></th>
									<td><?php 
										//print_r($classi);
										$m = 0;
										foreach($classi as $faixa){
											echo "<p class='m-0'>";
												if($faixa != ''){
													$term = get_term( $faixa, 'faixa_categories' );
													echo "<span class='blue-info'>Faixa etária</span> - " . $term->name;
												}
											echo "</p>";
										}
										//print_r($classi);
									?></td>                                    
								</tr>
							<?php endif; ?>

							<?php if($publico != '') : ?>
								<tr>
									<th scope="row" class="align-middle"><i class="fa fa-user-circle" aria-hidden="true"></i></th>
									<td><?php 
										
										$m = 0;
										foreach($publico as $alvo){
											echo "<p class='m-0'>";
												if($alvo != ''){
													$term = get_term( $alvo, 'publico_categories' );
													echo "<span class='blue-info'>Público alvo</span> - " . $term->name;
												}
											echo "</p>";
										}
										
									?></td>                                    
								</tr>
							<?php endif; ?>	
							
							<?php 
								if($turmas && $turmas != ''):
							?>
								<tr>
									<th scope="row" class="align-middle"><i class="fa fa-users" aria-hidden="true"></i></th>
									<td>
										<?php foreach($turmas as $turma) :?>
											
											<p class="mb-0">
												<span class='blue-info'><?php echo $turma['nome_da_turma']; ?></span> 
												- 
												<?php
													if($datas){

														if($datas['tipo_de_data'] == 'data'){ // Se for do tipo data
															
															$dataEvento = $datas['data'];
		
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
		
															echo $dataFinal;
															
														} else if($datas['tipo_de_data'] == 'periodo'){
															
															$dataInicial = $datas['data'];
															$dataFinal = $datas['data_final'];
		
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

															echo $dataFinal;
		
														} elseif($datas['tipo_de_data'] == 'semana'){ // se for do tipo semana
															
															$i = $turma['data'] - 1;
															$semana = $datas['dia_da_semana'][$i];
															
															foreach($semana as $dias){
		
																$total = count($dias); 
																$i = 0;
																$diasShow = '';
																$show = array();
																
																foreach($dias as $diaS){
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

																if(!$diasShow){
																	$dataEvento = get_the_date('Y-m-d');
																	$dataEvento = explode("-", $dataEvento);
																	$mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
																	$mes = translateMonth($mes);                                                                        
																	$diasShow = $mes;
																}
		
																echo $diasShow;
															}
														}
		
													}
												?>
												- 
												<?php
													if($horario['selecione_o_horario'] == 'horario'){
														$hora = $horario['hora'];
														echo convertHour($hora);
													} else {
														$hora = '';
														$i = $turma['horario'] - 1;

														$periodo = $horario['hora_periodo'][$i];
															
														if($periodo['periodo_hora_inicio']){
															$hora .= $periodo['periodo_hora_inicio'];
														} 
														
														if ($periodo['periodo_hora_final']){
															$hora .= ' às ' . $periodo['periodo_hora_final'];
														}
														echo convertHour($hora);
													}
												?>
											</p>														
										<?php endforeach; ?>
									</td>
								</tr>
							
							<?php endif; ?>	

							<?php
								global $post;
								$local = get_field('localizacao', $post->ID); 
							?>

							<?php 
								if($local && $local != ''):
							?>

								<tr>
									<th scope="row" class="align-middle"><i class="fa fa-map-marker" aria-hidden="true"></i></th>
									<?php if($local == 31244 || $tipo_evento == 'serie'): ?>
										<td><p class="m-0">Consulte abaixo CEUs participantes</p></td>
									<?php elseif($local == 31675): ?>
										<td><p class="m-0"><strong>Para toda a rede</strong></p></td>
									<?php else: ?>
										<td><strong><?php echo get_the_title($local); ?></strong>
										<?php 
											$end = get_field('informacoes_basicas', $local);
											
											if($end != '') : ?>
												<br>
												<?php echo $end['endereco'] . ', ' . $end['numero'] . ' - ' .$end['bairro'] . ' - CEP: ' .$end['cep']; ?>
											<?php endif; ?>	
										</td>
									<?php endif; ?>                                
								</tr>

							<?php endif; ?>

							<?php if($espaco != '') : ?>
								<tr>
									<th scope="row" class="align-middle"><i class="fa fa-street-view" aria-hidden="true"></i></th>
									<td><?php echo $espaco->name;?></td>                                    
								</tr>
							<?php endif; ?>

							<?php
								//echo "<pre>";
								//print_r($inscri);
								//echo "</pre>";
								?>

							<?php if($inscri != '' && $inscri['info_inscricoes'] != '') : ?>
								<tr>
									<th scope="row" class="align-middle"><i class="fa fa-ticket" aria-hidden="true"></i></th>
									<?php if($inscri['info_inscricoes'] != '' && $inscri['link_inscricoes'] != '') : ?>
										<td><a href="<?php echo $inscri['link_inscricoes']; ?>"><?php echo $inscri['info_inscricoes']; ?></a></td>
									<?php elseif($inscri['info_inscricoes'] != '') : ?>
										<td><?php echo $inscri['info_inscricoes']; ?></td>
									<?php endif; ?>                                  
								</tr>
							<?php endif; ?>
						</tbody>
					</table>

					<?php if($tipo_evento == 'serie'): 

						$participantes = get_field('ceus_participantes');
						if($participantes != '') :

							foreach($participantes as $participante):
							?>

							<table class="table border-right border-left border-bottom">
								<thead>
									<tr>
										<td colspan="2"><?php echo get_the_title($participante['localizacao_serie']); ?></td>
									</tr>
								</thead>
								<tbody>

								<?php
									// Verifica se possui campos
									$datas = $participante['data_serie'];
									if($datas){

										if($datas['tipo_de_data'] == 'data'){ // Se for do tipo data
											
											$dataEvento = $datas['data'];

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
											
										} else if($datas['tipo_de_data'] == 'periodo'){
											
											$dataInicial = $datas['data'];
											$dataFinal = $datas['data_final'];

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

										} elseif($datas['tipo_de_data'] == 'semana'){ // se for do tipo semana
											$semana = $datas['dia_da_semana'];
											
											
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
											
										}

									}
								?>

								<?php if($dataFinal) : ?>

									<tr>
										<th scope="row" class="align-middle"><i class="fa fa-calendar" aria-hidden="true"></i></th>
										<td><?php echo $dataFinal; ?></td>                                    
									</tr>

								<?php endif; // Fim tipo_evento ?>

									<?php
										// Exibe os horários
										$horario = $participante['horario_serie'];

										if($horario['selecione_o_horario'] == 'horario'){
											$hora = $horario['hora'];
										} elseif($horario['selecione_o_horario'] == 'periodo'){
											
											$hora = '';
											$k = 0;
											
											foreach($horario['hora_periodo'] as $periodo){
												
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
										<tr>
											<th scope="row" class="align-middle"><i class="fa fa-clock-o" aria-hidden="true"></i></th>
											<td><?php echo convertHour($hora); ?></td> 
										</tr>
									<?php endif; ?>

									<?php
										$espacoSerie = $participante['local_espaco_serie'];
										if($espacoSerie):													
									?>

										<tr>
											<th scope="row" class="align-middle"><i class="fa fa-user-circle-o" aria-hidden="true"></i></th>
											<td><?php echo $espacoSerie->name; ?></td>
										</tr>

									<?php endif; ?>

									<?php 
										$end = get_field('informacoes_basicas', $participante['localizacao_serie']);
										
										if($end != '') : ?>

										<tr>
											<th scope="row" class="align-middle"><i class="fa fa-map-marker" aria-hidden="true"></i></th>
											<td><strong><?php echo get_the_title($participante['localizacao_serie']); ?></strong>
											
													<br>
													<?php echo $end['endereco'] . ', ' . $end['numero'] . ' - ' .$end['bairro'] . ' - CEP: ' .$end['cep']; ?>
												
											</td>
										</tr>

									<?php endif; ?>	
									
								</tbody>               
							</table>
								
							<?php		
							endforeach;

						endif; // participantes
						
						//echo "<pre>";
						//print_r($participantes);
						//echo "</pre>";

					?>

					<?php endif; // tipo evento ?>
				</div>
				
				<div class="evento-informacoes mt-4">
					<div class="container p-0">
						<div class="row">

							<div class="col-md-7 evento-descri">
								<h2>Descritivo do evento:</h2>
								
								<?php echo get_field('descricao'); ?>
								
								<?php
									$flyer = get_field('adicionar_midia');
									$link_flyer = get_field('adicionar_link');
									
								if(isset($flyer)): ?>
									<?php if(!$link_flyer): ?>
										<div class="flyer-evento py-3">
											<?php if($flyer['url']): ?>
												<img src="<?php echo $flyer['url']; ?>" alt="<?php echo $flyer['alt']; ?>" class="img-fluid d-block mx-auto w-75" alt="">
											<?php endif; ?>
											<?php if($flyer['caption']): ?>
												<p class="text-center"><?php echo $flyer['caption']; ?></p>
											<?php endif; ?>											
										</div>
									<?php else: ?>
										<div class="flyer-evento py-3">
											<?php if($flyer['url']): ?>
												<a href="<?php echo $link_flyer; ?>"><img src="<?php echo $flyer['url']; ?>" alt="<?php echo $flyer['alt']; ?>" class="img-fluid d-block mx-auto w-75" alt=""></a>
											<?php endif; ?>
											<?php if($flyer['caption']): ?>
												<p class="text-center"><?php echo $flyer['caption']; ?></p>
											<?php endif; ?>											
										</div>
									<?php endif; ?>
								<?php endif; ?>

							</div>

							<div class="col-md-5 evento-details d-none d-md-block">
								<table class="table border-right border-left border-bottom">                            
									<tbody>

										<?php if($tipo && $tipo != '' && $sobre['evento_principal'] == 'parte' && $sobre['tipo'] != 'singular') : ?>
											<tr>
												<th scope="row" class="align-middle bg-tipo"><i class="fa fa-globe" aria-hidden="true"></i></th>
												<td class="py-4 bg-tipo">
													<p class='m-0'>	
														Esse evento pertence a: “<strong><?php echo get_the_title($tipo); ?></strong>”.<br>
														<strong><a href="<?php echo get_the_permalink($tipo); ?>">Clique aqui</a></strong> para conferir o restante da programação
													</p></td>                                    
											</tr>
										<?php endif; ?>

										<?php //print_r($sobre); ?>

										<?php
											// Verifica se possui campos
                                            if($datas){

                                                if($datas['tipo_de_data'] == 'data'){ // Se for do tipo data
													
													$dataEvento = $datas['data'];

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
													
                                                } else if($datas['tipo_de_data'] == 'periodo'){
													
													$dataInicial = $datas['data'];
                                                    $dataFinal = $datas['data_final'];

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

												} elseif($datas['tipo_de_data'] == 'semana'){ // se for do tipo semana
													$semana = $datas['dia_da_semana'];
													
													
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
													
                                                }

                                            }
                                        ?>

										<?php if($tipo_evento != 'serie') : ?>

											<tr>
												<th scope="row" class="align-middle"><i class="fa fa-calendar" aria-hidden="true"></i></th>
												<td><?php echo $dataFinal; ?></td>                                    
											</tr>

										<?php endif; // Fim tipo_evento ?>

										<?php
											// Exibe os horários

											if($horario['selecione_o_horario'] == 'horario'){
												$hora = $horario['hora'];
											} elseif($horario['selecione_o_horario'] == 'periodo'){
												
												$hora = '';
												$k = 0;
												
												foreach($horario['hora_periodo'] as $periodo){
													
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
											<tr>
												<th scope="row" class="align-middle"><i class="fa fa-clock-o" aria-hidden="true"></i></th>
												<td><?php echo convertHour($hora); ?></td> 
											</tr>
										<?php endif; ?>

										<?php if($classi != '') : ?>
											<tr>
												<th scope="row" class="align-middle"><i class="fa fa-star" aria-hidden="true"></i></th>
												<td><?php 
													//print_r($classi);
													$m = 0;
													foreach($classi as $faixa){
														echo "<p class='m-0'>";
															if($faixa != ''){
																$term = get_term( $faixa, 'faixa_categories' );
																echo "<span class='blue-info'>Faixa etária</span> - " . $term->name;
															}
														echo "</p>";
													}
													//print_r($classi);
												?></td>                                    
											</tr>
										<?php endif; ?>

										<?php if($publico != '') : ?>
											<tr>
												<th scope="row" class="align-middle"><i class="fa fa-user-circle" aria-hidden="true"></i></th>
												<td><?php 
													
													$m = 0;
													foreach($publico as $alvo){
														echo "<p class='m-0'>";
															if($alvo != ''){
																$term = get_term( $alvo, 'publico_categories' );
																echo "<span class='blue-info'>Público alvo</span> - " . $term->name;
															}
														echo "</p>";
													}
													
												?></td>                                    
											</tr>
										<?php endif; ?>	
										
										<?php 
											if($turmas && $turmas != ''):
										?>
											<tr>
												<th scope="row" class="align-middle"><i class="fa fa-users" aria-hidden="true"></i></th>
												<td>
													<?php foreach($turmas as $turma) :?>
														
														<p class="mb-0">
															<span class='blue-info'><?php echo $turma['nome_da_turma']; ?></span> 
															- 
															<?php
																if($datas){

																	if($datas['tipo_de_data'] == 'data'){ // Se for do tipo data
																		
																		$dataEvento = $datas['data'];
	
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
					
																		echo $dataFinal;
																		
																	} else if($datas['tipo_de_data'] == 'periodo'){
																		
																		$dataInicial = $datas['data'];
																		$dataFinal = $datas['data_final'];
					
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

																		echo $dataFinal;
					
																	} elseif($datas['tipo_de_data'] == 'semana'){ // se for do tipo semana
																		
																		$i = $turma['data'] - 1;
																		$semana = $datas['dia_da_semana'][$i];
																		
																		foreach($semana as $dias){
					
																			$total = count($dias); 
																			$i = 0;
																			$diasShow = '';
																			$show = array();
																			
																			foreach($dias as $diaS){
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

																			if(!$diasShow){
																				$dataEvento = get_the_date('Y-m-d');
																				$dataEvento = explode("-", $dataEvento);
																				$mes = date('M', mktime(0, 0, 0, $dataEvento[1], 10));
																				$mes = translateMonth($mes);                                                                        
																				$diasShow = $mes;
																			}
					
																			echo $diasShow;
																		}
																	}
					
																}
															?>
															- 
															<?php
																if($horario['selecione_o_horario'] == 'horario'){
																	$hora = $horario['hora'];
																	echo convertHour($hora);
																} else {
																	$hora = '';
																	$i = $turma['horario'] - 1;

																	$periodo = $horario['hora_periodo'][$i];
																		
																	if($periodo['periodo_hora_inicio']){
																		$hora .= $periodo['periodo_hora_inicio'];
																	} 
																	
																	if ($periodo['periodo_hora_final']){
																		$hora .= ' às ' . $periodo['periodo_hora_final'];
																	}
																	echo convertHour($hora);
																}
															?>
														</p>														
													<?php endforeach; ?>
												</td>
											</tr>
										
										<?php endif; ?>	

										<?php
											global $post;
											$local = get_field('localizacao', $post->ID); 
										?>

										<?php 
											if($local && $local != ''):
										?>

											<tr>
												<th scope="row" class="align-middle"><i class="fa fa-map-marker" aria-hidden="true"></i></th>
												<?php if($local == 31244 || $tipo_evento == 'serie'): ?>
													<td><p class="m-0">Consulte abaixo CEUs participantes</p></td>
												<?php elseif($local == 31675): ?>
													<td><p class="m-0"><strong>Para toda a rede</strong></p></td>
												<?php else: ?>
													<td><strong><?php echo get_the_title($local); ?></strong>
													<?php 
														$end = get_field('informacoes_basicas', $local);
														
														if($end != '') : ?>
															<br>
															<?php echo $end['endereco'] . ', ' . $end['numero'] . ' - ' .$end['bairro'] . ' - CEP: ' .$end['cep']; ?>
														<?php endif; ?>	
													</td>
												<?php endif; ?>                                
											</tr>

										<?php endif; ?>

										<?php if($espaco != '') : ?>
											<tr>
												<th scope="row" class="align-middle"><i class="fa fa-street-view" aria-hidden="true"></i></th>
												<td><?php echo $espaco->name;?></td>                                    
											</tr>
										<?php endif; ?>

										<?php
											//echo "<pre>";
											//print_r($inscri);
											//echo "</pre>";
											?>

										<?php if($inscri != '' && $inscri['info_inscricoes'] != '') : ?>
											<tr>
												<th scope="row" class="align-middle"><i class="fa fa-ticket" aria-hidden="true"></i></th>
												<?php if($inscri['info_inscricoes'] != '' && $inscri['link_inscricoes'] != '') : ?>
													<td><a href="<?php echo $inscri['link_inscricoes']; ?>"><?php echo $inscri['info_inscricoes']; ?></a></td>
												<?php elseif($inscri['info_inscricoes'] != '') : ?>
													<td><?php echo $inscri['info_inscricoes']; ?></td>
												<?php endif; ?>                                  
											</tr>
										<?php endif; ?>
									</tbody>
								</table>

								<?php if($tipo_evento == 'serie'): 

									$participantes = get_field('ceus_participantes');
									if($participantes != '') :

										foreach($participantes as $participante):
										?>

										<table class="table border-right border-left border-bottom">
											<thead>
												<tr>
													<td colspan="2"><?php echo get_the_title($participante['localizacao_serie']); ?></td>
												</tr>
											</thead>
											<tbody>

											<?php
												// Verifica se possui campos
												$datas = $participante['data_serie'];
												if($datas){

													if($datas['tipo_de_data'] == 'data'){ // Se for do tipo data
														
														$dataEvento = $datas['data'];

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
														
													} else if($datas['tipo_de_data'] == 'periodo'){
														
														$dataInicial = $datas['data'];
														$dataFinal = $datas['data_final'];

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

													} elseif($datas['tipo_de_data'] == 'semana'){ // se for do tipo semana
														$semana = $datas['dia_da_semana'];
														
														
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
														
													}

												}
											?>

											<?php if($dataFinal) : ?>

												<tr>
													<th scope="row" class="align-middle"><i class="fa fa-calendar" aria-hidden="true"></i></th>
													<td><?php echo $dataFinal; ?></td>                                    
												</tr>

											<?php endif; // Fim tipo_evento ?>

												<?php
													// Exibe os horários
													$horario = $participante['horario_serie'];

													if($horario['selecione_o_horario'] == 'horario'){
														$hora = $horario['hora'];
													} elseif($horario['selecione_o_horario'] == 'periodo'){
														
														$hora = '';
														$k = 0;
														
														foreach($horario['hora_periodo'] as $periodo){
															
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
													<tr>
														<th scope="row" class="align-middle"><i class="fa fa-clock-o" aria-hidden="true"></i></th>
														<td><?php echo convertHour($hora); ?></td> 
													</tr>
												<?php endif; ?>

												<?php
													$espacoSerie = $participante['local_espaco_serie'];
													if($espacoSerie):													
												?>

													<tr>
														<th scope="row" class="align-middle"><i class="fa fa-user-circle-o" aria-hidden="true"></i></th>
														<td><?php echo $espacoSerie->name; ?></td>
													</tr>

												<?php endif; ?>

												<?php 
													$end = get_field('informacoes_basicas', $participante['localizacao_serie']);
													
													if($end != '') : ?>

													<tr>
														<th scope="row" class="align-middle"><i class="fa fa-map-marker" aria-hidden="true"></i></th>
														<td><strong><?php echo get_the_title($participante['localizacao_serie']); ?></strong>
														
																<br>
																<?php echo $end['endereco'] . ', ' . $end['numero'] . ' - ' .$end['bairro'] . ' - CEP: ' .$end['cep']; ?>
															
														</td>
													</tr>

												<?php endif; ?>	
												
											</tbody>               
										</table>
											
										<?php		
										endforeach;

									endif; // participantes
									
									//echo "<pre>";
									//print_r($participantes);
									//echo "</pre>";

								?>

								<?php endif; // tipo evento ?>
							</div>

						</div>
					</div>
				</div>

			<?php
				echo '</article>';
			endwhile;
		endif;
		wp_reset_query();
	}
	public function getDataPublicacaoAlteracao(){
		//padrão de horario G\hi
		echo '<span class="display-autor">Publicado em: '.get_the_date('d/m/Y G\hi').' | Atualizado em: '.get_the_modified_date('d/m/Y').'</span>';
	}

	public function getMidiasSociais(){
		/*Utilizando as classes de personalização do Plugin Add This*/
		if (STM_URL === 'http://localhost/furuba-educacao-intranet'){
			echo do_shortcode('[addthis tool="addthis_inline_share_toolbox_d2ly"]');
		}else {
			echo do_shortcode('[addthis tool="addthis_inline_share_toolbox_q0q4"]');
		}
	}
	public function getArquivosAnexos(){
		$unsupported_mimes  = array( 'image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/tiff', 'image/x-icon' );
		$all_mimes          = get_allowed_mime_types();
		$accepted_mimes     = array_diff( $all_mimes, $unsupported_mimes );

		$attachments = get_posts( array(
			'post_type' => 'attachment',
			'post_mime_type'    => $accepted_mimes,
			'posts_per_page' => -1,
			'post_parent' => get_the_ID(),
			'orderby'	=> 'ID',
			'order'	=> 'ASC',
			'exclude'     => get_post_thumbnail_id()
		) );
		if ( $attachments ) {
			echo '<section id="arquivos-anexos">';
			echo '<h2>Arquivos Anexos</h2>';
			foreach ( $attachments as $attachment ) {
				echo '<article>';
				echo '<p><a target="_blank" style="font-size:26px" href="'.$attachment->guid.'"><i class="fa fa-file-text-o fa-3x" aria-hidden="true"></i> Ir para '. $attachment->post_title.'</a></p>';
				echo '<article>';
			}
			echo '</section>';
		}
	}
	public function getCategorias($id_post){
		$categorias = get_the_category($id_post);
		foreach ($categorias as $categoria){
			$category_link = get_category_link( $categoria->term_id );
			echo '<a href="'.$category_link.'"><span class="badge badge-pill badge-light border p-2 m-2 font-weight-normal">ir para '.$categoria->name.'</span></a>';
		}
	}
}
