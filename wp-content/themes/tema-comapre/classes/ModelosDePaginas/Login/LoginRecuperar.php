<?php

namespace Classes\ModelosDePaginas\Login;


use Classes\Lib\Util;

class LoginRecuperar extends Util
{
	
	public function __construct()
	{

		$this->montaHtmlLogin();
		//contabiliza visualiza��es de noticias
		//setPostViews(get_the_ID()); /*echo getPostViews(get_the_ID());*/

	}

	
	public function montaHtmlLogin(){
		$imagem = get_field('imagem');
		?>

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

							<h2>Recupere sua senha</h2>
							
							<p>Caso tenha cadastrado um endereço de e-mail, informe seu e-mail, CPF ou RF. Você receberá um e-mail com as orientações para redefinição da sua senha.</p>

							<p>Se você não é cadastrado ou não tem mais acesso ao endereço de e-mail cadastrado, procure a Coordenadoria de Contratos de Obras e Manutenção Predial - COMAPRE.</p>
							
							<form id="lost-pass" action="<?= get_the_permalink(); ?>" method="post">
								<p class="login-username">
									<label for="user">Usuário</label>
									<input type="text" name="log" id="user-field" class="input" value="" size="20" placeholder="Insira seu e-mail, CPF ou RF">
									<div class="buttons-form text-right">
										<a href="<?= get_home_url(); ?>" class="btn btn-outline-primary" id="cancel">Cancelar</a>
										<input type="submit" value="Continuar" id="continue">
									</div>
									
								</p>
							</form>
						</div>

					</div>
				</div>
			</div>			
		</div>
		<?php

		function filterEmail($email) {
			$emailSplit = explode('@', $email);
			$email = $emailSplit[0];
			$len = strlen($email);
			for($i = 3; $i < $len; $i++) {
				$email[$i] = '*';
			}
			return $email . '@' . $emailSplit[1];
		}

		echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

		if($_POST['log'] && $_POST['log'] != ''){
			
			$usuario = $rf;
			$api_url = getenv('SMEINTEGRACAO2_API_URL') . '/api/v1/autenticacao/RecuperarSenha/usuario?sistema=4';
    		$api_token = getenv('SMEINTEGRACAO2_API_TOKEN');

			$response = wp_remote_post( $api_url, array(
				'method'      => 'POST',                    
				'headers' => array( 
					'x-api-eol-key' => $api_token,
					'Content-Type' => 'application/json-patch+json'
				),
				'body' => '"' . $_POST['log'] . '"',
				)
			);

			if ( is_wp_error( $response ) ) {
				//$error_message = $response->get_error_message();
				//echo "Something went wrong: $error_message";
			} else {
				$response = $response;
			}
			
			if($response['response']['code'] == 200){		

				$userEmail = filterEmail($response['body']);
				echo "<script>Swal.fire({
					title: 'E-mail de recuperação de senha enviado com sucesso.',
  					icon: 'success',
					html:
					'Seu link de troca de senha dos sistemas da SME (Intranet, SGP e outros) foi enviado para " . $userEmail . ", verifique sua caixa de entrada. Se você não reconhece ou não tem acesso a esse e-mail, solicite o reset da senha da Gestão Predial via Whatsapp (+55 61 3247-3192).',
				}).then((result) => {
					if (result.isConfirmed) {
					  window.location.href = '" . get_home_url() . "';
					}
				});</script>";

				
				
			} elseif($response['response']['code'] == 601){
				
				$envioWP = enviar_link_recuperacao_senha_personalizado($_POST['log']);
				
				if($response['body'] == '"Usuário ou RF não encontrado"' && !$envioWP){
					echo "<script>Swal.fire('RF ou Usuário não encontrado!', 'Verifique os dados digitados e tente novamente!', 'info');</script>";
				} elseif($envioWP){

					$userEmail = filterEmail($envioWP);
					echo "<script>Swal.fire({
						title: 'E-mail de recuperação de senha enviado com sucesso.',
						icon: 'success',
						html:
						'E-mail de recuperação de senha enviado para " . $userEmail . ", verifique sua caixa de entrada. Se você não reconhece ou não tem acesso a esse e-mail, procure a Coordenadoria de Contratos de Obras e Manutenção Predial - COMAPRE.',
					}).then((result) => {
						if (result.isConfirmed) {
						  window.location.href = '" . get_home_url() . "';
						}
					});</script>";

				} else {
					echo "<script>Swal.fire('Email não encontrado!', 'Você não tem um e-mail cadastrado para recuperar sua senha. Solicite o reset da senha da Gestão Predial via Whatsapp (+55 61 3247-3192).', 'warning');	</script>";
				}
				
			} else {
				echo "<script>Swal.fire('Ocorreu um erro!', 'Por favor tente novamente. Caso o problema persista, procure a Coordenadoria de Contratos de Obras e Manutenção Predial - COMAPRE.', 'error');	</script>";
			}
			?>
			
			<?php
		} elseif($_POST['log'] && $_POST['log'] == '') {
			echo "<script>Swal.fire('Campo vazio!', 'Insira o seu usuário ou RF', 'error');	</script>";
		}
	}
}