<?php 

/**
* Function used to add dynamic functions to manage information also
* execute the new function
*/
function template_extend_functionality($function, $element, &$params){
	$function = $function . '_' . $element;
		if (function_exists($function))
		{
			$function($params);
		}
			
}


/**
* Convert entity array to string array
*/
function template_make_array_entity_value($array, &$vars){
	$result = array();
	if(isset($array[$vars['node']->language])){
		for($i=0; $i< sizeof($array[$vars['node']->language]); $i++){
			$result[$i] = $array[$vars['node']->language][$i]['entity']->name;
		}
	}
	return $result;
}

/**
* Create SVP Data Object only if doesn't exist
*/
function template_create_data_object(&$vars){
	if(!isset($vars['node']->obj_tpl_data)){
		$vars['node']->obj_tpl_data = new stdClass();
	}
}

/**
* Define Single Value variables inside SVP Data Object
*/

function template_def_tpl_var_assign_value(&$vars,$object_field_name, $value){
	template_create_data_object($vars);
	$vars['node']->obj_tpl_data->$object_field_name = $value;
}

function template_def_tpl_var_single_value(&$vars, $object_field_name, $form_field_name){
	template_create_data_object($vars);
	$node = entity_metadata_wrapper('node', $vars['node']);
	if(isset($node->$form_field_name)){
		$vars['node']->obj_tpl_data->$object_field_name = $node->$form_field_name->value();
	}

}

function template_def_tpl_var_single_wysiwyg_value(&$vars, $object_field_name, $form_field_name){
	template_create_data_object($vars);
	$node = entity_metadata_wrapper('node', $vars['node']);
	if(isset($node->$form_field_name)){
		$field = $node->$form_field_name->value();
		$vars['node']->obj_tpl_data->$object_field_name = $field['value'];
	}

}

function template_def_tpl_var_single_plain_text_value(&$vars, $object_field_name, $form_field_name){
	template_create_data_object($vars);
	$node = entity_metadata_wrapper('node', $vars['node']);
	if(isset($node->$form_field_name)){
		$field = $node->$form_field_name->value();
		$vars['node']->obj_tpl_data->$object_field_name = $field;
	}

}

/**
* Define Multi Value variables inside SVP Data Object
*/
function template_def_tpl_var_multiple_value(&$vars, $object_field_name, $form_field_name){
	template_create_data_object($vars);
	$array = array();
	$element = $vars['node']->$form_field_name;
	if(isset($element[$vars['node']->language])){
		foreach($element[$vars['node']->language] as $item) {
			array_push($array, $item['value']);
		}	
	}
	$vars['node']->obj_tpl_data->$object_field_name = $array;
}

function template_def_tpl_var_date_value(&$vars, $object_field_name, $form_field_name){
	template_create_data_object($vars);
	$elem = $vars['node']->$form_field_name;
	$vars['node']->obj_tpl_data->$object_field_name = date('m/d/Y', strtotime($elem['und'][0]['value']));
}
/**
* Define Single Image variables inside SVP Data Object
*/
function template_def_tpl_var_single_image(&$vars, $object_field_name, $form_field_name){
	template_create_data_object($vars);
	$element = $vars['node']->$form_field_name;		
	if(isset($element[$vars['node']->language])){
		$vars['node']->obj_tpl_data->$object_field_name = file_create_url($element[$vars['node']->language][0]['uri']);
	}else{
		$vars['node']->obj_tpl_data->$object_field_name = null;
	}

}

/**
* Define Multi Image variables inside SVP Data Object
*/
function template_def_tpl_var_multiple_image(&$vars, $object_field_name, $form_field_name){
	template_create_data_object($vars);
	if(isset($vars['node']->$form_field_name)){
		$array = array();
		$elements = $vars['node']->$form_field_name;	
		if(isset($elements[$vars['node']->language])){
			foreach($elements[$vars['node']->language] as $element) {
				array_push($array, file_create_url($element['uri']));
			}	
		}	
		$vars['node']->obj_tpl_data->$object_field_name = $array;
	}else{
		
		$vars['node']->obj_tpl_data->$object_field_name = null;
	}
	
}

