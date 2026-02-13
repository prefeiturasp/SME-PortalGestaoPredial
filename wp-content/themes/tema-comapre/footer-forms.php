</section>
<!--main-->

<?php
	/*
	$user = get_user_by('login', 'user_colab');
	$reset_key = get_password_reset_key($user);
	
	$verificador = check_password_reset_key($reset_key, '');
	// Exibe o resultado na tela
	echo "Resultado da verificação: ";
	if (is_wp_error($verificador)) {
		// Se houver um erro, exibe a mensagem de erro
		echo "Erro: " . $verificador->get_error_message();
	} else {
		// Se não houver erro, exibe o ID do usuário
		echo "<pre>";
		print_r($verificador);
		echo "</pre>";
	}
	*/
?>
		
<?php wp_footer() ?>
<script src="//api.handtalk.me/plugin/latest/handtalk.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var ht = new HT({
        token: "aa1f4871439ba18dabef482aae5fd934"
    });

	document.onkeyup = PresTab;
 
	function PresTab(e)	{
		var keycode = (window.event) ? event.keyCode : e.keyCode;
		

		if (keycode == 9){
			jQuery('.cabecalho-acessibilidade').show();	
			jQuery(" a[accesskey='1']").focus();
			document.onkeyup = null;
		}
	}

	jQuery('.container-a-icones-home').click(function(){
		jQuery('.container-a-icones-home').removeClass('active');
		jQuery(this).addClass('active');
	});

	jQuery( function ( $ ) {
		


		// Properly update the ARIA states on focus (keyboard) and mouse over events
		$( '[role="menubar"]' ).on( 'focus.aria', '[aria-haspopup="true"]', function ( ev ) {
			$( ev.currentTarget ).attr( 'aria-expanded', true );
			$(this).parent().attr( 'aria-expanded', true );
			$(this).parent().attr( 'aria-haspopup', true );
		} );

		// Properly update the ARIA states on blur (keyboard) and mouse out events
		$( '[role="menubar"]' ).on( 'blur.aria', '[aria-haspopup="true"]', function ( ev ) {
			$( ev.currentTarget ).attr( 'aria-expanded', false );
			$(this).parent().attr( 'aria-expanded', false );
			$(this).parent().attr( 'aria-haspopup', false );

			//$(this).click();
		} );

		$('#user').attr('placeholder', 'Informar RF/CPF');
        $('#pass').attr('placeholder', 'Informar senha');
		// Inclui botao hide/show no campo de nova senha
		$(".login-password").append('<i class="fa fa-eye-slash" id="senha-login" style="margin-left: -30px; cursor: pointer;"></i>');

		// Ao clicar no botão "Mostrar Senha"
        $('#senha-login').click(function() {
            // Obtém o tipo de input da senha
            var type = $('#pass').attr('type');
            // Alterna entre 'password' e 'text'
            $('#pass').attr('type', type === 'password' ? 'text' : 'password');
            // Muda o texto do botão entre "Mostrar Senha" e "Ocultar Senha"
            $(this).toggleClass('fa-eye fa-eye-slash');
        });
		

		$("#newPassForm").submit(function(e){
			//$("#newPassForm").preventDefault();
			//e.preventDefault();


			var nova1 = $("#senha-nova").val();
			var nova2 = $("#senha-repita").val();
			var ciente = $('#ciencia-senha:checked').length;
			
			if ($('#ciencia-senha').length > 0) { // Verifica se o checkbox existe
				if (!$('#ciencia-senha').is(':checked')) { // Verifica se o checkbox está marcado
					Swal.fire({
						icon: 'error',
						title: 'Atenção',
						text: 'Você precisa confirmar o termo de ciência para troca da senha.',
					});
					e.preventDefault();
				}
			} 
			
			if(!nova1 || !nova2){
				Swal.fire({
					icon: 'error',
					title: 'Senhas obrigatórias',
					text: 'Preencha todos os campos de senha.',
				});
				e.preventDefault();
			} else if(nova1 == nova2){				
				//validar_usuario(rf, atual, nova1, nova2);
				//alert('tudo certo');
				if (nova1.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,12}$/)) {
					e.submit();
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Senhas inválida',
						text: 'Sua nova senha deve conter letras maiúsculas, minúsculas, números e símbolos. Por favor digite outra senha.',
					});
					e.preventDefault();
				}				
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Senhas diferentes',
					text: 'As novas senhas não conferem, por gentileza revise e tente novamente.',
				});
				e.preventDefault();
			}
			
		});
		
		//console.log('To aqui');

		// Inclui botao hide/show no campo de nova senha
		$(".senha-nova").append('<i class="fa fa-eye-slash" id="senha-nova-show"></i>');

		const senhaNovaShow = document.querySelector('#senha-nova-show');
		const senhaNova = document.querySelector('#senha-nova'); 
		
		senhaNovaShow.addEventListener('click', function (e) {
			// toggle the type attribute
			const type = senhaNova.getAttribute('type') === 'password' ? 'text' : 'password';
			senhaNova.setAttribute('type', type);
			// toggle the eye slash icon
			this.classList.toggle('fa-eye-slash');
			this.classList.toggle('fa-eye');
		});

		// Inclui botao hide/show no campo de repita senha
		$(".senha-repita").append('<i class="fa fa-eye-slash" id="senha-repita-show"></i>');

		const senhaRepitaShow = document.querySelector('#senha-repita-show');
		const senhaRepita = document.querySelector('#senha-repita'); 
		
		senhaRepitaShow.addEventListener('click', function (e) {
			// toggle the type attribute
			const type = senhaRepita.getAttribute('type') === 'password' ? 'text' : 'password';
			senhaRepita.setAttribute('type', type);
			// toggle the eye slash icon
			this.classList.toggle('fa-eye-slash');
			this.classList.toggle('fa-eye');
		});
		

	} );
</script>
<?php if( isset($_GET['login']) && $_GET['login'] == 'new' ): ?>
	<script>
		Swal.fire({
			icon: 'success',
			title: 'Dados atualizados',
			text: 'Seus dados foram atualizados com sucesso!',
		});
	</script>
<?php endif; ?>
<?php if( isset($_GET['login']) && $_GET['login'] == 'user' ): ?>
	<script>
		Swal.fire({
			icon: 'warning',
			title: 'Usuário não permitido',
			text: 'Este usuário não tem permissão para acessar este site. Caso tenha dúvidas procure a Coordenadoria de Contratos de Obras e Manutenção Predial - COMAPRE.',
		});
	</script>
<?php endif; ?>
<?php if( isset($_GET['pass']) && $_GET['pass'] == 'new' ): ?>
	<script>
		Swal.fire({
			icon: 'error',
			title: 'Este link expirou',
			text: 'Solicite um novo link de recuperação de senha.',
		});
	</script>
<?php endif; ?>
</body>
</html>