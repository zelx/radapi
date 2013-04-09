<?php
include("include/class.testlogin.php");
?>
<?
	$message = "";
	$username = $firstname = $lastname = $mailaddr = $password = $password2 = "";
	foreach($_REQUEST as $key => $value)  {
		$$key = $value;
	}
	$error = array();
	for($i = 0; $i < 20; $i++) {
		$error[$i] = false;
	}
	if(isset($button)) {
		# check firstname
		if(empty($firstname)) {
			$error[0] = true;
		}
		# check lastname
		if(empty($lastname)) {
			$error[1] = true;
		}
		# check mailaddr
		if(empty($mailaddr)) {
			$error[2] = true;
		}
		# check username
		if(empty($username)) {
			$error[3] = true;
		}
		
		if(!$error[3]) {
			# check username duplicate
			$sql = "select * from account where username = '$username'";
			// echo $sql;
			$link->query($sql);
			if($link->num_rows() > 0) {
				$error[4] = true;
			}
		
		}
		
		# check password
		if(empty($password)) {
			$error[5] = true;
		}
		if(!$error[5]) {
			if(strlen($password) < 8) {
				$error[6] = true;
			}
		}
		# check password2
		if(empty($password2)) {
			$error[7] = true;
		}
		if(!$error[7]) {
			if(strlen($password2) < 8) {
				$error[8] = true;
			}
		}

		# check password and confirm password
		if(!$error[5] && !$error[6] && !$error[7] && !$error[8]) {
			if($password != $password2) {
				$error[9] = true;
			}
		}
		$pass = true;
		for($i = 0; $i <= 9; $i++) {
			if($error[$i]) {
				$pass = false;
			}
		}
		if($pass) {
			$sql = "SELECT * FROM configuration where variable = 'default_regis_status'";
			//echo $sql;
			$link->query($sql);
			$conf = $link->getnext();
			//echo $conf->value;
			$sql = "INSERT INTO account VALUES "
			     . "('$username','".substr(md5($password),0,15)."',"
			     . "'$firstname', '$lastname', '$mailaddr',"
			     . "'".date("Y-m-d H:i:s")."', 'md5','".$conf->value."')";
			//echo $sql;
			$link->query($sql);
			
			
	
			$sql = "INSERT INTO radcheck VALUES ('', '$username', 'Password', '==', '".substr(md5($password),0,15)."')";
			mysql_query($sql);
			$sql = "INSERT INTO radusergroup VALUES "
				 . "('$username','".$_REQUEST['selectG']."','1')";
			//echo $sql;
			$link->query($sql);
			if($conf->value) {
				$message = "บันทึกข้อมูลของคุณเรียบร้อยแล้ว คุณสามารถใช้งานระบบได้ทันทีครับ";
			} else {
				$message = "บันทึกข้อมูลของคุณเรียบร้อยแล้ว <br>แต่คุณจะสามารถใช้งานได้ก็ต่อเมื่อได้รับอนุญาตจากผู้ดูแลระบบแล้วเท่านั้น";
			}

	$username = $firstname = $lastname = $mailaddr = $password = $password2 = "";
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
    <link href="css/calendar-mos.css" type=text/css rel=stylesheet>
    <script language="javascript" src="js/calendar.js"></script>
<script>
function stoperror(){
return true
}
window.onerror=stoperror
</script>
	<title>-:- Registrat!on -:-</title>
</head>
<body>
    <div id="content">
    	<a href="register.php">    	</a>
    	<table width="95%" border="0" cellpadding="0" cellspacing="10" class="header">
          <tr>
            <td align="center"><img src="images/BlackNeonAgua_038.png" alt="" width="59" height="60" /></td>
            <td width="94%"><a href="register.php">Regis<span class="gray">trat!on</span></a><a href="index2.php?option=manage_interface"></a><br />
                <span class="normal"><span class="style1">เพิ่มผู้ใช้รายคน</span></span></td>
            <td align="right">&nbsp;</td>
          </tr>
        </table>
   	    <form action="" method="post" name="regis">
    	  <table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr>
                <td colspan="2" align="center"><?php 
	if(!empty($message)) { echo "<BR>".$message; } 
?>&nbsp;</td>
      </tr>
            
          <? if(!empty($message)) {  ?>
              <tr>
                <td colspan="2" align="center"><BR /><input name="" type="button" class="button" value="หน้าแรก" onclick="window.location='?option=register2'" style="cursor:hand" /></td>
              </tr>
            <? } ?>
	<? if(empty($message)) { ?>

            <? if($error[0]) { ?>
              <tr>
                <td width="21%" align="right">&nbsp;</td>
                <td width="79%" class="black">กรุณากรอกชื่อของคุณด้วยครับ</td>
      </tr>
            <? } ?>
              <tr>
                <td align="right">เลือกกลุ่ม : &nbsp;</td>
                <td><select name="selectG" class="inputbox-normal">
				<?
				$sql1 = "select * from groups  order by gdesc"; 
	  			$result1 = mysql_query($sql1);
				while($group1 = mysql_fetch_object($result1)) { 
				?>
                  <option value="<?=$group1->gname?>"><?=$group1->gdesc?></option>
				  <?
				  }
				?>
                </select>
                <span class="black">
                *ระบุกลุ่มให้ถูกต้อง  </span></td>
              </tr>
              <tr>
                <td width="21%" align="right">&#3594;&#3639;&#3656;&#3629; :</td>
              <td width="79%"><label>
                  <input name="firstname" type="text" class="inputbox-normal" id="firstname" style="background: <? if($error[0]) echo "#FFF0F0"; ?>" value="<?= $firstname ?>">
                <span class="black">* นายทองต่อ </span></label></td>
      </tr>
            <? if($error[1]) { ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="red">กรุณากรอกนามสกุลของคุณด้วยครับ</td>
              </tr>
            <? } ?>
              <tr>
                <td align="right">&#3609;&#3634;&#3617;&#3626;&#3585;&#3640;&#3621; :</td>
                <td><label>
                  <input name="lastname" type="text" class="inputbox-normal" id="lastname"  style="background: <? if($error[1]) echo "#FFF0F0"; ?>" value="<?= $lastname ?>">
                 <span class="black">* ศรีสวัสดิ์ </span></label></td>
              </tr>
            <? if($error[2]) { ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="red">กรุณากรอกอีเมล์ของคุณด้วยครับ</td>
              </tr>
            <? } ?>
              <tr>
                <td align="right">&#3629;&#3637;&#3648;&#3617;&#3621;&#3660; :</td>
                <td>
           <input name="mailaddr" type="text" class="inputbox-normal" id="mailaddr" style="width:250px;background:<? if($error[2]) echo "#FFF0F0"; ?>" value="<?= $mailaddr ?>">
                 <span class="black">* tongtoh@hotmail.com</span></td>
              </tr>
            <? if($error[3]) { ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="red">กรุณากรอกชื่อผู้ใช้ที่คุณต้องการด้วยครับ</td>
              </tr>
            <? } ?>
            <? if($error[4]) { ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="red">ชื่อผู้ใช้ที่คุณต้องการมีผู้อื่นใช้แล้ว กรุณากรอกใหม่ด้วยครับ</td>
              </tr>
            <? } ?>
              <tr>
                <td align="right">&#3594;&#3639;&#3656;&#3629;&#3612;&#3641;&#3657;&#3651;&#3594;&#3657; :</td>
                <td><label>
                  <input name="username" type="text" class="inputbox-normal" id="username" style="background: <? if($error[3] || $error[4]) echo "#FFF0F0"; ?>" value="<?= $username ?>">
                 <span class="black">* 08510 รหัสนักเรียน 5 หลัก </span></label></td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
                <td><span class="comment">กรอกเป็นตัวอักษรภาษาอังกฤษและตัวเลขเท่านั้น</span></td>
              </tr>
            <? if($error[5]) { ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="red">กรุณากรอกรหัสผ่านด้วยครับ</td>
              </tr>
	<? } ?>
              
           <? if($error[6]) { ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="red">ความยาวของรหัสผ่านต้องยาวอย่างน้อย 8 อักขระครับ</td>
              </tr>
 			<? } ?>
 
               <tr>
                <td align="right">&#3619;&#3627;&#3633;&#3626;&#3612;&#3656;&#3634;&#3609; :</td>
                <td><label>
                  <input name="password" type="password" class="inputbox-normal" id="password"  style="background: <? if($error[5] || $error[6] || $error[9]) echo "#FFF0F0"; ?>" value="<?= $password ?>">
                 <span class="red">*</span></label></td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="comment">ความยาวอย่างน้อย 8 อักขระ</td>
              </tr>
           <? if($error[7]) { ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="red">กรุณายืนยันรหัสผ่านด้วยครับ</td>
              </tr>
            <? } ?>
           <? if($error[8]) { ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="red">ความยาวของรหัสผ่านต้องยาวอย่างน้อย 8 อักขระครับ</td>
              </tr>
            <? } ?>
           <? if($error[9]) { ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td class="red">รหัสผ่านทั้งสองไม่ตรงกัน</td>
              </tr>
            <? } ?>
              <tr>
                <td align="right">&#3618;&#3639;&#3609;&#3618;&#3633;&#3609;&#3619;&#3627;&#3633;&#3626;&#3612;&#3656;&#3656;&#3634;&#3609; :</td>
                <td><label>
                  <input name="password2" type="password" class="inputbox-normal" id="password2"  style="background: <? if($error[7] || $error[8] || $error[9]) echo "#FFF0F0"; ?>" value="<?= $password2 ?>">
                <span class="red">*</span> </label></td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
                <td><label>
                  <input type="submit" name="button" id="button" class="button" value="ส่งข้อมูล">
                </label></td>
              </tr>
             <? } ?>
              <tr>
                <td align="right">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>   
	</form>
    <div id="footer">
            
    </div>

    </div>
</body>
</html>
