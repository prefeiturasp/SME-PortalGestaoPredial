<?php
/* Inicialização das Classes */

require_once 'LoadDependences.php';
require_once 'Lib/Util.php';
require_once 'Lib/SimpleXLSXGen.php';

require_once 'Header/Header.php';

require_once 'Usuarios/Editor/Editor.php';
require_once 'Usuarios/Colaborador/Colaborador.php';
require_once 'Usuarios/Administrador/Administrador.php';
require_once 'Usuarios/Educador/Educador.php';
require_once 'Usuarios/EngComapre/EngComapre.php';
require_once 'Usuarios/EngDre/EngDre.php';
require_once 'Usuarios/Administrativo/Administrativo.php';
require_once 'Usuarios/Vistoriador/Vistoriador.php';
require_once 'Usuarios/EnviarParaRevisao.php';
require_once 'Usuarios/CamposAdicionais.php';

//tutorial
require_once 'tutorial/tutorial.php';

require_once 'Cpt/Cpt.php';
require_once 'Cpt/CptPosts.php';
require_once 'Cpt/CptPages.php';
require_once 'Cpt/CptUnidades.php';
require_once 'Cpt/CptDestaque.php';

require_once 'TemplateHierarchy/Page.php';
require_once 'TemplateHierarchy/Tag.php';
require_once 'TemplateHierarchy/LoopSingleCard.php';
require_once 'TemplateHierarchy/LoopSingle/LoopSingle.php';
require_once 'TemplateHierarchy/LoopSingle/LoopSingleCabecalho.php';
require_once 'TemplateHierarchy/LoopSingle/LoopSingleMenuInterno.php';
require_once 'TemplateHierarchy/LoopSingle/LoopSingleNoticiaPrincipal.php';
require_once 'TemplateHierarchy/LoopSingle/LoopSingleMaisRecentes.php';
require_once 'TemplateHierarchy/LoopSingle/LoopSingleRelacionadas.php';

require_once 'TemplateHierarchy/LoopUnidades/LoopUnidades.php';
require_once 'TemplateHierarchy/LoopUnidades/LoopUnidadesCabecalho.php';
require_once 'TemplateHierarchy/LoopUnidades/LoopUnidadesSlide.php';
require_once 'TemplateHierarchy/LoopUnidades/LoopUnidadesTabs.php';

require_once 'TemplateHierarchy/ArchiveAgenda/ArchiveAgendaGetDatasEventos.php';
require_once 'TemplateHierarchy/ArchiveContato/ArchiveContatoMetabox.php';
require_once 'TemplateHierarchy/ArchiveContato/ArchiveContato.php';
require_once 'TemplateHierarchy/ArchiveContato/ExibirContatosTodasPaginas.php';
//require_once 'TemplateHierarchy/ArchiveAgenda/ArchiveAgenda.php';
//require_once 'TemplateHierarchy/ArchiveAgenda/ArchiveAgendaAjaxCalendario.php';

require_once 'TemplateHierarchy/Search/GetTipoDePost.php';
require_once 'TemplateHierarchy/Search/SearchForm.php';
require_once 'TemplateHierarchy/Search/LoopSearch.php';
require_once 'TemplateHierarchy/Search/SearchFormSingle.php';
require_once 'TemplateHierarchy/Search/LoopSearchSingle.php';

require_once 'ModelosDePaginas/Layout/construtor.php';

require_once 'ModelosDePaginas/PaginaProgramacao/PaginaProgramacao.php';
require_once 'ModelosDePaginas/PaginaProgramacao/PaginaProgramacaoSlide.php';
require_once 'ModelosDePaginas/PaginaProgramacao/PaginaProgramacaoBusca.php';
require_once 'ModelosDePaginas/PaginaProgramacao/PaginaProgramacaoCategorias.php';
require_once 'ModelosDePaginas/PaginaProgramacao/PaginaProgramacaoEventos.php';

require_once 'ModelosDePaginas/PaginaUnidades/PaginaUnidades.php';
require_once 'ModelosDePaginas/PaginaUnidades/PaginaUnidadesMapa.php';

require_once 'ModelosDePaginas/Login/Login.php';
require_once 'ModelosDePaginas/Login/LoginForm.php';
require_once 'ModelosDePaginas/Login/LoginRecuperar.php';

require_once 'BuscaDeEscolas/BuscaDeEscolasRewriteUrl.php';
require_once 'BuscaDeEscolas/BuscaDeEscolas.php';

require_once 'Breadcrumb/Breadcrumb.php';

require_once 'ModelosDePaginas/ModelosDePaginaRemoveThemeSupport.php';

require_once 'Cpt/CptMediaImages.php';

/* Inicialização CPTs */
$cptPostsExtend = new \Classes\Cpt\CptPosts();
$cptPagessExtend = new \Classes\Cpt\CptPages();
$cptUnidades = new \Classes\Cpt\Cpt('unidade', 'unidade', 'Informações Institucionais', 'Minhas Unidades', 'Unidades', 'Cadastro de Unidades', '', '', '', 'dashicons-building' , true);
$cptUnidadesExtend = new \Classes\Cpt\CptUnidades();

$cptDestaque = new \Classes\Cpt\Cpt('destaque', 'destaque', 'Destaque', 'Todos os Destaques', 'Destaques', 'Destaque', '', '', '', 'dashicons-feedback', true);
$cptDestaqueExtend = new \Classes\Cpt\CptDestaque();

//$cptAgendaSecretario = new \Classes\Cpt\Cpt('agenda', 'agenda', 'Agenda do Secretário', 'Todos os Eventos', 'Eventos', 'Eventos', null, null, null, 'dashicons-calendar-alt', true);
//$cptAgendaSecretarioExtend = new \Classes\Cpt\CptAgendaSecretario();


$taxonomiaMediaImages = new \Classes\Cpt\CptMediaImages();
