<?php
require_once("../../../wp-load.php");
$date = date('d_m_y_h_i_s');
$fileName = $date . '_usuarios_ceu.xlsx';
 
if($_GET['funcao'] == 'all'){
	$blogusers = get_users( array( 'fields' => array( 'id', 'user_login', 'user_email' ) ) );
} else {
	$blogusers = get_users( 
		array( 
			'fields' => array( 'id', 'user_login', 'user_email' ),
			'role__in' => array( $_GET['funcao'] )
		)
	);
}

$usuarios = array();
$usuarios[] = array(
	'<style bgcolor="#8EA9DB">ID</style>',
	'<style bgcolor="#8EA9DB">Login</style>',
	'<style bgcolor="#8EA9DB">Nome</style>',
	'<style bgcolor="#8EA9DB">E-mail</style>',
	'<style bgcolor="#8EA9DB">Função</style>',
	'<style bgcolor="#8EA9DB">Grupo</style>'
);

function convertFunc($funcao){
	switch ($funcao):
		case 'administrator':
			return 'Administrador - COCEU SME';
			break;
		case 'contributor':
			return 'Colaborador - CEU';
			break;
		case 'editor':
			return 'Editor - DICEU DRE';
			break;
		default:
			return $funcao;
	endswitch;
}

foreach($blogusers as $user){
	$user_meta = get_userdata($user->id);
	$user_roles = $user_meta->roles;
	$grupos = get_field('grupo', 'user_'. $user->id );
	$grupoTitle = '';
	$i = 0;
	if($grupos && $grupos != '' && $grupos[0] != ''){
		foreach($grupos as $grupo){
			$title = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim( get_the_title($grupo) )));
			if($i == 0){				
				$grupoTitle .= $title;
			} else {
				$grupoTitle .= ', ' . $title;
			}
			$i++;
		}				
	}
	
	$func = $user_roles[0];

	if($grupoTitle == '')
		$grupoTitle = '<center>-</center>';

	if($func == '')
		$func = '<center>-</center>';

	$user_info = get_userdata( $user->id );
	$nome = $user_info->first_name . ' ' . $user_info->last_name;

	$usuarios[] = array(
		$user->id,
		$user->user_login,
		$nome,
		$user->user_email,
		convertFunc($func),
		$grupoTitle
	);

}

$xlsx = Classes\Lib\SimpleXLSXGen::fromArray( $usuarios );
$xlsx->downloadAs($fileName); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 

exit();