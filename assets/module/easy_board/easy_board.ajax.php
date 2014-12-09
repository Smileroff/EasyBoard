<?php

include_once("../../cache/siteManager.php");

require_once '../../../'.MGR_DIR.'/includes/protect.inc.php';
include_once ('../../../'.MGR_DIR.'/includes/config.inc.php');
include_once (MODX_MANAGER_PATH.'includes/document.parser.class.inc.php');
$modx = new DocumentParser;
$modx->loadExtension("ManagerAPI");
$modx->getSettings();

// start session
startCMSSession();

if(!isset($_SESSION['mgrValidated'])){
    echo "Not Logged In!";
    exit;
}

include_once($modx->config['base_path'].'assets/modules/easy_board/easy_board.config.php');
include_once($modx->config['base_path'].'assets/modules/easy_board/easy_board.inc.php');

// Формирование ответа для ajax запросов

if ( isset($_GET[act]) ){
	if ( $_GET[act] == "del" AND isset( $_GET[item_id] ) ){
		$item_id = (int)$_GET[item_id];
		delImage($item_id, $mod_table);
		$modx->db->delete($mod_table, "id = $item_id");
		die("Запись №$item_id удалена");
	}
	if ( $_GET[act] == "pub" AND isset( $_GET[item_id] ) ){
		$item_id = (int)$_GET[item_id];
		$fields = array ( 'published' => 1 );
		$query = $modx->db->update($fields, $mod_table, "id = $item_id");
		die('<a href="#" title="Отменить публикацию" onclick="ItemAjax(\'unpub\', \''.$item_id.'\', \'pub\');return false">
			<img style="margin-top:11px;" src="'.$modx->config['site_url'].'assets/modules/easy_board/images/published1.png" /></a>');
	}
	if ( $_GET[act] == "unpub" AND isset( $_GET[item_id] ) ){
		$item_id = (int)$_GET[item_id];
		$fields = array ( 'published' => 0 );
		$query = $modx->db->update($fields, $mod_table, "id = $item_id");
		die('<a href="#" title="Опубликовать" onclick="ItemAjax(\'pub\', \''.$item_id.'\', \'pub\');return false">
			<img style="margin-top:11px;" src="'.$modx->config['site_url'].'assets/modules/easy_board/images/published0.png" /></a>');
	}
	if ( $_GET[act] == "delpic" AND isset( $_GET[item_id] ) ){
		delImage( (int)$_GET[item_id], $mod_table);
		$fields = array('image' => "");
		$query = $modx->db->update($fields, $mod_table, "id = ".(int)$_GET[item_id]);
		die("<input name=\"image\" type=\"file\" />");
	}
}


?>