<?php
// PHP如果要啟動session，必須在操作之前以 session_start() 啟動頁面的 session 功能
// 如果不同檔案都要有session()，都必須在程式一開始之前啟動session()
// 啟動 session 後，另一個讀取頁面就可以透過 $_SESSION 這個變數去存取
// 而$_SESSION 本身是一個 array的格式，所以存取方式也就比照 array
// 伺服器會把seesion的資料讀取下來

session_start();
// 設定台灣的時間格式
// 也可以去php.ini的檔案去設定時區
date_default_timezone_set("Asia/Taipei");
// echo date("H:m:s");

// class是一個類別，類別的觀念可以想成物件的設計圖(參考網路大大解釋)
// 這一個class的設計圖內有規劃好這個物件有哪些數值或屬性或有哪些功能函式
// 根據這個設計圖所指定產生的東西就叫做物件
// class內主要就是屬性與函式
// class的可視性(就是函式的存取權限)，關鍵字只有3種public protect private
// 函式如果沒有關鍵字就會被php視為public
// 屬性必須要加關鍵字否則會error
// public可以物件外部存取，protectc或private不可以物件外部存取

// 這裡宣告class叫做DB

class DB{
    // 關鍵字protected $table $dsn $pdo
    // $dsb是資料庫連線 指定資料庫位置名稱
    

   protected $table;
   protected $dsn='mysql:host=localhost;charset=utf8;dbname=db20';
   protected $pdo;

// __construct叫做建構子，會使用在資料庫連線，程式執行的準備工作
// __construct一旦宣告就直接執行，也叫做預載或預設函式功能
// class DB+_construct已經封裝成物件
   function __construct($table)
   {
    // 郭郭: $this->table這個物件裡面的table變數 = new DB()時帶入的參數
    // $this叫偽變數，$this中有個指標，誰呼叫就指誰
    // 邱邱: $this去參照function內的$table

        $this->table = $table;
        $this->pdo = new PDO($this->dsn,'root','');
   }


   /**這些是老師寫的
    * 1.新增資料 insert() insert into table
    * 2.修改資料 update() update table set
    *   -> save()
    * 3.查詢資料 all(),find() select from table
    * 4.刪除資料 del() delete from table 
    * 5.計算 max(),min(),sum(),count(),avg() -> math() select max() from table
    * ($array) //特定欄位條件的多筆資料
    * ($sql)  //只有額外條件的多筆資料...limit $start,$div .... ,order by....,group by......
    * ($array,$sql) //有欄位條件又有額外條件的多筆資料....where  ..... limit ...., ..where ....order by.....
    * ($sql,$sql) //有欄位條件又有額外條件的多筆資料....where  ..... limit ...., ..where ....order by.....
    * ()  //整張資料表的內容
    這些是老師寫的*/

// ...$arg叫做參數拆包 (php 5.6之後才有)
// ...可以將一個陣列參數拆開來並存放到新的陣列之中
// -> 箭頭函式也叫閉包，函數去完成某一些功能，引用一個類別的屬性就可以用->
// -> 去調用某一個參數
// 建立一個function叫做all，使用參數拆包(...$arg)

/**郭郭:
 // 可能有以下幾種模式 1. 沒有參數也就是什麼都不帶入 2.有兩個參數 第一個為陣列 第二個是字串 3.只有一個參數 是一個字串 OR 是一個陣列
 */


