<?php


namespace Classes\ModelosDePaginas\PaginaUnidades;

use Classes\TemplateHierarchy\ArchiveContato\ArchiveContato;

class PaginaUnidades extends ArchiveContato
{

    public function __construct()
	{
		$this->init();
	}
    

	public function init(){
		$container_geral_tags = array('section', 'section');
		$container_geral_css = array('container-fluid', 'row');
		$this->abreContainer($container_geral_tags, $container_geral_css);

		//$this->getTituloPagina();
        
        $this->formBusca();
		$this->listaUnidades();
		//$this->htmlMapaUnidades();        
	}

	public function getTituloPagina(){
		echo '<article class="col-12">';
	        echo '<h1 class="mb-4" id="'.get_queried_object()->post_name.'">'.get_the_title().'</h1>';
		echo '</article>';
    }

	public function formBusca(){
		?>

		<section class="col-12 p-0 mb-4">
			<div class="container">
				<div class="row">
					<div class="col col-md-5">

						<div class="row">
							<div class="col-md-9 pr-0">
								<input type="text" class="form-control input-custom input-top-search" id="addressInput" placeholder="Busque pelo nome do CEU ou endereço">
								<button onclick="clearSearch()" id="clearButton" style="display: none;">X</button>
							</div>
							<div class="pl-0 col-md-3">
								<button class="btn btn-secondary btn-block btn-top-search" onclick="geocodeAddress()">Buscar</button>
							</div>
						</div>

						<div id="searchResults" class="leaflet-locationiq-results d-block"></div>
						
					</div>

					<div class="col">                    
                        <select class="form-control input-custom" name="forma" onchange="location = this.value;">
                            <option disabled selected value> Filtre por zona de setorização ou DRE </option>';
                            <option value="/mapa-completo/">Todos</option>
                            <option value="/mapa-completo/?zona=centro">Zona Central</option>
                            <option value="/mapa-completo/?zona=leste">Zona Leste</option>
                            <option value="/mapa-completo/?zona=norte">Zona Norte</option>
                            <option value="/mapa-completo/?zona=oeste">Zona Oeste</option>
                            <option value="/mapa-completo/?zona=sul">Zona Sul</option>
                            <option value="/mapa-completo/?setor=DRE Butantã">DRE Butantã</option>
                            <option value="/mapa-completo/?setor=DRE Campo Limpo">DRE Campo Limpo</option>
                            <option value="/mapa-completo/?setor=DRE Capela do Socorro">DRE Capela do Socorro</option>
                            <option value="/mapa-completo/?setor=DRE Freguesia/Brasilândia">DRE Freguesia/Brasilândia</option>
                            <option value="/mapa-completo/?setor=DRE Guaianases">DRE Guaianases</option>
                            <option value="/mapa-completo/?setor=DRE Ipiranga">DRE Ipiranga</option>
                            <option value="/mapa-completo/?setor=DRE Itaquera">DRE Itaquera</option>
                            <option value="/mapa-completo/?setor=DRE Jaçanã/Tremembé">DRE Jaçanã/Tremembé</option>
                            <option value="/mapa-completo/?setor=DRE Penha">DRE Penha</option>
                            <option value="/mapa-completo/?setor=DRE Pirituba">DRE Pirituba</option>
                            <option value="/mapa-completo/?setor=DRE Santo Amaro">DRE Santo Amaro</option>
                            <option value="/mapa-completo/?setor=DRE São Mateus">DRE São Mateus</option>
                            <option value="/mapa-completo/?setor=DRE São Miguel">DRE São Miguel</option>
                        </select>                
                	</div>

					<div class="col">
                    <?php
                        echo '<div class="form-group">
                        <select class="form-control input-custom" name="forma" onchange="location = this.value;">
                            <option disabled selected value> Ir para a página da unidade </option>';
                            
                                $argsUnidades = array(
                                    'post_type' => 'unidade',
                                    'posts_per_page' => -1,
                                    'orderby' => 'title',
                                    'order' => 'ASC',
                                    'post__not_in' => array(31244),                                    
									'post_status' => array('publish', 'pending'),
                                );
                                $currentPage = get_the_permalink();

                                $todasUnidades = new \WP_Query( $argsUnidades );
        
                                // The Loop
                                if ( $todasUnidades->have_posts() ) {
                                    
                                    while ( $todasUnidades->have_posts() ) {
                                        $todasUnidades->the_post();
                                        echo '<option value="' . get_the_permalink() . '">' . get_the_title() .'</option>';
                                    }
                                
                                }
                                wp_reset_postdata();
                            
                            echo '</select>
                        </div>';
                    ?>
                
                </div>

				</div>
			</div>			
		</section>

		<div id="map" class="d-none"></div>
		

		<?php
	}

	public function listaUnidades(){
		$argsUnidades = array(
			'post_type' => 'unidade',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'post__not_in' => array(34180, 31675),
			'post_status' => array('publish', 'pending'),
		);

		$todasUnidades = new \WP_Query( $argsUnidades );

		// The Loop
		if ( $todasUnidades->have_posts() ) {
			echo "<div class='container mb-5'>";
				echo "<div class='row'>";
					while ( $todasUnidades->have_posts() ) {
						$todasUnidades->the_post();
						$idFoto = get_field('foto_principal_do_ceu');
						if(!$idFoto){
							$idFoto = 34543;
						}
						$foto = wp_get_attachment_image( $idFoto, 'recorte-eventos', '',  array( 'class' => 'img-fluid' ));
						$endereco = get_group_field( 'informacoes_basicas', 'endereco' );
						$numero = get_group_field( 'informacoes_basicas', 'numero' );
						$complemento = get_group_field( 'informacoes_basicas', 'complemento' );
						$bairro = get_group_field( 'informacoes_basicas', 'bairro' );
						$cep = get_group_field( 'informacoes_basicas', 'cep' );
						$dre = get_group_field( 'informacoes_basicas', 'dre_pertencente' );
						
						
						echo '<div class="col-12 col-md-6 col-lg-3">';
							echo '<div class="unidade-card">';
								echo '<a href="/mapa-completo/?idUnidade=' . get_the_ID() . '" class="mb-1">';
									echo $foto;
									echo '<h2>' . get_the_title() . '</h2>';
									echo '<table>';
										echo '<tr>';

											echo '<td>';
												echo '<i class="fa fa-map-marker" aria-hidden="true"></i>';
											echo '</td>';

											echo '<td>';
												if($endereco != '')
													echo  $endereco;

												if($numero != '')
													echo  ', ' . $numero;

												if($complemento != '')
													echo  ' - ' . $complemento;

												if($bairro != '')
													echo  ' - ' . $bairro;
												
												if($cep != '')
													echo  '<br>CEP: ' . $cep;

												if($dre != '')
													echo  '<br><br>' . $dre;
											echo '</td>';

										echo '</tr>';
									echo '</table>';
									echo '<a href="' . get_the_permalink() . '" class="card-link mt-auto">Acessar página <i class="fa fa-external-link" aria-hidden="true"></i></a>';
								echo '</a>';								
							echo '</div>';
						echo '</div>';
					}
				echo "</div>";
			echo "</div>";
		}
		wp_reset_postdata();
	}

	public function htmlMapaUnidades(){
		?>

		<section class="col-12 p-0">            
            <?php new PaginaUnidadesMapa(); ?>
		</section>

		<?php
    }
}