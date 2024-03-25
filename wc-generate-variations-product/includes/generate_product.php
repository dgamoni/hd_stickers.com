<?php 

function get_cpt_tours_select($field) {
	$field['choices'] = array();
	$field['choices']['0'] = 'new product';

	global $wpdb;
	$table = $wpdb->prefix."posts";
	$query = "SELECT * FROM ".$table." WHERE post_type='product' AND post_status='publish'";
	$results = $wpdb->get_results($query);

	$tours_array = array();

	foreach ($results as $res){
	 	$tours_array[] = $res->post_title;
	 	$field['choices'][ $res->ID ] = $res->post_title;
	 }

	 return $field;
} 
add_filter('acf/load_field/name=stickers_product', 'get_cpt_tours_select');

function get_product_categor($field) {
	$field['choices'] = array();
	$field['choices']['0'] = 'none';

	$args = array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
	);
	$terms = get_terms( $args );

	$tours_array = array();

	foreach ($terms as $res){
	 	$tours_array[] = $res->name;
	 	$field['choices'][ $res->term_id ] = $res->name;
	 }

	 return $field;
} 
add_filter('acf/load_field/name=stickers_category', 'get_product_categor');

function price_var($stickers_price, $size) {
	foreach ($stickers_price as $key => $stickers_price_) {
		
		if($stickers_price_[field_5983536140fb2] == 'Lowest' ){
			if( $size <= $stickers_price_[field_598354a5719b0]) {
				$coof =  $stickers_price_[field_598354e2719b1];
			}
		}

		if($stickers_price_[field_5983536140fb2] == 'InBetween' ){
			if( ($stickers_price_[field_59835299cf458] <= $size) && ($size <= $stickers_price_[field_598354a5719b0]) ) {
				$coof =  $stickers_price_[field_598354e2719b1];
			}
		}

		if($stickers_price_[field_5983536140fb2] == 'Greatest' ){
			if( $stickers_price_[field_59835299cf458] <= $size ) {
				$coof =  $stickers_price_[field_598354e2719b1];
			}
		}

	}
	return $coof;
}

