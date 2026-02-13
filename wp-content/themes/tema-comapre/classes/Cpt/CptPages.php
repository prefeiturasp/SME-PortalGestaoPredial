<?php

namespace Classes\Cpt;


class CptPages extends Cpt
{
	public function __construct()
	{
		//add_filter( 'manage_pages_columns' , array($this, 'exibe_cols' ));
		add_filter('manage_pages_columns', array($this, 'exibe_cols_pages'), 10, 2);
		add_action( 'manage_pages_custom_column' , array($this, 'cols_content_pages'), 10, 2 );
	}

	// add featured thumbnail to admin post columns
	public function exibe_cols_pages($cols) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => 'Titulo',
			'author' => 'Autor',
			'featured_thumb' => 'Thumbnail',
			'date' => 'Data',
			'lastmodified' => 'Modificado por',
		);
		return $columns;
	}

	public function cols_content_pages( $column) {
		switch ( $column ) {
			case 'featured_thumb':
				echo '<a href="' . get_edit_post_link() . '">';
				echo the_post_thumbnail( 'admin-list-thumb' );
				echo '</a>';
				break;

			case 'lastmodified':				
				//echo get_the_last_modified_info();				
				break;
		}
	}


}