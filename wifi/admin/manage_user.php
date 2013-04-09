<?php
include("include/class.testlogin.php");
?>
<?php
	// print_r($_REQUEST);
	$newup = $newdown = 0;
	$newexpire = "0000-00-00";
	if(isset($_REQUEST['action'])) { 
		$sql = "select * from groups where gid = '".$_REQUEST['gid']."'"; 
		$result = mysql_query($sql);
		$group = mysql_fetch_object($result);

		switch($_REQUEST['action']) {
			case 'lock' : 
				$sql = "update account set status = '0' where username = '".$_REQUEST['user']."'";
				mysql_query($sql);
				$message = "<font color=green>ล็อกผู้ใช้ที่ต้องการเรียบร้อยแล้ว</font>";
				break;
			case 'lockSel' : 
			 for($i=0;$i<count($_POST["chk"]);$i++)  {              
              if($_POST["chk"][$i] != "") {              				
              				$sql = "update account set status = '0' where username = '".$_POST["chk"][$i]."'";
       //       				echo $sql."<br>";              				              			
              				mysql_query($sql);
              }
         } 
  			$message = "<font color=green>ล็อกผู้ใช้ที่ต้องการเรียบร้อยแล้ว</font>";
        break;
      case 'unlockSel' : 
			 for($i=0;$i<count($_POST["chk"]);$i++)  {              
              if($_POST["chk"][$i] != "") {              				
              				$sql = "update account set status = '1' where username = '".$_POST["chk"][$i]."'";
       //       				echo $sql."<br>";              				              			
              				mysql_query($sql);
              }
         } 
  			$message = "<font color=green>ปลดล็อกผู้ใช้ที่ต้องการเรียบร้อยแล้ว</font>";
        break;
			case 'unlock' : 
				$sql = "update account set status = '1' where username = '".$_REQUEST['user']."'";
				mysql_query($sql);
				$message = "<font color=green>ปลดล็อกผู้ใช้ที่ต้องการเรียบร้อยแล้ว</font>";
				break;
			case 'delSel' :			 
        for($i=0;$i<count($_POST["chk"]);$i++)  {              
              if($_POST["chk"][$i] != "") {              				
              				$sql = "DELETE FROM radcheck where username = '".$_POST["chk"][$i]."'";
              				mysql_query($sql);
              				$sql = "DELETE FROM radusergroup where username = '".$_POST["chk"][$i]."'";
              				mysql_query($sql);
              				$sql = "DELETE FROM account where username = '".$_POST["chk"][$i]."'";
              				mysql_query($sql);
              				$sql = "update account set status = '-1' where username = '".$_POST["chk"][$i]."'";
              				mysql_query($sql);              				
              }
         } 
  			$message = "<font color=green>ลบผู้ใช้ที่ต้องการออกจากระบบเรียบร้อยแล้ว</font>";
				break;
			case 'delete' : 			    
				$sql = "DELETE FROM radcheck where username = '".$_REQUEST['user']."'";
				mysql_query($sql);
				$sql = "DELETE FROM radusergroup where username = '".$_REQUEST['user']."'";
				mysql_query($sql);
				$sql = "DELETE FROM account where username = '".$_REQUEST['user']."'";
        mysql_query($sql);
				$sql = "update account set status = '-1' where username = '".$_REQUEST['user']."'";
				mysql_query($sql);
				$message = "<font color=green>ลบผู้ใช้ที่ต้องการออกจากระบบเรียบร้อยแล้ว</font>";
				break;
			case 'move' :
				$sql = "update radusergroup set groupname = '".$_REQUEST['group']."' where username = '".$_REQUEST['user']."'";
			//	echo $sql;
				mysql_query($sql);
				$message = "<font color=green>ย้ายกลุ่มเรียบร้อยแล้ว</font>";
				break;				
			case 'moveSel' :			 
        for($i=0;$i<count($_POST["chk"]);$i++)  {              
              if($_POST["chk"][$i] != "") {
              				$sql = "update radusergroup set groupname = '".$_REQUEST['group']."' where username = '".$_POST["chk"][$i]."'";
       //       				echo $sql."<br>";              				              			
              				mysql_query($sql);
              }
         } 
  			$message = "<font color=green>ย้ายกลุ่มผู้ใช้ที่เลือกเรียบร้อยแล้ว</font>";
				break;
			case 'edit' :
				break;
			case 'success' :
				 $message = "<font color=green>บันทึกข้อมูลการแก้ไขเรียบร้อยแล้ว</font>";
				break;
			case 'saveadd' :
				$error = 0;
				$newup = $_REQUEST['newgroupupload'];
				$newdown = $_REQUEST['newgroupdownload'];
				$newexpire = $_REQUEST['newgroupexpire'];
				if(trim($_REQUEST['newgroupdesc']) == '') {
					$error = 1;
					$message = "<span class=\"alert\">กรุณากรอกชื่อกลุ่มด้วย</span>";
				} else {
					$sql = "select * from groups where gdesc = '".trim($_REQUEST['newgroupdesc'])."'";
					if(mysql_num_rows(mysql_query($sql))) {
						$message = "<span class=\"alert\">ชื่อกลุ่ม '".trim($_REQUEST['newgroupdesc'])."' ซ้ำ กรุณาเปลี่ยนชื่อกลุ่มใหม่</span>";
						$error = 1;
					} else {
						if($newdown != 0) {
							$down = $newdown * 1024;
							$sql = "insert into radgroupreply values ('', 'group".$_REQUEST['newgname']."', 'WISPr-Bandwidth-Max-Down', ':=', '$down')";
							mysql_query($sql);
						}
						if($newup != 0) {
							$upload = $newup * 1024;
							$sql = "insert into radgroupreply values ('', group'".$_REQUEST['newgname']."', 'WISPr-Bandwidth-Max-Up', ':=', '$upload')";
							mysql_query($sql);
						}
						$sql = "insert into groups values('','group".$_REQUEST['newgname']."','".$_REQUEST['newgroupdesc']."', '$newup', '$newdown', '0', '$newexpire', 'md5', '0')";
					//	echo $sql;
						mysql_query($sql);
						
							$message = "<font color=green>บันทึกข้อมูลกลุ่มใหม่เรียบร้อยแล้ว</font>";
						}
				}
				break;
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
<div id="content">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="10"  class="header">
  <tr>
    <td width="6%" align="center"><img src="images/BlackNeonAgua_246.png" alt="" width="59" height="60" /></td>
    <td width="46%"><a href="index2.php?option=manage_user">User Manager</a><br />
<span class="normal">จัดการข้อมูลผู้ใช้งานระบบ</span></td>
    <td width="48%" align="right" valign="bottom">
      <? $sql = "select * from groups";
		$result = mysql_query($sql);
		$num = mysql_num_rows($result);
	?>
    
		<span class="normal">        <? if(!isset($_REQUEST['group'])) { ?>
        กรุณาเลือกกลุ่ม
        <? } else { ?>
        <? 
			$sql = "select * from groups where gname = '".$_REQUEST['group']."'";
			$result2 = mysql_query($sql);
			$data2 = mysql_fetch_object($result2);
			echo  "กลุ่ม" .   $data2->gdesc . "";
		?>
        <? } ?>
&nbsp;</span><img src="images/b_ar.gif" align="absbottom"  onClick="showhide(1);event.cancelBubble=1" style="cursor:hand" />
		<div onmouseover="showhide(2);" onmouseout="showhide(0)" id="innermenu" style="position:absolute; width:300px; height:<?= $num * 25 ?>px;background-color:white; visibility:hidden; text-align:left; border: 1px #ddd dashed; padding: 10px 10px 10px 10px; font-weight: normal" class="normal">
        <script language="JavaScript1.2">
		
		
		function gl(linkname,dest){
		document.write('<li><a href="'+dest+'">'+linkname+'</a></li>')
		}
		
		function showhide(state){
		var cacheobj=document.getElementById("innermenu").style
		if (state==0)
		cacheobj.visibility="hidden"
		else if(state==2) 
		cacheobj.visibility="visible"
		else
		cacheobj.visibility=cacheobj.visibility=="hidden"? "visible" : "hidden"
		}
		
		//Specify your links here- gl(Item text, Item URL)
		<? while($groups = mysql_fetch_object($result)) { ?>
		gl("กลุ่ม<?= $groups->gdesc ?>","index2.php?option=manage_user&group=<?= $groups->gname ?>")
		<? } ?>

		//Extend this list as needed
		
		function ClickCheckAll(vol)
	{
	
		var i=1;
		for(i=1;i<=document.groupform.hdnCount.value;i++)
		{
			if(vol.checked == true)
			{
				eval("document.groupform.chk"+i+".checked=true");
			}
			else
			{
				eval("document.groupform.chk"+i+".checked=false");
			}
		}
	}

		document.onclick=function(){showhide(0)}
		
</script>
</div>
    </td>
  </tr>
</table>
<form action="" method="post" id="groupform" name="groupform">
<? if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit" || $_REQUEST['action'] == "save" )) { 
				 $message = "กรุณากรอกข้อมูลในช่องที่ท่านต้องการแก้ไขแล้วคลิกบันทึก<BR>";
	$sql = "SELECT * FROM account where username = '".$_REQUEST['user']."'";
			//echo $sql;
	$link->query($sql);
	$users = $link->getnext();
	
		foreach($_REQUEST as $key => $value) {
			$$key = $value;
			//echo $key . " => " . $value . "<BR>";
		}
	if($_REQUEST['action'] == "save") {
		$error = array();
		for($i = 0; $i < 20; $i++) {
			$error[$i] = false;
		}
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
			if($user != $username) {
				$sql = "select * from account where username = '$username'";
				// echo $sql;
				$link->query($sql);
				if($link->num_rows() > 0) {
					$error[4] = true;
				}
			}
		}
		
		# check password

		# check password and confirm password
			if($password != $password2) {
				$error[9] = true;
			}
		$pass = true;
		for($i = 0; $i <= 9; $i++) {
			if($error[$i]) {
				$pass = false;
			}
		}
		if($pass) {
			if(!empty($password)) {
				switch($users->encryption) {
					case 'md5' : $newpass = substr(md5($password),0,15); break;
					case 'crypt' : $newpass = crypt($password,"BL"); break;
					default : $newpass = $password; break;
				}
				$sql = "update account set username = '$username', password = '$newpass', firstname = '$firstname', lastname = '$lastname', mailaddr = '$mailaddr' where username = '$users->username'";
				mysql_query($sql);
				$sql = "update radcheck set username = '$username', value = '$newpass' where username = '$users->username'";
				mysql_query($sql);
			} else {
				$sql = "update account set username = '$username', firstname = '$firstname', lastname = '$lastname', mailaddr = '$mailaddr' where username = '$users->username'";
				mysql_query($sql);
				$sql = "update radcheck set username = '$username' where username = '$users->username'";
				mysql_query($sql);
			}
			$sql = "update radusergroup set username = '$username' where username = '$users->username'";
			mysql_query($sql);
			$sql = "update radacct set username = '$username' where username = '$users->username'";
			mysql_query($sql);
			 echo "<script>window.location='index2.php?option=manage_user&action=success&group=".$_REQUEST['group']."';</script>";
		}
	} else {
		$firstname = $users->firstname;
		$lastname = $users->lastname;
		$mailaddr = $users->mailaddr;
		$username = $users->username;
		
	}
	?>
  <table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr>
                <td colspan="2" align="center"><?php 
	if(!empty($message)) { echo "<BR>".$message; } 
?>&nbsp;</td>
      </tr>
            
        

            <? if($error[0]) { ?>
              <tr>
                <td width="32%" align="right">&nbsp;</td>
                <td width="68%" class="red">กรุณากรอกชื่อของคุณด้วยครับ</td>
      </tr>
            <? } ?>
              <tr>
                <td width="32%" align="right">&#3594;&#3639;&#3656;&#3629; :</td>
          <td width="68%"><label>
                  <input name="firstname" type="text" class="inputbox-normal" id="firstname" style="background: <? if($error[0]) echo "#FFF0F0"; ?>" value="<?= $firstname ?>">
                <span class="red">* 
                <input name="action" type="hidden" id="action" value="save" />
                </span></label></td>
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
                 <span class="red">*</span></label></td>
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
                 <span class="red">*</span></td>
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
                 <span class="red">*</span></label></td>
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
                  <input type="submit" name="button" id="button" class="button" value="บันทึก">
                  <input type="button" name="button2" id="button2" class="button" value="ยกเลิก" onclick="window.location='index2.php?option=manage_user&group=<?= $_REQUEST['group'] ?>'" />
                </label></td>
              </tr>
         
              <tr>
                <td align="right">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>   

<? } else { ?>

  <table width="95%" align="center" cellspacing="1" class="admintable">

 
    <tr>
      <td height="35" colspan="3" align="left"><? if(isset($_REQUEST['group'])) { ?>   จำนวนสมาชิกในกลุ่ม<?= $data2->gdesc ?> มีทั้งสิ้น <b class="red">
      <? $sql = "select * from radusergroup where groupname = '".$_REQUEST['group']."'";
	     echo mysql_num_rows(mysql_query($sql)); ?>
      
      
      
      
      
    </b>  คน<? } ?></td>
      <td height="35" colspan="3" align="right"> <?php 
	if(isset($message)) {  ?><?= $message  ?><? } 
?></td>
      </tr>
    
    <tr>
      <td width="68" align="center" class="key">ลำดับที่</td>
      <td width="364" align="center" class="key">ชื่อ - นามสกุล</td>
      <td width="147" align="center" class="key">ชื่อผู้ใช้งาน</td>
      <td width="130" align="center" class="key">วันที่สมัคร</td>
      <td width="81" align="center" class="key">สถานะ</td>
      <td width="115" align="center" class="key">ดำเนินการ </td>
      <td width="30" align="center" class="key"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="ClickCheckAll(this);"></td>
    </tr>
    <?php 
	  	$count = 0;
		$sql = "select * from radusergroup, account where radusergroup.groupname = '".$_REQUEST['group']."' and radusergroup.username = account.username and account.status != '-1' order by account.username"; 
		//echo $sql;
	  	$result = mysql_query($sql);
		while($users = mysql_fetch_object($result)) { 
			$count++;
			($count % 2 != 0) ? $bgcolor = "#FFFFFF" : $bgcolor = "#F6F6F6";
		?>
    <tr>
      <td width="68" align="center" valign="top" bgcolor="<?= $bgcolor ?>"><?= $count ?></td>
      <td width="364" align="left" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;
          <?= $users->firstname ?>
          <?= $users->lastname ?>     </td>
      <td align="left" valign="top" bgcolor="<?= $bgcolor ?>">
         &nbsp; <?= $users->username ?>
             </td>
      <td align="center" valign="top" bgcolor="<?= $bgcolor ?>">
          <?= substr($users->dateregis,0,10) ?>
             </td>
      <td width="81" align="center" valign="top" bgcolor="<?= $bgcolor ?>">      
          <? if($users->status) { ?>
          <a href="index2.php?option=manage_user&group=<?= $_REQUEST['group'] ?>&user=<?=$users->username?>&&action=lock"><img src="images/unlocked.png" alt="ล็อก" /></a>
          <? } else { ?>
          <a href="index2.php?option=manage_user&group=<?= $_REQUEST['group'] ?>&user=<?=$users->username?>&action=unlock"><img src="images/lock.png" alt="ปลดล็อก" /></a>
          <? } ?></td>
      <td width="115" align="center" valign="top" bgcolor="<?= $bgcolor ?>">
          <a href="index2.php?option=manage_user&group=<?= $_REQUEST['group'] ?>&user=<?=$users->username?>&action=edit"><img src="images/configure.png" alt="แก้ไข" /></a>
        
        <a href="index2.php?option=manage_user&group=<?= $_REQUEST['group'] ?>&user=<?=$users->username?>&action=delete"><img src="images/delete.png" alt="ลบ" /></a>
        
        
        
     <? $sql = "select * from groups";
		$result2 = mysql_query($sql);
		$num = mysql_num_rows($result2);
	?>
   
    <img src="images/go.png" alt="ย้ายกลุ่ม" onClick="showhide<?= $users->username?>(1);event.cancelBubble=1" style="cursor:hand" />
		<div  onmouseover="showhide<?= $users->username?>(2);" onmouseout="showhide<?= $users->username?>(0)" id="innermenu<?= $users->username?>" style="position:absolute; width:300px; height:<?= $num * 25 ?>px;background-color:white; visibility:hidden; text-align:left; border: 1px #ddd dashed; padding: 10px 10px 10px 10px; line-height:25px; font-weight: normal" class="normal">
        <script language="JavaScript1.2">
		
		
		function gl<?= $users->username?>(linkname,dest){
		document.write('<li><a href="'+dest+'">'+linkname+'</a></li>')
		}
		
		function showhide<?= $users->username?>(state){
		var cacheobj=document.getElementById("innermenu<?= $users->username?>").style
		if (state==0)
		cacheobj.visibility="hidden"
		else if(state==2)
		cacheobj.visibility="visible"
		else
		cacheobj.visibility=cacheobj.visibility=="hidden"? "visible" : "hidden"
		}
		
		//Specify your links here- gl(Item text, Item URL)
		<? while($groups = mysql_fetch_object($result2)) { ?>
		gl<?= $users->username?>("กลุ่ม<?= $groups->gdesc ?>","index2.php?option=manage_user&action=move&user=<?= $users->username?>&group=<?= $groups->gname ?>")
		<? } ?>

		//Extend this list as needed
		
		
		document.onclick=function(){showhide<?= $user->username?>(0)}
		
</script>
</div>

        
         </td>
      <td width="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>"><input type="checkbox" name="chk[]" id="chk<?=$count;?>" value="<?=$users->username?>" /></td>
    </tr>
    <? } 			($count % 2 == 0) ? $bgcolor = "#FFFFFF" : $bgcolor = "#F6F6F6";
 ?>
  </table>
 <? } ?>
      <? $sql = "select * from groups";
		$result2 = mysql_query($sql);
		$num = mysql_num_rows($result2);
	?>
