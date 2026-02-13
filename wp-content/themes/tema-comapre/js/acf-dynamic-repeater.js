jQuery(document).ready(function($) {

    function validarCampos(){
        Swal.fire({
            icon: 'error',
            title: 'Atenção',
            text: 'Existem campos a serem corrigidos. Por favor, revisite as abas para identificar as alterações necessárias.',
        });
    }

    acf.addAction('invalid_field', validarCampos); 

    var selectedPostID = null;

    if (!selectedPostID) {
        // Se não houver seleção, limpe o campo repetidor
        $('.acf-field-repeater[data-name="repetidor_dinamico"]').hide();
        clearRepeater();
        //return;
    }

    // Limpa as informacoes iniciais
    $('.acf-repeater').removeClass('-empty');
    $('select[name="acf[field_66627d9708f46][field_66627e7db4ecc]"]').val('');
    clearRepeater();
    

    // Escuta a mudança no seletor
    $('select[name="acf[field_66627d9708f46][field_66627e7db4ecc]"]').on('change', function() {
        
        selectedPostID = $(this).val();
        
        if (!selectedPostID) {
            // Se não houver seleção, limpe o campo repetidor
            $('.acf-field-repeater[data-name="repetidor_dinamico"]').hide();
            clearRepeater();
            return;
        }

        $.ajax({
            url: acf_vars.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'get_repeater_items',
                post_id: selectedPostID
            },
            success: function(response) {
                $('.no-ambientes-message').remove();
                if (response.success) {
                    // Limpar repetidor antes de adicionar novos itens
                    clearRepeater();

                    // Adiciona os itens ao repetidor dinamicamente
                    $.each(response.data, function(index, item) {
                        addRepeaterItem(item);
                    });
                    $('.acf-field-repeater[data-name="repetidor_dinamico"]').show();
                } else {
                    clearRepeater();
                    $('.acf-field-repeater[data-name="repetidor_dinamico"] .acf-input .acf-repeater .acf-table .ui-sortable .acf-row').after('<tr><td class="no-ambientes-message">Nenhum ambiente cadastrado para este prédio.</td></tr>');
                    $('.acf-field-repeater[data-name="repetidor_dinamico"]').show();
                    console.log(response.data);
                }
            }
        });
    });

    // Escuta a adição de nova linha no repetidor
    $(document).on('click', '.acf-repeater-add-row', function() {
        if (selectedPostID) {
            setTimeout(function() {
                updateRepeaterInPredio(selectedPostID);
            }, 100); // Timeout to ensure the row is added
        }
    });

    // Escuta a remoção de linha no repetidor
    $(document).on('click', '.acf-button-remove', function() {
        if (selectedPostID) {
            setTimeout(function() {
                updateRepeaterInPredio(selectedPostID);
            }, 100); // Timeout to ensure the row is removed
        }
    });

    // Escuta mudanças nos campos do repetidor e atualiza o CPT "predio"
    $(document).on('change', '.acf-field-repeater[data-name="repetidor_dinamico"] .acf-repeater .acf-input input, .acf-field-repeater[data-name="repetidor_dinamico"] .acf-repeater .acf-input textarea, .acf-field-repeater[data-name="repetidor_dinamico"] .acf-repeater .acf-input select', function() {
        if (selectedPostID) {
            updateRepeaterInPredio(selectedPostID);
        }
    });

    // Função para limpar o campo repetidor
    function clearRepeater() {
        var $repeater = $('.acf-field-repeater[data-name="repetidor_dinamico"] .acf-repeater');
        $repeater.find('.acf-row').not('.acf-clone').remove();
    }

    // Função para adicionar itens ao campo repetidor
    function addRepeaterItem(item) {
        var $repeater = $('.acf-field-repeater[data-name="repetidor_dinamico"] .acf-repeater .acf-table .ui-sortable');
        var $cloneRow = $repeater.find('.acf-row.acf-clone');
        var $newRow = $cloneRow.clone();
    
        $newRow.removeClass('acf-clone');
        $newRow.removeAttr('data-id');
        $newRow.removeAttr('style');
    
        // Função recursiva para preencher campos aninhados
        function fillFields(item, $context) {
            $.each(item, function(fieldName, fieldValue) {
                if (typeof fieldValue === 'object' && fieldValue !== null) {
                    // Handle nested object
                    $context.find('[data-name="' + fieldName + '"]').each(function() {
                        fillFields(fieldValue, $(this));
                    });
                } else {
                    // Handle normal fields
                    $context.find('[data-name="' + fieldName + '"]').find('.acf-input input, .acf-input textarea, .acf-input select').each(function() {
                        $(this).val(fieldValue);
                        $(this).removeAttr('disabled');
                        var newName = $(this).attr('name').replace('[acfcloneindex]', '[' + ($repeater.find('.acf-row').length - 1) + ']');
                        $(this).attr('name', newName);
                    });
                }
            });
        }
    
        fillFields(item, $newRow);
    
        // Atualiza a label do acordeon com os valores do select e do campo numérico
        var tipoAmbiente = $newRow.find('[data-name="tipo_de_ambiente"] select').val();
        var numAmbiente = $newRow.find('[data-name="num_ambiente"] input').val();
        var labelText = `Ambiente: ${tipoAmbiente} - Número: ${numAmbiente}`;
        $newRow.find('.acf-accordion[data-name="ambiente"] .acf-accordion-title label').html(labelText);
    
        $newRow.insertBefore($cloneRow);
        $newRow.find('select').removeAttr('disabled');
        acf.do_action('append', $newRow);
        updateRepeaterOrder($repeater);
    }

    // Função para atualizar a numeração das linhas do repetidor
    function updateRepeaterOrder($repeater) {
        $repeater.find('.acf-row').not('.acf-clone').each(function(index) {
            $(this).find('.acf-row-number').html(index + 1);
        });
    }

    // Função para atualizar o repetidor no CPT "predio"
    function updateRepeaterInPredio(post_id) {
        var repeaterData = [];
        var ambienteTypes = [];
        var ambienteNumbers = [];

        var hasDuplicate = false;

        $('.acf-field-repeater[data-name="repetidor_dinamico"] .acf-repeater .acf-row').not('.acf-clone').each(function() {
            var rowData = {};

            function gatherFields($context, result) {
                $context.find('[data-name]').each(function() {
                    var fieldName = $(this).data('name');
                    var $inputElement = $(this).find('.acf-input input, .acf-input textarea, .acf-input select');
                    var fieldValue;

                    if ($inputElement.is('select')) {
                        fieldValue = $inputElement.find('option:selected').val();
                    } else {
                        fieldValue = $inputElement.val();
                    }

                    if (fieldName) {
                        if ($(this).find('[data-name]').length > 0) {
                            // Handle nested object
                            result[fieldName] = {};
                            gatherFields($(this), result[fieldName]);
                        } else {
                            // Handle normal fields
                            result[fieldName] = fieldValue;

                            // Verifica duplicações para tipo_de_ambiente e num_ambiente
                            //if (fieldName === 'tipo_de_ambiente') {
                                //if (ambienteTypes.includes(fieldValue)) {
                                    //hasDuplicate = true;
                                    //alert('Tipo de ambiente duplicado: ' + fieldValue);
                                //} else {
                                    ambienteTypes.push(fieldValue);
                                //}
                            //} else if (fieldName === 'num_ambiente') {
                                //if (ambienteNumbers.includes(fieldValue)) {
                                    //hasDuplicate = true;
                                    //alert('Número de ambiente duplicado: ' + fieldValue);
                                //} else {
                                    ambienteNumbers.push(fieldValue);
                                //}
                            //}
                        }
                    }
                });
            }

            gatherFields($(this), rowData);
            repeaterData.push(rowData);
        });

        if (hasDuplicate) {
            return; // Cancela a atualização se houver duplicatas
        }

        $.ajax({
            url: acf_vars.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'update_repeater_in_predio',
                post_id: post_id,
                repeater_data: repeaterData
            },
            success: function(response) {
                if (response.success) {
                    console.log('Repeater data updated successfully.');
                    console.log('Prepared Data:', response.data.prepared_data);
                } else {
                    console.log(response.data);
                }
            }
        });
    }

    // ACF hook para captura da remoção de linha
    acf.addAction('remove', function($el) {
        setTimeout(function() {
            if (selectedPostID) {
                updateRepeaterInPredio(selectedPostID);
            }
        }, 1000); // Timeout to ensure the row is removed
    });

    // Captura o evento de clique no botão "Publicar" ou "Atualizar"
    $('.acf-save-next').on('click', function(e) {
        if (selectedPostID) { 
            updateRepeaterInPredio(selectedPostID); // Atualiza o repetidor no CPT "predio"            
        }
    });
});