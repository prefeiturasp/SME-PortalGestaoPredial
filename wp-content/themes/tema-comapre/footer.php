</section>
<!--main-->

<?php
if($_GET['qtd'] && $_GET['pagina']){
	$qtd = $_GET['qtd'];
	$page = $_GET['pagina'];

	$args = array(
		'post_type' => 'unidade', // Tipo de post que você deseja buscar
		'posts_per_page' => $qtd, // -1 para buscar todos os posts
		'paged' => $page
	);

	$posts = new WP_Query($args);

	if ($posts->have_posts()) {
		while ($posts->have_posts()) {
			$posts->the_post();
			
			// Recuperar o valor do campo personalizado
			$tipologia = get_field('tipologia', get_the_ID());
			$titulo = get_the_title();
			

			// Verificar se o título já foi modificado anteriormente
			if ($tipologia !== '' && !str_contains($titulo, $tipologia)) { // Substitua 'Seu Prefixo' pelo prefixo que você usa para os títulos modificados
				// Recuperar o valor do campo personalizado
				$tipologia = get_post_meta(get_the_ID(), 'tipologia', true);

				// Concatenar o valor do campo personalizado com o título original
				$novo_titulo = $tipologia . ' ' . get_the_title();

				// Atualizar o título do post com o novo valor
				wp_update_post(array(
					'ID' => get_the_ID(),
					'post_title' => $novo_titulo,
				));
			}
		}
		wp_reset_postdata(); // Restaurar os dados do post original
	}
}

?>

<footer style="background: #363636;color: #fff;margin-left: -15px;margin-right: -15px;">
	<div class="container pt-3 pb-3" id="irrodape">
		<div class="row">
			<div class="col-sm-3 align-middle d-flex align-items-center footer-logo">
                <a href="https://www.capital.sp.gov.br/"><img src="<?php the_field('logo_prefeitura','conf-rodape'); ?>" alt="<?php bloginfo('name'); ?>"></a>
			</div>
			<div class="col-sm-3 align-middle bd-contact">
				<p class='footer-title'><?php the_field('nome_da_secretaria','conf-rodape'); ?></p>
				<?php the_field('endereco_da_secretaria','conf-rodape'); ?>
			</div>
			<div class="col-sm-3 align-middle">
				<p class='footer-title'>Contatos</p>
				<p><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:<?php the_field('telefone','conf-rodape'); ?>"><?php the_field('telefone','conf-rodape'); ?></a></p>
				
				<?php if(get_field('email','conf-rodape')) :?>
				<p><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:<?php the_field('email','conf-rodape'); ?>"><?php the_field('email','conf-rodape'); ?></a></p>
				<?php endif; ?>

				<?php if(get_field('texto_link','conf-rodape') && get_field('link_adicional','conf-rodape')) :?>
				<p><i class="fa fa-comment" aria-hidden="true"></i> <a href="<?php the_field('link_adicional','conf-rodape'); ?>"><?php the_field('texto_link','conf-rodape'); ?></a></p>
				<?php endif; ?>				
			</div>
			<div class="col-sm-3 align-middle">				
            <p class='footer-title'>Redes sociais</p>
				<?php 
					$facebook = get_field('icone_facebook','conf-rodape');
					$instagram = get_field('icone_instagram','conf-rodape');
					$twitter = get_field('icone_twitter','conf-rodape');
					$youtube = get_field('icone_youtube','conf-rodape');
				?>
				<div class="row redes-footer">

					<?php if($facebook) : ?>
						<div class="col rede-rodape">
							<a href="<?php the_field('url_facebook','conf-rodape'); ?>">
							<img src="<?php echo $facebook; ?>" alt="Facebook"></a>
						</div>
					<?php endif; ?>

					<?php if($instagram) : ?>
						<div class="col rede-rodape">
							<a href="<?php the_field('url_instagram','conf-rodape'); ?>">
							<img src="<?php echo $instagram; ?>" alt="Instagram"></a>
						</div>
					<?php endif; ?>

					<?php if($twitter) : ?>
						<div class="col rede-rodape">
							<a href="<?php the_field('url_twitter','conf-rodape'); ?>">
							<img src="<?php echo $twitter; ?>" alt="Twitter"></a>
						</div>
					<?php endif; ?>

					<?php if($youtube) : ?>
						<div class="col rede-rodape">
							<a href="<?php the_field('url_youtube','conf-rodape'); ?>">
							<img src="<?php echo $youtube; ?>" alt="YouTube"></a>
						</div>
					<?php endif; ?>

					
				</div>
			</div>
		</div>
	</div>
