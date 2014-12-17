<?php
#####################
# Easy_Board ver 1.02
#####################

if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}
$output = "";
$modx->regClientCSS( $css );
switch ($action) {
case "viewboard":
#############################
# Генерируем список записей
#############################
    $boardPage = ( isset($_GET['boardPage']) ) ? (int)$_GET['boardPage'] : 1;
	$pageLimit = ( $boardPage>0 ) ? ($boardPage-1)*$limit.", {$limit}" : "";
	
	if ( $parent !="" || $city !="" || $user !="" || $published !="") {
		//
		$wheres = array();
		if ($parent !="") {
			$tmpParents = getParentsArray ($parent, $recursion);
			$whereParents = array();
			foreach ($tmpParents as $value){
				$whereParents[] = "$mod_table.parent = $value";
			}
			$wheres[] = "(". implode(" OR ", $whereParents) .")";
			}
		if ( trim($city) != "") $wheres[] = "($mod_table.city = $city or $mod_table.allcity = 1)";
		if ($user !="") $wheres[] = "$mod_table.createdby = $user";
		if ($published !="") $wheres[] = "$mod_table.published = $published";
		$where = "WHERE " . implode(" AND ", $wheres);
		}
	
	// СТАРТ пагинации
	$result = $modx->db->query( "SELECT COUNT(*), sc1.pagetitle as parentname, sc2.pagetitle as cityname, wb.username FROM $mod_table
	LEFT JOIN ".$dbprefix."site_content sc1 ON sc1.id = $mod_table.parent
			LEFT JOIN ".$dbprefix."site_content sc2 ON sc2.id = $mod_table.city
			LEFT JOIN ".$dbprefix."web_users wb ON wb.id = $mod_table.createdby
			$where");
    $stickers = $modx->db->getRow( $result );
    $boardCol = $stickers['COUNT(*)'];
    $boardPages = ceil($boardCol/$limit);

    //$pagination = "<div class=\"eb-paginate\">Страница: $boardPage из $boardPages. Всего объявлений в базе $boardCol <br/><br/><ul>";
	$pagination = "<div class=\"eb-paginate\">";
	$pagination .= str_replace(array("[+boardPage+]", "[+boardPages+]", "[+boardCol+]"), array($boardPage, $boardPages, $boardCol), $_lang['eb_pagination']);
    if ($boardPage > 1) $pagination .= '<li><a href="'.$modx->makeUrl( $modx->documentIdentifier, "", "&boardPage=".($boardPage-1)).'">'.$_lang['eb_paginationprevious'].'</a></li>';
    for ($i = 1; $i <= $boardPages; $i++) 
        if ($i == $boardPage) $pagination .= '<li class="eb-currentPage"><a class="eb-currentPage" href="'.$modx->makeUrl( $modx->documentIdentifier, "", "&boardPage=".$i).'">'.$i.'</a></li>'; 
        else if ( abs($i-$boardPage) < 5 || $i == 1 || $i == $boardPages) $pagination .= '<li><a href="'.$modx->makeUrl( $modx->documentIdentifier, "", "&boardPage=".$i).'">'.$i.'</a></li>';
    if ($boardPage < $boardPages) $pagination .= ' <li><a href="'.$modx->makeUrl( $modx->documentIdentifier, "", "&boardPage=".($boardPage+1)).'">'.$_lang['eb_paginationnext'].'</a></li>';
    $pagination .= "</ul></div><div class=\"eb-clear\"></div>\n";
	$modx->setPlaceholder('eb.pagination', $pagination);
    // ФИНИШ пагинации

	$sql = "SELECT $mod_table.id, $mod_table.pagetitle, $mod_table.content, $mod_table.createdon, $mod_table.price, $mod_table.parent, $mod_table.city, $mod_table.createdby, $mod_table.published, $mod_table.image, $mod_table.hit, sc1.pagetitle as parentname, sc2.pagetitle as cityname, wb.username
			FROM $mod_table
			LEFT JOIN ".$dbprefix."site_content sc1 ON sc1.id = $mod_table.parent
			LEFT JOIN ".$dbprefix."site_content sc2 ON sc2.id = $mod_table.city
			LEFT JOIN ".$dbprefix."web_users wb ON wb.id = $mod_table.createdby
			$where
			ORDER BY $mod_table.createdon DESC
			LIMIT $pageLimit";
	$data_query = $modx->db->query($sql);
	
	$LoginUserID = $modx->getLoginUserID();
	
	if ($jquery == 1) $modx->regClientStartupScript("assets/js/jquery.min.js");
	$modx->regClientStartupScript('<script language="JavaScript" type="text/javascript">
			function ItemAjax(act, id, elementID){
				$("#"+elementID+""+id).load("/assets/snippets/easy_board/easy_board.ajax.php","act="+act+"&item_id="+id);
				}
			</script>');
			
	if ($tplview == "") {
		$template = file_get_contents($snippetPath . "tpl/view.tpl");
		} else $template = $modx->getChunk($tplview);
	while ($data = mysql_fetch_array($data_query)){
		$pl = array(
			"pagetitle" =>$data['pagetitle'],
			"price" =>$data['price'],
			"username" =>$data['username'],
			"cityname" =>$data['cityname'],
			"parentname" =>$data['parentname'],
			"parent" =>$data['parent'],
			"hit" =>$data['hit'],
			"id" =>$data['id'],
			"published" =>$data['published'],
			"date" =>date( "Y.m.d G:i:s", $data['createdon'] ),
			"url" =>$modx->makeUrl( $idviewurl, "", "&eb=".$data['id'] )
			);
		$pl["annotation"] = ( strlen($data['content']) > $annotationlen ) ? mb_substr($data['content'], 0, $annotationlen, "UTF8")."..." : $data['content'];
		
		$data['image'] = ( $data['image'] != "" ) ? $data['image'] : $nophoto;
		$pl["image"] = "<img src=\"".$modx->config['site_url'].$modx->runSnippet('phpthumb', array( 'input' => $data['image'], 'options' => $phpthumboption ))."\"/>";
			
		if ( $LoginUserID == $data['createdby'] OR $_SESSION['mgrRole'] == 1 ){
			$pl['edit'] = "<a href=\"".$modx->makeUrl( $idediturl, "", "&eb=".$data['id'] )."\">$txtedit</a>";
			$pl['delete'] = '<a href="#" onclick="ItemAjax(\'unpub\', \''.$data['id'].'\', \'pub\');return false">'.$txtdelete.'</a>';
			} else {
			$pl['edit'] = "";
			$pl['delete'] = "";
			}
		$output .= $modx->parseText($template, $pl, '[+', '+]' );
	}
	

    break;

case "viewsingle":
	if ( isset($_GET['eb']) ) $id = (int)$_GET['eb']; else die();
	if ($tplviewsingle == "") {
		$template = file_get_contents($snippetPath . "tpl/view.single.tpl");
		} else $template = $modx->getChunk($tplviewsingle);
	
	$sql = "SELECT $mod_table.id, $mod_table.pagetitle, $mod_table.content, $mod_table.contact, $mod_table.createdon, $mod_table.price, $mod_table.parent, $mod_table.city, $mod_table.createdby, $mod_table.published, $mod_table.image, $mod_table.hit, sc1.pagetitle as parentname, sc2.pagetitle as cityname, wb.username
			FROM $mod_table
			LEFT JOIN ".$dbprefix."site_content sc1 ON sc1.id = $mod_table.parent
			LEFT JOIN ".$dbprefix."site_content sc2 ON sc2.id = $mod_table.city
			LEFT JOIN ".$dbprefix."web_users wb ON wb.id = $mod_table.createdby
			WHERE $mod_table.id = $id AND $mod_table.published = 1";
	$data_query = $modx->db->query($sql);
	
	$data = mysql_fetch_array($data_query);
	if ( $data !== false ){
		$pl = array(
			"id" =>$data['id'],
			"pagetitle" =>$data['pagetitle'],
			"content" =>$data['content'],
			"contact" =>$data['contact'],
			"price" =>$data['price'],
			"username" =>$data['username'],
			"cityname" =>$data['cityname'],
			"parentname" =>$data['parentname'],
			"parent" =>$data['parent'],
			"hit" =>$data['hit'],
			"date" =>date( "Y.m.d G:i:s", $data['createdon'] ),
			"url" =>$modx->makeUrl( $idviewurl, "", "&eb=".$data['id'] )
			);
		$pl["annotation"] = ( strlen($data['content']) > $annotationlen ) ? mb_substr($data['content'], 0, $annotationlen, "UTF8")."..." : $data['content'];

		if ( $data['image'] != "" ){
			$pl["image"] = "<img src=\"".$modx->config['site_url'].$modx->runSnippet('phpthumb', array( 'input' => $data['image'], 'options' => $phpthumboptionSingle ))."\"/>";
			} else $pl["image"] = "";
		$output .= $modx->parseText($template, $pl, '[+', '+]') ;
		$modx->setPlaceholder('eb.pagetitle', $data['pagetitle']);
		
		// увеличиваем кол-во просмотров объявления на 1
		$fields['hit'] = $data['hit'] + 1;
		$query = $modx->db->update( $fields, $mod_table, "id = ".$data['id'] );
		
		} else $modx->setPlaceholder('eb.pagetitle', $_lang['eb_notfound']);;
	
    break;
	
case "edit":
	if ( isset($_GET['eb']) ) $id = (int)$_GET['eb']; else die();
	
	$sql = "SELECT $mod_table.id, $mod_table.pagetitle, $mod_table.content, $mod_table.contact, $mod_table.createdon, $mod_table.price, $mod_table.parent, $mod_table.city, $mod_table.allcity, $mod_table.createdby, $mod_table.published, $mod_table.image, $mod_table.hit, sc1.pagetitle as parentname, sc2.pagetitle as cityname, wb.username
			FROM $mod_table
			LEFT JOIN ".$dbprefix."site_content sc1 ON sc1.id = $mod_table.parent
			LEFT JOIN ".$dbprefix."site_content sc2 ON sc2.id = $mod_table.city
			LEFT JOIN ".$dbprefix."web_users wb ON wb.id = $mod_table.createdby
			WHERE $mod_table.id = $id";
	$data_query = $modx->db->query($sql);
	
	$data = mysql_fetch_array($data_query);
	if ( $data !== false ){
		$LoginUserID = $modx->getLoginUserID();
		if ( $LoginUserID == $data['createdby'] OR $_SESSION['mgrRole'] == 1 ){
			if ($_POST['act'] == "edit") { // если отправлена форма, обновление записи
				$id = (int)$_POST['id'];
				$userid = $modx->db->getValue($modx->db->select("createdby", $mod_table, "id = $id", "", ""));
				if ($LoginUserID == $userid OR $_SESSION['mgrRole'] == 1 ){ // немного кривая проверка на права доступа, но должно работать.
					$fields = array(
						'pagetitle'  => $modx->db->escape( $_POST[pagetitle]),  
						'content' => $modx->db->escape( $_POST[content]),  
						'contact'  => $modx->db->escape( $_POST[contact]),  
						'price' => (int)$_POST[price],
						'parent' => (int)$_POST[parent],
						'city' => (int)$_POST[city]
						);
					$fields['published'] = (isset($_POST[published])) ? 1: 0;
					//$fields['allcity'] = (isset($_POST[allcity])) ? 1: 0;
					if ( isset($_FILES['image']) ){
						print_r($_FILES['image']);
						$fields['image'] = loadImage($imageDir, $imagesize);
						}
					$query = $modx->db->update($fields, $mod_table, "id = $id");
					header("Location: http://".$_SERVER['SERVER_NAME'].$modx->makeUrl($idafterediturl));
					} else die($_lang['eb_accessdenied']);
				
				die("");
				}
			
			if ($tpledit == "") {
				$template = file_get_contents($snippetPath . "tpl/edit.tpl");
				} else $template = $modx->getChunk($tpledit);
			
			$pl = array(
				"id" =>$data['id'],
				"pagetitle" =>$data['pagetitle'],
				"content" =>$data['content'],
				"contact" =>$data['contact'],
				"price" =>$data['price'],
				"username" =>$data['username'],
				"cityname" =>$data['cityname'],
				"parentname" =>$data['parentname'],
				"parent" =>$data['parent'],
				"parentIds" =>genOptionList($parentIds, $data['parent']),
				"cityIds" =>genOptionList($cityIds, $data['city'], false),
				"allcity" => genCheckbox($data['allcity']),
				"published" => genCheckbox($data['published']),
				"date" =>date( "Y.m.d G:i:s", $data['createdon'] )
				);
			
			// фотография
			if ($jquery == 1) $modx->regClientStartupScript("assets/js/jquery.min.js");
			$modx->regClientStartupScript('<script language="JavaScript" type="text/javascript">
			function ItemAjax(act, id, elementID){
				$("#"+elementID+""+id).load("/assets/snippets/easy_board/easy_board.ajax.php","act="+act+"&item_id="+id);
				}
			</script>');
			
			$tmp = "<div id=\"picdel$id\">".$_lang['eb_photo'];
			$image = $data['image'];
			if ( $image == "" ) {
				$tmp .= "<input name=\"image\" type=\"file\" />";
			} else if ( is_file($modx->config['base_path'].$image) ){
				$tmp .= "<table class=\"eb-table\" border=\"0\"><tr>";
				$tmp .= "<td><img src=\"".$modx->config['site_url'].$modx->runSnippet('phpthumb', array( 'input' => $image, 'options' => "w=150,h=120,far=R,zc=1,bg=FFFFFF" ))."\"/></td>";
				$img = getimagesize($modx->config['base_path'].$image);
				$tmp .= "<td valign=\"top\">$img[0]px x $img[1]px<br/>".(ceil( filesize( $modx->config['base_path'].$image ) / 1024) )." Kb<br/>";
				$tmp .= '<ul class="actionButtons">
							<li id="Button1" style="margin-top:7px;">
								<a href="#" title="Удалить" onclick="ItemAjax(\'delpic\', \''.$id.'\', \'picdel\');return false">'.$_lang['eb_photodel'].'</a>
							</li>
						</ul>';
				$tmp .= "</td></tr></table>";
				} else $tmp .= $_lang['eb_photonotfound1'].$image.$_lang['eb_photonotfound2']."<br/> <input name=\"image\" type=\"file\" />";
			$tmp .= "</div>";
			$pl['image'] = $tmp;
			
			$output .= $modx->parseText($template, $pl, '[+', '+]') ;
			} else die($_lang['eb_accessdenied']);
	}
		
    break;
	
case "add":
	$LoginUserID = $modx->getLoginUserID();
	if ( $LoginUserID !== NULL ){
		if ( $_POST['act'] == "add" ){
			$fields = array(
						'pagetitle'  => $modx->db->escape( $_POST[pagetitle]),  
						'content' => $modx->db->escape( $_POST[content]),  
						'contact'  => $modx->db->escape( $_POST[contact]),  
						'price' => (int)$_POST[price],
						'parent' => (int)$_POST[parent],
						'city' => (int)$_POST[city],
						'published' => 1,
						'image' =>loadImage($imageDir, $imagesize),
						'createdby' => $LoginUserID,
						'createdon' => time()
						);
			$modx->db->insert( $fields, $mod_table);
			header("Location: http://".$_SERVER['SERVER_NAME'].$modx->makeUrl($idafterediturl));
			die("");
			
			} else {
	
			if ($tpladd == "") {
				$template = file_get_contents($snippetPath . "tpl/add.tpl");
				} else $template = $modx->getChunk($tpladd);
			$pl = array(
				"parentIds" =>genOptionList($parentIds, ""),
				"cityIds" =>genOptionList($cityIds, "", false),
				"image" => $_lang['eb_photoadd']."<input name=\"image\" type=\"file\" />".$_lang['eb_photoaddlimit'].ceil($imagesize/1024)." Kb."
				);
			$output .= $modx->parseText($template, $pl, '[+', '+]') ;
			}
		}
	break;

case "count":
	if ( $parent !="" || $city !="" || $user !="" || $published !="") {
		$wheres = array();
		if ($parent !="") {
			$tmpParents = getParentsArray ($parent, $recursion);
			$whereParents = array();
			foreach ($tmpParents as $value){
				$whereParents[] = "$mod_table.parent = $value";
			}
			$wheres[] = "(". implode(" OR ", $whereParents) .")";
			}
		if ( trim($city) != "") $wheres[] = "($mod_table.city = $city or $mod_table.allcity = 1)";
		if ($user !="") $wheres[] = "$mod_table.createdby = $user";
		if ($published !="") $wheres[] = "$mod_table.published = $published";
		$where = "WHERE " . implode(" AND ", $wheres);
		}
	
	$result = $modx->db->query( "SELECT COUNT(*), sc1.pagetitle as parentname, sc2.pagetitle as cityname, wb.username FROM $mod_table
	LEFT JOIN ".$dbprefix."site_content sc1 ON sc1.id = $mod_table.parent
			LEFT JOIN ".$dbprefix."site_content sc2 ON sc2.id = $mod_table.city
			LEFT JOIN ".$dbprefix."web_users wb ON wb.id = $mod_table.createdby
			$where");
	$output .= $modx->db->getValue($result);
	
	break;

}

?>