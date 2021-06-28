<?php

require_once(dirname(__FILE__).'../config/config.php');
require_once(dirname(__FILE__).'../functions.php');

try{
session_start();
  if(isset($_SESSION["USER"])){
    //HOME画面
    header('Location:index.php');
    exit;
  }

if($_SERVER['REQUEST_METHOD']=='POST'){
//post処理時

//入力値取得
$user_no=$_POST['user_no'];
$password=$_POST['password'];

//echo $user_no.'<br>';
//echo $password;
//exit;


//バリデーションチェック
$err=array();

if(!$user_no){
  $err['user_no']='社員番号を入力してください';
}elseif(!preg_match('/^[0-9]+$/',$user_no)){
  $err['user_no']='社員番号を正しく入力してください';
}elseif(mb_strlen($user_no,'utf-8')>10){
  $err['user_no']='社員番号が長すぎます';
}

if(!$password){
  $err['password']='パスワードを入力してください';
}

if(empty($err)){

  $pdo=connect_db();
  $sql="SELECT user_no,name FROM user WHERE user_no=:user_no AND password=:password LIMIT 1";
  $stmt=$pdo->prepare($sql);
  $stmt->bindValue(':user_no',$user_no,PDO::PARAM_STR);
  $stmt->bindValue(':password',$password,PDO::PARAM_STR);
  $stmt->execute();
  $user=$stmt->fetch();

  //var_dump($user);
  //exit;
  if($user){
    //ログイン処理
    $_SESSION["USER"]= $user;
    //HOME画面
    header('Location:index.php');
    exit;
  }else{
    $err['password']='認証に失敗しました';
  }
}
}else{
//画面初回アクセス時
$user_no="";
$password="";
}catch(Exception $e)
{
  header('Location:error.php')
}
}
?>
<!doctype html>
<html lang="js">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <!-- CSS -->
    <link href="./css/style.css" rel="stylesheet">

    <title>エラー</title>
  </head>
  <body class="text-center bg-light">
  
  <div class="border rounded bg-white form-login" >

    <h1 class="h3 my-3">ERROR</h1>
<p>
システムエラーが発生しました。<br>
お手数ですがシステム管理者にお問い合わせ下さい。</p>

  
</div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>