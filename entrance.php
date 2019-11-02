<?php

function h($s){
	return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
	}

	session_start();
	//ログイン済みの場合
	if (isset($_SESSION['DISPNAME'])) {
	http_response_code( 301 ) ;
	header( "Location: ./main.php" ) ;
	echo 'ようこそ' .  h($_SESSION['DISPNAME']) . "さん<br>";
	exit;
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
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<title>ログイン</title>
<style type="text/css">

* {
	margin : 0 ;
	padding : 0 ; 
	font-size : 100% ;
}

body {
	font-size : 75% ;
	text-align : center ;
	font-family:"ＭＳ Ｐゴシック", Osaka, "ヒラギノ角ゴ Pro W3" ;
	line-height : 1.4 ;
}

@media screen and (min-width: 479px) { /*ウィンドウ幅が479px以上の場合に適用*/
	#container {
		width : 640px ;
		margin : 15px auto 30px ;
	}
	#container #header {
		width : 640px ;
		height :130px ;
	}
}
@media screen and (max-width: 479px) { /*ウィンドウ幅が最大479pxまでの場合に適用*/
	#container {
		width : 280px ;
		margin : 15px auto 30px ;
	}
	#container #header {
		width : 280px ;
		height :130px ;
	}
}
#container #header a {
	display : block ;
	text-indent : -500em ;
	overflow : hidden ;
	width : 292px ;
	height : 40px ;
	margin : 0 auto ;
}

#container dl#contents {
	height : 115px ;
	margin-bottom : 8px ;
}

dl#contents dt {
	width : 540px ;
	text-align : center ;
	line-height : 30px ;
	height : 30px ;
	font-weight : bold ;
	font-size : 115% ;
}

dl#contents dd {
	text-align : left ; 
	padding : 8px 12px ;
	position : relative ;
}

dl#contents dd #message {
	position : absolute ;
	top : 50px ;
	left : 30px ;
}

address {
	font-style : normal ;
}

section {
  width: 100%;
  height: 100vh;
  position: relative;
  overflow: hidden;
}
 
video {
  position: absolute;
  top: 0;
  left: 0;
  width: auto;
  height: auto;
  min-width: 100%;
  min-height: 100%;
  background: url('/images/bgjpg.jpg') no-repeat;
  background-size: cover;
  z-index: -2;
}
 
.bg-white {
  background: rgba(255, 255, 255, .75);
  position: absolute;
  top:  70px;
  left: 30px;
  padding: 20px;
}

.overlay {
  width: 100%;
  height: 100vh;
  position: absolute;
  top: 0;
  left: 0;
  background-image: linear-gradient(45deg, rgba(0,0,0,.3) 50%,rgba(0,0,0,.7) 50%);
  background-size: 4px 4px;
  z-index: -1;
}

</style>
</head>
<body>
	<section>
		<video src="/images/bgmp4.mp4" playsinline autoplay muted loop></video>
		<div class="overlay"></div>
		<div class="bg-white">
			<div id="base">
				<div id="container">
					<div id="header">
						ようこそデモサイトへ。以下でログインできます。<br />
						■管理者アカウント<br />
						mail「aaa@bbb.cc.dd」<br />
						pass「password1」<br />
						■利用者アカウント<br />
						mail「bbb@bbb.cc.dd」<br />
						pass「password1」<br />
					</div>
					<dl id="contents" style="font-size:100%"> 
						<form  action="login.php" method="post">
							<label for="email">mail</label>
							<input type="email" name="email">
							<br />
							<label for="password">pass</label>
							<input type="password" name="password">
							<br />
							<button type="submit">ログイン</button>
						</form>
						<br />
<!--
						<h1>初めての方はこちら</h1>
						<form action="signup.php" method="post">
							<label for="email">mail</label>
							<input type="email" name="email">
							<br />
							<label for="password">pass</label>
							<input type="password" name="password">
							<br />
							<label for="dispname">名前</label>
							<input type="text" name="dispname">
							<br />
							<button type="submit">新規登録</button>
							<p>※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
						</form>
-->
					</dl>
					<div id="footer">
					</div>
				</div>
			</div>
		</div>
	</section>
</body>
</html>
