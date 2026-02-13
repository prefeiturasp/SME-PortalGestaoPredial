<?php


namespace Classes\ModelosDePaginas\PaginaProgramacao;


class PaginaProgramacaoCategoria
{

    public function __construct()
	{
		$this->getCategoria();
	}

	public function getCategoria(){
    ?>
        <div class="atividades-categorias mt-5 mb-5">
            <div class="container d-none d-md-block">
                <div class="row">
                    <?php
                        $categorias = get_field('categorias_destaque');
                        //echo "<pre>";
                        //print_r($categorias);
                        //echo "</pre>";

                        foreach($categorias as $categoria) :
                    ?>
                        <div class="col-sm-2">
                            <div class="card-categ text-center">
                                <a href="<?php echo home_url( '/' );?>?categ=1&tipo=programacao&s=&atividades[]=<?php echo $categoria->slug;?>">
                                    
                                    <?php                                        
                                        $idImage = get_field('imagem_principal', 'term_' . $categoria->term_id);                                      
                                        
                                        if($idImage){
                                            $getImage = wp_get_attachment_image_src($idImage, 'categoria-eventos');
                                            $alt = get_the_title($idImage);
                                            $image = $getImage[0];
                                              
                                        } else {
                                            $image = "https://via.placeholder.com/300x300";
                                            $alt = "Imagem ilustrativa";
                                        }
                                    ?>
                                    <img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" class="img-fluid d-block rounded-circle">
                                    <h2><?php echo $categoria->name; ?></h2>
                                </a>
                            </div>
                        </div>
                    
                    <?php endforeach; ?>
                    
                </div>
            </div>

            <div class="swiper d-block d-md-none">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                <!-- Slides -->

                    <?php
                        $categorias = get_field('categorias_destaque');
                        //echo "<pre>";
                        //print_r($categorias);
                        //echo "</pre>";

                        foreach($categorias as $categoria) :
                    ?>
                        <div class="swiper-slide">
                            <div class="categ-bloco">
                                <div class="card-categ text-center">
                                    <a href="<?php echo home_url( '/' );?>?categ=1&tipo=programacao&s=&atividades[]=<?php echo $categoria->slug;?>">
                                        
                                        <?php                                        
                                            $idImage = get_field('imagem_principal', 'term_' . $categoria->term_id);                                      
                                            
                                            if($idImage){
                                                $getImage = wp_get_attachment_image_src($idImage, 'categoria-eventos');
                                                $alt = get_the_title($idImage);
                                                $image = $getImage[0];
                                                
                                            } else {
                                                $image = "https://via.placeholder.com/300x300";
                                                $alt = "Imagem ilustrativa";
                                            }
                                        ?>
                                        <img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" class="img-fluid d-block rounded-circle">
                                        <h2><?php echo $categoria->name; ?></h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                    
                    <?php endforeach; ?>
                </div>                 

            </div>
        </div>
    <?php
	}


}