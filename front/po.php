<!-- 這是七.4 點選分類網誌 列表後顯示相對應的內容文章 -->
<style>
    .type{
        cursor: pointer;
        color:blue;
        margin:1rem 0;
        max-width: max-content;
    }
    .type:hover{
        border-bottom:1px solid blue;
    }
</style>



<div>目前位置：首頁 > 分類網誌 > <span id="header">健康新知</span></div>
<div style="display:flex">
    <fieldset style="width:20%">
        <legend>分類網誌</legend>
        <div class="type">健康新知</div>
        <div class="type">菸害防制</div>
        <div class="type">癌症防治</div>
        <div class="type">慢性病防治</div>
    </fieldset>
    <fieldset style="width:80%">
        <legend>文章列表</legend>
        <div id='content'></div>
    </fieldset>
</div>

<script>

getList('健康新知');

$(".type").on("click",function(){
    let type=$(this).text()
    $("#header").text(type)
    getList(type);
})

function getList(type){
    $.get("./api/get_list.php",{type},(list)=>{
        $("#content").html(list)
    })
}

function getNews(id){
    $.get("./api/get_news.php",{id},(news)=>{
        $("#content").html(news)
    })
}
</script>