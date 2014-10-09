<?php
/*
 * Author: Mark Dickenson, akapanamajack@wildmail.com
 * You can stack multiple template display commands to have the entire page output as a compressed file.
 *
 * This output filter was specifically written to work with Alien Assault Traders but can be used on other projects.
 *
 * $send_now = 0 will cache the output and not send the data until $send_now = 1
 * $_tpl_saved is a reserved variable for storing the cached output
 * $force_compression = 1 will cause all output to be compressed and ignore what the browser or server indicates to gzip support
 * $compression_level is the amount of compression to use on the output 0 is the leasat and 9 is maximum
 * $template_object->enable_gzip = 0 output is not compressed $template_object->enable_gzip = 1 output is compressed
 */

function template_outputfilter_gzip($tpl_source, &$template_object)
{
	static $_tpl_saved = '';

	$gzipped = 0;
	if($template_object->enable_gzip)
	{
		if(extension_loaded("zlib") && !get_cfg_var('zlib.output_compression') && !$template_object->cache && (strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip") || $template_object->force_compression))
		{
			$_tpl_saved .= $tpl_source . "\n<!-- zlib compression level " . $template_object->compression_level . " -->\n\n";
			$tpl_source = "";

			if($template_object->send_now == 1)
			{
				$gzipped = 1;
				$tpl_source = gzencode($_tpl_saved, $template_object->compression_level);
				$_tpl_saved = "";
			}
		}
	}
	else
	{
		if(!$template_object->caching && !get_cfg_var('zlib.output_compression'))
		{
			$_tpl_saved .= $tpl_source."\n<!-- normal saved output -->\n\n";
			$tpl_source = "";

			if($template_object->send_now == 1)
			{
				$tpl_source = $_tpl_saved;
				$_tpl_saved = "";
			}
		}
	}

	if($template_object->send_now == 1 && $template_object->enable_gzip == 1)
	{
		if($gzipped == 1)
		{
			header("Content-Encoding: gzip");
			header("Content-Length: " . strlen($tpl_source));
		}
	}

	return $tpl_source;
}
?>