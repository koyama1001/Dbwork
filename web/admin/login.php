<?php

require_once(dirname(__FILE__).'/../config/config.php');
require_once(dirname(__FILE__).'/../functions.php');


session_start();
 if(isset($_SESSION["USER"])&& $_SESSION['USER']['auth_type']==1){
    //HOME画面
    header('Location:/web/admin/user_list.php');
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
  $sql="SELECT id,user_no,name,auth_type FROM user WHERE user_no=:user_no AND password=:password AND auth_type=1 LIMIT 1";
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
    header('Location:/web/admin/user_list.php');
    exit;
  }else{
    $err['password']='認証に失敗しました';
  }
}
}else{
//画面初回アクセス時
$user_no="";
$password="";
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

    <title>Web</title>
  </head>
  <body class="text-center bg-light">
  
  <form class="border rounded bg-white form-login" method="post">

    <h1 class="h3 my-3">Login</h1>

  <div class="form-group pt-3">
    <input type="text" class="form-control rounded-pill <?php if(isset($err['user_no']))echo 'is-invalid';?>" name="user_no" value="<?=$user_no?>" placeholder="社員番号" required>
 <div class="invalid-feedback"><?=$err['user_no']?></div>
 </div>
  <div class="form-group">
    <br><input type="password" class="form-control rounded-pill <?php if(isset($err['password'])) echo 'is-invalid';?>" name="password" placeholder="パスワード">
    <div class="invalid-feedback"><?=$err['password']?></div>
  </div>
  <button type="submit" class="btn btn-primary rounded-pill px-5 my-4">ログイン</button>
</form>
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