<?php
/**
 * Template Lite plugin converted from Smarty
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty count_sentences modifier plugin
 *
 * Type:     modifier<br>
 * Name:     count_sentences
 * Purpose:  count the number of sentences in a text
 * @link http://smarty.php.net/manual/en/language.modifier.count.paragraphs.php
 *          count_sentences (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return integer
 */
function tpl_modifier_count_sentences($string)
{
    // find periods with a word before but not after.
    return preg_match_all('/[^\s]\.(?!\w)/', $string, $match);
}

?>
