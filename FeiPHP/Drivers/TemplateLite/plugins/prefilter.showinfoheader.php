<?php
/*
 * Template Lite plugin converted from Smarty
 * -------------------------------------------------------------
 * File:     prefilter.showinfoheader.php
 * Type:     prefilter
 * Name:     showinfoheader
 * Version:  1.0
 * Date:     March 14th, 2002
 * Purpose:  Add a header stating smarty version
 *           and current date.
 * Install:  Drop into the plugin directory,
 *           call load_filter('pre','showinfoheader');
 *           from your application.
 * Author:   Monte Ohrt <monte@ohrt.com>
 * -------------------------------------------------------------
 */
 
 function template_prefilter_showinfoheader($tpl_source, &$template_object)
 {
	return '<!-- Template Lite '.$template_object->_version.' '.strftime("%Y-%m-%d %H:%M:%S %Z").' -->'."\n\n".$tpl_source; 
 }
?>