/**
* Define Entity Array String variables from collection of entities inside SVP Data Object
*/
function template_def_tpl_var_entities_collection(&$vars, $object_field_name, $form_field_name, $array_form_fields){
	template_create_data_object($vars);
	$vars['node']->obj_tpl_data->$object_field_name = array();
	$entities = $vars['node']->$form_field_name;
$count = 0;

	if(isset($entities['und'])){
		foreach($entities['und'] as $item){
			$tmp = array();
			foreach($array_form_fields as $value){
				$key = array_search($value, $array_form_fields);
				$field = $item['entity']->$value;
				
				if(isset($field['und'][0]) && !is_string($field)){
					if(isset($field['und'][0]['target_id'])){
						$tmp[$key] = count($field['und']);		
					}elseif(isset($field['und'][0]['uri'])){
						$tmp[$key] = file_create_url($field['und'][0]['uri']);
					}elseif(isset($field['und'][0]['value'])){
						$tmp[$key] = $field['und'][0]['value'];
					}elseif(isset($field['und'][0]['count'])){
						$tmp[$key] =$field['und'][0]['count'];
					}
				}else{
					$tmp[$key] = $field;
				}
			}
			$tmp['node_url'] = drupal_get_path_alias('node/'.$item['entity']->nid, '');
			
			array_push($vars['node']->obj_tpl_data->$object_field_name, $tmp);
			$count++;
		}
		
	}

	
}

/**
* Define Entity Value  variables inside SVP Data Object
*/
function template_def_tpl_var_entity(&$vars, $object_field_name, $form_field_name, $array_form_fields){
	template_create_data_object($vars);
	$entity = $vars['node']->$form_field_name;
	if(!empty($entity)){
		foreach($array_form_fields as $value){
			$key = array_search($value, $array_form_fields);
			$field = $entity['und'][0]['entity']->$value;
			if(isset($field['und'][0])){
				if(isset($field['und'][0]['uri'])){
					$tmp[$key] = file_create_url($field['und'][0]['uri']);
				}elseif(isset($field['und'][0]['value'])){
					$tmp[$key] = $field['und'][0]['value'];
				}
			}else{
				$tmp[$key] = $field;
			}
			
		}
		$tmp['node_url'] = drupal_get_path_alias('node/'.$entity['und'][0]['entity']->nid, '');
		$vars['node']->obj_tpl_data->$object_field_name =  $tmp;
	}
}

/**
* Define Taxonomy Value variables inside SVP Data Object
*/
function template_def_tpl_var_taxonomy(&$vars, $object_field_name, $form_field_name){
	template_create_data_object($vars);
	$elements = $vars['node']->$form_field_name;
	$vars['node']->obj_tpl_data->$object_field_name = $elements[$vars['node']->language]['0']['taxonomy_term']->name;
}

function template_def_tpl_taxonomies_id_text(&$vars, $object_field_name, $vocab_name){
	template_create_data_object($vars);
	$myvoc = taxonomy_vocabulary_machine_name_load($vocab_name);

	$tree = taxonomy_get_tree($myvoc->vid);
	$terms = array();
	foreach ($tree as $term) {
		$term_info = array();
		$term_info['tid']=$term->tid;
		$term_info['name']=$term->name;
		array_push($terms, $term_info);
	}
	$vars['node']->obj_tpl_data->$object_field_name = $terms;
}

/**
* Get field collection and create array with the values.
*/
function template_def_tpl_var_field_collections(&$vars, $object_field_name, $field_collection_name, $array_form_fields){
	template_create_data_object($vars);
	$vars['node']->obj_tpl_data->$object_field_name = array();
	
	$wrapper = entity_metadata_wrapper('node', $vars['node']);
 	$formtype = field_get_items('node', $vars['node'], $field_collection_name);

 	if($formtype){
 		foreach($formtype as $itemid) { 
 			$tmp_array = array(); 
        	$item = field_collection_field_get_entity($itemid);
        	foreach($array_form_fields as $field_form){
        		$tmp = $item->$field_form;
        		if(isset($tmp[$vars['node']->language])){
        			if(isset($tmp[$vars['node']->language][0]['uri'])){
						$tmp_array[array_search($field_form, $array_form_fields)] = file_create_url($tmp[$vars['node']->language][0]['uri']);
					}else{
            			$tmp_array[array_search($field_form, $array_form_fields)] = $tmp[$vars['node']->language][0]['value'];
            		}
            	}
        	}
    		array_push($vars['node']->obj_tpl_data->$object_field_name, $tmp_array);           
		}
	}
}

/**
* Get the reference node field and convert into an url.
*/
function template_def_tpl_var_field_node_reference_url(&$vars, $object_field_name, $form_field_name){
	template_create_data_object($vars);
	$elements = $vars['node']->$form_field_name;
	if(isset($elements[$vars['node']->language]['0'])){
		$vars['node']->obj_tpl_data->$object_field_name = drupal_get_path_alias('node/'.$elements[$vars['node']->language]['0']['nid'], '');
	}
}

