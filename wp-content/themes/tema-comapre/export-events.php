<?php
require_once("../../../wp-load.php");
$date = date('d_m_y_h_i_s');
$fileName = $date . '_eventos_ceu.xlsx';

function nameCat($cat_id){
	$showNames = '';
	if($cat_id){
		$i = 0;
		foreach($cat_id as $id){
			$term = get_term($id);
			if($i == 0){
				$showNames .= $term->name;
			} else {
				$showNames .= ', ' . $term->name;
			}
			$i++;
		}
	}

	return $showNames;
}

function convertTipo($tipo){
	switch ($tipo):
		case 'serie':
			return 'Evento em Série';
			break;
		case 'outro':
			return 'Grande Evento';
			break;
		case 'singular':
			return 'Evento Singular';
			break;
		default:
			return $tipo;
	endswitch;
}

function inverteData($data){
    if(count(explode("/",$data)) > 1){
        return implode("-",array_reverse(explode("/",$data)));
    }elseif(count(explode("-",$data)) > 1){
        return implode("/",array_reverse(explode("-",$data)));
    }
}

function convertData($tipoData){
	switch ($tipoData):
		case 'data':
			return 'Data única';
			break;
		case 'periodo':
			return 'Período';
			break;
		case 'semana':
			return 'Dias da semana';
			break;
		case 'multi':
			return 'Múltiplas Datas';
			break;
		default:
			return $tipoData;
	endswitch;
}

$eventos_export = array();
$eventos_export[] = array(
	'<style bgcolor="#8EA9DB">ID</style>',
	'<style bgcolor="#8EA9DB">Titulo</style>',
	'<style bgcolor="#8EA9DB">Tipo Evento</style>',
	'<style bgcolor="#8EA9DB">Online</style>',
	'<style bgcolor="#8EA9DB">Atividades</style>',
	'<style bgcolor="#8EA9DB">Localização</style>',
	'<style bgcolor="#8EA9DB">Tipo Data</style>',
	'<style bgcolor="#8EA9DB">Data</style>',
	'<style bgcolor="#8EA9DB">Status</style>'
);

global $wpdb;
$query = "SELECT wp_posts.ID, wp_posts.post_title FROM wp_posts";

if( $_GET['unidade'] != 'all' ){
	$query .= ' INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id )';
}

$query .= " WHERE 1=1";

if( $_GET['ano'] != 'all' ){
	$query .= ' AND ( YEAR( wp_posts.post_date ) = ' . $_GET['ano'] . ')';
}

if( $_GET['unidade'] != 'all' ){
	$query .= " AND ( ( ( wp_postmeta.meta_key = 'localizacao' AND wp_postmeta.meta_value = '" . $_GET['unidade'] . "' ) OR ( wp_postmeta.meta_key LIKE 'ceus_participantes_%_localizacao_serie' AND wp_postmeta.meta_value = '" . $_GET['unidade'] . "' ) ) )";
}

$query .= " AND wp_posts.post_type = 'post' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'pending') GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC";

echo $query;

$result = $wpdb->get_results( $query, 'ARRAY_A');

$eventos = array();

foreach($result as $evento){
	$eventos[$evento['ID']]['title'] = $evento['post_title'];
	$field_names = array('tipo_de_evento_tipo','tipo_de_evento_online', 'atividades', 'localizacao', 'data_tipo_de_data', 'data_data', 'data_data_final', 'data_dia_da_semana', 'ceus_participantes');
	foreach ($field_names as $field_name) {
		$eventos[$evento['ID']][$field_name] = get_field($field_name, $evento['ID']);
	}	 
}

