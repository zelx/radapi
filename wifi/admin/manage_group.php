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
				$sql = "update groups set gstatus = 0 where gid = '".$_REQUEST['gid']."'";
				//echo $sql;
				mysql_query($sql);
				$message = "<span class=\"info\">ล็อกกลุ่มที่ต้องการเรียบร้อยแล้ว</span>";
				break;
			case 'unlock' : 
				$sql = "update groups set gstatus = 1 where gid = '".$_REQUEST['gid']."'";
				//echo $sql;
				mysql_query($sql);
				$message = "<span class=\"info\">ปลดล็อกกลุ่มที่ต้องการเรียบร้อยแล้ว</span>";
				break;
			case 'delete' : 
				//$sql = "update groups set gstatus = 1 where gid = '".$_REQUEST['gid']."'";
				//echo $sql;
				//mysql_query($sql);
				$sql = "select * from radusergroup where groupname = '$group->gname'";
				$gcount = mysql_num_rows(mysql_query($sql));
				if($gcount) {
					$message = "<span class=\"alert\">เกิดข้อผิดพลาด ในกลุ่มดังกล่าวยังมีผู้ใช้อยู่</span>";
				} else {
					$sql = "delete from groups where gid = '".$_REQUEST['gid']."'"; 
					mysql_query($sql);
					$sql = "delete from radgroupcheck where groupname = '".$group->gname."'"; 
					mysql_query($sql);
					$sql = "delete from radgroupreply where groupname = '".$group->gname."'"; 
					mysql_query($sql);
					$message = "<span class=\"info\">ลบกลุ่มที่ต้องการออกเรียบร้อยแล้ว</span>";
				}
				break;
			case 'save' :
			    $newup = $_REQUEST['groupupload'];
				$d_time = $_REQUEST['d_time'];
				$s_time = $_REQUEST['s_time'];
				$a_time = $_REQUEST['a_time'];
				$i_time = $_REQUEST['i_time'];
				$start_page = $_REQUEST['start_page'];
				$newdown = $_REQUEST['groupdownload'];
				$newexpire = $_REQUEST['groupexpire'];
				$newexpiretocheck = date("d M Y", strtotime($newexpire));
				$noexpire = "0000-00-00";
				$sql = "update groups set gdesc = '".$_REQUEST['groupdesc']."', gupload = '".$_REQUEST['groupupload']."', gdownload ='".$_REQUEST['groupdownload']."', gexpire = '".$_REQUEST['groupexpire']."' where gid = '".$_REQUEST['gid']."'";
			//	echo $sql;
				mysql_query($sql);
				$sql = "delete from radgroupcheck where groupname =  '".$group->gname."'";
				mysql_query($sql);
				$sql = "delete from radgroupreply where groupname =  '".$group->gname."'";
				mysql_query($sql);
				//$sql = "insert into radgroupreply values ('', '".$group->gname."', 'WISPr-Bandwidth-Max-Down', ':=', '$down')";
				   
					$sql = "insert into radgroupcheck values ('', '".$group->gname."', 'Auth-Type', ':=', 'Local')";
					mysql_query($sql);
							
					$sql = "insert into radgroupcheck values ('', '".$group->gname."', 'Simultaneous-Use', ':=', '1')";
					mysql_query($sql);
					
					 if ($newexpire != $noexpire ){
				    $sql = "insert into radgroupcheck values ('', '".$group->gname."', 'Expiration', ':=', '$newexpiretocheck')";
					mysql_query($sql);
					}
					
					$sql = "insert into radgroupcheck values ('', '".$group->gname."', 'Max-Daily-Session', ':=', '$d_time')";
					mysql_query($sql);
					//$sql = "insert into radgroupreply values ('', '".$group->gname."', 'Simultaneous-Use', ':=', '1')";
					//mysql_query($sql);
					
					$sql = "insert into radgroupreply values ('', '".$group->gname."', 'Service-Type', ':=', 'Login-User')";
					mysql_query($sql);
					
					$sql = "insert into radgroupreply values ('', '".$group->gname."', 'Acct-Interim-Interval', ':=', '$a_time')";
					mysql_query($sql);
					
					$sql = "insert into radgroupreply values ('', '".$group->gname."', 'Idle-Timeout', ':=', '$i_time')";
					mysql_query($sql);
					
					$sql = "insert into radgroupreply values ('', '".$group->gname."', 'Session-Timeout', ':=', '$s_time')";
					mysql_query($sql);
					
					if($start_page != "") {
					$sql = "insert into radgroupreply values ('', '".$group->gname."', 'WISPr-Redirection-URL', ':=', '$start_page')";
					mysql_query($sql);
					}
					if($newdown != 0) {
					$down = $newdown * 1024;
					$sql = "insert into radgroupreply values ('', '".$group->gname."', 'WISPr-Bandwidth-Max-Down', ':=', '$down')";
					mysql_query($sql);
					}
					if($newup != 0) {
					$upload = $newup * 1024;
					$sql = "insert into radgroupreply values ('', '".$group->gname."', 'WISPr-Bandwidth-Max-Up', ':=', '$upload')";
					mysql_query($sql);
					}
				$message = "<span class=\"info\">บันทึกข้อมูลการแก้ไขเรียบร้อยแล้ว</span>";
				break;
			case 'edit' :
				$message = "<span class=\"note\">กรุณากรอกข้อมูลในช่องที่ท่านต้องการแก้ไขแล้วคลิกบันทึกด้วย</span>";
				break;
			case 'add' :
				$message = "<span class=\"note\">กรุณากรอกข้อมูลในช่องด้านล่างแล้วคลิกบันทึกเพื่อเพิ่มกลุ่มใหม่</span>";
				break;
			case 'saveadd' :
				$error = 0;
				$newup = $_REQUEST['newgroupupload'];
				$d_time = $_REQUEST['d_time'];
				$s_time = $_REQUEST['s_time'];
				$a_time = $_REQUEST['a_time'];
				$i_time = $_REQUEST['i_time'];
				$start_page = $_REQUEST['start_page'];
				$newdown = $_REQUEST['newgroupdownload'];
				$newexpire = $_REQUEST['newgroupexpire'];
				$newexpiretocheck = date("d M Y", strtotime($newexpire));
				$noexpire = "0000-00-00";
				if(trim($_REQUEST['newgroupdesc']) == '') {
					$error = 1;
					$message = "<span class=\"alert\">กรุณากรอกชื่อกลุ่มด้วย</span>";
				} else {
					$sql = "select * from groups where gdesc = '".trim($_REQUEST['newgroupdesc'])."'";
					if(mysql_num_rows(mysql_query($sql))) {
						$message = "<span class=\"alert\">ชื่อกลุ่ม '".trim($_REQUEST['newgroupdesc'])."' ซ้ำ กรุณาเปลี่ยนชื่อกลุ่มใหม่</span>";
						$error = 1;
					} else {
					$sql = "insert into radgroupcheck values ('', 'group".$_REQUEST['newgname']."', 'Auth-Type', ':=', 'Local')";
					mysql_query($sql);
							
					$sql = "insert into radgroupcheck values ('', 'group".$_REQUEST['newgname']."', 'Simultaneous-Use', ':=', '1')";
					mysql_query($sql);
					//$sql = "insert into radgroupreply values ('', 'group".$_REQUEST['newgname']."', 'Simultaneous-Use', ':=', '1')";
					//mysql_query($sql);
					if ($newexpire != $noexpire ){
				    $sql = "insert into radgroupcheck values ('', 'group".$_REQUEST['newgname']."', 'Expiration', ':=', '$newexpiretocheck')";
					mysql_query($sql);
					}
					
						$sql = "insert into radgroupcheck values ('', 'group".$_REQUEST['newgname']."', 'Max-Daily-Session', ':=', '$d_time')";
					mysql_query($sql);
					
					$sql = "insert into radgroupreply values ('', 'group".$_REQUEST['newgname']."', 'Service-Type', ':=', 'Login-User')";
					mysql_query($sql);
					
					$sql = "insert into radgroupreply values ('', 'group".$_REQUEST['newgname']."', 'Acct-Interim-Interval', ':=', '$a_time')";
					mysql_query($sql);
					
					$sql = "insert into radgroupreply values ('', 'group".$_REQUEST['newgname']."', 'Idle-Timeout', ':=', '$i_time')";
					mysql_query($sql);
					
					$sql = "insert into radgroupreply values ('', 'group".$_REQUEST['newgname']."', 'Session-Timeout', ':=', '$s_time')";
					mysql_query($sql);
					
					if($start_page != "") {
					$sql = "insert into radgroupreply values ('', 'group".$_REQUEST['newgname']."', 'WISPr-Redirection-URL', ':=', '$start_page')";
					mysql_query($sql);
					}
					if($newdown != 0) {
					$down = $newdown * 1024;
					$sql = "insert into radgroupreply values ('', 'group".$_REQUEST['newgname']."', 'WISPr-Bandwidth-Max-Down', ':=', '$down')";
					mysql_query($sql);
					}
					if($newup != 0) {
					$upload = $newup * 1024;
					$sql = "insert into radgroupreply values ('', 'group".$_REQUEST['newgname']."', 'WISPr-Bandwidth-Max-Up', ':=', '$upload')";
					mysql_query($sql);
					}
						$sql = "insert into groups values('','group".$_REQUEST['newgname']."','".$_REQUEST['newgroupdesc']."', '$newup', '$newdown', '$newexpire', '0', '0')";
					//	echo $sql;
						mysql_query($sql);
						
							$message = "<span class=\"info\">บันทึกข้อมูลกลุ่มใหม่เรียบร้อยแล้ว</span>";
							//echo"$s_time $1_time  $a_time $d_time";
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

<form action="index2.php?option=manage_group" method="post" id="groupform" name="groupform">
<table width="95%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
  <tr>
    <td width="6%" align="center"><img src="images/BlackNeonAgua_009.png" alt="" width="59" height="60" /></td>
    <td width="94%"><a href="index2.php?option=manage_group">Group Manager</a><br />
<span class="normal">จัดการกลุ่มผู้ใช้งานอินเทอร์เน็ต</span></td>
    <td width="94%"><input type="button" class="button" name="button" id="button" value="เพิ่มกลุ่ม" onclick="window.location='index2.php?option=manage_group&action=add'"/></td>
  </tr>
</table>
 
  <table width="95%" align="center" cellspacing="1" class="admintable">
    <tr>
      <td colspan="6"><?php 
	if(isset($message)) { echo $message; } 
?>
&nbsp;</td>
      </tr>
    <tr>
      <td width="64" align="center" class="key">กลุ่มที่</td>
      <td width="291" align="center" class="key">ชื่อกลุ่ม</td>
      <td width="189" align="center" class="key">ความเร็วเน็ต</td>
      <td width="183" align="center" class="key">วันหมดอายุ (ปี-เดือน-วัน)</td>
      <td width="56" align="center" class="key">สถานะ</td>
      <td width="122" align="center" class="key">ดำเนินการ</td>
    </tr>
    <?php 
	  	$count = 0;
		$sql = "select * from groups order by gid"; 
	  	$result = mysql_query($sql);
		while($group = mysql_fetch_object($result)) { 
			$count++;
			($count % 2 != 0) ? $bgcolor = "#FFFFFF" : $bgcolor = "#F6F6F6";
			$sql = "select * from radusergroup where groupname = '$group->gname'";
			$gcount = mysql_num_rows(mysql_query($sql));
			$edit = false;
			if(isset($_REQUEST['gid']) && isset($_REQUEST['action'])) {
				if($group->gid == $_REQUEST['gid'] && $_REQUEST['action'] == "edit") {
					$edit = true;
				}
			} 
		?>
    <tr>
      <td width="64" height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>"><?= $group->gid ?></td>
      <td width="291" height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;
          <? if(!$edit) { ?>
          <?= $group->gdesc ?>
          <? } else { ?>
              <input name="groupdesc" type="text" class="noborder" id="groupdesc" value="<?= $group->gdesc ?>"  />
              <input name="action" type="hidden" id="action" value="save" />
              <input name="gid" type="hidden" id="gid" value="<?= $group->gid ?>" />
        <? } ?>      </td>
  
      <td width="189" height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>">
          <? if(!$edit) { ?>
          <font color=orange><?= $group->gdownload ?></font> : <font color="green"> <?= $group->gupload ?></font>
      
          <? } else { ?>
          <input name="groupdownload" type="text" class="noborder3" id="groupdownload" value="<?= $group->gdownload ?>" style="width: 30px; text-align: left; padding-left: 2px;"  /> : 
        <input name="groupupload" type="text" class="noborder3" id="groupupload" value="<?= $group->gupload ?>" style="width: 30px; text-align: right; padding-left: 2px;"  /><? } ?>       </td>
      <td width="183" height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>">
                    <? if(!$edit) { ?><?= $group->gexpire ?>
   <? } else { ?>
      <input name="groupexpire" type="text" class="noborder3" id="groupexpire" value="<?= $group->gexpire ?>" style="width:80px; text-align: center; padding-left: 2px; " />
   <? } ?>      </td>
      <td width="56" align="center" valign="top" bgcolor="<?= $bgcolor ?>">
      
      	  <? if($group->gstatus) { ?>
          <a href="index2.php?option=manage_group&gid=<?=$group->gid?>&action=lock"><img src="images/unlocked.png" alt="ล็อก" /></a>
          <? } else { ?>
          <a href="index2.php?option=manage_group&gid=<?=$group->gid?>&action=unlock"><img src="images/lock.png" alt="ปลดล็อก" /></a>
          <? } ?>      </td>
      <td width="122" height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>">
                    <? if(!$edit) { ?>
        <a href="index2.php?option=manage_group&gid=<?=$group->gid?>&action=edit"><img src="images/configure.png" alt="แก้ไข" /></a>
        <? } else { ?>
        <input name="action" type="image" value="save" src="images/save.png" alt="บันทึก" />
        <? } ?>
        <a href="index2.php?option=manage_group&gid=<?=$group->gid?>&action=delete"><img src="images/delete.png" alt="ลบ" /></a> </td>
    </tr> 

		<? if(!$edit) {}else{ 
			$attribute_e[0]='WISPr-Redirection-URL';
			$attribute_e[1]='Session-Timeout';
			$attribute_e[2]='Idle-Timeout';
			$attribute_e[3]='Max-Daily-Session';
			$attribute_e[4]='Acct-Interim-Interval';
			$a=0;
			for($i=0;$i<5;$i++){
				if($i == 3){
				$sql_e = "select * from radgroupcheck where (groupname = '".$group->gname."')and(Attribute = 'Max-Daily-Session')" ;
					} else {
				$sql_e = "select * from radgroupreply where (groupname = '".$group->gname."')and(Attribute = '".$attribute_e[$i]."')";
					}
				$result_e = mysql_query($sql_e);
				$group_e = mysql_fetch_object($result_e);
				$attribute_v[$i]= $group_e->value;
				$a++;
			}
		?>
<tr>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"></td>
          <td height="30" align="right" valign="top" bgcolor="#F6F6F6">login 1ครั้งเล่นได้นานครั้งละ : </td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"><select name="s_time" id="s_time" >
            <option value="3600" <? if($attribute_v[1]=='3600'){echo"selected=\"Selected\"";}?>>1 hour</option>
            <option value="7200" <? if($attribute_v[1]=='7200'){echo"selected=\"Selected\"";}?>>2 hour</option>
            <option value="10800" <? if($attribute_v[1]=='10800'){echo"selected=\"Selected\"";}?>>3 hour</option>
            <option value="14400" <? if($attribute_v[1]=='14400'){echo"selected=\"Selected\"";}?>>4 hour</option>
            <option value="18000" <? if($attribute_v[1]=='18000'){echo"selected=\"Selected\"";}?>>5 hour</option>
            <option value="21600" <? if($attribute_v[1]=='21600'){echo"selected=\"Selected\"";}?>>6 hour</option>
            <option value="25200" <? if($attribute_v[1]=='25200'){echo"selected=\"Selected\"";}?>>7 hour</option>
            <option value="28800" <? if($attribute_v[1]=='28800'){echo"selected=\"Selected\"";}?>>8 hour</option>
            <option value="36000" <? if($attribute_v[1]=='36000'){echo"selected=\"Selected\"";}?>>10 hour</option>
            <option value="54000" <? if($attribute_v[1]=='54000'){echo"selected=\"Selected\"";}?>>15 hour</option>
            <option value="72000" <? if($attribute_v[1]=='72000'){echo"selected=\"Selected\"";}?>>20 hour</option>
                                                  </select></td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"></td>
          <td height="30" align="right" valign="top" bgcolor="<?= $bgcolor ?>">เล่นได้วันละ :</td>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"><select name="d_time" id="d_time">
            <option value="3600" <? if($attribute_v[3]=='3600'){echo"selected=\"Selected\"";}?>>1 hour</option>
            <option value="7200" <? if($attribute_v[3]=='7200'){echo"selected=\"Selected\"";}?>>2 hour</option>
            <option value="10800" <? if($attribute_v[3]=='10800'){echo"selected=\"Selected\"";}?>>3 hour</option>
            <option value="14400" <? if($attribute_v[3]=='14400'){echo"selected=\"Selected\"";}?>>4 hour</option>
            <option value="18000" <? if($attribute_v[3]=='18000'){echo"selected=\"Selected\"";}?>>5 hour</option>
            <option value="21600" <? if($attribute_v[3]=='21600'){echo"selected=\"Selected\"";}?>>6 hour</option>
            <option value="25200" <? if($attribute_v[3]=='25200'){echo"selected=\"Selected\"";}?>>7 hour</option>
            <option value="28800" <? if($attribute_v[3]=='28800'){echo"selected=\"Selected\"";}?>>8 hour</option>
            <option value="36000" <? if($attribute_v[3]=='36000'){echo"selected=\"Selected\"";}?>>10 hour</option>
            <option value="54000" <? if($attribute_v[3]=='54000'){echo"selected=\"Selected\"";}?>>15 hour</option>
            <option value="72000" <? if($attribute_v[3]=='72000'){echo"selected=\"Selected\"";}?>>20 hour</option>
          </select></td>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
          <td align="center" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"></td>
          <td height="30" align="right" valign="top" bgcolor="#F6F6F6">&nbsp;ถ้าไม่ใช้งานเป็นเวลา จะตัดการใช้งาน:</td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"><select name="i_time" id="i_time" >
            <option value="300" <? if($attribute_v[2]=='300'){echo"selected=\"Selected\"";}?>>5 minute</option>
            <option value="600" <? if($attribute_v[2]=='600'){echo"selected=\"Selected\"";}?>>10 minute</option>
            <option value="900" <? if($attribute_v[2]=='900'){echo"selected=\"Selected\"";}?>>15 minute</option>
                              </select></td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"></td>
          <td height="30" align="right" valign="top" bgcolor="<?= $bgcolor ?>">ตรวจสอบสถานะทุก : </td>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"><select name="a_time" id="a_time" >
            <option value="60" <? if($attribute_v[4]=='60'){echo"selected=\"Selected\"";}?>>1 minute</option>
            <option value="120" <? if($attribute_v[4]=='120'){echo"selected=\"Selected\"";}?>>2 minute</option>
            <option value="180" <? if($attribute_v[4]=='180'){echo"selected=\"Selected\"";}?>>3 minute</option>
                              </select></td>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
          <td align="center" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"></td>
          <td height="30" align="right" valign="top" bgcolor="#F6F6F6">เมื่อทำการ login ให้เปิดเวป : </td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"><input name="start_page" type="text" class="" id="start_page"  style="width: 150px;   " size="15" value="<? echo"$attribute_v[0]"?>" /></td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
        </tr>

 <? } ?>
    <? $last = $group->gid + 1;
	} 			
	
	($count % 2 == 0) ? $bgcolor = "#FFFFFF" : $bgcolor = "#F6F6F6";
 ?>
 	<? if($_REQUEST['action'] == "add" || $error == 1) {
	$bgcolor = "#FFFFFF";
	 ?>
   
        <tr>
      <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"></td>
      <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"><input type="text" name="newgroupdesc" id="newgroupdesc" class="<? if($error) { echo "noborder4" ;} else { echo "noborder" ; } ?>"  style="width: 200px;   " />
        <input type="hidden" name="newgname" id="newgname" value="<?= date("YmdHis") ?>" /></td>
      <td height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>"><input name="newgroupdownload" type="text" class="noborder3" id="newgroupdownload" value="<?= $newup ?>" style="width: 30px; text-align: right; padding-left: 2px;"  />
:
  <input name="newgroupupload" type="text" class="noborder3" id="newgroupupload" value="<?= $newdown ?>" style="width: 30px; text-align: left; padding-left: 2px;"  /></td>
      <td height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>"><input name="newgroupexpire" type="text" class="noborder3" id="newgroupexpire" value="<?= $newexpire ?>" style="width:80px; text-align: center; padding-left: 2px; " /></td>
      <td align="center" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
      <td height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>">        <input name="action" type="hidden" id="action" value="saveadd" /><input name="action" type="image" value="save" src="images/save.png" alt="บันทึก" /></td>
    </tr>
        <tr>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"></td>
          <td height="30" align="right" valign="top" bgcolor="#F6F6F6">login 1ครั้งเล่นได้นานครั้งละ : </td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"><select name="s_time" id="s_time" >
            <option value="3600">1 hour</option>
            <option value="7200">2 hour</option>
            <option value="10800">3 hour</option>
            <option value="14400">4 hour</option>
            <option value="18000">5 hour</option>
            <option value="21600">6 hour</option>
            <option value="25200">7 hour</option>
            <option value="28800">8 hour</option>
            <option value="36000">10 hour</option>
            <option value="54000">15 hour</option>
            <option value="72000">20 hour</option>
                                                  </select></td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"></td>
          <td height="30" align="right" valign="top" bgcolor="<?= $bgcolor ?>">เล่นได้วันละ :</td>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"><select name="d_time" id="d_time">
            <option value="3600">1 hour</option>
            <option value="7200">2 hour</option>
            <option value="10800">3 hour</option>
            <option value="14400">4 hour</option>
            <option value="18000">5 hour</option>
            <option value="21600">6 hour</option>
            <option value="25200">7 hour</option>
            <option value="28800">8 hour</option>
            <option value="36000">10 hour</option>
            <option value="54000">15 hour</option>
            <option value="72000">20 hour</option>
          </select></td>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
          <td align="center" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"></td>
          <td height="30" align="right" valign="top" bgcolor="#F6F6F6">&nbsp;ถ้าไม่ใช้งานเป็นเวลา จะตัดการใช้งาน :</td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"><select name="i_time" id="i_time" >
            <option value="300">5 minute</option>
            <option value="600">10 minute</option>
            <option value="900">15 minute</option>
                              </select></td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"></td>
          <td height="30" align="right" valign="top" bgcolor="<?= $bgcolor ?>">ตรวจสอบสถานะทุก  : </td>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>"><select name="a_time" id="a_time" >
            <option value="60">1 minute</option>
            <option value="120">2 minute</option>
            <option value="180">3 minute</option>
                              </select></td>
          <td height="30" align="left" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
          <td align="center" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"></td>
          <td height="30" align="right" valign="top" bgcolor="#F6F6F6">เมื่อทำการ login ให้เปิดเวป : </td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6"><input name="start_page" type="text" class="" id="start_page"  style="width: 150px;   " size="15" value="http://www.google.com" /></td>
          <td height="30" align="left" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
          <td height="30" align="center" valign="top" bgcolor="#F6F6F6">&nbsp;</td>
        </tr>

    <? } ?>
  </table>
</form>
</div>
</body>
</html>
