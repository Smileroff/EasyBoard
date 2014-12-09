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
$LoginUserID = $modx->getLoginUserID();

if( isset( $_SESSION['mgrValidated'] ) OR $LoginUserID !== NULL ){


include_once($modx->config['base_path'].'assets/modules/easy_board/easy_board.config.php');
include_once($modx->config['base_path'].'assets/modules/easy_board/easy_board.inc.php');

$masterID = $modx->db->getValue( $modx->db->select( 'createdby', $mod_table, "id=".(int)$_GET[item_id] ) ); 

// Формирование ответа для ajax запросов
if ( isset($_GET[act]) AND ($masterID == $LoginUserID OR isset( $_SESSION['mgrValidated'] ) ) ){
	
	if ( $_GET[act] == "delpic" AND isset( $_GET[item_id] ) ){
		delImage( (int)$_GET[item_id], $mod_table);
		$fields = array('image' => "");
		$query = $modx->db->update($fields, $mod_table, "id = ".(int)$_GET[item_id]);
		die("<input name=\"image\" type=\"file\" />");
	}
	if ( $_GET[act] == "unpub" AND isset( $_GET[item_id] ) ){
		$item_id = (int)$_GET[item_id];
		$fields = array ( 'published' => 0 );
		$query = $modx->db->update($fields, $mod_table, "id = $item_id");
		die('Снято с публикации');
	}
}
} else {
    echo "Not Logged In!";
    exit;
}


?>