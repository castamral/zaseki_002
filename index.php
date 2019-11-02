<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">

<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="copyright" content="Copyright XSERVER Inc." />
<meta name="robots" content="noindex,nofollow,noarchive" />
<title>XFREE サーバー初期ページ</title>
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

#container {
	width : 540px ;
	margin : 15px auto 30px ;
}

#container #header {
	width : 540px ;
	height : 15px ;
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
	height : 108px ;
	margin-bottom : 8px ;
	background : url(default_page.png) left bottom no-repeat ;
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

</style>
</head>
<body>
	<?php
		//クラスの読み込み
		function autoload($className){
		  require './class/'.$className.'.php';
		}
		spl_autoload_register('autoload');
	?>

<div id="base">
	<div id="container">
    	<div id="header">
        	テストページHEAD
        </div>
        
		<dl id="contents"> 
			<dt>
				<p>今日は、<?php echo date("Y/m/d"); ?> です。</p>
			</dt>
			<dd>
				<p>
					<?php 
						$num=0;
						
						while ($num<3){
							print 'num = '.$num.'<br />';
							$num += 1;
						}
					?>
				</p>
				<p>
					<?php 
						$user = new User();
						//$user->setName('やまだ');
						print 'user = '.$user->getName().'さん<br />';

						$pdo1 = ConnDB::getInstance();
						$pdo2 = ConnDB::getInstance();
						print '接続結果 = '.ConnDB::getResult().'<br />';
						if ($pdo1===$pdo2) {
							print 'おなじもの';
						}
					?>
				</p>
				<p>
					<?php 
						$testtbl = new TestTbl();
						print 'クエリ結果<br />';
						$stmt = $testtbl->getList();
						while($row = $stmt->fetch(PDO::FETCH_NUM)) {
							$f001 = htmlspecialchars($row[0]);
							$f002 = htmlspecialchars($row[1]);
							$f003 = htmlspecialchars($row[2]);
							$f004 = htmlspecialchars($row[3]);
							$f005 = htmlspecialchars($row[4]);
							print $f001.",".$f002.",".$f003.",".$f004.",".$f005."<br />";
						}
						//while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						//	$f001 = htmlspecialchars($row["FIELD001"]);
						//	$f002 = htmlspecialchars($row["FIELD002"]);
						//	$f003 = htmlspecialchars($row["FIELD003"]);
						//	$f004 = htmlspecialchars($row["FIELD004"]);
						//	$f005 = htmlspecialchars($row["FIELD005"]);
						//	print $f001.",".$f002.",".$f003.",".$f004.",".$f005."<br />";
						//}
						//$testtbl->updData2('4','1','2','3','4');
					?>
				</p>
			</dd>
		</dl>
		
        <div id="footer">
        	テストページFOOT
        </div>
        
	<!--//container--></div>
<!--//base--></div>

</body>
</html>
