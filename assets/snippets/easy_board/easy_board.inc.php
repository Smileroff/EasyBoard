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

?>