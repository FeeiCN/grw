<?php

class model_type extends FeiModel
{
	var $pk = "typeid";
	var $table = "type";
	var $verifier = array(
		"rules"    => array(
			'typeid' => array(
				'notnull'   => TRUE,
				'maxlength' => 5
			)
		),
		"messages" => array()
	);
}