   function all(...$arg){
    // $this去參照table
    // 郭郭:從$this->table這張資料表撈出所有欄位
        // $sql="select * from $this->table ";
        // 郭郭的寫法
        $sql="SELECT * FROM `$this->table`";
        // 如果$sql的陣列第一個位置有放..
        // 郭郭:如果第一個變數是陣列
        if(isset($arg[0])){
            if(is_array($arg[0])){
                // 郭:把這個陣列使用迴圈 重複做以下的事
                // foreach當作陣列讀取的方法，取索引$key與取值$val
                // 例如: $scores['john'] = 77；foreach ($scores as $name => $score){echo "";}

                foreach($arg[0] as $key => $val){
                    // 郭: 把陣列的每一個資料都寫成sql需要的語法格式,存在一個臨時的tmp陣列中
                    // 郭: " `資料庫的欄位名稱` = '該欄位的資料' "
                    $tmp[]="`$key`='$val'";
                }
                // 老師: $sql = $sql . " where " . join(" && ",$tmp);
                // 郭: $sql .= "WHERE " . join('AND',$tmp);
                // 郭: 將原本的$sql變數 加上 WHERE 並把$tmp使用join函式把$tmp用AND 串成一串字串
                // 老師: $sql = $sql . " where " . join(" && ",$tmp);
                // sql 語法 join合併查詢
                // 合併查詢就是將儲存在不同資料表的欄位資料，利用資料表之間欄位的關連性來結合多資料表之所需要的資訊

                $sql .= " where " . join(" && ",$tmp);
            }else{
                // 師:$sql=$sql . $arg[0];
                // 郭郭:如果第一個變數不是陣列,所以他會是一個字串
                // 郭郭:將原來的$sql變數加上這個變數
                $sql .= $arg[0];
            }
        }
        // 郭郭:如果$arg有第二個變數 陣列中的第1個位置
        if(isset($arg[1])){
            // 郭郭:因為解題需要 所以預設第一個變數是陣列那麼第二個變數只能是字串
            // 將原來的$sql變數加上這個變數
            $sql .= $arg[1];
        }
        

        // 郭郭:上面的程式都執行完後 ,$this->pdo 這個物件裡的pdo變數 -> query($sql) 去進行資料庫存取的操作(執行sql語句)->
        // -> fetchAll(PDO::FETCH_ASSOC) 回傳給我全部的資料，所有資料會放在一個陣列
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
   }
    // 師:2.修改資料 update() update table set-> save()
    // 於class DB內建立一個fund function
    // 郭郭:找到特定條件的單一筆資料
    // 郭郭:因為解題需要 所以預設$id變數只可能是一個陣列或是一個數字
   function find($arg){
    // 郭郭: 先建立一個變數 $id (老師是$arg) 存放SQL的語法
    // 郭郭: $sql="SELECT * FROM `$this->table`";
    // $sql="select * from $this->table where";
    // 郭郭的寫法
    $sql = "SELECT * FROM `$this->table` ";
        //郭郭: 如果$id這個變數是一個陣列is_array
        if(is_array($arg)){
            // 就執行下面迴圈
            // 郭郭: 重複做
            foreach($arg as $key => $val){
                // 郭郭:把陣列的每一個資料都寫成sql需要的語法格式,存在一個臨時的tmp陣列中
                // 郭郭:" `資料庫的欄位名稱` = '該欄位的資料' "
                $tmp[]="`$key`='$val'";
            }
            //$sql = $sql . " where " . join(" && ",$tmp);
            // $sql .=  join(" && ",$tmp);
            $sql .= " where " . join('AND',$tmp);
        }else{
            // 老師:$sql=$sql . $arg[0];
            // 不是陣列就是數字
            // 郭郭:如果$id這個變數是一個數字 將原本的$sql變數 加上 WHERE id($arg)的欄位資料
            $sql .= "`id`='$arg'";
        }
   
    //  郭郭: 上面的程式都執行完後 , $this->pdo 這個物件裡的pdo變數 -> query($sql)
    // 去進行資料庫存取的操作(執行sql語句)-> fetch(PDO::FETCH_ASSOC) 回傳給我單一筆的資料，資料會放在一個陣列
    //echo $sql;
    return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
   }

    // 郭郭的版本沒有save 那可能是不需要?!
    // 建立一個save的function
    // 變數$arrry(陣列)
   function save($array){
    // 如果能找到資料表'id'
        if(isset($array['id'])){
            // 執行迴圈
            // 有索引與值 存放一個$temp陣列
            foreach($array as $key => $val){
                $tmp[]="`$key`='$val'";
            }
            // sql語法 Update

            // 這句還是有點看不懂...在查?
            $sql="update $this->table set  ".join(',',$tmp)."  where `id`='{$array['id']}'";
        }else{
            // 如果找不到就新增
            // 師: 新增資料 insert() insert into table
            // `資料表 聯合查詢join
            // 這句有不懂?
            $sql="insert into $this->table (`".join("`,`",array_keys($array))."`) 
                                     values('".join("','",$array)."')";
        }
        
        // 郭郭: 上面的程式都執行完後 , $this->pdo 這個物件裡的pdo變數 -> exec($sql) 去資料庫進行操作(執行sql語句) 會回傳給我影響的資料的筆數
        return $this->pdo->exec($sql);
   }
    // 老師: 刪除資料 del() delete from table
    // 建立del function可以刪除資料表的欄位
    // 郭郭: 刪除特定條件的單一筆資料 因為解題需要 所以預設$id變數只可能是一個陣列或是一個數字

