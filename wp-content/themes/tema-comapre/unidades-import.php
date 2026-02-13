<?php

    // Função para buscar os dados na tabela
    function buscar_dados_tabela() {
        global $wpdb;

        // Nome da tabela
        $tabela = $wpdb->prefix . 'import_unidades'; // Substitua 'sua_tabela' pelo nome real da sua tabela

        // Consulta SQL para buscar os dados
        $sql = "SELECT * FROM $tabela ORDER BY `ID` DESC";

        // Buscar os dados na tabela
        $resultados = $wpdb->get_results( $sql );

        // Retornar os resultados
        return $resultados;
    }

    // Função para exibir a tabela HTML com os dados
    function exibir_tabela_dados() {
        // Buscar os dados na tabela
        $dados = buscar_dados_tabela();

        // Verificar se há dados
        if ( $dados ) {

            // Loop pelos resultados
            foreach ( $dados as $linha ) {
                // Exibir cada linha da tabela
                echo '<tr>';
                echo '<td>' . $linha->data_ini . '</td>';
                echo '<td>' . $linha->data_fin . '</td>';
                echo '<td>' . $linha->cadastros . '</td>';
                echo '<td>' . $linha->atualizados . '</td>';
                echo '<td>' . $linha->erro . '</td>';
                echo '</tr>';
            }
            
        } else {
            // Se não houver dados, exibir uma mensagem
            echo 'Nenhum dado encontrado na tabela.';
        }
    }
?>

<div class="wrap">
    <h2>Importação de Unidades</h2>
    <!-- Adicione aqui o conteúdo da página de opções -->
    <br>
    <table class="widefat striped fixed">
        <thead>
            <tr>
                <th>Hora inicio</th>
                <th>Hora final</th>
                <th>Importações</th>
                <th>Atualizações</th>
                <th>Erro</th>
            </tr>
        </thead>

        <tbody>
            <?php exibir_tabela_dados(); ?>
        </tbody>

        <tfoot>
            <tr>
                <th>Hora inicio</th>
                <th>Hora final</th>
                <th>Importações</th>
                <th>Atualizações</th>
                <th>Erro</th>
            </tr>
        </tfoot>
    </table>
    
</div>