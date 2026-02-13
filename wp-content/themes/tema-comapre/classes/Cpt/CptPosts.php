<?php

namespace Classes\Cpt;


class CptPosts extends Cpt
{
	public function __construct()
	{
		add_filter('manage_posts_columns', array($this, 'exibe_cols'), 10, 2);
		add_action( 'manage_posts_custom_column' , array($this, 'cols_content'), 10, 2 );
		//add_action('manage_edit-post_sortable_columns');
	}

	// add featured thumbnail to admin post columns
	public function exibe_cols($cols, $post_type) {
		if ($post_type === 'post') {
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => 'Evento',
				'author' => 'Autor',
				'unidade' => 'Unidade',
				'destaque' => 'Destaque Home',
				//'categories' => 'Categories',
				//'tags' => 'Tags',
				//'comments' => '<span class="vers"><div title="Comments" class="comment-grey-bubble"></div></span>',
				//'featured_thumb' => 'Thumbnail',
				//'destaque' => 'Destaque',
				//'posicao_destaque' => 'Posição Destaque',
				'date' => 'Data',
				'status' => 'Status'

			);

			return $columns;
		}else{
			return $cols;
		}

	}

	public function cols_content($column) {
		switch ( $column ) {
			case 'unidade':
				$localizacao = get_field('localizacao');
				$tipo = get_field('tipo_de_evento_tipo');
				if($tipo == 'serie'){
					$title = "Múltiplas Unidades";
				} elseif($localizacao) {
					$title = '<a href="' . get_admin_url() . '/edit.php?s&post_status=all&post_type=post&action=-1&m=0&search_unidade=' . $localizacao . '&filter_action=Filtrar&paged=1&action2=-1">' . get_the_title($localizacao) . '</a>';
				}

				if($title == ''){
					$title = '-';
				}
				echo $title;
				break;

			case 'featured_thumb':
				echo '<a href="' . get_edit_post_link() . '">';
				echo the_post_thumbnail( 'admin-list-thumb' );
				echo '</a>';
				break;

			case 'destaque':
				echo $this->getDestaque();
				break;

			case 'posicao_destaque':
				$posicao_destaque = get_field('posicao_de_destaque_deste_post');
				echo '<h4>'.$posicao_destaque.'</h4>';
				break;

			case 'status':
				$tipoEvento = get_field('tipo_de_evento_tipo');
				
				$tipo = get_field('data_tipo_de_data');
				if($tipo == 'semana'){
					echo '<h4>Atividades permanentes</h4>';
				} elseif($tipoEvento == 'serie'){					
					$participantes = get_field('ceus_participantes');
					if($participantes){
						$countPart = count($participantes);
						$countPart = $countPart - 1;
						$dtFinal = $participantes[$countPart]['data_serie'];

						$today = date('Y-m-d');
						if($dtFinal['data'] > $today){
							echo '<h4>Próximas atividades</h4>';
						} else {
							echo '<h4>Atividades encerradas</h4>';
						}
					} else {
						echo '<h4>-</h4>';
					}
					
				} elseif($tipo == 'data' || $tipo == 'periodo'){
					if($tipo == 'data'){
						$dataIn = get_field('data_data');
						if($dataIn && $dataIn != ''){
							$today = date('Y-m-d');
							if($dataIn > $today){
								echo '<h4>Próximas atividades</h4>';
							} else {
								echo '<h4>Atividades encerradas</h4>';
							}
						} else {
							echo '<h4>-</h4>';
						}
												
					} elseif($tipo == 'periodo') {
						$dataFn = get_field('data_data_final');
						if($dataFn && $dataFn != ''){
							$today = date('Y-m-d');
							if($dataFn > $today){
								echo '<h4>Próximas atividades</h4>';
							} else {
								echo '<h4>Atividades encerradas</h4>';
							}
						} else {
							echo '<h4>-</h4>';
						}
					}
					
				} else {
					echo '<h4>-</h4>';
				}
				
				
				break;

		}
	}	

	public function getDestaque(){
		$destaque = get_field('evento_destaque_home');
		if ($destaque == 'sim'){
			return '<h4>Sim</h4>';
		}else{
			return '<h4>-</h4>';
		}

	}

}