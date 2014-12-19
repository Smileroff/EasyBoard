<?php
###############################################
#
# toget - сниппет для получения $_GET параметра
#
# пример вызова:
# [!toget? &name=`search`!]
# вернет значение $_GET[search]
#
###############################################
	
$name = ( isset($name) ) ? $name : "";
	
	if ( $name != "" AND isset($_GET[$name]) ) return $modx->db->escape($_GET[$name]);
?>