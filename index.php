<?php
/*
Plugin Name:Adding New Product
Description:This plugin is for adding new product  
*/

function cmb_add_meta_box() {

add_meta_box('custom_post_metabox','Add new Product','product_add_box','product','normal','high');

}

add_action( 'add_meta_boxes', 'cmb_add_meta_box' );

function product_add_box(){
?>
<div class="custom_pro_fieldd">
<label>Select Product category</label>
<select name="select_cat_new">
<option value="951">Men</option>
<option value="955">Women</option>
</select>  
<label>Product title</label>
<input type="text" name="pro_title" class="product_title_new">
</div>

<?php	
}
function mv_save_wc_order_other_fields( $post_id ) {

    remove_action( 'save_post', 'mv_save_wc_order_other_fields' );

    if(isset($_POST['select_cat_new']) && !empty($_POST["pro_title"])){         
    $product = wc_get_product( $post_id );
	$pro_title = get_the_title();
	$description_pro = get_the_content($post_id);
	$seo_descrip = get_post_meta($post_id,'_yoast_wpseo_metadesc',true);
    $seo_replace = str_replace($pro_title,$_POST["pro_title"],$seo_descrip);
	$string_rep = str_replace($pro_title,$_POST["pro_title"],$description_pro);
    $wc_adp = new WC_Admin_Duplicate_Product;
    $dup_product = $wc_adp->product_duplicate( $product );
	$dup_product2 = $dup_product->get_id(); 
    $dup_product = wc_get_product( $dup_product->get_id() ); // recall the WC_Product Object
    $dup_product->set_name($_POST["pro_title"]);
    $dup_product->set_slug( sanitize_title($_POST["pro_title"]) ); // slug
    $dup_product->set_status( 'publish'); 
	$dup_product->set_description($string_rep);
    //wp_set_object_terms($product_ID, $term->term_id, 'product_cat');	
    $dup_product->save();
	update_post_meta($dup_product2, '_yoast_wpseo_title', $_POST["pro_title"]);
	update_post_meta($dup_product2, '_yoast_wpseo_metadesc', $seo_replace);
	update_post_meta($dup_product2, '_yoast_wpseo_focuskw', $_POST["pro_title"]);
	
    add_action( 'save_post', 'mv_save_wc_order_other_fields' );
  
}
}
add_action( 'save_post', 'mv_save_wc_order_other_fields', 10, 1 ); 