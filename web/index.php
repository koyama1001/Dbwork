<?php

require_once(dirname(__FILE__).'../config/config.php');
require_once(dirname(__FILE__).'../functions.php');
//ログイン状態をチェック
session_start();

  if(!isset($_SESSION["USER"])){
    //ログインされていない場合はHOME画面
    header('Location:login.php');
    exit;
  }
$session_user=$_SESSION["USER"];

//var_dump($session_user);
//exit;

//ログイン
$pdo=connect_db();
$target_date=date('Y-m-d');
if($_SERVER['REQUEST_METHOD']=='POST')
{
  //日報登録

  //入力値をPOSTから取得
  $target_date=$_POST['target_date'];
  $modal_start_time=$_POST['modal_start_time'];
  $modal_break_time=$_POST['modal_break_time'];
  $modal_end_time=$_POST['modal_end_time'];

  //データベースにデータがあるかどうか
  $sql="SELECT id FROM work WHERE user_id=:user_id AND date=:date LIMIT 1";
  $stmt=$pdo->prepare($sql);
  $stmt->bindValue(':user_id',(int)$session_user,PDO::PARAM_INT);
  $stmt->bindValue(':date',$target_date,PDO::PARAM_STR);
  $stmt->execute();
  $work=$stmt->fetch();

  if($work)
  {
    $sql="UPDATE work SET date=:date,start_time = :start_time, end_time = :end_time, break_time = :break_time WHERE id = :id" ;
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':id',(int)$work,PDO::PARAM_INT);
    $stmt->bindValue(':date',$target_date,PDO::PARAM_STR);
    $stmt->bindValue(':start_time',$modal_start_time,PDO::PARAM_STR);
    $stmt->bindValue(':end_time',$modal_end_time,PDO::PARAM_STR);
    $stmt->bindValue(':break_time',$modal_break_time,PDO::PARAM_STR);
    $stmt->execute();
  }else{
    $sql="INSERT INTO work (user_id,date,start_time, end_time, break_time) VALUES (:user_id,:date,:start_time,:end_time,:break_time)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':user_id',(int)$session_user,PDO::PARAM_INT);
    $stmt->bindValue(':date',$target_date,PDO::PARAM_STR);
    $stmt->bindValue(':start_time',$modal_start_time,PDO::PARAM_STR);
    $stmt->bindValue(':end_time',$modal_end_time,PDO::PARAM_STR);
    $stmt->bindValue(':break_time',$modal_break_time,PDO::PARAM_STR);
    $stmt->execute();
  }

}
//ユーザーの業務日報データを取得
if(isset($_GET['m'])){
  $yyyymm = $_GET['m'];
  $day_count=date('t',strtotime($yyyymm));
}else{
  $yyyymm=date('Y-m');
  $day_count=date('t');
}

//ログインユーザーの情報をセッションから取得
//sql指定
$sql="SELECT date,id,start_time,end_time,break_time FROM work WHERE user_id = :user_id AND DATE_FORMAT(date,'%Y-%m')=:date";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(':user_id',(int)$session_user, PDO::PARAM_INT);
$stmt->bindValue(':date', $yyyymm, PDO::PARAM_STR);
$stmt->execute();
$work_list = $stmt->fetchAll(PDO::FETCH_UNIQUE);


//データベースに当日のデータがあるかどうか
$sql="SELECT id,start_time,end_time,break_time FROM work WHERE user_id=:user_id AND date=:date LIMIT 1";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(':user_id',(int)$session_user,PDO::PARAM_INT);
$stmt->bindValue(':date',date('Y-m-d'),PDO::PARAM_STR);
$stmt->execute();
$today_work=$stmt->fetch();


//echo'<pre>';
//var_dump($work_list);
//exit;


//モーダルの自動表示

