<?php

namespace Classes\TemplateHierarchy\LoopSingle;

use Classes\Lib\Util;

class LoopSingle extends Util
{

	public function __construct()
	{
		$this->init();
	}

	public function init(){
		$container_geral_tags = array('section', 'section');
		$container_geral_css = array('container-fluid', 'row');
		$this->abreContainer($container_geral_tags, $container_geral_css);

		new LoopSingleCabecalho();
		//new LoopSingleMenuInterno();
		new LoopSingleNoticiaPrincipal();
		//new LoopSingleMaisRecentes(get_the_ID());
		new LoopSingleRelacionadas(get_the_ID());

		$this->fechaContainer($container_geral_tags);
	}



}