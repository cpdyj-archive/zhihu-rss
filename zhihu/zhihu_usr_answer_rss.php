<?php
require('zhihu_rss_config.php');
function get_page_url(){
	//return 'http://127.0.0.1:8080/zhi2/answers.html';
	$url='https://www.zhihu.com/people/'.$_GET['user'].'/answers?order_by=created';
	return $url;
}
function get_page_string(){
	//return file_get_contents('answers.html');
$co=curl_init(get_page_url());
curl_setopt($co,CURLOPT_RETURNTRANSFER,1);
curl_setopt($co,CURLOPT_USERAGENT,get_curl_ua());
$page=curl_exec($co);
curl_close($co);
return $page;
}

$page=get_page_string();
	$q_url=array();
	$q_title=array();
	$q_voteup=array();
	$q_content=array();
	$q_date=array();
	$q_len=0;
	//get page title
	preg_match('#<title>.+?</title>#',$page,$ct);
	$ct=$ct[0];
	//find the question url
	$q_len=preg_match_all('#"question_link".+?<#s',$page,$out);
	$out=$out[0];
	foreach($out as $str){
		preg_match_all('#/question/[0-9]+?/answer/[0-9]+#s',$str,$title);
		array_push($q_url,'https://www.zhihu.com'.$title[0][0]);
	}
	//find the question title
	foreach($out as $ts){
		preg_match_all('#>.+<#s',$ts,$ts);
		$ts=$ts[0][0];
		$ts=trim(substr($ts,1,strlen($ts)-2));
		array_push($q_title,$ts);
	}
	//find the question vote up
	preg_match_all('#zm-item-vote-info.+?>#s',$page,$out);
	foreach($out[0] as $vote){
		preg_match_all('#[0-9]+#',$vote,$a);
		array_push($q_voteup,$a[0][0]);
	}
	//find the question content
	preg_match_all('%textarea hidden class="conte.+?<%s',$page,$out);
	//var_dump($out);
	foreach($out[0] as $cont){
		preg_match('#>.+<#s',$cont,$cont);
		//var_dump($cont);
		$cont=$cont[0];
		$cont=substr($cont,1,strlen($cont)-2);
		array_push($q_content,$cont);
	}
	//find the question date
	preg_match_all('#data-created="1[0-9]+#s',$page,$out);
	foreach($out[0] as $dts){
		preg_match_all('#[0-9]+$#',$dts,$dts);
		array_push($q_date,date('r',$dts[0][0]));
	}
	/*
	var_dump($q_url);
	var_dump($q_title);
	var_dump($q_voteup);
	var_dump($q_content);
	var_dump($q_date);
	*/
	
echo '<?xml version="1.0"?>';
?>
<rss version="2.0">
<channel>
	<link><?php echo get_page_url(); ?></link>
	<title><?php echo $ct; ?></title>
	<description><?php echo $ct; ?></description>
<?php
for($i=0;$i<$q_len;$i++){
?>
	<item>
		<title><?php echo $q_title[$i]; ?></title>
		<link><?php echo $q_url[$i]; ?></link>
		<description><?php echo $q_content[$i]; ?>&lt;br&gt;&lt;br&gt;&lt;p&gt;&lt;bold&gt;<?php echo $q_voteup[$i]; ?>赞同&lt;/bold&gt;&lt;/p&gt;</description>
		<pubDate><?php echo $q_date[$i]; ?></pubDate>
		<guid><?php echo $q_url[$i]; ?></guid>
	</item>
<?php
}
?>
</channel>
</rss>
