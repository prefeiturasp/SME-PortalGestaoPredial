<?php
/*
 * Template Name: Construtor de páginas
 * Description: Modelo para construção de páginas dinamicas
 */

use Classes\ModelosDePaginas\Layout\Construtor;

get_header();
$Construtor = new Construtor();
//contabiliza visualizações de noticias
setPostViews(get_the_ID());  //echo getPostViews(get_the_ID());
get_footer();