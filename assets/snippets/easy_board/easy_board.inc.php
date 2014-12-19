<?php
	function getParentsArray ($parent, $recursion=0){
		global $modx;
		$parents = array();
		$parents = explode(",", $parent);
		if ($recursion == 1){
			foreach ($parents as $value){
				$parentsChilds = $modx->getChildIds( trim($value) );
					foreach ($parentsChilds as $id){
						$parents[] = $id;
						}
				}
			}
		return $parents;
	}
	function checkRequiredArray ( $required, $fields = array() ){
		$requireds = array();
		$requireds = explode(",", $required);
		$output = array(true, "" );
		$tmp = array();
		foreach ($requireds as $value){
			if ( trim( $fields[trim( $value )] ) == "" ){
				$output[0] = false;
				$tmp[] = trim( $value );
			}
			$output[1] = implode(",", $tmp);
		}
		return $output;
	}
?>