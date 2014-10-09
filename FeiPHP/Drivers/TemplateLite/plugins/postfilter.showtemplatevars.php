<?php
/*
 * Template Lite plugin converted from Smarty
 * -------------------------------------------------------------
 * File:     postfilter.showtemplatevars.php
 * Type:     postfilter
 * Name:     showtemplatevars
 * Purpose:  Output code that lists all current template vars.
 * -------------------------------------------------------------
 */
 function template_postfilter_showtemplatevars($compiled, &$template_object)
 {
     $compiled = "<pre>\n<?php print_r(\$this->_vars); ?>\n</pre>" . $compiled;
     return $compiled;
 }
?>