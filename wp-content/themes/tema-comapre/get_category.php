<?php

require( '../../../wp-load.php' ); // Carrega as funcoes do WP

if( isset($_POST['data']) ){

   //print_r($_POST['data']);
    
    $data = $_POST['data'];

    

    $categorias = array();
    $j = 0;

    $categs = array();
    

    foreach($data as $d){

        $term = get_term_by('slug', $d, 'atividades_categories' );

        
        $i = 0;
        
        $termos = get_terms(
            array( 
                'taxonomy' => 'atividades_categories',
                'child_of' => $term->term_id,
                'hide_empty' => false
            )
        );

        if($termos){
            foreach ($termos as $termo){
                $categs[$i]['name'] = $termo->name;
                $categs[$i]['value'] = $termo->slug;
                $categs[$i]['checked'] = false;
    
                $i++;
            }

            $categorias[$j] = array(
                'label' => $term->name,
                'options' => $categs
            );
            $j++;
        }

        

       
        
    }

    //print_r($categs);
    

    $json_response = json_encode($categorias);

    //$json_response  = str_replace('"name"', 'name', $json_response);
    //$json_response  = str_replace('"options"', 'options', $json_response);
    //$json_response  = str_replace('"label"', 'label', $json_response);
    //$json_response  = str_replace('"value"', 'value', $json_response);
    //$json_response  = str_replace('"checked"', 'checked', $json_response);

	echo $json_response;
}