<table width="95%" align="center" cellspacing="1" class="admintable">
	      <td width="68" align="center" class="key"></td>
      <td width="364" align="center" class="key"></td>
      <td width="147" align="center" class="key"></td>
      <td width="130" align="center" class="key"></td>
      <td width="81" align="center" class="key"></td>
      <td width="115" align="center" class="key"> </td>
      <td width="30" align="center" class="key">
      <img src="images/lock.png"  onclick="changeSubmitLock()" alt="ล็อก" /></a>
      <img src="images/unlocked.png" onclick="changeSubmitUnlock()" alt="ปลดล็อก" /></a>
      <img src="images/delete.png" onclick="changeSubmitDel()" alt="ลบ" />
      <img src="images/go.png" alt="ย้ายกลุ่มตามที่เลือก" onClick="showhideSel(1);event.cancelBubble=1" style="cursor:hand" />
      <div  onmouseover="showhideSel(2);" onmouseout="showhideSel(0)" id="innermenuSel" style="position:absolute; width:300px; height:<?= $num * 25 ?>px;background-color:white; visibility:hidden; text-align:left; border: 1px #ddd dashed; padding: 10px 10px 10px 10px; line-height:25px; font-weight: normal" class="normal"> 
 <input type="hidden" name="hdnCount" value="<?=$count?>">	

