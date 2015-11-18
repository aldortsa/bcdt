<?php
require_once('template-helper.php');
require_once('template-overrides.php');
require_once('template-fields.php');
require_once('template-forms.php');




/**
*
* Preprocess for Nodes
*
*/
function template_preprocess_page(&$vars, $hook){
	$header = drupal_get_http_header('status');
	
	if (isset($vars['page']))
	{
		$vars['theme_hook_suggestions'][] = '/templates/page/page__'. $vars['page']->type;
		armorall_extend_functionality(__FUNCTION__, $vars['node']->type, $vars);
	}
	if ($header == '404 Not Found') {     
    	$vars['theme_hook_suggestions'][] = 'page__404';
  	}
	
}

function template_preprocess_region(&$vars, $hook){
	if (isset($vars['region']))
	{
		$vars['theme_hook_suggestions'][] = '/templates/region/region__'. $vars['region'];
	}	
}

function template_preprocess_block(&$vars, $hook){
	if (isset($vars['block']))
	{
		$vars['theme_hook_suggestions'][] = '/templates/block/block__' . $vars['block']->module.'__'.$vars['block']->delta;
	}
}

function template_preprocess_node(&$vars, $hook){
	if (isset($vars['node']))
	{
		if ($blocks_footer  = block_get_blocks_by_region('content_footer')) {

  			$vars['content_footer'] = $blocks_footer;
		}
		if ($blocks_middle  = block_get_blocks_by_region('content_middle')) {
			
  			$vars['content_middle'] = $blocks_middle;
		}
		if ($blocks_header  = block_get_blocks_by_region('content_header')) {
			
  			$vars['content_header'] = $blocks_header;
		}
		if ($blocks_middle  = block_get_blocks_by_region('content_blog_middle')) {
			
  			$vars['content_blog_middle'] = $blocks_middle;
		}
		$vars['theme_hook_suggestions'][] = '/templates/node/node__'. $vars['node']->type;
		armorall_extend_functionality(__FUNCTION__, $vars['node']->type, $vars);
	}
	
}

/*
* Preprocess for view
*/
function template_preprocess_views_view(&$vars, $hook)
{
	if (isset($vars['view']))
	{	
		$vars['theme_hook_suggestions'][] = '/templates/views/views_view__'. $vars['view']->name;
		armorall_extend_functionality(__FUNCTION__, $vars['view']->name, $vars);
	}
}