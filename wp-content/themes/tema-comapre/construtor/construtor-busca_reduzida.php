<?php
    if ( !current_user_can( 'educador' ) && !current_user_can( 'administrativo' ) ):
        $titulo = get_sub_field('titulo_principal');
        $placeholder = get_sub_field('placeholder');
        if(!$placeholder)
            $placeholder = 'Encontre a unidade pelo código EOL ou nome';
?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="busca-unidades busca-result">

                    <div class="form-texto">
                        <div class="texto-pesquisa">
                            <?php if($titulo): ?>
                                <p><?= $titulo; ?></p>
                            <?php endif; ?>                       
                        </div>
                        <div class="form-busca">
                            <form id="search-form">
                                <input type="text" id="search-input" name="s" placeholder="<?= $placeholder; ?>" autocomplete="off">
                            </form>
                            <div id="search-results"></div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <?php if($_GET['result'] && $_GET['result'] != ''): ?>
        <?php $id = $_GET['result']; ?>
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="table-results">
                        <h3 class="results-title">
                            Resultado da pesquisa
                        </h3>
                        <hr>
                        <div class="table-padding">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="align-middle" scope="col">Código EOL</th>
                                        <th class="align-middle" scope="col">Nome da unidade</th>
                                        <th class="align-middle" scope="col">DRE</th>
                                        <th class="align-middle" scope="col">Data abertura da vistoria</th>
                                        <th class="align-middle" scope="col">Status</th>
                                        <th class="align-middle" scope="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle" scope="row">                                            
                                            <?php
                                                $eol = get_field('cod_eol', $id);
                                                echo $eol ? $eol : '-';
                                            ?>
                                    
                                        </td>
                                        <td class="align-middle"><?= get_the_title($id); ?></td>
                                        <td class="align-middle"><?= get_field('dre', $id); ?></td>
                                        <td class="align-middle">
                                            <?php
                                                $data_abertura = get_field('data_abertura', $id);
                                                echo $data_abertura ? $data_abertura : '-';
                                            ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                                $status = get_field('status_vistoria', $id);
                                                $class = 'badge-warning';
                                                if($status == 'Em andamento'){
                                                    $class = 'badge-primary';
                                                } elseif($status == 'Concluída'){
                                                    $class = 'badge-success';
                                                }
                                                echo $status ? '<span class="px-2 py-1 badge ' . $class . '">' . $status . '</span>' : '-';
                                            ?>
                                        </td>
                                        <td class="align-middle text-center"><a href="<?= get_the_permalink($id); ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                    </tr>                       
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

    <?php endif; ?>
<?php endif; ?>