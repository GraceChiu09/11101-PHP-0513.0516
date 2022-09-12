<!-- 這是九.1 建置忘記密碼頁面 還有尋找 -->
<!-- 九.1內容包含提示文字 請輸入信箱以查詢密碼 輸入信箱的欄位與尋找的按鈕 -->
<fieldset>
    <legend>忘記密碼</legend>
    <div>請輸入信箱以查詢密碼</div>
    <div><input type="text" name="email" id="email"></div>
    <div id="result"></div>
    <div><button onclick="findPw()">尋找</button></div>
</fieldset>
<script>
    function findPw(){
        $.get("./api/find_pw.php",{email:$("#email").val()},(result)=>{
            $("#result").html(result)
        })
    }
</script>