<?php


namespace Classes\ModelosDePaginas\PaginaProgramacao;

use Classes\TemplateHierarchy\ArchiveContato\ArchiveContato;

class PaginaProgramacao extends ArchiveContato
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
        
        $this->htmlSlideProgramacao();
        $this->htmlBuscaProgramacao();
        $this->htmlCategoriasProgramacao();
        $this->htmlEventosProgramacao();
		$this->fechaContainer($container_geral_tags);
	}

	public function getTituloPagina(){
		echo '<article class="col-12">';
	        echo '<h1 class="mb-4" id="'.get_queried_object()->post_name.'">'.get_the_title().'</h1>';
		echo '</article>';
    }


	public function htmlSlideProgramacao(){
		?>

		<section class="col-12 p-0">            
            <?php new PaginaProgramacaoSlide(); ?>
		</section>

		<?php
    }
    
    public function htmlBuscaProgramacao()
    {   
	?>
		<section class="col-12 p-0">            
            <?php new PaginaProgramacaoBusca(); ?>
		</section>

		<?php
    }

    public function htmlCategoriasProgramacao()
    {   
	?>
		<section class="col-12 p-0">            
            <?php new PaginaProgramacaoCategoria(); ?>
		</section>

		<?php
    }

    public function htmlEventosProgramacao()
    {   
	?>
		<section class="col-12 p-0">            
            <?php new PaginaProgramacaoEventos(); ?>
		</section>

		<?php
    }
}