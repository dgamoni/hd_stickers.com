<?php

function edit_product ($product_data)  
{
    $post = array( // Set up the basic post data to insert for our product
        'ID'           => $product_data['ID'],
        'post_author'  => 1,
        'post_content' => $product_data['description'],
        'post_status'  => 'publish',
        'post_title'   => $product_data['name'],
        'post_parent'  => '',
        'post_type'    => 'product'
    );

    $post_id = wp_insert_post($post); // Insert the post returning the new post id

    if (!$post_id) // If there is no post id something has gone wrong so don't proceed
    {
        return false;
    }

    update_post_meta($post_id, '_sku', $product_data['sku']); // Set its SKU
    update_post_meta( $post_id,'_visibility','visible'); // Set the product to visible, if not it won't show on the front end

    wp_set_object_terms($post_id, $product_data['categories'], 'product_cat'); // Set up its categories
    wp_set_object_terms($post_id, 'variable', 'product_type'); // Set it to a variable product type

    insert_product_attributes($post_id, $product_data['available_attributes'], $product_data['variations']); // Add attributes passing the new post id, attributes & variations
    edit_product_variations($post_id, $product_data['variations']); // Insert variations passing the new post id & variations   
}

function insert_product ($product_data,$custom_size)  
{
    $post = array( // Set up the basic post data to insert for our product

        'post_author'  => 1,
        'post_content' => $product_data['description'],
        'post_status'  => 'publish',
        'post_title'   => $product_data['name'],
        'post_parent'  => '',
        'post_type'    => 'product'
    );

    $post_id = wp_insert_post($post); // Insert the post returning the new post id

    if (!$post_id) // If there is no post id something has gone wrong so don't proceed
    {
        return false;
    }

    update_post_meta($post_id, '_sku', $product_data['sku'].$post_id); // Set its SKU
    update_post_meta( $post_id,'_visibility','visible'); // Set the product to visible, if not it won't show on the front end

    wp_set_object_terms($post_id, $product_data['categories'], 'product_cat'); // Set up its categories
    wp_set_object_terms($post_id, 'variable', 'product_type'); // Set it to a variable product type

    insert_product_attributes($post_id, $product_data['available_attributes'], $product_data['variations']); // Add attributes passing the new post id, attributes & variations
    insert_product_variations($post_id, $product_data['variations']); // Insert variations passing the new post id & variations   
    
    if($custom_size) {
        $string = 'a:11:{s:15:"calculator_type";s:14:"area-dimension";s:9:"dimension";a:4:{s:7:"pricing";a:6:{s:7:"enabled";s:2:"no";s:5:"label";s:0:"";s:4:"unit";s:2:"cm";s:10:"calculator";a:1:{s:7:"enabled";s:2:"no";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:6:"length";a:5:{s:7:"enabled";s:3:"yes";s:5:"label";s:15:"Required Length";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:5:"width";a:5:{s:7:"enabled";s:2:"no";s:5:"label";s:14:"Required Width";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:6:"height";a:5:{s:7:"enabled";s:2:"no";s:5:"label";s:15:"Required Height";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}s:4:"area";a:2:{s:7:"pricing";a:6:{s:7:"enabled";s:2:"no";s:5:"label";s:0:"";s:4:"unit";s:5:"sq cm";s:10:"calculator";a:1:{s:7:"enabled";s:2:"no";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:4:"area";a:4:{s:5:"label";s:13:"Required Area";s:4:"unit";s:5:"sq cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}s:14:"area-dimension";a:3:{s:7:"pricing";a:6:{s:7:"enabled";s:3:"yes";s:5:"label";s:0:"";s:4:"unit";s:5:"sq cm";s:10:"calculator";a:1:{s:7:"enabled";s:3:"yes";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:6:"length";a:4:{s:5:"label";s:6:"Length";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:5:"width";a:4:{s:5:"label";s:5:"Width";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}s:11:"area-linear";a:3:{s:7:"pricing";a:6:{s:7:"enabled";s:2:"no";s:5:"label";s:0:"";s:4:"unit";s:2:"cm";s:10:"calculator";a:1:{s:7:"enabled";s:2:"no";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:6:"length";a:4:{s:5:"label";s:6:"Length";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:5:"width";a:4:{s:5:"label";s:5:"Width";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}s:12:"area-surface";a:4:{s:7:"pricing";a:6:{s:7:"enabled";s:2:"no";s:5:"label";s:0:"";s:4:"unit";s:5:"sq cm";s:10:"calculator";a:1:{s:7:"enabled";s:2:"no";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:6:"length";a:4:{s:5:"label";s:6:"Length";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:5:"width";a:4:{s:5:"label";s:5:"Width";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:6:"height";a:4:{s:5:"label";s:6:"Height";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}s:6:"volume";a:2:{s:7:"pricing";a:6:{s:7:"enabled";s:2:"no";s:5:"label";s:0:"";s:4:"unit";s:2:"ml";s:10:"calculator";a:1:{s:7:"enabled";s:2:"no";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:6:"volume";a:4:{s:5:"label";s:15:"Required Volume";s:4:"unit";s:2:"ml";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}s:16:"volume-dimension";a:4:{s:7:"pricing";a:6:{s:7:"enabled";s:2:"no";s:5:"label";s:0:"";s:4:"unit";s:2:"ml";s:10:"calculator";a:1:{s:7:"enabled";s:2:"no";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:6:"length";a:4:{s:5:"label";s:6:"Length";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:5:"width";a:4:{s:5:"label";s:5:"Width";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:6:"height";a:4:{s:5:"label";s:6:"Height";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}s:11:"volume-area";a:3:{s:7:"pricing";a:6:{s:7:"enabled";s:2:"no";s:5:"label";s:0:"";s:4:"unit";s:2:"ml";s:10:"calculator";a:1:{s:7:"enabled";s:2:"no";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:4:"area";a:4:{s:5:"label";s:4:"Area";s:4:"unit";s:5:"sq cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:6:"height";a:4:{s:5:"label";s:6:"Height";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}s:6:"weight";a:2:{s:7:"pricing";a:6:{s:7:"enabled";s:2:"no";s:5:"label";s:0:"";s:4:"unit";s:2:"kg";s:10:"calculator";a:1:{s:7:"enabled";s:2:"no";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:6:"weight";a:4:{s:5:"label";s:15:"Required Weight";s:4:"unit";s:2:"kg";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}s:14:"wall-dimension";a:3:{s:7:"pricing";a:6:{s:7:"enabled";s:2:"no";s:5:"label";s:0:"";s:4:"unit";s:5:"sq cm";s:10:"calculator";a:1:{s:7:"enabled";s:2:"no";}s:9:"inventory";a:1:{s:7:"enabled";s:2:"no";}s:6:"weight";a:1:{s:7:"enabled";s:2:"no";}}s:6:"length";a:4:{s:5:"label";s:25:"Distance around your room";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}s:5:"width";a:4:{s:5:"label";s:6:"Height";s:4:"unit";s:2:"cm";s:8:"editable";s:3:"yes";s:7:"options";a:1:{i:0;s:0:"";}}}}';
        update_post_meta($post_id, '_wc_price_calculator', $string);
    }
}