<script language="JavaScript1.2">
	function changeSubmitLock(){
        document.groupform.action ="index2.php?option=manage_user&action=lockSel&group=<?= $_REQUEST['group'] ?>"
		    document.groupform.submit()	
	}
	function changeSubmitUnlock(){
		    document.groupform.action ="index2.php?option=manage_user&action=unlockSel&group=<?= $_REQUEST['group'] ?>"
		    document.groupform.submit()
	}
	function changeSubmitDel(){
		if(confirm('ท่านต้องการลบผู้ใช้ที่เลือกไว้ จริงหรือ ?')==true){	
        document.groupform.action ="index2.php?option=manage_user&action=delSel&group=<?= $_REQUEST['group'] ?>"
		    document.groupform.submit()	
		}	
	}

		function showhideSel(state){
		var cacheobj=document.getElementById("innermenuSel").style
		if (state==0)
		cacheobj.visibility="hidden"
		else if(state==2)
		cacheobj.visibility="visible"
		else
		cacheobj.visibility=cacheobj.visibility=="hidden"? "visible" : "hidden"
		}
		
		//Specify your links here- gl(Item text, Item URL)
		<? while($groups = mysql_fetch_object($result2)) { ?>		
				document.write('<li><a href="#" onClick="changeSubmit<?= $groups->gname ?>() ">กลุ่ม <?= $groups->gdesc ?></a></li>')		
				
		function changeSubmit<?= $groups->gname ?>(){
				document.groupform.action ="index2.php?option=manage_user&action=moveSel&group=<?= $groups->gname ?>"
		    document.groupform.submit()		
		}
		<? } ?>

		//Extend this list as needed		
		document.onclick=function(){showhideSel(0)}
		
</script>
 </div>
 </td>
    </tr>
  </table>
 
</form>
</div>
</body>
</html>
