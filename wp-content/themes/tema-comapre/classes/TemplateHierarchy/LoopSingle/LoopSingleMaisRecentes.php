<?php
namespace Classes\TemplateHierarchy\LoopSingle;


class LoopSingleMaisRecentes
{
	private $id_post_atual;
	private $args_mais_recentes;
	private $query_mais_recentes;

	public function __construct($id_post_atual)
	{
		$this->id_post_atual = $id_post_atual;
		$this->init();
	}

	public function init(){
		$this->getQueryMaisRecentes();
		$this->montaHtmlMaisRecentes();
	}

	public function getQueryMaisRecentes(){
		$this->args_mais_recentes = array(
			'numberposts' => 6,
			'offset' => 0,
			'category' => 0,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'include' => '',
			'exclude' => $this->id_post_atual,
			'meta_key' => '',
			'meta_value' =>'',
			'post_type' => 'post',
			'post_status' => 'publish',
			'suppress_filters' => true
		);

		$this->query_mais_recentes = wp_get_recent_posts( $this->args_mais_recentes, ARRAY_A );
	}

	public function montaHtmlMaisRecentes(){
		echo '<section class="col-lg-4 col-sm-12 card align-self-start container-titulos-mais-recente">';
		echo '<h2 class="titulo-cabecalho-mais-recentes">Notícias Mais Recentes</h2>';
		foreach( $this->query_mais_recentes as $recent ){
			echo '<article>';
			echo '<p class="titulo-categoria">'.$this->getCategory($recent["ID"]).'</p>';
			echo '<h3 class="titulo-noticias-mais-recentes"><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a> </h3> ';
			echo '</article>';
		}
		echo '</section>';
		wp_reset_query();
	}

	public function getCategory($id_post){
		$categoria = get_the_category($id_post);
		return $categoria[0]->name;
	}

}