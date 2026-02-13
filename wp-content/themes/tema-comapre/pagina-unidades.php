<?php
/*
 * Template Name: Página Unidades CEUs
 * Description: Página Mapa DRE`s, bloco de texto e imagem acima, mapa das dres, respectivos botões e bloco de texto e imagem abaixo
 */

use Classes\ModelosDePaginas\PaginaUnidades\PaginaUnidades;

get_header();
$pagina_abas = new PaginaUnidades();
get_footer();