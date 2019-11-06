<?php
	$CONST_GRP_NO = "99999";

	function h($s){
		return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
	}

	session_start();
	//ログイン済みの場合
	if (isset($_SESSION['DISPNAME'])) {
		$message='ようこそ'.h($_SESSION['DISPNAME'])."さん";
		$admin=h($_SESSION['ADMIN']);
		if ($admin=="1") {
			$mente_disp="block";
		} else {
			$mente_disp="none";
		}
	} else {
		http_response_code( 301 ) ;
		header( "Location: ./entrance.php" ) ;
	}

	//クラスの読み込み
	function autoload($className){
	require './class/'.$className.'.php';
	}
	spl_autoload_register('autoload');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">

<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="copyright" content="Copyright XSERVER Inc." />
<meta name="robots" content="noindex,nofollow,noarchive" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="jquery.pep.js"></script>

<title>スクレイピングのテスト</title>

<script>
$(document).ready(function(){

	
	//座席表画面へ
	$('#main').on('click',function(e){
		location.href = "mente.php";
	});

});
</script>


<style type="text/css">

* {
	margin : 0 ;
	padding : 0 ; 
	font-size : 100% ;
}

@media screen and (min-width: 479px) { /*ウィンドウ幅が479px以上の場合に適用*/
	body {
		font-size : 75% ;
		text-align : left ;
		font-family:"ＭＳ Ｐゴシック", Osaka, "ヒラギノ角ゴ Pro W3" ;
		line-height : 1.4 ;
		background: url('/images/bgcjpg.jpg') no-repeat;
		background-size:100% auto;
	}
}
@media screen and (max-width: 479px) { /*ウィンドウ幅が最大479pxまでの場合に適用*/
	body {
		font-size : 75% ;
		text-align : left ;
		font-family:"ＭＳ Ｐゴシック", Osaka, "ヒラギノ角ゴ Pro W3" ;
		line-height : 1.4 ;
		background: rgba(0, 0, 0, 1.0);
		background-size:100% auto;
	}
}
</style>
</head>
<body>
	<div id="floorarea">
		<div id="main" >
			座席表へ戻る
		</div>
	</div>
	<div class="bg-white">
	</div>
</body>

</html>
