<?php 

/**Fields*/

/**Product Category*/

function armorall_preprocess_field_prod_cat_breadcrumbs(&$vars){
	$fields = array('breadcrumb_text' =>'field_prod_cat_breadcrumb_text', 'breadcrumb_link'=>'field_prod_cat_breadcrumb_link', 'is_selected'=>'field_prod_cat_is_selected_link');
	armorall_def_tpl_var_field_collections($vars, 'breadcrumbs', 'field_prod_cat_breadcrumbs', $fields);
	armorall_patch_title_field($vars, 'breadcrumbs'); /**patch**/

}


function armorall_preprocess_field_prod_cat_products(&$vars){
	$fields = array('image' =>'field_product_cover_image', 'title'=>'title', 'is_new_product'=>'field_product_is_a_new_product', 'external_link'=>'field_product_external_link', 'background_image'=>'field_product_background_image');
	armorall_def_tpl_var_entities_collection($vars, 'products', 'field_prod_cat_products', $fields);	
	armorall_patch_title_field($vars, 'products'); /**patch**/
}

/**Product Category*/

/**Product*/

function armorall_preprocess_field_product_sds(&$vars){
	$fields = array('sds_label' =>'field_product_sds_label', 'sds_file'=>'field_product_sds_file');
	armorall_def_tpl_var_field_collections($vars, 'sds', 'field_product_sds', $fields);

}

function armorall_preprocess_field_product_breadcrumbs(&$vars){
	$fields = array('breadcrumb_text' =>'field_product_breadcrumb_text', 'breadcrumb_link'=>'field_product_breadcrumb_link', 'is_selected'=>'field_product_is_selected_link');
	armorall_def_tpl_var_field_collections($vars, 'breadcrumbs', 'field_product_breadcrumbs', $fields);
}

function armorall_preprocess_field_product_related_products(&$vars){
	$fields = array('image' =>'field_product_related_image', 'title'=>'title');
	armorall_def_tpl_var_entities_collection($vars, 'products', 'field_product_related_products', $fields);	
	armorall_patch_title_field($vars, 'products'); /**patch**/
}

function armorall_preprocess_field_product_previous_product(&$vars){
	$fields = array('title' =>'title');
	armorall_def_tpl_var_entity($vars, 'previous_product', 'field_product_previous_product', $fields);
}

function armorall_preprocess_field_product_next_product(&$vars){
	$fields = array('title' =>'title');
	armorall_def_tpl_var_entity($vars, 'next_product', 'field_product_next_product', $fields);
}
/**Product*/

/**Global Page*/
function armorall_preprocess_field_global_breadcrumbs(&$vars){
	$fields = array('breadcrumb_text' =>'field_global_breadcrumb_text', 'breadcrumb_link'=>'field_global_breadcrumb_link', 'is_selected'=>'field_global_selected_link');
	armorall_def_tpl_var_field_collections($vars, 'breadcrumbs', 'field_global_breadcrumbs', $fields);
}

function armorall_preprocess_field_global_videos(&$vars){
	$fields = array('video_url' =>'field_global_video_url', 'video_title' =>'field_global_video_title');
	armorall_def_tpl_var_field_collections($vars, 'videos', 'field_global_videos', $fields);
}

function armorall_preprocess_field_global_history_element(&$vars){
	$fields = array('type' =>'field_history_element_type', 'image' =>'field_history_element_image', 'title'=>'field_history_element_title', 'subtitle'=>'field_history_element_subtitle', 'body'=>'field_history_element_body');
	armorall_def_tpl_var_field_collections($vars, 'history_elements', 'field_global_history_element', $fields);
}

function armorall_preprocess_field_global_offers(&$vars){
	$fields = array('offer_title' =>'field_global_offer_title', 
				    'offer_subtitle' =>'field_global_offer_subtitle', 
				    'offer_description'=>'field_global_offer_description', 
				    'offer_image'=>'field_global_offer_image', 
				    'offer_file'=>'field_global_offer_attached_file', 
				    'offer_is_new'=>'field_global_offer_new_product',
				    'offer_color'=>'field_global_offer_color',
					'offer_bg_image'=>'field_global_offer_bg_image');
	armorall_def_tpl_var_field_collections($vars, 'offers', 'field_global_offers', $fields);
}

