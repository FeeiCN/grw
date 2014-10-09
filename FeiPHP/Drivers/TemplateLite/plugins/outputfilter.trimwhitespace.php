<?php
/**
 * Template Lite plugin converted from Smarty
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty trimwhitespace outputfilter plugin
 *
 * File:     outputfilter.trimwhitespace.php<br>
 * Type:     outputfilter<br>
 * Name:     trimwhitespace<br>
 * Date:     Jan 25, 2003<br>
 * Purpose:  trim leading white space and blank lines from
 *           template source after it gets interpreted, cleaning
 *           up code and saving bandwidth. Does not affect
 *           <<PRE>></PRE> and <SCRIPT></SCRIPT> blocks.<br>
 * Install:  Drop into the plugin directory, call
 *           <code>$template_object->load_filter('output','trimwhitespace');</code>
 *           from application.
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @author Contributions from Lars Noschinski <lars@usenet.noschinski.de>
 * @version  1.3
 * @param string
 * @param Smarty
 */
 
function template_outputfilter_trimwhitespace($tpl_source, &$template_object)
{
    // Pull out the script blocks
    preg_match_all("!<script[^>]+>.*?</script>!is", $tpl_source, $match);
    $_script_blocks = $match[0];
    $tpl_source = preg_replace("!<script[^>]+>.*?</script>!is",
                           '@@@TEMPLATELITE:TRIM:SCRIPT@@@', $tpl_source);

    // Pull out the pre blocks
    preg_match_all("!<pre>.*?</pre>!is", $tpl_source, $match);
    $_pre_blocks = $match[0];
    $tpl_source = preg_replace("!<pre>.*?</pre>!is",
                           '@@@TEMPLATELITE:TRIM:PRE@@@', $tpl_source);

    // Pull out the textarea blocks
    preg_match_all("!<textarea[^>]+>.*?</textarea>!is", $tpl_source, $match);
    $_textarea_blocks = $match[0];
    $tpl_source = preg_replace("!<textarea[^>]+>.*?</textarea>!is",
                           '@@@TEMPLATELITE:TRIM:TEXTAREA@@@', $tpl_source);

    // remove all leading spaces, tabs and carriage returns NOT
    // preceeded by a php close tag.
    $tpl_source = trim(preg_replace('/((?<!\?>)\n)[\s]+/m', '\1', $tpl_source));

    // replace script blocks
    template_outputfilter_trimwhitespace_replace("@@@TEMPLATELITE:TRIM:SCRIPT@@@",$_script_blocks, $tpl_source);

    // replace pre blocks
    template_outputfilter_trimwhitespace_replace("@@@TEMPLATELITE:TRIM:PRE@@@",$_pre_blocks, $tpl_source);

    // replace textarea blocks
    template_outputfilter_trimwhitespace_replace("@@@TEMPLATELITE:TRIM:TEXTAREA@@@",$_textarea_blocks, $tpl_source);

    return $tpl_source;
}

function template_outputfilter_trimwhitespace_replace($search_str, $replace, &$subject) {
    $_len = strlen($search_str);
    $_pos = 0;
    for ($_i=0, $_count=count($replace); $_i<$_count; $_i++)
	{
        if (($_pos=strpos($subject, $search_str, $_pos))!==false)
		{
            $subject = substr_replace($subject, $replace[$_i], $_pos, $_len);
		}
        else
		{
            break;
		}
	}
}

?>
