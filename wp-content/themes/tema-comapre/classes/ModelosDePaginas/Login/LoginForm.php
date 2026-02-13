<?php

namespace Classes\ModelosDePaginas\Login;

class LoginForm
{

	public function __construct()
	{
		$this->montaHtmlForm();
	}

	public function montaHtmlForm(){
		
		// Se ja estiver conectado
		if (is_user_logged_in() && !is_admin()):
			echo "<h2>Você já está conectado!</h2>";
		else:
		// Inclui o formulario de login
		?>
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

				<h2>Bem-vindo ao Gestão Predial</h2>
				
				<?php
					// Mensagem de erro exibida na tela
					$page_showing = basename($_SERVER['REQUEST_URI']);

					if (strpos($page_showing, 'failed') !== false) {
						echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  							<strong>ERRO:</strong> Usuário e/ou senha inválidos.
  							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    						<span aria-hidden="true">&times;</span>
  							</button>
						</div>';
					} elseif (strpos($page_showing, 'blank') !== false ) {						
						echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
							<strong>ERRO:</strong> Usuário e/ou senha estão vazios.
  							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    						<span aria-hidden="true">&times;</span>
  							</button>
						</div>';
					}
				
					$args = array(
						'redirect'          => home_url(), // Após login redireciona para a home
						'id_username'       => 'user', // ID no input de usuário
						'id_password'       => 'pass', // ID no input da senha
						'label_username'    => __( 'Usuário' ),
						'remember'          => false,
						'placeholder_username' => 'Insira seu nome de usuário', // Placeholder para o campo de usuário
						'placeholder_password' => 'Insira sua senha', // Placeholder para o campo de senha
					);
					
					wp_login_form( $args ); // Inclui o formulario de login
					
				?>

			</div>
		<?php
			endif;		
	}

}