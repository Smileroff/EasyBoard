<?php
	$dbname = $modx->db->config['dbase']; 				//имя базы данных
    $dbprefix = $modx->db->config['table_prefix']; 		//префикс таблиц
    $mod_table = $dbprefix."easy_board"; 				//таблица модуля
    $theme = $modx->config['manager_theme']; 			//тема админки
    $basePath = $modx->config['base_path']; 			//путь до сайта на сервере
	if ( !defined("IMAGEPATH") ){
		define (IMAGEPATH, "assets/images/easy_board/"); 	//путь папки с фотографиями
	}
	$imageDir = $modx->config['base_path'].IMAGEPATH; 
	
	#########################################################
	#														#
	# Настройка контекстов									#
	# Например: 											#
	# $contexts = array("catalog" => "", "board" => "");	#
	#														#
	#########################################################
	
	$contexts = array( "main" => "");
	
?>