jQuery(document).ready(function ($) {
    var allStates = $("svg.mapa_dres > *");

    allStates.on("click", function(evento) {
        var id_clicado = evento.currentTarget.id;
        allStates.removeClass("on");
        $(this).addClass("on");
        var div_correspondente =  $('#container-div-'+id_clicado).find('div#div-'+id_clicado);
        div_correspondente.addClass('show');
        var id_div_correspondente = div_correspondente.attr('id');
        percorreBotoes(id_div_correspondente);

    });

    $('.a-click-botao').each(function (e) {

        $(this).click(function (e) {
	    $('.a-click-botao').removeClass('a-click-botao-clicado');
	    $('.card-header').removeClass('container-titulo-botoes-on-click');
            $('.collapse').removeClass('show');
	    $('.st1').removeClass('on');
            var id_botao_atual = $(this).attr('id');
            $(this).toggleClass('a-click-botao-clicado');
            $(this).parent().parent().toggleClass('container-titulo-botoes-on-click');
            percorreMapa(id_botao_atual);
        });

    });

    function percorreBotoes(id_div_correspondente) {
        $('.dre-atual').each(function (e) {
            var id_div_atual = this.id;
            if (id_div_atual != id_div_correspondente ){
                $(this).removeClass('show');
            }
        });
    }

    function percorreMapa(id_botao_atual) {
        $('.st1').each(function (e) {
            var id_mapa_atual = this.id;
            if (id_botao_atual == id_mapa_atual){
                $(this).toggleClass('on');
            }
        });
    }

});