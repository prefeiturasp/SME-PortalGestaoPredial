jQuery(document).ready(function($) {
    function fetchAmbientes(nomePredio, categoria) {
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'filtrar_ambientes',
                nomePredio: nomePredio,
                categoria: categoria,
                current_post_id: ajax_object.current_post_id // Passa o ID do post atual
            },
            success: function(response) {
                var data = JSON.parse(response);
                $('.num_ambientes').text(data.count);

                console.log(data);

                var cardsContainer = $('.cards-ambientes');
                cardsContainer.empty(); // Limpa os resultados anteriores

                var listaContainer = $('.lista-ambientes');
                listaContainer.empty(); // Limpa os resultados anteriores

                if (typeof data.ambientes === 'string' && data.ambientes.length > 0) {
                    
                    cardsContainer.empty();
                    cardsContainer.append(data.ambientes);
                    
                    listaContainer.empty();
                    listaContainer.append(data.lista);
                } else {
                    cardsContainer.append('<p>Nenhum ambiente encontrado.</p>');
                }

                $('.accordion-button').on('click', function(event) {
                    // Prevenir a submissão do formulário
                    event.preventDefault();
                });

                $('.accordion-button').on('click', function() {
                    $(this).toggleClass('expanded');
                });
                
            },
            error: function(xhr, status, error) {
                console.error('Erro na solicitação AJAX:', status, error);

                $('.accordion-button').on('click', function(event) {
                    // Prevenir a submissão do formulário
                    event.preventDefault();
                });

                $('.accordion-button').on('click', function() {
                    $(this).toggleClass('expanded');
                });
            }
        });
    }

    $('#filterButton').on('click', function(e) {
        e.preventDefault();
        var nomePredio = $('#nomePredio').val();
        var categoria = $('#categoria').val();
        fetchAmbientes(nomePredio, categoria);
    });

    $('#clearFilterButton').on('click', function(e) {
        e.preventDefault();
        $('#nomePredio').val('');
        $('#categoria').val('');
        fetchAmbientes('', ''); // Passa vazio para buscar todos os dados
    });

    // Selecionar todos os botões de acordeão
    $('.accordion-button').on('click', function(event) {
        // Prevenir a submissão do formulário
        event.preventDefault();
    });

    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if(target.length) {
          event.preventDefault();
          $('html, body').stop().animate({
            scrollTop: target.offset().top
          }, 1000);
        }
    });

    $('.accordion-button').on('click', function() {
       $(this).toggleClass('expanded');
    });
});