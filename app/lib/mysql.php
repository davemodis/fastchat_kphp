<?php
/*
	Author: Andrey Goglev
	VK: https://vk.com/ru151
*/

$inited_mysql = false;
$mysql_iid = 0;
$mysqli = null;
function mysql_query($query, $multi = false){
	global $mysql_iid;

	new_db_decl();

	$res = dbQuery($query);

	if(is_bool($res))
		return $res;

	$mysql_iid = dbInsertedId();

	if($multi){
		$data = array();
		while ($row = dbFetchRow($res)) $data[] = $row;
		return $data;
	}else return dbFetchRow($res);
}

function new_db_decl()
{
	global $mysqli, $inited_mysql;

	if($inited_mysql)
		return;

	$mysqli = new mysqli("localhost", "boxed", "password", "boxed_base");

	if ($mysqli->connect_errno) {
	    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	    die();
	}

	dbQuery("SET NAMES 'utf8'");
	$inited_mysql = true;
}

function dbQuery($query)
{
	global $mysqli;
	return $mysqli->query($query);
}
function dbFetchRow($res)
{
	return $res->fetch_assoc();
}
function dbInsertedId()
{
	global $mysqli;
	return mysqli_insert_id( $mysqli );
}