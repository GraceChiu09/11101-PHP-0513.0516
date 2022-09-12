<!-- 確認帳號密碼 -->
<?php
include_once "../base.php";

//$acc=$_POST['acc'];
//echo $User->math('count','id',['acc'=>$acc]);
$acc=$_POST['acc']??$_GET['acc'];
echo $User->math('count','id',['acc'=>$acc]);

?>