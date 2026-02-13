<?php
/**
 * Defines the custom field type class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PREFIX_acf_field_ambientes class.
 */
class PREFIX_acf_field_ambientes extends \acf_field {
	/**
	 * Controls field type visibilty in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private $env;

	/**
	 * Constructor.
	 */
	public function __construct() {
		/**
		 * Field type reference used in PHP and JS code.
		 *
		 * No spaces. Underscores allowed.
		 */
		$this->name = 'ambientes';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Ambientes', 'tema-comapre' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME

		/**
		 * Field type Description.
		 *
		 * For field descriptions. May contain spaces.
		 */
		$this->description = __( 'FIELD_DESCRIPTION', 'tema-comapre' );

		/**
		 * Field type Doc URL.
		 *
		 * For linking to a documentation page. Displayed in the field picker modal.
		 */
		$this->doc_url = 'FIELD_DOC_URL';

		/**
		 * Field type Tutorial URL.
		 *
		 * For linking to a tutorial resource. Displayed in the field picker modal.
		 */
		$this->tutorial_url = 'FIELD_TUTORIAL_URL';

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = array(
			'font_size'	=> 14,
		);

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("ambientes", "error");
		 * ```
		 */
		$this->l10n = array(
			'error'	=> __( 'Error! Please enter a higher value', 'tema-comapre' ),
		);

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ), // URL to the acf-FIELD-NAME directory.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

		/**
		 * Field type preview image.
		 *
		 * A preview image for the field type in the picker modal.
		 */
		$this->preview_image = $this->env['url'] . '/assets/images/field-preview-custom.png';

		parent::__construct();
	}

	/**
	 * Settings to display when users configure a field of this type.
	 *
	 * These settings appear on the ACF “Edit Field Group” admin page when
	 * setting up the field.
	 *
	 * @param array $field
	 * @return void
	 */
	public function render_field_settings( $field ) {
		/*
		 * Repeat for each setting you wish to display for this field type.
		 */
		acf_render_field_setting(
			$field,
			array(
				'label'			=> __( 'Font Size','tema-comapre' ),
				'instructions'	=> __( 'Customise the input font size','tema-comapre' ),
				'type'			=> 'number',
				'name'			=> 'font_size',
				'append'		=> 'px',
			)
		);

		// To render field settings on other tabs in ACF 6.0+:
		// https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/#moving-field-setting
	}

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */



	public function render_field( $field ) {
		// Debug output to show what field data is available.
		//echo 'ID: ' . get_the_ID();
		//echo '<pre>';
		//print_r( $field );
		//echo '</pre>';

	
		$args = array(
			'post_type' => 'predio',
			'meta_query' => array(
				array(
					'key' => 'unidade_rel', // Chave do campo personalizado
					'value' => get_the_ID(), // Valor a ser comparado com o ID da página atual
					'compare' => '=', // Operador de comparação (igual)
					'type' => 'NUMERIC' // Tipo de dado (numérico)
				)
			)
		);

		// The Query.
		$the_query = new \WP_Query( $args );
		$unidades = array();
		$ambientes = array();
		$repeater_field = array();
		// The Loop.
		if ( $the_query->have_posts() ) {
			
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$nome = get_field('predio_nome_predio');
				$unidades[get_the_ID()] = $nome;
				$ambientes[] = get_field('repetidor_dinamico');
				$repeater_field[] = get_field_object('repetidor_dinamico');
			}
						
		} 
		// Restore original Post Data.
		wp_reset_postdata();

		// Display an input field that uses the 'font_size' setting.
		?>

		
		
		<?php 
		/*
		<input
			type="text"
			class="setting-font-size"
			name="<?php echo esc_attr($field['name']) ?>"
			value="<?php echo esc_attr($field['value']) ?>"
			style="font-size:<?php echo esc_attr( $field['font_size'] ) ?>px;"
		/>
		*/ 
		$result = array_reduce($ambientes, 'array_merge', []);

		// Obtém os dados do campo ACF
		$field = get_field_object('field_66699ff91ea29');
		$field2 = get_field_object('field_6663117b794c3');
		// Verifica se o campo existe e possui valores
		$options = isset($field['choices']) ? $field['choices'] : [];
		$tipos = isset($field2['choices']) ? $field2['choices'] : [];
		$i = 1;
		//echo "<pre>";
		//print_r($repeater_field);
		//echo "</pre>";

		?>

		
		<form id="filterForm">
			<div class="row">
				
				<div class="col-12">
					<h2 class="title-form">Ambientes</h2>
				</div>
				
				<div class="col-12 col-md-6 mb-4">
					<label for="nomePredio">Filtrar por nome do prédio</label>
					<select id="nomePredio" class="form-control" name="nomePredio">
						<option value="" selected>Selecione</option>
						<?php
							if($unidades){
								foreach($unidades as $k => $unidade){
									echo "<option value='" . $k . "'>" . $unidade . "</option>";
								}
							}
						?>
					</select>
				</div>
				<div class="col-12 col-md-6 mb-4">
					<label for="categoria">Filtrar por categoria de ambiente</label>
					<select id="categoria" class="form-control" name="categoria">
						<option value="" selected>Selecione</option>
						<?php
						// Itera sobre as opções do campo de seleção ACF e cria os elementos <option>
						foreach ($options as $value => $label) {
							echo "<option value='" . esc_attr($value) . "'>" . esc_html($label) . "</option>";
						}
						?>
					</select>
				</div>

				<div class="col-12 col-md-6 mb-4">
					<label for="tipoAmbiente">Tipo de ambiente</label>
					<select id="tipoAmbiente" class="form-control" name="tipoAmbiente">
						<option value="" selected>Selecione</option>
						<?php
						// Itera sobre as opções do campo de seleção ACF e cria os elementos <option>
						foreach ($tipos as $value => $label) {
							echo "<option value='" . esc_attr($value) . "'>" . esc_html($label) . "</option>";
						}
						?>
					</select>
				</div>

				<div class="col-12 d-flex justify-content-end">
					<button id="clearFilterButton" class="btn btn-clear">Limpar</button>
					<button id="filterButton" class="btn btn-filter">Filtrar</button>					
				</div>

			</div>
			
		</form>
		<br><hr><br>

		<div class="row">
			<div class="col-md-3">

				<div class="card card-total mb-3">
					
					<div class="card-header bg-transparent">Total de ambientes</div>
					
					<div class="card-body">
						<h1 class="card-text num_ambientes"><?= count($result); ?></h1>
					</div>
					
					<div class="card-footer border-0 bg-transparent"><small class="text-muted"><a href="#lista-ambientes">Conferir Lista</a></small></div>
						
				</div>

			</div>
		</div>

		<div class="mt-5 cards-ambientes">
			<?php
				//echo "<pre>";
				//print_r($result);
				//echo "</pre>";

				// Agrupar os itens por categoria e tipo
				$agrupadosPorCategoriaETipo = agruparPorCategoriaETipo($result);

				// Exibir os itens agrupados e contados
				foreach ($agrupadosPorCategoriaETipo as $categoria => $tipos) {
					echo "<div class='title-categorias'>Categoria: " . $categoria . "</div>";
					echo "<div class='row'>";
						foreach ($tipos as $tipo => $total) {
							?>

							<div class="col-md-2">
								<div class="card mb-3">									
									<div class="card-header bg-transparent"><?= $tipo; ?></div>
									
									<div class="card-body">
										<h1 class="card-text"><?= $total; ?></h1>
									</div>
									
									<div class="card-footer border-0 bg-transparent"><small class="text-muted"><a href="#lista-ambientes">Conferir Lista</a></small></div>
										
								</div>
							</div>

							<?php
						}
					echo "</div>";
					echo "<br>";
				}
			?>
		</div>

		<div class="mt-5 lista-ambientes" id="lista-ambientes">
			<?php
				if($repeater_field){
					foreach($repeater_field as $fields){

							usort($fields['value'], function($a, $b) {
								return strcmp($a['tipo_de_ambiente'], $b['tipo_de_ambiente']);
							});

							foreach($fields['value'] as $field){
								$formattedNumber = str_pad($field['num_ambiente'], 2, '0', STR_PAD_LEFT);
						?>
								<div id="accordion">
									<div class="card">
										<div class="card-header" id="heading<?= $i; ?>">
										<h5 class="mb-0">
											<button class="btn btn-link accordion-button" data-toggle="collapse" data-target="#collapse<?= $i; ?>" aria-expanded="false" aria-controls="collapse<?= $i; ?>">
												<?= $field['tipo_de_ambiente'] . ' ' . $formattedNumber; ?>
											</button>
										</h5>
										</div>
		
										<div id="collapse<?= $i; ?>" class="collapse" aria-labelledby="heading<?= $i; ?>" data-parent="#accordion">
										<div class="card-body">

											<div class="row">
												<div class="col-12">
													<p><strong>Itens do ambiente</strong></p>
												</div>
											</div>

											<?php
												
												?>
		
													
														<?php
															$conteudos = array();
															foreach ($fields['sub_fields'][4]['sub_fields'] as $key => $item):
																if($item['type'] == 'accordion'){
																	$conteudos[$key]['inicio'] = '<div class="title">' . $item['label'] .'</div>';
																	$conteudos[$key]['conteudo'] = '';
																}
													
																if($item['type'] == 'group'){                
																	$conteudos[$key - 1]['conteudo'] = $item['sub_fields'];
																}
															endforeach; 
														?>
													
													
												<?php
												$chave = 0;
												$conteudosExibir = array();
											?>
		
											<div id="accordion0_1">	
		
												<?php
													$j = 0;
													foreach($conteudos as $conteudo){
														$conteudosExibir = array();
													?>
													
														<div class="card-header" id="headingnv2<?= $j . $i; ?>">
															<h5 class="mb-0">
																<button class="btn btn-link accordion-button" data-toggle="collapse" data-target="#collapsenv2<?= $j . $i; ?>" aria-expanded="false" aria-controls="collapsenv2<?= $j . $i; ?>">
																	<?= $conteudo['inicio']; ?>
																</button>
															</h5>
														</div>
														<div id="collapsenv2<?= $j . $i; ?>" class="collapse" aria-labelledby="headingnv2<?= $j . $i; ?>" data-parent="#accordion0_1">
															<div class="card-body">
																<?php
																	foreach($conteudo['conteudo'] as $key => $item){
		
																		if($item['type'] == 'accordion'){
																			$chave = $key;
																			$conteudosExibir[$chave]['inicio'] = '<div class="title">' . $item['label'] .'</div>';
																			$conteudosExibir[$chave]['conteudo'] = array();
																		} else {
																			$conteudosExibir[$chave]['conteudo'][] = $item;
																		}
																	}
		
																	$k = 0;
																	foreach($conteudosExibir as $conteudos){
																?>
		
																		<div id="accordion0_2">
																			
																			<div class="card-header" id="heading<?= $k . $j; ?>">
																			<h5 class="mb-0">
																				<button class="btn btn-link accordion-button" data-toggle="collapse" data-target="#collapse<?= $k . $j; ?>" aria-expanded="false" aria-controls="collapse<?= $k . $j; ?>">
																					<?= $conteudos['inicio']; ?>
																				</button>
																			</h5>
																			</div>
		
																			<div id="collapse<?= $k . $j; ?>" class="collapse" aria-labelledby="heading<?= $k . $j; ?>" data-parent="#accordion0_2">
																				<div class="card-body">
																					<div class="row">
																						<?php 
																						
																						
		
																						foreach($conteudos['conteudo'] as $resultado){
																							$chave_procurada = $resultado['name'];
																							$valor = procurar_chave($field, $chave_procurada);
		
																							if($valor && $valor != ''){
																								?>
																									<div class="col-12 col-md-3">
																										<fieldset disabled>
																											<div class="form-group">
																												<label for="disabledTextInput"><?= $resultado['label']; ?></label>
																												<input type="text" id="disabledTextInput" class="form-control" placeholder="<?= $valor; ?>">
																											</div>
																										</fieldset>
																									</div>
																								<?php
																							}
																							//echo "Valor: " . $valor;
																						}
		
																						//print_r($conteudos['conteudo']); ?>
																					</div>
																				</div>
																			</div>
																			
																		</div>
																
																<?php
																		$k++;
																	}
																	//echo "<pre>";
																	//print_r($conteudosExibir);
																	//echo "</pre>";
																	
																?>
															</div>
														</div>
		
													<?php
														$j++;
													}
		
													//echo "<pre>";
													//print_r($conteudosExibir);
													//echo "</pre>";
														
													//}										
												?>
		
											</div>
		
										</div>
										</div>
									</div>
								</div>
						<?php
						
							$i++;
							}
					}
				}
			?>
		</div>
		

		<?php
	}

	/**
	 * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
	 *
	 * Callback for admin_enqueue_script.
	 *
	 * @return void
	 */
	public function input_admin_enqueue_scripts() {
		$url     = trailingslashit( $this->env['url'] );
		$version = $this->env['version'];

		wp_register_script(
			'PREFIX-FIELD-NAME',
			"{$url}assets/js/field.js",
			array( 'acf-input' ),
			$version
		);

		wp_register_style(
			'PREFIX-FIELD-NAME',
			"{$url}assets/css/field.css",
			array( 'acf-input' ),
			$version
		);

		wp_enqueue_script( 'PREFIX-FIELD-NAME' );
		wp_enqueue_style( 'PREFIX-FIELD-NAME' );
	}
}
