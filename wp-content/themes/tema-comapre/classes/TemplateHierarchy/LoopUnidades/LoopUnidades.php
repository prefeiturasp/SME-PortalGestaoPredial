<?php

namespace Classes\TemplateHierarchy\LoopUnidades;

use Classes\Lib\Util;

class LoopUnidades extends Util
{

	public function __construct()
	{
		$this->init();
	}

	public function init(){
		$container_geral_tags = array('div', 'div');
		$container_geral_css = array('container-fluid', 'rowa');
		$this->abreContainer($container_geral_tags, $container_geral_css);

		new LoopUnidadesCabecalho();
		new LoopUnidadesSlide();
        new LoopUnidadesTabs();
		//new LoopSingleNoticiaPrincipal();
		//new LoopSingleMaisRecentes(get_the_ID());
		//new LoopSingleRelacionadas(get_the_ID());

		$this->fechaContainer($container_geral_tags);
	}



}