    // 把all 或find拿來改
   function del($arg){
    // $sql="delete from $this->table where";
    // 郭郭的寫法
    // 建立$sql的語法刪除單一筆資料
    $sql = "DELETE FROM `$this->table` ";
    // 郭郭: 如果$id($arg)這個變數是一個陣列
        if(is_array($arg)){
            // 是的話就執行迴圈
            foreach($arg as $key => $val){
                $tmp[]="`$key`='$val'";
            }
            //老師: $sql = $sql . " where " . join(" && ",$tmp);
            // 郭郭:將原本的$sql變數 加上 WHERE 並把$tmp使用join函式把$tmp用AND 串成一串字串
            // 資料聯合查詢
            $sql .=  join(" && ",$tmp);
        }else{
            // $sql=$sql . $arg[0];
            // 郭郭:如果$id這個變數是一個數字(邱邱:不是陣列就是變數)
            $sql .= " `id`='$arg'";
        }
   

    //echo $sql;
    // 郭郭: 上面的程式都執行完後 , $this->pdo 這個物件裡的pdo變數 -> exec($sql) 去資料庫進行操作(執行sql語句) 會回傳給我影響的資料的筆數
    return $this->pdo->exec($sql);
   }


   // 老師: 5.計算 max(),min(),sum(),count(),avg() -> math() select max() from table
   // 解題需要的計算
   // 建立math的function   
   // 變數有$math $colum $arg 等不定參數(參數拆包)
   // MATH 是 PHP 數學函數
   function math($math,$col,...$arg){
    // 建立sql語法

    // $sql="select $math($col) from $this->table ";
    // 郭郭寫法
    $sql = "SELECT $math($col) FROM `$this->table` ";
    // 如果有找到
    if(isset($arg[0])){
        // 有找到而且是陣列位置1
        if(is_array($arg[0])){
            // 就執行迴圈 放在空陣列
            foreach($arg[0] as $key => $val){
                $tmp[]="`$key`='$val'";
            }
            //$sql = $sql . " where " . join(" && ",$tmp);
            
            $sql .= " where " . join(" && ",$tmp);
        }else{
            // $sql=$sql . $arg[0];
            // 郭郭:如果$id($arg)這個變數是一個數字
            $sql .= $arg[0];
        }
    }
    // 如果$arg有第二個變數 陣列中的第1個位置 
    if(isset($arg[1])){
        // 郭郭: 將原來的$sql變數加上這個變數
        $sql .= $arg[1];
    }

    //echo $sql;
    // 回傳單筆資料
    // 老師: return $this->pdo->query($sql)->fetchColumn();
    // 郭郭直接回傳全部 
    // return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return $this->pdo->query($sql)->fetchColumn();

   }


   // 郭郭:$sql為我們自訂的SQL語法 如果上面的function都不適用時才會用到 目前第一題第二題都用不到 可以省略沒關係
   // 意似是沒有資料 可以試著叫q    
   function q($sql){
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
   }

}

// --------------------------------郭郭:物件導向結束了喔
// 不要包錯了喔


// 郭郭: 自定義的函式 叫dd 用來查看$array陣列中存放了些甚麼
function dd($array){
// 郭郭:html中的標籤 用意是顯示資料原本的格式
echo "<pre>";
// 把陣列叫出來
print_r($array);
// 郭郭:標籤是成對的 務必記得加上一個結尾的標籤
echo "</pre>";
}

// 導向其他頁面 簡寫
// 郭郭:$url這個變數要帶入的是檔案的路徑或是網址
function to($url){
    header('location:'.$url);
}

// 在物件中的函式「無法」被外部的程式碼直接呼叫
// 實體化物件 php就用new
$Total=new DB('total');
$User=new DB('user');
$News=new DB('news');
$Que=new DB('que');
$Log=new DB('log');

// 郭郭沒有這段
// 檢查session是否有設置
// 應該是瀏覽人次
// 如果沒有設置session[total]
if(!isset($_SESSION['total'])){
    // 先讓$Total去計算
    $chkDate=$Total->math('count','id',['date'=>date("Y-m-d")]);
    // 如果$chkDate是大於等於1
    if($chkDate>=1){
        // 就讓$Total先執行find
        $total=$Total->find(['date'=>date("Y-m-d")]);
        // 資料表`total`內的'total'加1
        $total['total']=$total['total']+1;
        // 執行save function
        $Total->save($total);
        // ?!不太確定session又等於1?
        $_SESSION['total']=1;
    }else{
        // 沒有新增瀏覽人次
        // 就保持一樣
        $Total->save(['date'=>date("Y-m-d"),'total'=>1]);
        $_SESSION['total']=1;
    }
}


?>