function insert_product_attributes ($post_id, $available_attributes, $variations)  
{
    foreach ($available_attributes as $attribute) // Go through each attribute
    {   
        $values = array(); // Set up an array to store the current attributes values.

        foreach ($variations as $variation) // Loop each variation in the file
        {
            $attribute_keys = array_keys($variation['attributes']); // Get the keys for the current variations attributes

            foreach ($attribute_keys as $key) // Loop through each key
            {
                if ($key === $attribute) // If this attributes key is the top level attribute add the value to the $values array
                {
                    $values[] = $variation['attributes'][$key];
                }
            }
        }

        // Essentially we want to end up with something like this for each attribute:
        // $values would contain: array('small', 'medium', 'medium', 'large');

        $values = array_unique($values); // Filter out duplicate values

        // Store the values to the attribute on the new post, for example without variables:
        // wp_set_object_terms(23, array('small', 'medium', 'large'), 'pa_size');
        wp_set_object_terms($post_id, $values, 'pa_' . $attribute);
    }

    $product_attributes_data = array(); // Setup array to hold our product attributes data

    foreach ($available_attributes as $attribute) // Loop round each attribute
    {
        $product_attributes_data['pa_'.$attribute] = array( // Set this attributes array to a key to using the prefix 'pa'

            'name'         => 'pa_'.$attribute,
            'value'        => '',
            'is_visible'   => '1',
            'is_variation' => '1',
            'is_taxonomy'  => '1'

        );
    }

    update_post_meta($post_id, '_product_attributes', $product_attributes_data); // Attach the above array to the new posts meta data key '_product_attributes'
}

