<?php
/**
 * Template Name: Nova senha
 *
 * Allow users to update their profiles from Frontend.
 *
 
*/
if( !isset($_GET['token']) && $_GET['token'] == ''){
    wp_redirect( get_home_url() );
}

$imagem = get_field('imagem');

if($_GET['action']){
    $action = get_the_permalink() . '?token=' . $_GET['token'] . "&action=" . $_GET['action'];
} else {
    $action = get_the_permalink() . '?token=' . $_GET['token'];
}

$login_page = get_home_url() . "/?login=new";

get_header('forms'); // Loads the header.php template. ?>

        <div class="container-fluid container-forms" style="background-image: url('<?= $imagem; ?>');">
			<div class="container">
				<div class="row">
					<div class="col-12 col-md-6 offset-md-6">

						<div class='core_login_form'>
                            <?php
                                // Traz o Logotipo cadastrado no Admin
                                $custom_logo_id = get_theme_mod('custom_logo');
                                $image = wp_get_attachment_image_src($custom_logo_id, 'full');
                            ?>
                            <p class="logo-form">
                                <a class="brand" href="<?php echo STM_URL ?>">
                                    <img class="img-fluid" src="<?php echo $image[0] ?>" alt="Logotipo da Secretaria Municipal de Educação - Ir para a página principal"/>
                                </a>
                            </p>
							<h2>Definir nova senha</h2>
														
							<form id="newPassForm" action="<?= $action; ?>" method="post">
								<p class="login-username">
                                    <div class="requisitos">
                                        <p>Identificamos que você ainda não definiu uma senha pessoal para acesso ao Gestão Predial. Este passo é obrigatório para que você tenha acesso ao sistema.</p>
                                        <?php if(!$_GET['action']): ?>
                                            <div class="form-check">
                                                <input class="form-check-input w-auto" type="checkbox" value="" id="ciencia-senha">
                                                <label class="form-check-label" for="ciencia-senha">
                                                    <span>*</span> Estou ciente que a nova senha será aplicada para os outros acessos (Portais e Sistemas) da SME, incluindo o SGP.
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                        <div class="form-group senha-nova">
                                            <label for="senha-atual"><span>*</span> Nova senha</label>
                                            <input type="password" class="form-control" id="senha-nova" name="senha-nova" placeholder="Nova senha">
                                        </div>
                                        <div class="form-group senha-repita">
                                            <label for="senha-atual"><span>*</span> Confirmação da nova senha</label>
                                            <input type="password" class="form-control" id="senha-repita" name="senha-repita" placeholder="Repita a nova senha">
                                        </div>
                                        
                                        <div class="requisitos-texto">
                                            <strong>Requisitos de segurança da senha:</strong>
                                            <br><br>
                                            Uma letra maiúscula<br>
                                            Uma letra minúscula<br>
                                            As senhas devem ser iguais<br>
                                            Não pode conter espaços em branco<br>
                                            Não pode conter caracteres acentuados<br>
                                            Um número ou símbolo (caractere especial)<br>
                                            Deve ter no mínimo 8 e no máximo 12 caracteres
                                        </div>
                                        
                                    </div>
									<div class="buttons-form text-right">
										<a href="<?= get_home_url(); ?>" class="btn btn-outline-primary" id="cancel">Cancelar</a>
										<input type="submit" value="Continuar" id="newPass" class="btn btn-primary">
									</div>
									
								</p>
							</form>
						</div>

					</div>
				</div>
			</div>			
		</div>

<?php get_footer('forms'); // Loads the footer.php template. ?>

