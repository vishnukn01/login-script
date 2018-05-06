<?php
require_once('config.php');

function check_form($post){
	
	$calc_str = sprintf('%s_%s_%s',$post['timestamp'], $post['form_action'], NONCE);
	$calc_hash = hash('sha512',$calc_str);
	
	if($calc_hash == $post['form_hash']){
		return true;
	}
	return false;
	
}

?>