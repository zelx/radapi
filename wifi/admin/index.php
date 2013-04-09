<?php
	include("include/class.mysqldb.php");
	include("include/config.inc.php");
	$username = "";
	$password = "";
	if(!isset($_SESSION['logined'])) {
		$classtext = array("", "");
		$classbox = array("noborder2", "noborder2");
		$message = "ท่านผู้ดูแลระบบสามารถล็อกอินได้ที่นี่";
		if(isset($_REQUEST['action'])) {
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			if(empty($_REQUEST['username']) && empty($_REQUEST['password'])) {
				$message = "<span class=\"red\">กรุณากรอกชื่อผู้ใช้และัรหัสผ่านของท่านด้วย</span>";
			} else if(empty($_REQUEST['username']) && !empty($_REQUEST['password'])) {
				$message = "<span class=\"red\">กรุณากรอกชื่อผู้ใช้ของท่านด้วย</span>";
			} else if(!empty($_REQUEST['username']) && empty($_REQUEST['password'])) {
				$message = "<span class=\"red\">กรุณากรอกรหัสผ่านของท่านด้วย</span>";
			} else {
				$sql = "select * from administrator where username = '".$_REQUEST['username']."' and password = '".md5($_REQUEST['password'])."'";
				$result = $link->query($sql);
				if($link->num_rows() == 0) {
					$message = "<span class=\"red\">ข้อมูลของท่านไม่ถูกต้อง กรุณาตรวจสอบข้อมูลด้วย</span>";
				} else {
					$data = mysql_fetch_object($result);
					$_SESSION['logined'] = true;
					$_SESSION['username'] = $_REQUEST['username'];
					$_SESSION['name'] = $data->name;
					$sql = "update administrator set lastlogin = '".date("Y-m-d H:i:s")."' where username = '".$_REQUEST['username']."'";
					$link->query($sql);
					?><meta http-equiv="refresh" content="0;url=index2.php"><?
					exit(0);
				}
			}
		} 
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Burapha Linux Laboratory" />
	<meta name="keywords" content="authentication system" />
	<meta name="description" content="Burapha Linux Authentication Project" />	
    <link href="css/main.css" type=text/css rel=stylesheet>
	<title>-:- Authent!cation -:-</title>
</head>
<body>
<div id="maincontent">
	<div id="loginform">
		<h2><a href="index2.php">Authen<span class="gray">t!cation</span> For <span class="gray">School</span></a></h2>
  <form name="login" id="login" method="post" action="">
			<?php echo $message; ?>
			<p>
				ชื่อผู้ใช้ :
				<input type="text" name="username" id="username" class="<?php echo $classbox[0]; ?>"  value="<?php echo $username; ?>"  onclick="this.value=''" /><br />
				รหัสผ่าน : 
				<input type="password" name="password" id="password" class="<?php echo $classbox[1]; ?>"  value="<?php echo $password; ?>"  onclick="this.value=''" /><br />
				<input type="hidden" name="action" id="action" value="login"> 
                <input name="button" type="submit" class="button" id="button" value="เข้าสู่ระบบ"   />
                <input name="button2" type="button" class="button" id="button2" value="ยกเลิก" onClick="window.location='index.php'" /><br />
			</p>
		</form>
		<div style="line-height: 18px">
            <br />
        ปรับปรุงเพิ่มเติม: <a href="http://bls.buu.ac.th/">ศูนย์พัฒนากลุ่มการงานอาชีพและเทคโนโลยี</a> จังหวัดจันทบุรี สพม.17<br />
			ออกแบบและพัฒนาระบบ: <a href="http://bls.buu.ac.th/">ห้องปฏิบัติการวิจัยลีนุกซ์</a>
		</div>
	</div>
</div>
</body>
</html>
<?php
	} else {
		?><meta http-equiv="refresh" content="0;url=index2.php"><?
	}
?>