<?php
use Classes\TemplateHierarchy\LoopSingle\LoopSingle;
get_header();
$loop_single = new LoopSingle();
//contabiliza visualizações de noticias
setPostViews(get_the_ID()); /*echo getPostViews(get_the_ID());*/
get_footer();
?>