function armorall_preprocess_field_global_sds_category(&$vars){
	$fields = array('category_name' =>'field_global_sds_category_name', 'subcategory_name' =>'field_global_subcategory_name', 'sds_label'=>'field_global_sds_label', 'sds_file'=>'field_global_sds_file');
	armorall_def_tpl_var_field_collections($vars, 'sds', 'field_global_sds_category', $fields);

	$category_array = array();
	$subcategories_tmp = $vars['node']->armorall_data->sds;
	foreach($vars['node']->armorall_data->sds as $item){
		if(!array_key_exists($item['category_name'], $category_array)){
			$category_array[$item['category_name']] = array();
		}
		$subcategory_array = array();
		foreach($subcategories_tmp as $subitem){
			if($item['category_name'] == $subitem['category_name']){
				if(!array_key_exists($subitem['subcategory_name'], $subcategory_array)){
					$subcategory_array[$subitem['subcategory_name']] = array();
				}		
				array_push($subcategory_array[$subitem['subcategory_name']], $subitem);
				unset($subcategories_tmp[array_search($subitem, $subcategories_tmp)]);
			}
		}
		if(!empty($subcategory_array)){
			$category_array[$item['category_name']] =  $subcategory_array;
		}
		
		
	}
	$vars['node']->armorall_data->sds = $category_array;
}
/**Global Page*/


/**Expert Tips*/
function armorall_preprocess_field_expert_tips_next_tip(&$vars){
	$fields = array('title' =>'title');
	armorall_def_tpl_var_entity($vars, 'next_tip', 'field_expert_tips_next_tip', $fields);
	armorall_patch_title_field($vars, 'next_tip'); /**patch**/
}

function armorall_preprocess_field_expert_tips_previous_tip(&$vars){
	$fields = array('title' =>'title');
	armorall_def_tpl_var_entity($vars, 'previous_tip', 'field_expert_tips_previous_tip', $fields);
	armorall_patch_title_field($vars, 'previous_tip'); /**patch**/
}

function armorall_preprocess_field_expert_tips_breadcrumbs(&$vars){
	$fields = array('breadcrumb_text' =>'field_tips_breadcrumb_text', 'breadcrumb_link'=>'field_tips_breadcrumb_link', 'is_selected'=>'field_tips_is_selected_link');
	armorall_def_tpl_var_field_collections($vars, 'breadcrumbs', 'field_expert_tips_breadcrumbs', $fields);
	armorall_patch_title_field($vars, 'breadcrumbs'); /**patch**/

}
function armorall_preprocess_field_expert_tips_faqs(&$vars){
	$fields = array('faq_title' =>'field_tips_title', 'faq_body'=>'field_tips_body');
	armorall_def_tpl_var_field_collections($vars, 'faqs', 'field_expert_tips_faqs', $fields);


}

function armorall_preprocess_field_expert_tips_related_pages(&$vars){
	$fields = array('menu_text' =>'field_expert_tips_menu_text');
	armorall_def_tpl_var_entities_collection($vars, 'related_pages', 'field_expert_tips_related_pages', $fields);	
}
/**Expert Tips*/

/**Landing Page**/
function armorall_preprocess_field_lading_page_breadcrumbs(&$vars){
	$fields = array('breadcrumb_text' =>'field_landing_breadcrumb_text', 'breadcrumb_link'=>'field_landing_breadcrumb_link', 'is_selected'=>'field_landing_is_selected_link');
	armorall_def_tpl_var_field_collections($vars, 'breadcrumbs', 'field_lading_page_breadcrumbs', $fields);
}

function armorall_preprocess_field_landing_page_headlines(&$vars){
	$fields = array('headline_title' =>'field_landing_headline_title', 
					'headline_body'=>'field_landing_headline_body', 
					'headline_link'=>'field_landing_headline_link',
					'headline_image'=>'field_landing_headline_image',
					'headline_image_2'=>'field_landing_headline_image_2',
					'headline_color'=>'field_landing_headline_color',
					'headline_link_text'=>'field_landing_link_text',
					'headline_alignment'=>'field_landing_alignment');
	armorall_def_tpl_var_field_collections($vars, 'headlines', 'field_landing_page_headlines', $fields);
}

function armorall_preprocess_field_landing_global_pages(&$vars){
	$fields = array('title' =>'title', 'color'=>'field_global_items_color', 'image'=>'field_global_cover_image', 'menu_text'=>'field_global_menu_text');
	armorall_def_tpl_var_entities_collection($vars, 'global_pages', 'field_landing_global_pages', $fields);	
	armorall_patch_title_field($vars, 'global_pages'); /**patch**/
}

function armorall_preprocess_field_landing_expert_tips(&$vars){
	$fields = array('title' =>'title', 'color'=>'field_expert_tips_color', 'image'=>'field_expert_tips_cover_image', 'menu_text'=>'field_expert_tips_menu_text');
	armorall_def_tpl_var_entities_collection($vars, 'expert_tips', 'field_landing_expert_tips', $fields);	
	armorall_patch_title_field($vars, 'expert_tips'); /**patch**/
}

function armorall_preprocess_field_landing_prod_categories(&$vars){
	$fields = array('title' =>'title', 'image'=>'field_prod_cat_cover_image', 'color'=>'field_prod_cat_color', 'total_products'=>'field_prod_cat_products');
	armorall_def_tpl_var_entities_collection($vars, 'product_categories', 'field_landing_prod_categories', $fields);	
	armorall_patch_title_field($vars, 'product_categories'); /**patch**/
}

/**Landing Page**/
/**Fields*/