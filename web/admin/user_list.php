<?php
require_once(dirname(__FILE__).'/../config/config.php');
require_once(dirname(__FILE__).'/../functions.php'); 

session_start();

if(!isset($_SESSION["USER"]) ||$_SESSION['USER']['auth_type'] !=1){
  //HOMEされてない場合はログイン画面
  header('Location:/web/admin/login.php');
  exit;
}

$pdo=connect_db();

$sql="SELECT* FROM user";
$stmt=$pdo->query($sql);
$user_list=$stmt->fetchAll();

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
    <link href="../css/style.css" rel="stylesheet">

    <title>社員リスト</title>
  </head>
  <body class="text-center bg-light">
  
  <form class="border rounded bg-white form-user-list" action="user_list.php">

    <h1 class="h3 my-3">社員リスト</h1>

<table class="table table-dark">
  <thead>
    <tr class="bg-light">
      <th scope="col">社員番号</th>
      <th scope="col">社員名</th>
      <th scope="col">権限</th>
     
    </tr>
  </thead>
  <tbody>
  <?php foreach($user_list as $user):?>
    <tr>
      <th scope="row"><?=$user['user_no']?></th>
      <td><a href="/web/admin/user_result.php?id=<?=$user['id']?>"><?=$user['name']?></a></td>
      <th scope="row"><?php if($user['auth_type']==1) echo '管理者'?></th>
    </tr>
   <?php endforeach;?>
  </tbody>
</table>
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