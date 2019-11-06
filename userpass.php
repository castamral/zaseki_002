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

	//フロアーマスタ
	$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_FLOOR";
	$T_99999_M_FLOOR = new $tmpCLASS_NAME;
	$fstmt = $T_99999_M_FLOOR->getList();
	$M_FLOOR_NAME = array();
	$M_FLOOR_WIDTH = array();
	$M_FLOOR_HEIGHT = array();
	$dflt_f_key = "0";
	$dflt_f_width = "1152px";  //"1152px";
	$dflt_f_height = "715px";  //"715px"; 
	$cnt = 0;
	while($frow = $fstmt->fetch(PDO::FETCH_NUM)) {
		$ff001 = htmlspecialchars($frow[0]);
		$ff002 = htmlspecialchars($frow[1]);
		$ff003 = htmlspecialchars($frow[2]);
		$ff004 = htmlspecialchars($frow[3]);
		$ff005 = htmlspecialchars($frow[4]);
		$M_FLOOR_NAME[$ff001] = $ff003;
		$M_FLOOR_WIDTH[$ff001] = $ff004;
		$M_FLOOR_HEIGHT[$ff001] = $ff005;
		if($cnt==0) { 
			$dflt_f_key = $ff001;
			$dflt_f_width = $ff004;
			$dflt_f_height = $ff005;
		}
		$cnt++;
	}
	if(isset($_GET['f_key'])){
		$gff001 = htmlspecialchars($_GET['f_key']);
		$dflt_f_key = $gff001;
		$dflt_f_width = $M_FLOOR_WIDTH[$gff001];
		$dflt_f_height = $M_FLOOR_HEIGHT[$gff001];
	}

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
<meta http-equiv="refresh" content="<?php print $M_COMMON_RELOAD; ?>">
<meta name="copyright" content="Copyright XSERVER Inc." />
<meta name="robots" content="noindex,nofollow,noarchive" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="jquery.pep.js"></script>

<title>ログインパスワード変更</title>

<script>
$(document).ready(function(){

	
	//座席表へ
	$('#main').on('click',function(e){
		var f_key = $("#floor").attr("f_key");
		location.href = "main.php?f_key=" + f_key;
	});

	//更新処理
	$('.info_input_btn').on('click',function(e){
		var u_password1 = $('#u_password1').val();
		var u_password2 = $('#u_password2').val();
		var u_password3 = $('#u_password3').val();
		var u_id = "<?php print $_SESSION['ID']; ?>";
		$.ajax({
			url:'./userpassupdate.php',
			type:'POST',
			data:{
				'u_id'       :u_id,
				'u_password1':u_password1,
				'u_password2':u_password2,
				'u_password3':u_password3,
			}
		})
		// Ajaxリクエストが成功した時発動
		.done( (data) => {
			alert(data);
			location.reload(false);
		})
		// Ajaxリクエストが失敗した時発動
		.fail( (data) => {
			alert('更新が失敗しました。:' + data);
		});
		e.stopPropagation();
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
#headline {
	text-align : left;
	background: rgba(255, 255, 255, 1.0);
	width : 1152px ;
	height: 55px ;
	padding-left:  0;
	padding-right: 0;
	margin: 0 auto;
	position: relative;
}
#logout {
	font-size : 105% ;
	margin-top: 10px;
	margin-left: 10px;
	text-align : left;
	background: rgba(255, 255, 255, 1.0);
	height: 20px ;
	top:  10px;
}
#logo {
	position: absolute;
	right: 0px;
	top: 0px;
	float: right;
	background: url('/images/zasekilogo.png');
	background-size: contain;
	width: 160px;
	height: 40px;
}
#floorarea {
	text-align : left;
	background: rgba(255, 255, 255, 1.0);
	width : 1152px ;
	height: 20px ;
	padding-left:  0;
	padding-right: 0;
	padding-bottom: 5px;
	margin: 0 auto;
}
#main {
	background: rgba(125, 125, 125, 1.0);
	color: white;
	font-size : 120% ;
	margin-right: 10px;
	width : 200px ;
	height : 20px ;
	text-align : center;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	border-radius: 5px;
	position: relative;
	float: right;
	display: none;
}
.bg-white {
	background: url('/images/hogan.png') repeat;
	position: relative;
	padding-left:  0;
	padding-right: 0;
	width : 1152px ;
	margin: 0 auto;
}
#base {
	width : 100% ;
	height : 100% ;
}
#container {
	width : 1152px ;
}
#container dl#contents {
	height : 700px ;
	margin : 0px ;
}
.info_area {
	width : 600px ;
	clear : left ;
}
.info_title {
	background: mediumpurple;
	color: white;
	font-size : 120% ;
	display : block ;
	float : left ;
	width : 200px ;
	height : 25px ;
	margin:  5px auto;
	border-radius: 5px;
	text-align : left;
	padding-left : 10px;
}
.info_input {
	font-size : 120% ;
	width : 200px ;
	height : 20px ;
	margin:  5px auto;
	border-radius: 5px;
}
.info_input_btn {
	font-size : 120% ;
	width : 200px ;
	height : 25px ;
	margin-left : 210px;
	padding-left : 10px;
	border-radius: 5px;
}
</style>
</head>
<body>
	<div id="headline">
		<div id="logout" admin="<?php print $admin; ?>">
			<div><?php	print $message." <a href='/logout.php'>ログアウトはこちら。</a>";	?></div>
		</div>
		<div id="logo"></div>
	</div>
	<div id="floorarea">
		<div id="floor" f_key="<?php print $dflt_f_key; ?>"></div>
		<div id="main" style="display:<?php print $mente_disp; ?>;">
			座席表へ
		</div>
	</div>
	<div class="bg-white">
		<div id="base">
			<div id="container">
				<dl id="contents"> 
					<div class="info_area">
						<label for="password" class="info_title">現在のパスワード</label>
						<input type="password" id="u_password1" name="password" class="info_input" />
					</div>
					<div class="info_area">
						<label for="password2" class="info_title">新しいパスワード</label>
						<input type="password" id="u_password2" name="password2" class="info_input" />
					</div>
					<div class="info_area">
						<label for="password3" class="info_title">新しいパスワード(確認用)</label>
						<input type="password" id="u_password3" name="password3" class="info_input" />
					</div>
					<div class="info_area">
						<p>※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
						<input type="button" class="info_input_btn" value="更新する" />
					</div>
				</dl>
			</div>
		</div>
	</div>
</body>
</html>
