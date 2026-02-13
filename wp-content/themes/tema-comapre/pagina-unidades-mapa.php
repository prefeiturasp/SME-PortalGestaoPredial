<?php
/*
 * Template Name: Página Unidades CEUs - Mapa
 * Description: Página Mapa DRE`s, bloco de texto e imagem acima, mapa das dres, respectivos botões e bloco de texto e imagem abaixo
 */

use Classes\ModelosDePaginas\PaginaUnidades\PaginaUnidadesMapa;

get_header();
$pagina_abas = new PaginaUnidadesMapa();
get_footer();