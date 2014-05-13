<?php
function get_sql_null ($iteam) {
	return is_null($iteam) ? "NULL" : "'". $iteam. "'";
}

/**
 * [getExtenName description]
 * @param  [type] $fileName [description]
 * @return [type]           [description]
 */
function getExtenName($fileName) {
     $start = strrpos($fileName, ".") +1;
     $length = strlen($fileName);

     return substr($fileName , $start);
 }
?>