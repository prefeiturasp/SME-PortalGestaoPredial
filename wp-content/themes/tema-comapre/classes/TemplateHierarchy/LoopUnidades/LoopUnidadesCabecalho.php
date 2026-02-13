<?php

namespace Classes\TemplateHierarchy\LoopUnidades;

class LoopUnidadesCabecalho extends LoopUnidades{

	public function __construct()
	{
		$this->cabecalhoUnidade();
	}

	public function cabecalhoUnidade(){
        $infoBasicas = get_field('informacoes_basicas');
        //echo "<pre>";
        //print_r($infoBasicas['horario']);
        //echo "</pre>";
    ?>
        <div class="container color-<?php echo $infoBasicas['zona_sp']; ?>" id="Noticias">
            <div class="row info-title border-bottom mb-3">
                <div class="col-md-9 col-sm-12">
                    <h1><?php echo get_the_title(); ?></h1>
                    <p><?php echo $infoBasicas['dre_pertencente']; ?></p>                    
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="form-group">
                        <select class="form-control" name="forma" onchange="location = this.value;">
                            <option disabled selected value> Selecione outra unidade </option>
                            <?php
                                $argsUnidades = array(
                                    'post_type' => 'unidade',
                                    'posts_per_page' => -1,
                                    'orderby' => 'title',
                                    'order' => 'ASC',
                                    'post__not_in' => array(31244),
                                    'post_status' => array('publish', 'pending'),
                                );

                                $todasUnidades = new \WP_Query( $argsUnidades );
        
                                // The Loop
                                if ( $todasUnidades->have_posts() ) {
                                    
                                    while ( $todasUnidades->have_posts() ) {
                                        $todasUnidades->the_post();
                                        echo '<option value="' . get_the_permalink() .'">' . get_the_title() .'</option>';
                                    }
                                
                                }
                                wp_reset_postdata();
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row info-contacts">            
                <div class="col-sm-12">                    
                    <p class='endereco'>
                        <?php 
                            if($infoBasicas['endereco'] && $infoBasicas['endereco'] != ''){
                                echo $infoBasicas['endereco'];
                            }

                            if($infoBasicas['numero'] && $infoBasicas['numero'] != ''){
                                echo ', ' . $infoBasicas['numero'];
                            }

                            if($infoBasicas['complemento'] && $infoBasicas['complemento'] != ''){
                                echo ' - ' . $infoBasicas['complemento'];
                            }

                            if($infoBasicas['bairro'] && $infoBasicas['bairro'] != ''){
                                echo ' - ' . $infoBasicas['bairro'];
                            }

                            if($infoBasicas['cep'] && $infoBasicas['cep'] != ''){
                                echo ' - CEP: ' . $infoBasicas['cep'];
                            }
                        ?>
                    </p>

                    <p class='contatos'>
                        <span>Contatos: </span>
                        <?php 
                            $contatos = array();
                            $tel_primary = $infoBasicas['telefone']['telefone_principal'];
                            $tel_second = $infoBasicas['telefone']['tel_second'];
                            $email_primary = $infoBasicas['email']['email_principal'];
                            $email_second = $infoBasicas['email']['email_second'];
                            $i = 0;

                            if($tel_primary && $tel_primary != ''){
                                $contatos[] = $tel_primary;
                            }
                        
                            if($tel_second && $tel_second != ''){
                                foreach($tel_second as $tel){
                                    $contatos[] = $tel['telefone_sec'];
                                }
                            }

                            if($email_primary && $email_primary != ''){
                                $contatos[] = $email_primary;
                            }
                        
                            if($email_second && $email_second != ''){
                                foreach($email_second as $email){
                                    $contatos[] = $email['email'];
                                }
                            }
                            
                            foreach($contatos as $contato){
                                if($i == 0){
                                    echo $contato;
                                } else {
                                    echo ' / '. $contato;
                                }

                                $i++;
                            }
                        ?>
                    </p>

                    <p class="horarios">
                        <span>Horário de Funcionamento: </span>
                        <?php
                            $horario = $infoBasicas['horario'];
                            
                            if($horario['dia_abertura'] && $horario['dia_abertura'] != ''){
                                echo $horario['dia_abertura'];
                            }

                            if($horario['dia_fechamento'] && $horario['dia_fechamento'] != ''){
                                echo ' a ' . $horario['dia_fechamento'];
                            }

                            if($horario['horario_abertura'] && $horario['horario_abertura'] != ''){
                                $hora_abert = convertHour($horario['horario_abertura']);                       
                                echo ' das ' . $hora_abert;
                            }

                            if($horario['horario_fechamento'] && $horario['horario_fechamento'] != ''){
                                $hora_fech = convertHour($horario['horario_fechamento']); 
                                echo ' às ' . $hora_fech;
                            }
                        ?>

                        <?php if($horario['horario_de_funcionamento'] && $horario['horario_de_funcionamento'] != '') : ?>
                        
                            <?php 
                                foreach($horario['horario_de_funcionamento'] as $horario){
                                    if($horario['data_inicial'] && $horario['data_inicial'] != ''){
                                        echo ' / ' . $horario['data_inicial'];
                                    }
        
                                    if($horario['data_final'] && $horario['data_final'] != '' && $horario['data_final'] != 'Feriados'){
                                        echo ' e ' . $horario['data_final'];
                                    }
        
                                    if($horario['hora_abertura'] && $horario['hora_abertura'] != ''){
                                        $hora_a = convertHour($horario['hora_abertura']);      
                                        echo ' das ' . $hora_a;
                                    }
        
                                    if($horario['hora_fechamento'] && $horario['hora_fechamento'] != ''){
                                        $hora_f = convertHour($horario['hora_fechamento']);
                                        echo ' às ' . $hora_f;
                                    }
                                }
                            ?>
                        
                        <?php endif; ?>
                    </p>
                </div> 
            </div>
        </div>

    <?php
        
    }

}