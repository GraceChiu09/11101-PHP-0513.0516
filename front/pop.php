<!-- 十二 建置人氣文章區 -->

<fieldset>
     <!-- 1.左上方路徑導覽列  -->
    <legend>目前位置：首頁 > 人氣文章區</legend>
    <table id="pop">
        <!-- 2. 建置標題 內容 人氣 顯示資料庫的人氣文章-->
        <tr>
            <td width="30%">標題</td>
            <td width="50%">內容</td>
            <td width="20%">人氣</td>
        </tr>
         
        <?php
        $all=$News->math('count','id',['sh'=>1]);
        $div=5;
        $pages=ceil($all/$div);
        $now=$_GET['p']??1;
        $start=($now-1)*$div;

        $rows=$News->all(['sh'=>1]," order by good desc limit $start,$div");

        foreach($rows as $row){
        ?>
        <tr>
            <td class="title clo" style="cursor:pointer"><?=$row['title'];?></td>
            <td class="pop">
                <span class="summary"><?=mb_substr($row['text'],0,20);?>...</span>
                
                <div class="modal">
                    <?=nl2br($row['text']);?>
                </div>
            </td>
            <td>
                <span><?=$row['good'];?></span>個人說<img src="./icon/02B03.jpg" style="width:25px">
                <?php 
                if(isset($_SESSION['user'])){
                    if($Log->math('count','id',['news'=>$row['id'],'user'=>$_SESSION['user']])>0){
                        echo " - <a class='great' href='#' data-id='{$row['id']}'>收回讚</a>";
                    }else{
                        echo " - <a class='great' href='#' data-id='{$row['id']}'>讚</a>";
                    }
                }
                ?>
            </td>
        </tr>
        <?php 
        }

        ?>
    </table>
    <div>
        <!-- 建立分頁導覽功能 -->
        <?php 

        if(($now-1)>0){
            $p=$now-1;
            echo "<a href='?do=news&p={$p}'> &lt; </a>";
        }
        
        for($i=1;$i<=$pages;$i++){
            $fontsize=($now==$i)?'24px':'18px';
            echo "<a href='?do=news&p={$i}' style='font-size:{$fontsize}'> $i </a>";
        }
        if(($now+1)<=$pages){
            $p=$now+1;
            echo "<a href='?do=news&p={$p}'> &gt; </a>";
        }
        
        ?>
    </div>        
    </table>
</fieldset>
<!-- 這是按讚與收回讚的效果 -->
<script>
$(".title,.pop").hover(
    function (){
        $(this).parent().find('.modal').show()
    },
    function (){
        $(this).parent().find('.modal').hide()
    }
)

$(".great").on("click",function(){
    let type=$(this).text()
    let num=parseInt($(this).siblings('span').text())
    let id=$(this).data('id')
    $.post('./api/good.php',{id,type},()=>{
        if(type==='讚'){
            $(this).text('收回讚')
            $(this).siblings('span').text(num+1)
        }else{
            $(this).text('讚')
            $(this).siblings('span').text(num-1)
        }
    })
})

/* $(".title").hover(
    function (){
        $(this).next().children('.modal').show()
    },
    function (){
        $(this).next().children('.modal').hide()
    }
)
$(".pop").hover(
    function (){
        $(this).children('.modal').show()
    },
    function (){
        $(this).children('.modal').hide()
    }
)
 */
</script>