if($today_work){
//時間デフォルト値指定
$modal_start_time=$today_work['start_time'];
$modal_end_time=$today_work['end_time'];
$modal_break_time=$today_work['break_time'];

}else{
//時間デフォルト値指定
$modal_start_time=''; 
$modal_end_time='';
$modal_break_time='01:00';
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

    <title>月別リスト</title>
  </head>
  <body class="text-center bg-light">
  
  <form class="border rounded bg-white form-time-table" action="index.php">

    <h1 class="h3 my-3">月別リスト</h1>

<select class="form-control rounded-pill mb-3" name="m" onchange="submit(this.form)">
    <option value="<?=date('Y-m')?>"><?=date('Y/m')?></option>

    <?php for($i = 1; $i < 12; $i++):?>
    <?php $target_yyyymm = strtotime("-{$i}months"); ?>
    <option value="<?= date('Y-m',$target_yyyymm)?>" <?php if($yyyymm == date('Y-m',$target_yyyymm))echo 'selected'?>>
    <?=date('Y/m',$target_yyyymm) ?></option>
    <?php endfor ?>
</select>

<table class="table table-dark">
  <thead>
    <tr class="bg-light">
      <th class="fix-col">日</th>
      <th class="fix-col">出勤</th>
      <th class="fix-col">退勤</th>
      <th class="fix-col">休憩</th>
      <th class="fix-col"></th>
     
    </tr>
  </thead>
  <tbody>
  <?php for($i=1; $i<=$day_count; $i++): ?>
  <?php

  $start_time='';
  $end_time='';
  $break_time='';

if(isset($work_list[date('Y-m-d',strtotime($yyyymm.'-'.$i))])){

  $work=$work_list[date('Y-m-d',strtotime($yyyymm.'-'.$i))];
  
  if($work['start_time']){
    $start_time=date('H:i',strtotime($work['start_time']));
  }
  
  if($end_time=$work['end_time']){
    $end_time=date('H:i',strtotime($work['end_time']));
  }
  
  if($break_time=$work['break_time']){
    $break_time=date('H:i',strtotime($work['break_time']));
  }
}
?>
    <tr>
      <th scope="row"><?=time_format_dw($yyyymm.'-'.$i)?></th>
      <td><?=$start_time ?></td>
      <td><?=$end_time ?></td>
      <td><?=$break_time ?></td>
      <td>
      <button type="button" class="btn btn-primary h-auto py-0"  data-bs-toggle="modal"
      data-bs-target="#inputModal" data-day= " <?=$yyyymm.'-'.sprintf('%02d', $i) ?> " >編集</button>
      </td>
    </tr>
   <?php endfor; ?>
  </tbody>
</table>
</form>
<!-- Modal -->
<form method="POST">
<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      
        <h5 class="modal-title" id="exampleModalLabel">出勤登録</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="container">
      <div class="alert alert-primary" role="alert">
      <?= date('n').'/'.time_format_dw(date('Y-m-d')) ?></span>
</div>
  <div class="row">
    <div class="col-sm">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="出勤" id="modal_start_time" name="modal_start_time" value="<?= $modal_start_time ?>">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-primary" id="start_btn">打刻</button>
     </div>
    </div>
    </div>
    <div class="col-sm">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="退勤" id="modal_end_time" name="modal_end_time"value="<?= $modal_end_time ?>">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-primary" id="end_btn">打刻</button>
     </div>
    </div>
    </div>
    <div class="col-sm">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="休憩"id="modal_break_time" name="modal_break_time"value="<?= $modal_break_time ?>">
    </div>
    </div>
  </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary text-white rounded-pill px-5">登録</button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="target_date" name="target_date">
</from>
    <!-- Optional JavaScript; choose one of the two! -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script>

$('#start_btn').click(function()
{
  const now=new Date();
  const hour=now.getHours().toString().padStart(2,'0');
  const minute=now.getMinutes().toString().padStart(2,'0');
  $('#modal_start_time').val(hour+':'+minute);
  console.log(hour+':'+minute);
})
$('#end_btn').click(function()
{
  const now=new Date();
  const hour=now.getHours().toString().padStart(2,'0');
  const minute=now.getMinutes().toString().padStart(2,'0');
  $('#modal_end_time').val(hour+':'+minute);
  console.log(hour+':'+minute);
})

$('#inputModal').on('show.bs.modal',function(event){
  var button=$(event.relatedTarget)
  var target_day=button.data('day')

  //編集ボタンが押された時対称日のデータを取得
  var day=button.closest('tr').children('th')[0].innerText
  var start_time=button.closest('tr').children('td')[0].innerText
  var end_time=button.closest('tr').children('td')[1].innerText
  var break_time=button.closest('tr').children('td')[2].innerText

  //取得したデータをモーダルに確定
  $('#modal_day').text(day)
  $('#modal_start_time').val(start_time)
  $('#modal_end_time').val(end_time)
  $('#modal_break_time').val(break_time)
  $('#target_date').val(target_day)
 
  console.log(day)
})
</script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>