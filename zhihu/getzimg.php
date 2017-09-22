<?php
$url='https://pic1.zhimg.com/'.$_GET['url'];
$co=curl_init($url);
curl_setopt($co,CURLOPT_RETURNTRANSFER,1);
echo curl_exec($co);
?>