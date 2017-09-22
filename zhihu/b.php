<?php
$pageurl='https://zhuanlan.zhihu.com/'.$_GET['user'];
$ua='Mozilla/5.0 (Linux; Android 5.0.2) AppleWebKit/537 (KHTML, like Gecko) Chrome/59 Mobile Safari/537';
$co=curl_init($pageurl);
curl_setopt($co,CURLOPT_RETURNTRANSFER,1);
curl_setopt($co,CURLOPT_USERAGENT,$ua);
$page=curl_exec($co);
curl_close($co);
preg_match('#<title>.+?</title>#',$page,$ct);
$ct=$ct[0];
$ct=substr($ct,7,strlen($ct)-15);
preg_match('#<textarea id="preloadedSta.+?</textarea>#',$page,$jsonsrc);
preg_match('#>.+<#',$jsonsrc[0],$jsonsrc);
$jsonsrc=$jsonsrc[0];
$jsonsrc=substr($jsonsrc,1,strlen($jsonsrc)-2);
$json=json_decode($jsonsrc,true);
//echo var_dump($json['database']['Post']);
$posts=$json['database']['Post'];

//fix img elements
foreach($posts as &$post){
	//Fix img elements.
	$post['content']=preg_replace('#data-rawheight="[0-9]+?">#','/>',$post['content']);
	//echo $post['content'];
	$post['content']=preg_replace('#<img src="#','<img src="zhihu/getzimg.php?url=',$post['content']);
	$post['content']=preg_replace(array('#<#','#>#'),array('&lt;','&gt;'),$post['content']);
	
	//change update time to timestamp.
	$post['updated']=strtotime($post['updated']);
}

//sort array order by updated.
usort($posts,"cmptime");
function cmptime($a,$b){
	return $b['updated']-$a['updated'];
}

echo '<?xml version="1.0"?>';
?>
<rss version="2.0">
<channel>
	<link><?php echo $url; ?></link>
	<title><?php echo $ct; ?></title>
	<description><?php echo $ct; ?></description>
<?php
foreach($posts as $post){
?>
	<item>
		<title><?php echo $post['title']; ?></title>
		<link><?php echo 'https://zhuanlan.zhihu.com'.$post['url']; ?></link>
		<description><?php echo $post['content']; ?></description>
		<pubDate><?php echo date('r',$post['updated']); ?></pubDate>
		<guid><?php echo $post['url']; ?></guid>
	</item>
<?php
}
?>
</channel>
</rss>
