<?php

namespace Classes\TemplateHierarchy\LoopSingle;

use Classes\TemplateHierarchy\Search\SearchFormSingle;

class LoopSingleCabecalho extends LoopSingle
{

	public function __construct()
	{
		$this->cabecalhoDetalheNoticia();
	}

	public function cabecalhoDetalheNoticia(){
		global $post;
		$local = get_field('localizacao', $post->ID); 
		$zona = get_group_field( 'informacoes_basicas', 'zona_sp', $local );
		$tipo_evento = get_field('tipo_de_evento_tipo', $post->ID);
		?>
		<div class="evento-title mt-3 mb-3 col-12 color-<?php echo $zona; ?>" id="Noticias">
            <div class="container">
                <div class="row bg-event-title py-4">
                    <div class="col-md-5 offset-md-1">

						<?php 
							//$featured_img_url = get_the_post_thumbnail_url($post->ID, 'recorte-eventos');
							$imgSelect = get_field('capa_do_evento', $post->ID);							
                            $featured_img_url = wp_get_attachment_image_src($imgSelect, 'recorte-eventos');
							if($featured_img_url){
								$imgEvento = $featured_img_url[0];
								//$thumbnail_id = get_post_thumbnail_id( $post->ID );
								$alt = get_post_meta($imgSelect, '_wp_attachment_image_alt', true);  
							} else {
								$imgEvento = get_template_directory_uri().'/img/placeholder_portal_ceus.jpg';
								$alt = get_the_title($post->ID);
							}
						?>
						<img src="<?php echo $imgEvento; ?>" class="img-fluid d-block" alt="<?php echo $alt; ?>">

                    </div>

                    <div class="col-md-5 evento-infos d-flex align-content-between flex-wrap">
						
					<?php
						$atividades = get_the_terms( $post->ID, 'atividades_categories' );
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
					
					
						<p class="categ-dest w-100 m-0">
							<?php echo $showAtividades; ?>
                        </p>

						<h1 class="m-0 py-3 w-100"><?php echo get_the_title(); ?></h1>

						<?php
							$subtitulo = get_field('subtitulo', $post->ID);
							if($subtitulo && $subtitulo != ''):						
								echo '<h2>- ' . $subtitulo . '</h2>';
							endif;
						?>
						
						<?php
							$post_categories = wp_get_post_categories( $post->ID );
							$local = get_field('localizacao', $post->ID); 
						?>

						<p class="evento-unidade m-0 w-100">
							<?php
								$local = get_field('localizacao', $post->ID); 
								if(!$local):
									if($tipo_evento == 'serie'){
										echo 'Múltiplas Unidades';
									} else {
										echo '';
									}
									                                                   
								elseif($local == '31675' || $local == '31244'):
							?>
								<?php echo get_the_title($local); ?>
							<?php else: ?>
								<a href="<?php echo get_the_permalink($local); ?>"><?php echo get_the_title($local); ?></a>
							<?php endif; ?>
						</p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
	}
}