function save_sync() {
	$screen = get_current_screen();
	if (strpos($screen->id, "acf-options-generate-variations") == true) {
		// print_r($_POST['acf']);
		// die();

		$paraments = $_POST['acf'];
		// print_r($paraments);
		// die();


	$field_59834eb499045 = $paraments[field_59834eb499045];

	if ( $field_59834eb499045 != 0 ):	
		$ID = ' "ID"        : "'.$field_59834eb499045.'",';
		$name = '';
	else:
		$ID = '';
		$name = '"name"        : "Generate stickers",';
	endif;


	$field_598350ac09f0c = $paraments[field_598350ac09f0c];
	if(	$paraments[field_598351f2d7f60] ) {
		$custom_size = array(
			'field_5983cdc2038d2' => 'Custom size', 
 			'field_5983cde9038d3' => '1:1' 
		);
		array_push($field_598350ac09f0c, $custom_size);
	}
	// print_r($field_598350ac09f0c);
	// print_r($custom_size);
	// die();

	$field_5983515e8d646 = $paraments[field_5983515e8d646];
	$attributes = '';
	$base_price = $paraments[field_5983507f09f0b];

	$stickers_price = $paraments[field_59835274cf457];

	//print_r($base_price);

	//print_r('$field_598350ac09f0c='.count($field_598350ac09f0c));
	//print_r('$field_5983515e8d646='.count($field_5983515e8d646));
	$loop1 = 0;
	$loop2 = 0;
	foreach ($field_598350ac09f0c as $key => $sticker_size) {
		$loop1++;
		$loop2 = 0;
		foreach ($field_5983515e8d646 as $key2 => $sticker_quantity) {
			$loop2++;
			//print_r($sticker_size);
			//print_r($sticker_quantity);

			$curent_sizes = explode(":", $sticker_size[field_5983cde9038d3]);
			//print_r($curent_sizes);
			$this_size = $curent_sizes[0]*$curent_sizes[1];
			$cooficient = price_var($stickers_price, $sticker_quantity[field_5983cf4c75133]);
			//print_r($cooficient);
			
			if($cooficient){
				$sum_price = $cooficient*$this_size*$sticker_quantity[field_5983cf4c75133];
			} else {
				$sum_price = $base_price*$this_size*$sticker_quantity[field_5983cf4c75133];
			}
			//print_r('<pre>$sticker_size='.$sticker_size.' $sticker_quantity='.$sticker_quantity.' $this_size='.$this_size.'->'.$sum_price.' coof='.$cooficient.' quantity='.$sticker_quantity[field_5983cf4c75133].'</pre>');

			//print_r($sticker_quantity);
			//print_r('<pre>'.$key.' '.$loop1.'</pre>');
			//print_r('<pre>'.$key2.' '.$loop2.'</pre>');
			//if( ($key+1 == count($field_598350ac09f0c) ) && ($key2+1 == count($field_5983515e8d646) ) ){
			if( ($loop1 == count($field_598350ac09f0c) ) && ($loop2 == count($field_5983515e8d646) ) ){
				//print_r('end');
				$comma ='';
			} else {
				$comma =',';
			}
			$attributes .= '		            {
		                "attributes": {
		                    "sticker_size"  : "'.$sticker_size[field_5983cdc2038d2].'",
		                    "sticker_quantity" : "'.$sticker_quantity[field_5983cf4c75133].'"
		                },
		                "price" : "'.$sum_price.'"
		            }'.$comma;
		}
	}
	// print_r($attributes);
	 //die();

			// $attributes = '{
			// 	                "attributes": {
			// 	                    "sticker_size"  : "3cm X 3cm",
			// 	                    "sticker_quantity" : "50"
			// 	                },
			// 	                "price" : "220.00"
			// 	            }';

		$arr = '[
		    {
		    	'.$ID.'
		        '.$name.'
		        "sku"         : "GEN10009",
		        "description" : "",
		        "available_attributes": [
		            "sticker_size", "sticker_quantity"
		        ],
		        "variations":
		        [
					'.$attributes.'                           
		        ]
		    }
		]'; 

		$products_data = json_decode($arr, true);
		//print_r($arr);
		//die();

		if($paraments[field_59834eb499045]==0):
			insert_products($products_data);
		else:
			edit_products($products_data);
		endif;


		// set default value
		if($_POST['acf'][field_59834e7199044]):
			update_field('field_59834e7199044', 0, 'option');
		endif;
		if($_POST['acf'][field_59834eb499045]):
			update_field('field_59834eb499045', 0, 'option');
		endif;
		if($_POST['acf'][field_59834eea99046]):
			update_field('field_59834eea99046', 1, 'option');
		endif;
		if($_POST['acf'][field_59834f6dbc5df]):
			update_field('field_59834f6dbc5df', 1, 'option');
		endif;
		if($_POST['acf'][field_59834f9bf6200]):
			update_field('field_59834f9bf6200', 25, 'option');
		endif;
		if($_POST['acf'][field_59834fd2f6201]):
			update_field('field_59834fd2f6201', 25, 'option');
		endif;
		if($_POST['acf'][field_5983507f09f0b]):
			update_field('field_5983507f09f0b', 0.5, 'option');
		endif;
		if($_POST['acf'][field_598350ac09f0c]):
			$value[] = array(
				"field_5983cdc2038d2" => "3cm X 3cm",
				"field_5983cde9038d3" => "3:3"
			);
			$value[] = array ( 
				'field_5983cdc2038d2' => '5cm X 5cm', 
				'field_5983cde9038d3' => '5:5' 
			); 
			$value[] = array ( 
				'field_5983cdc2038d2' => '10cm X 10cm', 
				'field_5983cde9038d3' => '10:10'
			); 
			$value[] = array ( 
				'field_5983cdc2038d2' => '15cm X 15cm', 
				'field_5983cde9038d3' => '15:15' 
			);

			update_field( 'field_598350ac09f0c', $value, 'option' );
		endif;

		if($_POST['acf'][field_5983515e8d646]):



			$value2[] = array ( 
				'field_5983cf4c75133' => 50
			);
			$value2[] = array ( 
				'field_5983cf4c75133' => 75
			);  
			$value2[] = array ( 
				'field_5983cf4c75133' => 100
			);
			$value2[] = array ( 
				'field_5983cf4c75133' => 150
			);
			$value2[] = array ( 
				'field_5983cf4c75133' => 200
			);
			$value2[] = array ( 
				'field_5983cf4c75133' => 250
			);
			$value2[] = array ( 
				'field_5983cf4c75133' => 500
			);
			update_field( 'field_5983515e8d646', $value2, 'option' );
		endif;

		if($_POST['acf'][field_598351f2d7f60]):
			update_field('field_598351f2d7f60', 1, 'option');
		endif;


		if($_POST['acf'][field_59835274cf457]):
			$value3[] = array ( 
				'field_59835299cf458' => 0,
				'field_5983536140fb2' => 'Lowest',
				'field_598354a5719b0' => 100,
				'field_598354e2719b1' => 0.25
			);
			$value3[] = array ( 
				'field_59835299cf458' => 101,
				'field_5983536140fb2' => 'InBetween',
				'field_598354a5719b0' => 500,
				'field_598354e2719b1' => 0.20
			);
			$value3[] = array ( 
				'field_59835299cf458' => 501,
				'field_5983536140fb2' => 'InBetween',
				'field_598354a5719b0' => 1000,
				'field_598354e2719b1' => 0.15
			);
			$value3[] = array ( 
				'field_59835299cf458' => 1001,
				'field_5983536140fb2' => 'Greatest',
				'field_598354a5719b0' => null,
				'field_598354e2719b1' => 0.10
			);
			update_field( 'field_59835274cf457', $value3, 'option' );
		endif;