</footer>
<div class="subfooter rodape-api-col" style="margin-left: -15px;margin-right: -15px;">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Prefeitura Municipal de São Paulo - Viaduto do Chá, 15 - Centro - CEP: 01002-020</p>
			</div>
		</div>
	</div>
</div>
<div id="loading-overlay"></div>
<?php wp_footer() ?>

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
		var $s = jQuery.noConflict();

        jQuery(document).ready(function($) {

			$('#search-form').on('keypress', function(event) {
				// Verifica se a tecla pressionada é a tecla Enter (código 13)
				if (event.which === 13) {
					// Cancela o envio do formulário
					event.preventDefault();
				}
			});			

			<?php
				$user = wp_get_current_user();
				if ( in_array( 'educador', (array) $user->roles ) || in_array( 'administrativo', (array) $user->roles )) :
			?>
				$('.acf-form-submit').append('<input type="button" name="Anterior" value="Voltar" class="acf-button acf-prev button button-secondary button-large" />');
				$('.acf-form-submit').append('<input type="button" name="second_submit" value="Continuar" class="acf-button acf-next button button-secondary button-large" />');
			
			<?php elseif(in_array( 'administrator', (array) $user->roles )): ?>
				$('.acf-form-submit').append('<input type="button" name="Anterior" value="Voltar" class="acf-button acf-prev button button-secondary button-large" />');
				$('.acf-form-submit').append('<input type="button" name="second_submit" value="Salvar e continuar" class="acf-button acf-save-next button button-secondary button-large" />');
			<?php else: ?>
				$('.acf-form-submit').append('<input type="button" name="Anterior" value="Voltar" class="acf-button acf-prev button button-secondary button-large" />');
				$('.acf-form-submit').append('<input type="button" name="second_submit" value="Salvar e continuar" class="acf-button acf-save-next button button-secondary button-large" />');
				$('.acf-form-submit').append('<input type="button" name="second_submit" value="Continuar" class="acf-button acf-next button button-secondary button-large" />');
			<?php endif; ?>

			// Alter o primeiro botao
			<?php
				$user = wp_get_current_user();
				if ( in_array( 'vistoriador', (array) $user->roles ) || in_array( 'engcomapre', (array) $user->roles ) || in_array( 'engdre', (array) $user->roles ) ) :
			?>

				var currentTab = $('.acf-hl.acf-tab-group li.active a').data('endpoint');
				if(currentTab == 1){
					$('.acf-save-next').hide();
					$('input.acf-form-submit').hide();
					$('.acf-prev').hide();
				} else {
					$('.acf-next').hide();
					$('.acf-prev').show();
				}

			<?php endif; ?>

			<?php
				$user = wp_get_current_user();
				if(in_array( 'administrator', (array) $user->roles )) :
			?>
				var currentTab = $('.acf-hl.acf-tab-group li.active a').data('endpoint');
				if(currentTab == 1){					
					$('.acf-prev').hide();
				} else {
					$('.acf-prev').show();
				}
			<?php endif; ?>

			$('.acf-tab-button').on('click', function() {
				var verifyFistTab = $(this).data('endpoint');
				<?php
					$user = wp_get_current_user();
					if ( in_array( 'vistoriador', (array) $user->roles ) || in_array( 'engcomapre', (array) $user->roles ) || in_array( 'engdre', (array) $user->roles ) ) :
				?>
					if(verifyFistTab == 0){
						$('.acf-save-next').show();
						$('input.acf-form-submit').show();
						$('.acf-next').hide();
						$('.acf-prev').show();
					} else {
						$('.acf-save-next').hide();
						$('input.acf-form-submit').hide();
						$('.acf-next').show();
						$('.acf-prev').hide();
					}
				<?php endif; ?>

				<?php
					$user = wp_get_current_user();
					if(in_array( 'administrator', (array) $user->roles )) :
				?>
					if(verifyFistTab == 0){
						$('.acf-prev').show();
					} else {
						$('.acf-prev').hide();
					}
				<?php endif; ?>
			});
			
			// Adiciona um evento de clique ao segundo botão
			$('.acf-save-next').on('click', function() {

				$('#loading-overlay').show();

				// Serializa os dados do formulário
				var formData = $('#acf-form').serialize();

				//console.log(formData);

				// Envia os dados do formulário via Ajax
				$.ajax({
					type: 'POST',
					url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
					data: {
						action: 'save_acf_form_data', // Nome da ação que será tratada no servidor
						formData: formData // Dados do formulário serializados
					},
					success: function(response) {
						// Manipule a resposta do servidor conforme necessário
						//console.log(response);

						var dados = JSON.parse(response);

						console.log(dados);

						if(dados.status == 'success'){
							if(dados.data){
								$("#modified-post").text(dados.data);
							}
							Swal.fire({
								icon: 'success',
								title: 'Informações salvas com sucesso!',
								text: 'As informações da unidade foram atualizadas com sucesso!',
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Erro ao salvar os dados!',
								text: 'Tente salvar novamente, caso o problema persista entre em contato com a COMAPRE!',
							});
						}
						$('#loading-overlay').hide();
						
					},
					error: function(xhr, status, error) {
						// Manipule os erros de requisição, se necessário
						//console.error('Erro ao salvar os dados: ' + error);
						Swal.fire({
							icon: 'error',
							title: 'Erro ao salvar os dados!',
							text: 'Tente salvar novamente, caso o problema persista entre em contato com a COMAPRE!',
						});

						$('#loading-overlay').hide();
					}
				});
				
				// Obtém a aba atual
				var currentTab = $('.acf-hl.acf-tab-group li.active');
				//$('.acf-prev').show();

				// Verifica se há uma aba atual
				if (currentTab.length > 0) {
					
					// Obtém a próxima aba
					var nextTab = currentTab.next('li');

					// Verifica se há uma próxima aba
					if (nextTab.length > 0) {
						// Obtém o link de guia dentro da próxima aba
						var nextTabLink = nextTab.find('a');

						// Força o clique no link de guia da próxima aba
						nextTabLink.trigger('click');
					} else {
						// Se não houver próxima aba, estamos na última aba
						//$('.acf-save-next').hide();
					}
				}
			});

			// Adiciona um evento de clique ao segundo botão
			$('.acf-next').on('click', function() {

				// Obtém a aba atual
				var currentTab = $('.acf-hl.acf-tab-group li.active');
				//$('.acf-prev').show();				

				// Verifica se há uma aba atual
				if (currentTab.length > 0) {
					
					// Obtém a próxima aba
					var nextTab = currentTab.next('li');

					// Verifica se há uma próxima aba
					if (nextTab.length > 0) {
						// Obtém o link de guia dentro da próxima aba
						var nextTabLink = nextTab.find('a');
						var verifyFistTab = nextTabLink.data('endpoint');

						<?php
							$user = wp_get_current_user();
							if ( in_array( 'vistoriador', (array) $user->roles ) || in_array( 'engcomapre', (array) $user->roles ) || in_array( 'engdre', (array) $user->roles ) ) :
						?>
							if(verifyFistTab == 0){
								$('.acf-save-next').show();
								$('input.acf-form-submit').show();
								$('.acf-next').hide();
								$('.acf-prev').show();
							} else {
								$('.acf-save-next').hide();
								$('input.acf-form-submit').hide();
								$('.acf-next').show();
								$('.acf-prev').hide();
							}
						<?php endif; ?>
						// Força o clique no link de guia da próxima aba
						nextTabLink.trigger('click');
					} else {
						// Se não houver próxima aba, estamos na última aba
						//$('.acf-save-next').hide();
					}
				}
			});

			// Adiciona um evento de clique ao segundo botão
			$('.acf-prev').on('click', function() {
				// Obtém a aba atual
				var currentTab = $('.acf-hl.acf-tab-group li.active');
				//$('.acf-save-next').show();

				// Verifica se há uma aba atual
				if (currentTab.length > 0) {
					// Obtém a próxima aba
					var previousTab = currentTab.prev('li');

					// Verifica se há uma próxima aba
					if (previousTab.length > 0) {
						// Obtém o link de guia dentro da próxima aba
						var previousTabLink = previousTab.find('a');

						var verifyFistTab = previousTabLink.data('endpoint');

						<?php
							$user = wp_get_current_user();
							if ( in_array( 'vistoriador', (array) $user->roles ) || in_array( 'engcomapre', (array) $user->roles ) || in_array( 'engdre', (array) $user->roles ) ) :
						?>
							if(verifyFistTab == 0){
								$('.acf-save-next').show();
								$('input.acf-form-submit').show();
								$('.acf-next').hide();
								$('.acf-prev').show();
							} else {
								$('.acf-save-next').hide();
								$('input.acf-form-submit').hide();
								$('.acf-next').show();
								$('.acf-prev').hide();
							}
						<?php endif; ?>

						// Força o clique no link de guia da próxima aba
						previousTabLink.trigger('click');
					}

					if (previousTab.length === 0) {
						// Se não houver aba anterior, estamos na primeira aba
						//$('.acf-prev').hide();
					}
				}
			});

			$('#search-input').on('input', function() {
				var searchTerm = $(this).val().trim(); // Obter o termo de busca inserido pelo usuário e remover espaços em branco
				var formData = $('#search-form').serialize();

				$.ajax({
					type: 'GET',
					url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
					data: formData + '&action=search_posts',
					success: function(response) {
						$('#search-results').html(response);							
					}
				});

				event.preventDefault();			
			});

			
			// Abrir o primeiro acordeão e fechar os outros ao trocar de aba
			$('.acf-tab-button').on('click', function() {
				var conteudo = $(this).data('key');
				conteudo = conteudo.replace(/_/g, "-");
				conteudo = 'acf-' + conteudo;
				$("." + conteudo).next('.acf-accordion').addClass('-open');
				$("." + conteudo).next('.acf-accordion-content').show();
				//console.log(conteudo);
			});

			// Ao carregar a página, insere o valor do campo "nome_oficial" na label
			var nomeOficialValue = $('#acf-field_65afe1222aa07').val();
    		$('.acf-field-661431a39e84c .acf-label label').text(nomeOficialValue);
			
			// Observa mudanças no campo "nome_oficial"
			$('#acf-field_65afe1222aa07').on('change', function() {
				// Pega o valor do campo "nome_oficial"
				var nomeOficialValue = $(this).val();
				
				// Define o valor do campo de mensagem como o valor do campo "nome_oficial"
				var labelElement = $('.acf-field-661431a39e84c .acf-label label');
				console.log(labelElement);
				labelElement.text(nomeOficialValue);
			});
			<?php
				$user = wp_get_current_user();
				if ( in_array( 'educador', (array) $user->roles ) || in_array( 'administrativo', (array) $user->roles )) :
			?>
				
				acf.add_filter('validation_complete', function( json, $form ){

					// check errors
					if( json.errors ) {
						// do something
						Swal.fire({
							icon: 'error',
							title: 'Atenção',
							text: 'Para continuar, preencha os campos obrigatórios',
						});
					}

					// return
					return json;        
				});
				
			<?php endif; ?>

		});		

        // Carrocel
        $s('.carousel').carousel({
            interval: 8000
        });

        $s( ".tab-mobile" ).click(function() {
            $s('#filtroBusca').modal('toggle');
        });
	</script>

	<!-- Flatpickr CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<!-- Flatpickr JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
	<script>
		flatpickr('#intervalo_datas', {
			mode: 'range', // Define o modo de seleção como intervalo
			dateFormat: 'd/m/Y', // Formato da data
			altInput: false, // Usa um campo de entrada alternativo
			altFormat: 'd/m/Y', // Formato do campo de entrada alternativo
			locale: 'pt',
			clearButton: true, // Habilita o botão de limpar
			placeholder: 'Selecione um intervalo de datas', // Define o placeholder
			readOnly: false,
		});
		$s('#intervalo_datas').removeAttr('readonly');
	</script>

    

	<script src="//api.handtalk.me/plugin/latest/handtalk.min.js"></script>
	<script>
		var ht = new HT({
			token: "aa1f4871439ba18dabef482aae5fd934"
		});
	</script>

    <script type="text/javascript">
        var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })

        
    </script>

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

</body>
</html>