<?php

/**
 * Functions for PHP
 * 
 * $all_reg = select_reg();
 * for ($i=0; $i<count($all_reg); $i++){
 *   $reg_id_reg[$i] = $all_reg[$i] ["id_reg"];
 *   $reg_name_doc[$i]=$all_reg[$i] ["name_doc"];
 *   $reg_name_org[$i]=$all_reg[$i] ["name_org"];
 *   echo "<p>'$reg_id_reg[$i]' $reg_name_org[$i] $reg_name_doc[$i]</p>";
 *  }
 */
$mysqli = false;

function connectDB()
{
  global $mysqli;
  $mysqli = new mysqli("localhost", "root", "0000", "my_reg");
  $mysqli->query("SET NAMES 'utf8'");
  if (!$mysqli) {
    exit(mysql_error());
  }
}
function closeDB()
{
  global $mysqli;
  $mysqli->close();
}
function fromObjectToArray($res_object)
{
  $res_array = array();
  $i = 0;
  while (($row = $res_object->fetch_assoc()) != false) {
    $res_array[$i] = $row;
    $i++;
  }
  return $res_array;
}

function select_doc()
{
  global $mysqli;
  connectDB();
  $res = $mysqli->query("SELECT id_doc, name_doc FROM doc");
  closeDB();
  return fromObjectToArray($res);
}

function select_org()
{
  global $mysqli;
  connectDB();
  $res = $mysqli->query("SELECT id_org, name_org FROM org");
  closeDB();
  return fromObjectToArray($res);
}

function select_reg()
{
  global $mysqli;
  connectDB();
  $res = $mysqli->query("SELECT reg.id_reg, doc.name_doc, org.name_org
	FROM (reg INNER JOIN doc ON reg.id_doc = doc.id_doc)
	INNER JOIN org ON reg.id_org = org.id_org WHERE reg.id_reg_doc IS NULL");
  closeDB();
  return fromObjectToArray($res);
}

function select_dep()
{
  global $mysqli;
  connectDB();
  $res = $mysqli->query("SELECT id_dep, name_dep FROM dep");
  closeDB();
  return fromObjectToArray($res);
}

function select_tp_reg()
{
  global $mysqli;
  connectDB();
  $res = $mysqli->query("SELECT reg.id_reg, dep.name_dep, dep.FIO
	FROM (tp_reg INNER JOIN dep ON tp_reg.id_dep = dep.id_dep) INNER JOIN reg ON tp_reg.id_reg = reg.id_reg ");
  closeDB();
  return fromObjectToArray($res);
}

function insert_reg($id_docP, $id_orgP)
{
  global $mysqli;
  connectDB();
  $mysqli->query("INSERT INTO reg (date_def, id_doc, id_org) VALUES  (CURDATE(), $id_docP, $id_orgP)");
  closeDB();
}

function insert_tp_reg($id_reg_tp, $id_dep_tp)
{
  global $mysqli;
  connectDB();
  $mysqli->query("INSERT INTO tp_reg (id_reg, id_dep) VALUES  ($id_reg_tp, $id_dep_tp)");
  closeDB();
}

function update_reg_other($param_date, $param_text, $id_reg_tp)
{
  global $mysqli;
  connectDB();
  $mysqli->query("UPDATE reg SET date_reg = $param_date, id_reg_doc = $param_text WHERE id_reg = $id_reg_tp");
  closeDB();
}
