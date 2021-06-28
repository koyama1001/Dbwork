<?php
require_once(dirname(__FILE__).'../config/config.php');
function connect_db()
{
    $param ='mysql:dbname='.DB_NAME.';host='.DB_HOST;
    $pdo=new PDO($param,DB_USER,DB_PASSWORD);
    $pdo->query('SET NAMES utf8;');
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    return $pdo;
}
//日付変換
function time_format_dw($date)
{
$format_date=NULL;
$Week=array('日','月','火','水','木','金','土');

if($date){
    $format_date=date('j('.$Week[date('w',strtotime($date))].')',strtotime($date));
}
return $format_date;

}
?>