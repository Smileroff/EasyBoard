<?php
		
	function fParseCSV($f, $length, $d=";", $q='"') {
        $list = array();
        $st = fgets($f, $length);
        if ($st === false || $st === null) return $st;
        while ($st !== "" && $st !== false) {
            if ($st[0] !== $q) {
                # Non-quoted.
                list ($field) = explode($d, $st, 2);
                $st = substr($st, strlen($field)+strlen($d));
            } else {
                # Quoted field.
                $st = substr($st, 1);
                $field = "";
                while (1) {
                    # Find until finishing quote (EXCLUDING) or eol (including)
                    preg_match("/^((?:[^$q]+|$q$q)*)/sx", $st, $p);
                    $part = $p[1];
                    $partlen = strlen($part);
                    $st = substr($st, strlen($p[0]));
                    $field .= str_replace($q.$q, $q, $part);
                    if (strlen($st) && $st[0] === $q) {
                        # Found finishing quote.
                        list ($dummy) = explode($d, $st, 2);
                        $st = substr($st, strlen($dummy)+strlen($d));
                        break;
                    } else {
                        # No finishing quote - newline.
                        $st = fgets($f, $length);
                    }
                }
 
            }
            $list[] = $field;
        }
        return $list;
    }
	
	
	echo "<p>Импорт начат</p>";
	$csv = array();
	$handle = fopen($_FILES['csv']['tmp_name'], "r");
	$csvFieldName = fParseCSV($handle, 1024); // получаем имена колонок
	$csvLine = array();
	$i = 1;
	while (!feof($handle)) {
		$csvLine = fParseCSV($handle, 1024);
		if ( is_array($csvLine) ){
			foreach ( $csvLine as $key=>$value){
				$fields[$i][$csvFieldName[$key]] = $modx->db->escape( $value );
			}
			if ( isset($fields[$i]['createdon']) ) $fields[$i]['createdon'] = strtotime( $fields[$i]['createdon'] );
			if ( isset($fields[$i]['parent']) )	$fields[$i]['parent'] = searchCategory($fields[$i]['parent'], getListChildsOrParent($categoryID) );
			if ( isset($fields[$i]['city']) )	$fields[$i]['city'] = searchCategory($fields[$i]['city'], getListChildsOrParent($cityID) );
			if ( !isset($fields[$i]['published']) AND isset( $_POST['published'] )) $fields[$i]['published'] = 1;
			$fields[$i][createdby] = $defaultuser;
		$modx->db->insert( $fields[$i], $mod_table);	
		}
		$i++;
		}
	fclose($handle);
	echo "<p>Парсинг файла завершен.</p>";
	echo "<p>Записи добавлены в БД.</p>";
	echo '<a href="#" onclick="postForm(\'reload\',null);return false;">Назад</a>';

?>