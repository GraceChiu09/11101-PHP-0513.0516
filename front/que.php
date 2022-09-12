<!-- 這是問卷調查api -->
<?php

include_once "../base.php";

if(!empty($_POST['subject'])){

    $Que->save(['text'=>$_POST['subject'],'count'=>0,'subject_id'=>0]);
    
    $subject_id=$Que->find(['text'=>$_POST['subject']])['id'];

    if(!empty($_POST['option'])){
        foreach($_POST['option'] as $opt){
            $Que->save(['text'=>$opt,'count'=>0,'subject_id'=>$subject_id]);
        }
    }

}


to("../back.php?do=que");