脚本内置了一下方法：
1.exec,shell_exec,system,popen,proc_open,passthru,pcntl_exec
2.php7.4+ ffi特性的利用
3.windows com调用shell.script
4.linux劫持ld_preload(需自己编译evil.c为so，然后上传evil.so并使用$_GET['so_path']指定上传路径
5.CVE-2018-19518 imap_open的利用

除了bypass.php的方法外，还有imagemagic，直接与php-fpm，mod_cgi通信，破壳漏洞可用
bypass.php当使用ld_preload方法时，需要上传evil.so文件后用so_path指定so文件位置

php-fpm通信绕过方法可使用fpm.py利用，示例:python fpm.py 127.0.0.1 -p 9000 /var/www/html/phpinfo.php -c '<?php echo `id`;exit;?>'，(需要服务器把php-fpm端口暴露在外网，一般为9000端口)脚本来源https://gist.github.com/phith0n/9615e2420f31048f7e30f3937356cf75
