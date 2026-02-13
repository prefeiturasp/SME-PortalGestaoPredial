<?php


namespace Classes\ModelosDePaginas\PaginaProgramacao;


class PaginaProgramacaoBusca
{

    public function __construct()
	{
		$this->getBusca();
	}

	public function getBusca(){
    ?>
        <div class="search-home py-4" id='programacao'>
            <div class="container">
                
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h1>Encontre atividades que você goste</h1>
                        <p>As melhores atividades diretamente dos CEUs para a comunidade</p>
                        <?php 
                            
                            // Atividades
                            $terms = get_terms( array( 
                                'taxonomy' => 'atividades_categories',
                                'parent'   => 0,                                
                                'hide_empty' => false
                            ) );

                            // Publico Alvo
                            $publicos = get_terms( array( 
                                'taxonomy' => 'publico_categories',
                                'parent'   => 0,                                
                                'hide_empty' => false
                            ) );

                            // Faixa Etaria
                            $faixas = get_terms( array( 
                                'taxonomy' => 'faixa_categories',
                                //'parent'   => 0,                                
                                'hide_empty' => false
                            ) );

                            // Unidades
                            $unidades = get_terms( array( 
                                'taxonomy' => 'category',
                                'parent'   => 0,                                
                                'hide_empty' => false,
                                'exclude' => 1
                            ) );

                            // Periodo
                            $periodos = get_terms( array( 
                                'taxonomy' => 'periodo_categories',
                                'parent'   => 0,                                
                                'hide_empty' => false,
                                'exclude' => 1
                            ) );
                            
                        ?>
                    </div>

                    <div class="col-12">

                        <form action="<?php echo home_url( '/' ); ?>"  id="searchform" class="row form-prog">
                            <input id="prodId" name="tipo" type="hidden" value="programacao">

                            <div class="col-sm-6 mt-3">
                                <input type="text" name="s" class="form-control" placeholder="Busque por palavra-chave">
                            </div>

                            <div class="col-sm-6 mt-3">
                                <select name="atividades[]" multiple="multiple" class="ms-list-1" style="">
                                    <?php if ( !empty( $terms ) && !is_wp_error( $terms ) ): ?>
                                        <?php foreach( get_terms( 'atividades_categories', array( 'hide_empty' => false, 'parent' => 0 ) ) as $parent_term ) : ?>
                                            <?php
                                                $term_children = get_term_children($parent_term->term_id, 'atividades_categories');
                                                if($term_children):
                                            ?>
                                                <optgroup label="<?= $parent_term->name; ?>">
                                                    <?php foreach($term_children as $term): 
                                                        $categoria = get_term( $term, 'atividades_categories' );
                                                    ?>
                                                        <option value="<?= $categoria->slug; ?>"><?= $categoria->name; ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php else: ?>
                                                <option value="<?= $parent_term->slug; ?>"><?= $parent_term->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>                                                   
                                </select>
                            </div>

                            <div class="col-sm-6 mt-3">
                                <select name="faixaEtaria[]" multiple="multiple" class="ms-list-4" style="">                        
                                    <?php if ( !empty( $faixas ) && !is_wp_error( $faixas ) ): ?>
                                        <?php foreach( get_terms( 'faixa_categories', array( 'hide_empty' => false, 'parent' => 0 ) ) as $parent_term ) : ?>
                                            <?php
                                                $term_children = get_term_children($parent_term->term_id, 'faixa_categories');
                                                if($term_children):
                                            ?>
                                                <optgroup label="<?= $parent_term->name; ?>">
                                                    <?php foreach($term_children as $term): 
                                                        $faixa_etaria = get_term( $term, 'faixa_categories' );
                                                    ?>
                                                        <option value="<?= $faixa_etaria->slug; ?>"><?= $faixa_etaria->name; ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php else: ?>
                                                <option value="<?= $parent_term->slug; ?>"><?= $parent_term->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>                      
                                </select>
                            </div>

                            <div class="col-sm-3 mt-3">
                                <select name="publico[]" multiple="multiple" class="ms-list-3" style="">                        
                                    <?php foreach ($publicos as $publico): ?>
                                        <option value="<?php echo $publico->slug; ?>"><?php echo $publico->name; ?></option>
                                    <?php endforeach; ?>                    
                                </select>
                            </div>

                            <div class="col-sm-3 mt-3">
                                <select name="unidades[]" multiple="multiple" class="ms-list-5" style="">
                                    <?php
                                            $currentID = get_the_id();
                                            $argsUnidades = array(
                                                'post_type' => 'unidade',
                                                'posts_per_page' => -1,
                                                'post__not_in' => array(31675, 31244),
                                                'orderby' => 'title',
                                                'order'   => 'ASC',
                                                'post_status' => array('publish', 'pending'),
                                            );

                                            $todasUnidades = new \WP_Query( $argsUnidades );
                    
                                            // The Loop
                                            if ( $todasUnidades->have_posts() ) {
                                                
                                                while ( $todasUnidades->have_posts() ) {
                                                    $todasUnidades->the_post();

                                                    $titulo = htmlentities(get_the_title());
                                                    $seletor = explode (" &amp;", $titulo);

                                                    if($currentID == get_the_id() ) {
                                                        echo '<option selected value="' . get_the_id() .'">' . $seletor[0] .'</option>';
                                                    } else {
                                                        echo '<option value="' . get_the_id() .'">' . $seletor[0] .'</option>';
                                                    }
                                                    
                                                }
                                            
                                            }
                                            wp_reset_postdata();
                                        ?>      
                                </select>
                            </div>

                            <div class="col-sm-6 mt-3">
                                <div id='date-range'>
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" class="input-sm form-control" name="start" placeholder="Data" />
                                        <span class="input-group-addon px-2">Até</span>
                                        <input type="text" class="input-sm form-control" name="end" placeholder="Data" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 mt-3">
                                <select name="data[]" multiple="multiple" class="ms-list-9" style=""> 
                                    <optgroup label="Dia da semana">
                                        <option value="segunda">Segunda</option>
                                        <option value="terca">Terça</option>
                                        <option value="quarta">Quarta</option>
                                        <option value="quinta">Quinta</option>
                                        <option value="sexta">Sexta</option>
                                        <option value="sabado">Sábado</option>
                                        <option value="domingo">Domingo</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="col-sm-3 mt-3">
                                <select name="periodos[]" multiple="multiple" class="ms-list-8" style="">                        
                                    <option value='manha'>Manhã</option>
                                    <option value='tarde'>Tarde</option>
                                    <option value='noite'>Noite</option>                        
                                </select>
                            </div>

                            <div class="col-sm-12 text-right mt-3">
                                <a href="<?= get_home_url();?>/programacao" class="btn btn-outline-primary mr-3">Limpar busca</a>
                                <button type="submit" class="btn btn-search">Buscar</button>
                            </div>

                        </form>

                    </div>
                    
                </div> <!-- end row -->
            </div>
        </div>
    <?php
	}


}