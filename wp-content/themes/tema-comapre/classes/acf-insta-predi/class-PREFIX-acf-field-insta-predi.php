<?php
/**
 * Defines the custom field type class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PREFIX_acf_field_insta_predi class.
 */
class PREFIX_acf_field_insta_predi extends \acf_field {
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
		$this->name = 'insta_predi';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Instalação Predial', 'tema-comapre' );

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
		 * const errorMessage = acf._e("insta_predi", "error");
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

		echo "<p><strong>Informações gerais</strong></p>";

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

		// The Loop.
		if ( $the_query->have_posts() ) {
			echo '<table class="table table-bordered">';
				echo '<thead class="thead-light">
						<tr>
							<th scope="col">Nome do prédio</th>
							<th scope="col">Tipo</th>
							<th scope="col">Ações</th>
						</tr>
					</thead>';
					echo '<tbody>';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$nome = get_field('predio_nome_predio');
				$principal = get_field('predio_principal');

				if($principal){
					$identificacao = '<span class="badge badge-light">Edificação Principal</span>';
				} else {
					$identificacao = '';
				}
				echo '<tr>					
					<td>' . $nome . '</td>
					<td>' . $identificacao . '</td>
					<td class="align-middle text-center"><a class="action-button" href="' . get_the_permalink() . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
				</tr>';
			}
						echo '</tbody>';
			echo '</table>';
		} else {
			echo '<table class="table table-bordered">';
				echo '<thead class="thead-light">
						<tr>
							<th scope="col">Nome do prédio</th>
							<th scope="col">Tipo</th>
							<th scope="col">Ações</th>
						</tr>
					</thead>';
					echo '<tbody>
						<tr>					
							<td colspan="3">Nenhuma edificação cadastrada para esta unidade</td>
						</tr>
						</tbody>';
			echo '</table>';
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
		*/ // Obtém o usuário atual
		$current_user = wp_get_current_user();
		$current_user_roles = $current_user->roles;
		$status = get_post_meta(get_the_ID(), 'status_vistoria', true);
		?>

		<?php if( in_array('vistoriador', $current_user_roles) && ($status == 'analise' || $status == 'concluida') ): ?>
			<button type="button" class="btn btn-primary btn-new-predio" disabled>Adicionar um novo prédio</button>
		<?php else: ?>
			<a class="btn btn-primary btn-new-predio" href="<?= get_home_url(); ?>/novo-predio/?unidadeatb=<?= get_the_ID(); ?>" role="button">Adicionar um novo prédio</a>
		<?php endif; ?>

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