foreach($eventos as $key => $value){
	
	if($value['tipo_de_evento_online']){
		$online = 'Online';
	} else {
		$online = 'Presencial';
	}

	if($value['tipo_de_evento_tipo'] == 'serie'){
		$local = '';
		$ceus = $value['ceus_participantes'];
		if($ceus){
			$i = 0;
			foreach($ceus as $ceu){
				if($i == 0){
					//$titulo = htmlentities(get_the_title($ceu['localizacao_serie']));
                    //$seletor = explode (" &amp;", $titulo);
					//$seletor2 = explode (" &#8211;", $seletor[0]);			
					$local .= get_the_title($ceu['localizacao_serie']);
				} else {
					//$titulo = htmlentities(get_the_title($ceu['localizacao_serie']));
                    //$seletor = explode (" &amp;", $titulo);
					//$seletor2 = explode (" &#8211;", $seletor[0]);				
					$local .= ', ' . get_the_title($ceu['localizacao_serie']);
				}
				$i++;
			}
		}
	} elseif($value['localizacao'] && $value['tipo_de_evento_tipo'] != 'serie'){
		$local = get_the_title($value['localizacao']);
	} else {
		$local = '';
	}

	if($value['tipo_de_evento_tipo'] == 'serie'){
		$dataTipo = 'multi';
	} else {
		$dataTipo = $value['data_tipo_de_data'];
	}

	if($value['data_tipo_de_data'] == 'data'){
		$data = inverteData($value['data_data']);
	} elseif($value['data_tipo_de_data'] == 'periodo') {
		$data = inverteData($value['data_data']) . ' à ' . inverteData($value['data_data_final']);
	} elseif($value['data_tipo_de_data'] == 'semana'){
		$semana = $value['data_dia_da_semana'];
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
				$dias .= " / " . $diaShow;
			}
		}
		$data = $dias;
		$dias = '';
        $show = '';

	} else {
		$data = '';
	}

	if($value['tipo_de_evento_tipo'] == 'serie'){
		$participantes = $value['ceus_participantes'];
		$countPart = count($participantes);
		$countPart = $countPart - 1;
		
		$dtInicial = $participantes[0]['data_serie'];
		$dtFinal = $participantes[$countPart]['data_serie'];

		if($dtInicial['tipo_de_data'] == 'data' && $dtFinal['tipo_de_data'] == 'data'){
			
			$data = inverteData($dtInicial['data']) . ' à ' . inverteData($dtFinal['data']);	

		} else {
			$data = 'Múltiplas Datas';
		}
	}

	$tipoEvento = $value['tipo_de_evento_tipo'];				
	$tipo = $value['data_tipo_de_data'];
	if($tipo == 'semana'){
		$status = 'Atividades permanentes';
	} elseif($tipoEvento == 'serie'){					
		$participantes = $value['ceus_participantes'];
		if($participantes){
			$countPart = count($participantes);
			$countPart = $countPart - 1;
			$dtFinal = $participantes[$countPart]['data_serie'];

			$today = date('Y-m-d');
			if($dtFinal['data'] > $today){
				$status = 'Próximas atividades';
			} else {
				$status = 'Atividades encerradas';
			}
		} else {
			$status = '';
		}
		
	} elseif($tipo == 'data' || $tipo == 'periodo'){
		if($tipo == 'data'){
			$dataIn = $value['data_data'];
			if($dataIn && $dataIn != ''){
				$today = date('Y-m-d');
				if($dataIn > $today){
					$status = 'Próximas atividades';
				} else {
					$status = 'Atividades encerradas';
				}
			} else {
				$status = '';
			}
									
		} elseif($tipo == 'periodo') {
			$dataFn = $value['data_data_final'];
			if($dataFn && $dataFn != ''){
				$today = date('Y-m-d');
				if($dataFn > $today){
					$status = 'Próximas atividades';
				} else {
					$status = 'Atividades encerradas';
				}
			} else {
				$status = '';
			}
		}
		
	} else {
		$status = '';
	}

	$eventos_export[] = array(
		$key,
		$value['title'],
		convertTipo($value['tipo_de_evento_tipo']),
		$online,
		nameCat($value['atividades']),
		$local,
		convertData($dataTipo),
		$data,
		$status
	);
}

$xlsx = Classes\Lib\SimpleXLSXGen::fromArray( $eventos_export );
$xlsx->downloadAs($fileName); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 

exit();