
<?php
$subject=$Que->find($_GET['id']);
$options=$Que->all(['subject_id'=>$_GET['id']]);

?>


<fieldset>
    <!-- 13.1 顯示問卷調查內容 -->
    <legend>目前位置：首頁 > 問卷調查 > <?=$subject['text'];?></legend>

    <h3><?=$subject['text'];?></h3>
    <form action="./api/vote.php" method='post'>
        <?php
            foreach($options as $opt){
                echo "<p>";
                echo "<input type=radio name='opt' value='{$opt['id']}'>";
                echo $opt['text'];
                echo "</p>";
            }
        ?>
        <p class='ct'>
            <input type="submit" value="我要投票">
        </p>
    </form>

</fieldset>