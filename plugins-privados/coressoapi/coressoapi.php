<?php
/**
* Plugin Name: CoreSSO Integração API
* Plugin URI: https://amcom.com.br/
* Description: Integração do Login do WordPress com o CoreSSO.
* Version: 1.3
* Author: AMcom
* Author URI: https://amcom.com.br/
**/

// Substituir a autenticacao do WordPress
add_filter( 'authenticate', 'demo_auth', 10, 3 );

function demo_auth( $user, $username, $password ){
    // Verifica se o usuario e senha foram preenchidos
    if($username == '' || $password == '') return;

    // URL da API
    $api_url = getenv('SMEINTEGRACAO2_API_URL') . '/api/v1/autenticacao';
    $api_token = getenv('SMEINTEGRACAO2_API_TOKEN');

    // Conversao do body para JSON
    $body = wp_json_encode( array(
        "login" => $username,
        "senha" => $password,
    ) );

    $response = wp_remote_post( $api_url ,
            array(
                'headers' => array( 
                    'x-api-eol-key' => $api_token, // Chave da API
                    'Content-Type'=> 'application/json-patch+json'
                ),
                'body' => $body, // Body da requisicao
            ));

    $user = json_decode($response['body']);



    if( $response['response']['code']  != 200 ) {
        // Caso nao encontre o usuario retorna o erro na pagina
        $user = new WP_Error( 'denied', __("ERRO: Usuário/senha incorretos") );

    } else if( $response['response']['code'] == 200 ) {
        //echo $user->codigoRf;
        
        // Verifica se tem o codigo RF e busca os dados do usuario
        if($user->codigoRf){
            
            $rf = $user->codigoRf;
          
            $api_url = getenv('SMEINTEGRACAO2_API_URL') . '/api/AutenticacaoSgp/' . $user->codigoRf . '/dados';
            $api_token = getenv('SMEINTEGRACAO2_API_TOKEN');
            $response = wp_remote_get( $api_url ,
                array( 
                    'headers' => array( 
                        'x-api-eol-key' => $api_token,							
                    )
                )
            );

            $user = json_decode($response['body']); 
            
                       
        }
       
        if($user->email){
            $email = $user->email;
        } elseif(is_array($user)) {
            $email = $user[0]->email;
        } else {
            $email = $rf . "@sme.prefeitura.sp.gov.br";
        }
        
        // Verifica se o usuario ja esta cadastrado no WordPress
        $userobj = new WP_User();
        $user_wp = $userobj->get_data_by( 'email', $email ); // Does not return a WP_User object :(
        if($user_wp->ID != 0){
            $user_wp = new WP_User($user_wp->ID); // Attempt to load up the user with that ID
        }

        // Verifica se o usuario esta com um email temporario cadastrado
        // email temporario corresponde a 'rf + @sme.prefeitura.sp.gov.br'
        if( $user_wp->ID == 0) {
            $email = $rf . "@sme.prefeitura.sp.gov.br";
            $userobj = new WP_User();
            $user_wp = $userobj->get_data_by( 'email', $email ); // Does not return a WP_User object :(
            $user_wp = new WP_User($user_wp->ID); // Attempt to load up the user with that ID

            $args = array(
                'ID'         => $user_wp->ID,
                'user_email' => esc_attr( $user->email )
            );
            wp_update_user( $args );
        }

        if($rf){

            $api_url = getenv('SMEINTEGRACAO2_API_URL') . '/api/AutenticacaoCOMAPRE/' . $rf . '/dados';
            $api_token = getenv('SMEINTEGRACAO2_API_TOKEN');
            $response = wp_remote_get( $api_url ,
                array( 
                    'headers' => array( 
                        'x-api-eol-key' => $api_token,							
                    )
                )
            );

            $userInfo = json_decode($response['body']);


            $grupo = $userInfo->grupos[0];
            $dre = $userInfo->dre;
            $nomeUe = $userInfo->nomeUe;
            $enderecoUe = $userInfo->enderecoUe;
            $telefoneUe = $userInfo->telefoneUe;
            $email = $userInfo->email;
            $nome = $userInfo->nome;
            $cpf = $userInfo->cpf;
            $login = $userInfo->login;

        }

        
        // Se nao estiver cadastrado faz a criacao do usuario
        if( $user_wp->ID == 0 ) {
             
            // Caso nao queira adicionar o usuario no WordPress
            // descomente a linha abaixo
            //$user_wp = new WP_Error( 'denied', __("ERROR: Not a valid user for this system") );

            // Recebe o nome completo do usuario            
            $name = $nome;
            
            if($email){
                $email = $email;
            } elseif($user->email){
                $email = $user->email;
            } elseif(is_array($user)) {
                $email = $user[0]->email;
            } else {
                $email = $rf . "@sme.prefeitura.sp.gov.br";
            }

            if($user->nome){
                $nome = $user->nome;
            } elseif(is_array($user)) {
                $nome = $user[0]->nome;
            }

           
            $cpf = $user->cpf;

            // Divide o nome em Nome e Sobrenome
            $parts = explode(" ", $name);
            if(count($parts) > 1) {
                $firstname = array_shift($parts);
                $lastname = implode(" ", $parts);
            } else {
                $firstname = $name;
                $lastname = " ";
            }

            if(!$login){
                $login = $email;
            }

            // Perfil de usuario
            $perfis = array(
                '1c2bf318-b797-4edc-91c1-4ce991e03d6a' => 'educador',
                'baebb618-9aa8-42e9-b1c4-db0342cae53b' => 'administrator',
                '79af2e9b-72dc-4194-80ec-5c108c495d9f' => 'engcomapre',
                'd1ca0e66-bd51-47ef-939f-697b1c7eaf34' => 'administrativo',
                '40c5905b-324e-4b44-b285-05b7f91684dc' => 'engdre',
            );

            $funcao = $perfis[$grupo];

            if(!$funcao){
                wp_redirect(get_home_url() . '/?login=user');
                exit;
            }            

            $userdata = array( 'user_email' =>$email,
                                'user_login' =>$login,
                                'first_name' => $firstname,
                                'last_name' => $lastname,
                                'role' => $funcao,                              
                            );
            $new_user_id = wp_insert_user( $userdata ); // Um novo usuario sera criado
            update_user_meta($new_user_id, "rf", $username);
            
            if(strlen($username) != 6){
                update_user_meta($new_user_id, "cpf_user", $cpf);

                $grupo = $userInfo->grupos[0];
                $dre = convert_dre($dre);
                $nomeUe = $userInfo->nomeUe;
                $enderecoUe = $userInfo->enderecoUe;
                $telefoneUe = $userInfo->telefoneUe;
                $email = $userInfo->email;
                $nome = $userInfo->nome;                
                
                if($dre)
                    update_user_meta($new_user_id, "dre", $dre);

                if($nomeUe)
                    update_user_meta($new_user_id, "nome_ue", $nomeUe);

                if($enderecoUe)
                    update_user_meta($new_user_id, "endereco_ue", $enderecoUe);

                if($telefoneUe)
                    update_user_meta($new_user_id, "local", $telefoneUe);

                if($telefoneUe)
                    update_user_meta($new_user_id, "telefone_ue", $telefoneUe);
            }

            
            
            // Carregar as novas informações do usuário
            $user_wp = new WP_User ($new_user_id);
            
        } 

    }

    if(!$user_wp){

        // Verifique se o campo personalizado 'cpf_user' está definido como o nome de usuário
        $args = array(
            'meta_key'     => 'cpf_user',
            'meta_value'   => $username,
            'meta_compare' => '='
        );

        $user_query = new WP_User_Query($args);
        $users = $user_query->get_results();

        // Se houver um usuário com o campo personalizado correspondente, tente autenticá-lo
        if (!empty($users)) {
            $user = $users[0];
            // Tenta autenticar o usuário
            $user_wp = wp_authenticate_username_password(null, $user->user_login, $password);
        } else {
            $user_wp = wp_authenticate_username_password(null, $username, $password);
        }
        
    }

    // Comente esta linha se você deseja recorrer a autenticacao do WordPress
    // Util para momentos em que o servico externo esta offline    
    remove_action( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
    remove_action( 'authenticate', 'wp_authenticate_email_password', 20, 3 );

    return $user_wp;
}

#########################################################################################
// Criacao do shortcode de login
//function intranet_add_login_shortcode() {
	//add_shortcode( 'intranet-login-form', 'intranet_login_form_shortcode' );
//}

// funcao callbacl do shortcode
function intranet_login_form_shortcode() {
	
	// Se ja estiver conectado
    if (is_user_logged_in() && !is_admin()):
        echo "<h2>Você já está conectado!</h2>";
    else:
    // Inclui o formulario de login
    ?>
    	<div class='wp_login_form'>
			<?php
                // Mensagem de erro exibida na tela
				$page_showing = basename($_SERVER['REQUEST_URI']);

				if (strpos($page_showing, 'failed') !== false) {
					echo '<p class="error-msg"><strong>ERRO:</strong> Usuário e/ou senha inválidos.</p>';
				} elseif (strpos($page_showing, 'blank') !== false ) {
					echo '<p class="error-msg"><strong>ERRO:</strong> Usuário e/ou senha estão vazios.</p>';
				}
			
                $args = array(
                'redirect' => home_url(), // Apos login redireciona para a home
                'id_username' => 'user', // ID no input de usuario
                'id_password' => 'pass', // ID no input da senha
                );
				
                wp_login_form( $args ); // Inclui o formulario de login
                
            ?>

		</div>
<?php
    endif;
}

// Carrega a funcao do shortcode
//add_action( 'init', 'intranet_add_login_shortcode' );


#####################################################################################

// Direcionar o usuario da pagina de login do WordPress para uma pagina de login customizada
function goto_login_page() {
	global $page_id;
	$login_page = home_url();
	$page = basename($_SERVER['REQUEST_URI']);

	if( $page == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
		wp_redirect($login_page);
		exit;
	}
}
// Funcao desabilitada no momento, para habilitar descomente a linha abaixo
//add_action('init','goto_login_page');

// Se nao autenticar o usuario redireciona para o login novamente
// icluindo o parametro GET na URL
function login_failed() {
	global $page_id;
	$login_page = home_url();
	wp_redirect( $login_page . '?login=failed' );
	exit;
}
// Verifica se nao esta na pagina de login do WordPress
if( $pagenow == 'wp-login.php' && isset($_POST['login_page']) ){
	add_action( 'wp_login_failed', 'login_failed' );
}

// Se usuario/senha estiver vazio redireciona para o login novamente
// icluindo o parametro GET na URL
function blank_username_password( $user, $username, $password ) {
	global $page_id;
	$login_page = home_url();
	if( $username == "" || $password == "" ) {
		wp_redirect( $login_page . "?login=blank" );
		exit;
	}
}

// Verifica se nao esta na pagina de login do WordPress
if( $pagenow == 'wp-login.php' && isset($_POST['login_page']) ){
	add_filter( 'authenticate', 'blank_username_password', 1, 3);
}

// Se for acionado a funcao de Logout (sair) redireciona o usuario para a pagina de login
function logout_page() {
	global $page_id;
	$login_page = home_url();
	wp_redirect( $login_page . "?login=false" );
	exit;
}
add_action('wp_logout', 'logout_page');

// Inclui um input oculto no formulario de login personalizado
// Para que seja validado o usuario via API e nao pelo WordPress
add_filter('login_form_middle','my_added_login_field');
function my_added_login_field(){
     //Output your HTML
     $additional_field = '<div class="login-custom-field-wrapper"">
        <input type="hidden" value="1" name="login_page"></label>
     </div>';

     return $additional_field;
}

// Verifica se esta na pagina de Login do WordPress
// para validar o usuario pelo WordPress e NAO pela API
add_action( 'login_init', 'wpse8170_login_init' );
function wpse8170_login_init() {
	global $pagenow;
	if( $pagenow == 'wp-login.php' && !isset($_POST['login_page']) ){
		remove_filter( 'authenticate', 'demo_auth' );
		remove_filter( 'authenticate', 'blank_username_password');
	}    
}