// Array ( 
// 	[field_59834e7199044] => 0 //stickers_category
// 	[field_59834eb499045] => 0 //stickers_product
// 	[field_59834eea99046] => 1 //stickers_min_length
// 	[field_59834f6dbc5df] => 1 //stickers_min_width
// 	[field_59834f9bf6200] => 25 //stickers_max_length
// 	[field_59834fd2f6201] => 25 //stickers_max_width
// 	[field_5983507f09f0b] => 0.5 //stickers_base_price

// 	[field_598350ac09f0c] => Array ( //stickers_default_size
// 		[0] => Array ( 
// 			[field_5983cdc2038d2] => 3cm X 3cm //stickers_default_size_label
// 			[field_5983cde9038d3] => 3:3 ) //stickers_default_size_value
// 		[1] => Array ( 
// 			[field_5983cdc2038d2] => 5cm X 5cm 
// 			[field_5983cde9038d3] => 5:5 ) 
// 		[2] => Array ( 
// 			[field_5983cdc2038d2] => 10cm X 10cm 
// 			[field_5983cde9038d3] => 10:10 ) 
// 		[3] => Array ( 
// 			[field_5983cdc2038d2] => 15cm X 15cm 
// 			[field_5983cde9038d3] => 15:15 ) ) 

// 	[field_5983515e8d646] => Array ( //stickers_default_quantity
// 		[0] => Array ( 
// 			[field_5983cf4c75133] => 50 ) //stickers_default_quantity_value
// 		[1] => Array ( 
// 			[field_5983cf4c75133] => 75 ) 
// 		[2] => Array ( 
// 			[field_5983cf4c75133] => 100 ) 
// 		[3] => Array ( 
// 			[field_5983cf4c75133] => 150 ) 
// 		[4] => Array ( 
// 			[field_5983cf4c75133] => 200 ) 
// 		[5] => Array ( 
// 			[field_5983cf4c75133] => 250 ) ) 
// 	[field_598351f2d7f60] => 1 //stickers_show_custom_size
// 	[field_59835274cf457] => Array ( //stickers_price
// 		[0] => Array ( 
// 			[field_59835299cf458] => 0 //stickers_price_start
// 			[field_5983536140fb2] => Lowest //stickers_price_operator
// 			[field_598354a5719b0] => 100 //stickers_price_end
// 			[field_598354e2719b1] => 0.25 ) //stickers_price_price
// 		[1] => Array ( 
// 			[field_59835299cf458] => 101 
// 			[field_5983536140fb2] => InBetween 
// 			[field_598354a5719b0] => 500 
// 			[field_598354e2719b1] => 0.20 ) 
// 		[2] => Array ( 
// 			[field_59835299cf458] => 501 
// 			[field_5983536140fb2] => InBetween 
// 			[field_598354a5719b0] => 1000 
// 			[field_598354e2719b1] => 0.15 ) 
// 		[3] => Array ( 
// 			[field_59835299cf458] => 1001 
// 			[field_5983536140fb2] => Greatest 
// 			[field_598354a5719b0] => 
// 			[field_598354e2719b1] => 0.10 ) ) )


	}

}
add_action('acf/save_post', 'save_sync', 20);


