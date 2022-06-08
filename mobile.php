<?php
// (A) MOBILE DEVICE CHECK
$isMob = is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
 
// (B) TABLET CHECK
$isTab = is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "tablet"));
 
// (C) DESKTOP?
$isDesktop = !$isMob && !$isTab;

if($isMob || $isTab){
	echo 'https://xray.co.th/mob';
}else{
	echo 'https://xray.co.th/e';
}