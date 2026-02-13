<?php

namespace Classes\ModelosDePaginas\PaginaUnidades;


class PaginaUnidadesMapa
{
    public function __construct(){
		$this->getMapa();
	}

	public function getMapa(){
        
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
        
        <div class="container mb-5">
            <div class="row m-0">
                <div class="col-sm-4 p-0 p-list">

                    <div class="filtro-zonas-button open-close">
                        <button id="collpaseContent" class="openbtn closeContent" onclick="openUnidades()"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
                    </div>
                
                    <div class="unidades-busca d-none">
                        <div id="search-box"></div>
                        <button class="btn-unidade" data-toggle="modal" data-target="#locationModal"><i class="fa fa-crosshairs" aria-hidden="true"></i></button>
                        <div class="" id='unidades-mapa'></div>
                    </div>

                    <div class="filtro-zonas-button d-none">
                        <button class="openbtn" onclick="openNav()">Filtrar por zona <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                    </div>
                   
                    
                    <?php
                        $args = array(
                            'post_type' => 'unidade',
                            'post__not_in' => array(31244, 31675),
                            'order' => 'ASC',
                            'orderby' => 'title',
                            'posts_per_page' => -1,
                            'post_status' => array('publish', 'pending'),                        
                        );

                        if($_GET['idUnidade'] && $_GET['idUnidade'] != ''){
                            $args['p'] = $_GET['idUnidade'];
                        }

                        if($_GET['zona'] && $_GET['zona'] != ''){
                            $args['meta_key'] = 'informacoes_basicas_zona_sp';
                            $args['meta_value'] = $_GET['zona'];
                        }

                        if($_GET['setor'] && $_GET['setor'] != ''){
                            $args['meta_key'] = 'informacoes_basicas_dre_pertencente';
                            $args['meta_value'] = $_GET['setor'];
                        }
                        
                        // The Query
                        $the_query = new \WP_Query( $args );

                        $count = 1;
                        
                        // The Loop
                        if ( $the_query->have_posts() ) {
                            echo '<ul class="lista-unidades hidemapa">';
                            while ( $the_query->have_posts() ) {
                                $the_query->the_post();
                                $zona = get_group_field( 'informacoes_basicas', 'zona_sp', get_the_id() );
                                $latitude = get_group_field( 'informacoes_basicas', 'latitude', get_the_id() );
                                $longitude = get_group_field( 'informacoes_basicas', 'longitude', get_the_id() );
                                $endereco = get_group_field( 'informacoes_basicas', 'endereco', get_the_id() );
                                $numero = get_group_field( 'informacoes_basicas', 'numero', get_the_id() );
                                $bairro = get_group_field( 'informacoes_basicas', 'bairro', get_the_id() );
                                $cep = get_group_field( 'informacoes_basicas', 'cep', get_the_id() );
                                $emails = get_group_field( 'informacoes_basicas', 'email', get_the_id() );
                                $tels = get_group_field( 'informacoes_basicas', 'telefone', get_the_id() );
                                $idFoto = get_field('foto_principal_do_ceu');
                                                                $foto = wp_get_attachment_image( $idFoto, 'recorte-eventos', '',  array( 'class' => 'img-fluid' ));
                                
                                echo '<li>
                                        <a href="#map" class="story" onclick="alerta(this)" data-point="' . $latitude . ',' . $longitude . '">';
                                            if(!$idFoto){
                                                echo '<p class="unidades-title">' . get_the_title() . '</p>';
                                            } else {
                                                echo '<p class="unidades-title">' . get_the_title() . '  <a href="#a" class="openPopup" data-id="' . $count . '"><i class="fa fa-picture-o" aria-hidden="true"></i></a></p>
                                                
                                                <div class="popup popup-' . $count . '">
                                                    ' . $foto . '
                                                    <button type="button" class="closePopup"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                                                </div>';
                                            }
                                        echo '</a>
                                        <p>' . nomeZona($zona) . ' • ' . $endereco . ', '. $numero .' - ' . $bairro . ' - CEP: ' . $cep . '</p>
                                        <p>';
                                            if($emails['email_principal'] != ''){
                                                echo '<a href="mailto:' . $emails['email_principal'] .'"><i class="fa fa-envelope-o" aria-hidden="true"></i> ' . $emails['email_principal'] .'</a><br>';
                                            }
                                            echo '<div class="d-flex justify-content-between">';
                                                if($tels['telefone_principal'] != ''){
                                                    echo '<a href="tel:' . clearPhone($tels['telefone_principal']) . '"><i class="fa fa-phone" aria-hidden="true"></i> ' . $tels['telefone_principal'] .'</a>';
                                                } else {
                                                    echo '<span>&nbsp;</span>';
                                                }
                                                
                                            echo '<a href="' . get_the_permalink() . '">Acessar página <i class="fa fa-external-link" aria-hidden="true"></i></a>
                                            </div>
                                        </p>
                                      </li>';
                                      $count++;
                            }
                            if (!empty($_GET)) {
                                echo '<li class="border-0"><a href="' . $currentPage . '" class="btn-all"><i class="fa fa-map-o" aria-hidden="true"></i> Ver todos os CEUs</a></li>';
                            }
                            echo '</ul>';
                        } else {
                            // no posts found
                        }
                        /* Restore original Post Data */
                        wp_reset_postdata();    
                    ?>                    
                </div>
                <div class="col-sm-8 p-0 p-map">

                    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel" aria-hidden="true" data-backdrop="false"> 
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">                            
                                <div class="modal-body">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                    <p>Usar minha localização para encontrar os CEUs mais próximos.</p>
                                    <button type="button" class="btn btn-location" data-dismiss="modal" onclick="getLocation()"><i class="fa fa-globe" aria-hidden="true"></i> Usar minha localização</button>
                                </div>                            
                            </div>
                        </div>
                    </div>

                    <div id="map"></div>
                </div>
            </div>
        </div>
    <?php
    }
}