function edit_product_variations ($post_id, $variations)  
{

    foreach ($variations as $index => $variation)
    {
        $variation_post = array( // Setup the post data for the variation
            'post_title'  => 'Variation #'.$index.' of '.count($variations).' for product#'. $post_id,
            'post_name'   => 'product-'.$post_id.'-variation-'.$index,
            'post_status' => 'publish',
            'post_parent' => $post_id,
            'post_type'   => 'product_variation',
            'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index
        );

        $_variations = get_posts( $variation_post );
        // if($index == 2):
        //     print_r($_variations[0]->ID);
        //     die();
        // endif;
        //wp_delete_post($_variations[0]->ID, true);


        $variation_post_id = wp_insert_post($variation_post); // Insert the variation

        foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes
        {   
            $attribute_term = get_term_by('name', $value, 'pa_'.$attribute); // We need to insert the slug not the name into the variation post meta

            update_post_meta($variation_post_id, 'attribute_pa_'.$attribute, $attribute_term->slug);
          // Again without variables: update_post_meta(25, 'attribute_pa_size', 'small')
        }

        update_post_meta($variation_post_id, '_price', $variation['price']);
        update_post_meta($variation_post_id, '_regular_price', $variation['price']);
        //wp_delete_post($_variations[0]->ID, true);
    }
}
function insert_product_variations ($post_id, $variations)  
{
    foreach ($variations as $index => $variation)
    {
        $variation_post = array( // Setup the post data for the variation

            'post_title'  => 'Variation #'.$index.' of '.count($variations).' for product#'. $post_id,
            'post_name'   => 'product-'.$post_id.'-variation-'.$index,
            'post_status' => 'publish',
            'post_parent' => $post_id,
            'post_type'   => 'product_variation',
            'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index
        );

        $variation_post_id = wp_insert_post($variation_post); // Insert the variation

        foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes
        {   
            $attribute_term = get_term_by('name', $value, 'pa_'.$attribute); // We need to insert the slug not the name into the variation post meta

            update_post_meta($variation_post_id, 'attribute_pa_'.$attribute, $attribute_term->slug);
          // Again without variables: update_post_meta(25, 'attribute_pa_size', 'small')
        }

        update_post_meta($variation_post_id, '_price', $variation['price']);
        update_post_meta($variation_post_id, '_regular_price', $variation['price']);
    }
}

function insert_products ($products)  
{
    if (!empty($products)) // No point proceeding if there are no products
    {
        array_map('insert_product', $products); // Run 'insert_product' function from above for each product
        //insert_product($products[0],$custom_size);
    }   
}

function edit_products ($products)  
{
    if (!empty($products)) // No point proceeding if there are no products
    {
        array_map('edit_product', $products); // Run 'insert_product' function from above for each product
    }
}

function your_login_function()
{
    if ( is_user_logged_in() == true ) {
       $json = file_get_contents('my-product-data.json', FILE_USE_INCLUDE_PATH); // Get json from sample file
		$products_data = json_decode($json, true); // Decode it into an array

        // print_r($products_data);
        // die();

		//insert_products($products_data); 
    } else {
        /* Some other code */
    }
}
//add_action('init', 'your_login_function');