<?php

    function valida_senha($senha){
        if(!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,12}$/',$senha)) {
           return false;
        } else {
            return true;
        }
    }

    if($_GET['token'] && $_GET['token'] != ''){

        if($_GET['action'] && $_GET['action'] == 'rp'){

            $chave = isset($_GET['token']) ? $_GET['token'] : '';
            
            // Parâmetros da consulta
            $args = array(
                'meta_key'     => 'chave_temp', // Substitua pelo nome do seu campo meta_key
                'meta_value'   => $chave,
                'meta_compare' => '=', 
            );

            // Obtém os usuários que correspondem aos parâmetros da consulta
            $usuarios = get_users($args);

            // Verifica se há usuários correspondentes
            if (!empty($usuarios)) {
                
                // O ID do primeiro usuário correspondente
                $user_login = $usuarios[0]->data->user_login;

                // Verifique se o token é válido
                $user = check_password_reset_key($chave, $user_login);

                if (is_wp_error($user)) {
                    $token = false;
                    echo "<script>                    
                            window.location.replace('" . get_home_url() . "/recuperar-senha/?pass=new');
                        </script>";
                } else {
                    $token = true;
                    
                    if(isset($_POST['senha-nova']) && $_POST['senha-nova'] == '' && $_POST['senha-repita'] == '' && $token){
                        echo 
                            "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Senhas obrigatórias',
                                    text: 'Preencha todos os campos de senha.',
                                });
                            </script>";
                    }
        
                    if($_POST['senha-nova'] != $_POST['senha-repita'] && $chave){
                        echo 
                            "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Senhas diferentes',
                                    text: 'As novas senhas não conferem, por gentileza revise e tente novamente.',
                                });
                            </script>";
                    } 
                    
                   if( $_POST['senha-nova'] != '' && $_POST['senha-repita'] != '' && $token && $_POST['senha-nova'] == $_POST['senha-repita']){
                        if($_POST['senha-nova']){}
                        // Atualize a senha do usuário
                        wp_set_password($_POST['senha-nova'], $user->ID);
                        
                        echo "<script>                    
                            window.location.replace('" . get_home_url() . "?login=new');
                        </script>";
                   }
                }
                
               
            } else {
                
                echo "<script>                    
                        window.location.replace('" . get_home_url() . "/recuperar-senha/?pass=new');
                    </script>";
                
            }
            

        } else {
            
            $api_url = getenv('SMEINTEGRACAO2_API_URL') . '/api/v1/autenticacao/RecuperarSenha/token/validar';
            $response = wp_remote_post( $api_url, array(
                'method'      => 'POST',                    
                'headers' => array( 
                    'x-api-eol-key' => getenv('SMEINTEGRACAO2_API_TOKEN'),
                    'Content-Type' => 'application/json-patch+json'
                ),
                'body' => '"' . $_GET['token'] . '"',
                )
            );

            if ( is_wp_error( $response ) ) {
                //$error_message = $response->get_error_message();
                //echo "Something went wrong: $error_message";
            } else {
                $response = $response;
            }

            if($response['response']['code'] == 200 && $response['body'] == 'true'){
                $token = true;
            } else {
                $token = false;
                echo "<script>                    
                        window.location.replace('" . get_home_url() . "/recuperar-senha/?pass=new');
                    </script>";
            }

            if(isset($_POST['senha-nova']) && $_POST['senha-nova'] == '' && $_POST['senha-repita'] == '' && $token){
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Senhas obrigatórias',
                            text: 'Preencha todos os campos de senha.',
                        });
                    </script>";
            }

            if($_POST['senha-nova'] != $_POST['senha-repita'] && $token){
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Senhas diferentes',
                            text: 'As novas senhas não conferem, por gentileza revise e tente novamente.',
                        });
                    </script>";
            } else {

                if(isset($_POST['senha-nova'])){
                    $senha = $_POST['senha-nova'];
                    $validar = valida_senha($senha);
                    if($token && $validar){
                        
                        // URL da API
                        $api_url = getenv('SMEINTEGRACAO2_API_URL') . '/api/v1/autenticacao/AlterarSenha';

                        // Conversao do body para JSON
                        $tokenKey = $_GET['token'];

                        $response = wp_remote_post( $api_url ,
                                array(
                                    'headers' => array( 
                                        'x-api-eol-key' => getenv('SMEINTEGRACAO2_API_TOKEN'), // Chave da API
                                    ),
                                    'body' => array("Token" => "$tokenKey","Senha" => "$senha"),
                                ));

                        if($response['response']['code'] == 200){
                            
                            echo "<script>                    
                                window.location.replace('" . get_home_url() . "/?login=new');
                            </script>";

                        } elseif($response['body'] == 'A nova senha não pode ser uma das ultimas 5 anteriores'){
                            echo 
                            "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Atenção',
                                    text: 'A nova senha não pode ser uma das últimas 5 anteriores.',
                                });
                            </script>";
                        } else {
                            echo 
                            "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Senha não alterada',
                                    text: 'A sua senha não foi alterada, tente novamente. Caso o problema persista solicite o reset da senha da Intranet via Whatsapp (+55 61 3247-3192).',
                                });
                            </script>";
                        }                        
                        
                    } else {
                        echo 
                        "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Senhas inválida',
                                text: 'Sua nova senha deve conter letras Maiúsculas, Minúsculas, números e símbolos. Por favor digite outra senha.',
                            });
                        </script>";
                    }
                }
                
            }
        }

    }