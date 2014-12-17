<?php
#######################
# Easy Board v 1.02
#######################
	include_once($modx->config['base_path'].'assets/modules/easy_board/easy_board.config.php');
	
	include_once($modx->config['base_path'].'assets/modules/easy_board/easy_board.inc.php');
	
	$template = file_get_contents($basePath."assets/modules/easy_board/tpl/head.tpl");
	$template = str_replace("[+theme+]", $theme, $template);
	$template = str_replace("[+site_url+]", $modx->config['site_url'], $template);
	echo $template;
     
    $action = isset($_POST['action']) ? $_POST['action']:'';
     
    switch($action) {
     
    //Установка модуля (создание таблицы в БД)
    case 'install':
    $sql = "CREATE TABLE IF NOT EXISTS `$mod_table` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent` int(10) NOT NULL,
  `city` int(10) NOT NULL,
  `allcity` int(1) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `pagetitle` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `contact` varchar(255) NOT NULL,
  `price` int(10) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '0',
  `createdby` int(10) NOT NULL DEFAULT '0',
  `createdon` int(20) NOT NULL DEFAULT '0',
  `hit` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $modx->db->query($sql);
	if ( !is_dir($imageDir) ) mkdir($imageDir, 0777);
	chmod($imageDir, 0777);
    header("Location: $_SERVER[REQUEST_URI]");
    break;
     
    //Удаление таблицы модуля
    case "uninstall":
    $sql = "DROP TABLE $mod_table";
    $modx->db->query($sql);
    header("Location: $_SERVER[REQUEST_URI]");
    break;
     
    /********************************************************
	*                   Добавление записи                   *
	********************************************************/
    case 'add':
    if (!empty($_POST['item_id'])){//редактирование записи
    $id = (int)$_POST['item_id'];
	$data = mysql_fetch_array($modx->db->select("*", $mod_table, "id = $id", "", ""));
    $pagetitle = $data['pagetitle'];
    $content = $data['content'];
	$createdby = $data['createdby'];
	$published = $data['published'];
	$parent = $data['parent'];
	$contact = $data['contact'];
	$price = $data['price'];
	$city = $data['city'];
	$allcity = $data['allcity'];
	$hit = $data['hit'];
	$image = $data['image'];
    $save = "update";
    }else{//если запись новая
    $id = '';
    $pagetitle = '';
    $content = '';
	$createdby = $defaultuser;
	$published = 0;
	$parent = 0;
	$contact = "";
	$price = "";
	$city = 0;
	$allcity = 0;
	$hit = 0;
	$image = "";
    $save = "save";
    }
    
	$template = file_get_contents($basePath."assets/modules/easy_board/tpl/add.tpl");
	$template = str_replace("[+id+]", $id, $template);
	$template = str_replace("[+pagetitle+]", $pagetitle, $template);
	$template = str_replace("[+content+]", $content, $template);
	$template = str_replace("[+createdby+]", $createdby, $template);
	$template = str_replace("[+contact+]", $contact, $template);
	$template = str_replace("[+price+]", $price, $template);
	$template = str_replace("[+hit+]", $hit, $template);
	$template = str_replace("[+parent+]", genOptionList($categoryID, $parent), $template);
	$template = str_replace("[+city+]", genOptionList($cityID, $city, false), $template);
	$template = str_replace("[+published+]", genCheckbox($published), $template);
	$template = str_replace("[+allcity+]", genCheckbox($allcity), $template);
	$template = str_replace("[+image+]", genImageForm($image, $id), $template);
	
	echo $template;
	
	echo '
    <button class="styler" href="#" onclick="postForm(\''.$save.'\',\''.$id.'\');return false;">Сохранить</button>
    &nbsp;&nbsp;
    <a href="#" onclick="postForm(\'reload\',null);return false;">Отмена</a>
    ';
    break;
     
    //Сохранение записи в БД
    case 'save':
	$fields = array();
	$fields = array('pagetitle'  => $modx->db->escape( $_POST[pagetitle]),  
                    'content' => $modx->db->escape( $_POST[content]),  
                    'contact'  => $modx->db->escape( $_POST[contact]),  
                    'price' => (int)$_POST[price],
					'parent' => (int)$_POST[parent],
					'city' => (int)$_POST[city],
					'hit' => (int)$_POST[hit],					
					'createdby' => (int)$_POST[createdby],
					'image' => loadImage($imageDir),
					'createdon' => time()
                    );
	$fields['published'] = (isset($_POST[published])) ? 1: 0;
	$fields['allcity'] = (isset($_POST[allcity])) ? 1: 0;
	$modx->db->insert( $fields, $mod_table);
    header("Location: $_SERVER[REQUEST_URI]");
    break;
     
    //Обновление записи в БД
    case 'update':
    $fields = array('pagetitle'  => $modx->db->escape( $_POST[pagetitle]),  
                    'content' => $modx->db->escape( $_POST[content]),  
                    'contact'  => $modx->db->escape( $_POST[contact]),  
                    'price' => (int)$_POST[price],
					'parent' => (int)$_POST[parent],
					'city' => (int)$_POST[city],
					'hit' => (int)$_POST[hit],
					'createdby' => (int)$_POST[createdby]
                    );
	$fields['published'] = (isset($_POST[published])) ? 1: 0;
	$fields['allcity'] = (isset($_POST[allcity])) ? 1: 0;
	if ( isset($_FILES['image']) ) $fields['image'] = loadImage($imageDir);
    $query = $modx->db->update($fields, $mod_table, "id = ".(int)$_POST['item_id']."");
    header("Location: $_SERVER[REQUEST_URI]");
    break;
     
     
    //Перезагрузка страницы (сброс $_POST)
    case 'reload':
    header("Location: $_SERVER[REQUEST_URI]");
    break;
     
    //Удаление записи в БД
    case 'delete':
    $modx->db->delete($mod_table, "id = ".(int)$_POST[item_id]);
    header("Location: $_SERVER[REQUEST_URI]");
    break;
     
	//Импорт из csv
    case 'importcsv':
	
	$template = file_get_contents($basePath."assets/modules/easy_board/tpl/importcsv.tpl");
	echo $template;
	
	break;
	
	case 'importcsvgo':
	
	echo "<h1>Импорт из файла csv начат - ".$_FILES['csv']['name'].".</h1>";
	if ( isset($_FILES['csv']) ){
		if (substr($_FILES['csv']['name'], -3) === "csv"){
			include_once($modx->config['base_path'].'assets/modules/easy_board/easy_board.import.php');
		} else {
			echo "<p>Неверный формат файла</p>";
		}
	}
	
	break;
	
    //Страница модуля
    default:
    if (mysql_num_rows(mysql_query("show tables from $dbname like '$mod_table'"))==0){
    //если таблицы не существует, выводим кнопку "Установить модуль"
     
    echo '<a href="#" onclick="postForm(\'install\',null);return false;">Установить модуль</a>';
     
    }else{
    //если же модуль уже установлен, выводим его
	echo '<br/><br/><ul class="actionButtons">
				<li><a href="#" onclick="postForm(\'add\',null);return false;"><img src="media/style/MODxRE/images/icons/page_white_add.png" align="absmiddle"> Добавить объявление</a></li>
				<li><a href="#" onclick="postForm(\'importcsv\',null);return false;"> Импорт из csv</a></li>
	</ul>';
	
	// Обрабатываем параметры фильтрации
	$filtertext = ( isset($_POST[filtertext]) ) ? $modx->db->escape( $_POST[filtertext] ) : "";
	$filter = ( isset($_POST[filter]) ) ? (int)$_POST[filter] : "";
	$filters = array("","","","","","","","");
	$filters[$filter-1] = " selected";
	$where = "";
	if ( $filtertext != ""){
		switch ($filter) {
			case 1:
				$where = "WHERE $mod_table.pagetitle LIKE '%$filtertext%'";
				break;
			case 2:
				$where = "WHERE $mod_table.content LIKE '%$filtertext%'";
				break;
			case 3:
				$where = "WHERE username LIKE '%$filtertext%'";
			break;
			case 4:
				$where = "WHERE $mod_table.createdby = $filtertext";
			break;
			case 5:
				$where = "WHERE sc2.pagetitle LIKE '%$filtertext%'";
			break;
			case 6:
				$where = "WHERE $mod_table.city = $filtertext";
			break;
			case 7:
				$where = "WHERE sc1.pagetitle LIKE '%$filtertext%'";
			break;
			case 8:
				$where = "WHERE $mod_table.parent = $filtertext";
			break;
		}
	}
	echo '
		<input class="styler" type="text" name="filtertext" style="width:200px" value="'.$filtertext.'" placeholder="Текст для фильтрации" />
		<select name="filter">
			<option value=1'.$filters[0].'>по заголовку</option>
			<option value=2'.$filters[1].'>по описанию</option>
			<option value=3'.$filters[2].'>по имени автора</option>	
			<option value=4'.$filters[3].'>по ID автора</option>
			<option value=5'.$filters[4].'>по городу</option>	
			<option value=6'.$filters[5].'>по ID города</option>	
			<option value=7'.$filters[6].'>по рубрике</option>	
			<option value=8'.$filters[7].'>по ID рубрики</option>			
		</select>
		<button class="styler" href="#" onclick="postFormPag(\'1\');return false;">Фильтровать</button>
    </form>';
    

	/*********
	Пагинация
	*********/
	
    $boardPage = ( isset($_POST['boardPage']) ) ? (int)$_POST['boardPage'] : 1;
    $pageLimit = ( $boardPage>0 ) ? ($boardPage-1)*$col.", {$col}" : "";
	
    $result = $modx->db->query( "SELECT COUNT(*), sc1.pagetitle as parentname, sc2.pagetitle as cityname, wb.username FROM $mod_table
	LEFT JOIN ".$dbprefix."site_content sc1 ON sc1.id = $mod_table.parent
			LEFT JOIN ".$dbprefix."site_content sc2 ON sc2.id = $mod_table.city
			LEFT JOIN ".$dbprefix."web_users wb ON wb.id = $mod_table.createdby
			$where");
    $stickers = $modx->db->getRow( $result );
    $boardCol = $stickers['COUNT(*)'];
    $boardPages = ceil($boardCol/$col);
       
	$sql = "SELECT $mod_table.id, $mod_table.pagetitle, $mod_table.createdon, $mod_table.price, $mod_table.parent, $mod_table.city, $mod_table.createdby, $mod_table.published, $mod_table.image, sc1.pagetitle as parentname, sc2.pagetitle as cityname, wb.username
			FROM $mod_table
			LEFT JOIN ".$dbprefix."site_content sc1 ON sc1.id = $mod_table.parent
			LEFT JOIN ".$dbprefix."site_content sc2 ON sc2.id = $mod_table.city
			LEFT JOIN ".$dbprefix."web_users wb ON wb.id = $mod_table.createdby
			$where
			ORDER BY $mod_table.createdon DESC
			LIMIT $pageLimit";
	$data_query = $modx->db->query($sql);
	
	// СТАРТ Вывод номеров страниц
        echo "<div id=\"pagination\" class=\"paginate\">Страница: $boardPage из $boardPages. Всего объявлений в базе $boardCol <br/><br/><ul>";
        if ($boardPage > 1) echo '<li><a href="#" onclick="postFormPag(\''.($boardPage-1).'\');return false;"><< предыдущая</a></li>';
        for ($i = 1; $i <= $boardPages; $i++) 
            if ($i == $boardPage) echo '<li class="currentPage"><a class="currentPage" href="#" onclick="postFormPag(\''.$i.'\');return false;">'.$i.'</a></li>'; 
            else echo '<li><a href="#" onclick="postFormPag(\''.$i.'\');return false;">'.$i.'</a></li>';
        if ($boardPage < $boardPages) echo ' <li><a href="#" onclick="postFormPag(\''.($boardPage+1).'\');return false;">Следующая >></a></li>';
        echo "</ul></div>\n";
    // ФИНИШ Вывод номеров страниц
	
    $template = file_get_contents($basePath."assets/modules/easy_board/tpl/table.head.tpl");
	echo $template;
    
	$tmp = $template = file_get_contents($basePath."assets/modules/easy_board/tpl/table.row.tpl");
    while ($data = mysql_fetch_array($data_query)){//выводим записи
		$template = $tmp;
		$template = str_replace("[+id+]", $data[id], $template);
		$template = str_replace("[+pagetitle+]", $data['pagetitle'], $template);
		$template = str_replace("[+createdon+]", date("Y.m.d G:i:s", $data[createdon]), $template);
		$template = str_replace("[+price+]", $data[price], $template);
		$template = str_replace("[+parent+]", $data[parentname]." (".$data[parent].")", $template);
		$template = str_replace("[+city+]", $data[cityname]." (".$data[city].")", $template);
		$template = str_replace("[+createdby+]", $data[username]." (".$data[createdby].")", $template);
		$template = str_replace("[+theme+]", $theme, $template);
		$data[published] = ( $data[published] == 1 ) ? 
		'<a href="#" title="Отменить публикацию" onclick="ItemAjax(\'unpub\', \''.$data[id].'\', \'pub\');return false">
			<img style="margin-top:11px;" src="'.$modx->config['site_url'].'assets/modules/easy_board/images/published1.png" /></a>' : 
		'<a href="#" title="Опубликовать" onclick="ItemAjax(\'pub\', \''.$data[id].'\', \'pub\');return false">
			<img style="margin-top:11px;" src="'.$modx->config['site_url'].'assets/modules/easy_board/images/published0.png" /></a>';
		$template = str_replace("[+published+]", $data[published], $template);
		$data[image] = ( $data[image] != "" ) ? '<img src="'.$modx->config['site_url'].'assets/modules/easy_board/images/image.png" />' : '';
		$template = str_replace("[+image+]", $data[image], $template);
	echo $template;
    }
     
    echo '
    </tbody>
    </table>';
	
	// СТАРТ Вывод номеров страниц
        echo "<div id=\"pagination\" class=\"paginate\"><ul>";
        if ($boardPage > 1) echo '<li><a href="#" onclick="postFormPag(\''.($boardPage-1).'\');return false;"><< предыдущая</a></li>';
        for ($i = 1; $i <= $boardPages; $i++) 
            if ($i == $boardPage) echo '<li class="currentPage"><a class="currentPage" href="#" onclick="postFormPag(\''.$i.'\');return false;">'.$i.'</a></li>'; 
            else echo '<li><a href="#" onclick="postFormPag(\''.$i.'\');return false;">'.$i.'</a></li>';
        if ($boardPage < $boardPages) echo ' <li><a href="#" onclick="postFormPag(\''.($boardPage+1).'\');return false;">Следующая >></a></li>';
        echo "</ul></div>\n";
    // ФИНИШ Вывод номеров страниц
	
	echo '
    <br /><br />
    <a href="#" onclick="if(confirm(\'Вы уверены? Удаление всех записей не обратимо!\')){postForm(\'uninstall\',null)};return false;"><img src="media/style/'.$theme.'/images/icons/delete.gif" align="absmiddle" />Удалить модуль</a>
    ';
	
     
    }
    break;
    }
     
    echo '
    </div>
    </body>
    </html>
    ';
?>