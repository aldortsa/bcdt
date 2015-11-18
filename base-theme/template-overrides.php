<?php

//example of how to use the functions
function template_preprocess_node_product(&$vars){
	if (isset($vars['node'])){

		armorall_def_tpl_var_single_value($vars, 'title', 'title');
		armorall_def_tpl_var_single_value($vars, 'body', 'field_prod_body');
	}

}


