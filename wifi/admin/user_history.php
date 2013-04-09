<?php
include("include/class.testlogin.php");
?>
<?
	$start = $end = date("Y-m-d");
	if(isset($_REQUEST['submit'])) {
		$start = $_REQUEST['start'];
		$end = $_REQUEST['end'];
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
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
  <tr>
    <td width="6%" align="center"><img src="images/BlackNeonAgua_013.png" alt="" width="59" height="60" /></td>
    <td width="94%"><a href="index2.php?option=user_history">History</a><br />
<span class="normal">ประวัติการใช้งานอินเทอร์เน็ต</span></td>
    <td width="94%">&nbsp;</td>
  </tr>
</table>
<form id="form1" method="post" action="">
<table width="100%" border="0" cellspacing="3" cellpadding="0">
  
  <tr>
    <td width="35%" align="right">&nbsp;</td>
    <td width="65%" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">วันที่เริ่มต้น :
      <input name="start" type="text" class="inputbox" id="start" value="<?= $start ?>" style="width: 100px; text-align:center; padding-left:2px" /> 
      วันที่สิ้นสุด :
      <input name="end" type="text" class="inputbox" id="end" value="<?= $end ?>" style="width: 100px; text-align:center; padding-left: 2px"   />
      <input name="submit" type="submit" class="button" id="submit" value="แสดงข้อมูล" /></td>
    </tr>
</table>
</form>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="header">
  <tr>
    <td align="center" height="5"></td>
  </tr>
</table>
<?php 
	if(isset($_REQUEST['submit'])) {
		$sql = "select * from radacct,account where radacct.acctstarttime >= '".$_REQUEST['start']." 00:00:00' and radacct.acctstarttime <= '".$_REQUEST['end']." 23:59:59' and radacct.username = account.username order by radacct.acctstarttime ";
		$result = mysql_query($sql);
		$totals = mysql_num_rows($result);
		
    } else {
		$sql = "select * from radacct,account where radacct.acctstarttime >= '".date("Y-m-d")." 00:00:00' and radacct.acctstarttime <= '".date("Y-m-d")." 23:59:59' and radacct.username = account.username order by radacct.acctstarttime ";
		$result = mysql_query($sql);
		$totals = mysql_num_rows($result);
    }
?>
       
  <table width="95%" align="center" cellspacing="1" class="admintable">
    <tr>
      <td height="30" colspan="5" align="left" >    จำนวนการใช้งานภายในช่วงเวลาดังกล่าว มีทั้งสิ้น
   <b class="red">   <?= $totals ?>
    </b> ครั้ง</td>
    </tr>
    <tr>
      <td width="122" height="30" align="center" class="key">ลำดับที่</td>
      <td width="360" height="30" align="center" class="key">ชื่อ - นามสกุล</td>
      <td width="342" height="30" align="center" class="key">เริ่มต้นใช้งาน</td>
      <td width="180" align="center" class="key">หมายเลขไอพี</td>
      <td width="147" height="30" align="center" class="key">เป็นเวลา</td>
    </tr>
        <?
		$count = 0;
		while($data = mysql_fetch_object($result)) {
			$count++;
			($count % 2 != 0) ? $bgcolor = "#FFFFFF" : $bgcolor = "#F6F6F6";
		?>
	  <tr>
      <td width="122" align="center" valign="top" bgcolor="<?= $bgcolor ?>"><?= $count  ?></td>
      <td width="360" align="left" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;<?= $data->firstname ?> <?= $data->lastname ?></td>
      <td width="342"  align="center" valign="top" bgcolor="<?= $bgcolor ?>"><?= $data->acctstarttime ?></td>
      <td width="180"  align="center" valign="top" bgcolor="<?= $bgcolor ?>"><?= $data->framedipaddress ?>&nbsp;</td>
      <td width="147"  align="right" valign="top" bgcolor="<?= $bgcolor ?>">&nbsp;<?
      
	  $hours = floor($data->acctsessiontime/60.0/60.0);
	  $mins = floor(($data->acctsessiontime - $hours * 60.0 * 60.0)/60.0);
	  $secs = $data->acctsessiontime - ($hours * 60.0 * 60.0) - ($mins * 60.0);
	  printf("%d:%02d:%02d", $hours, $mins, $secs);
	  
	  ?>&nbsp;&nbsp;</td>
    </tr>
<?

		}
?> 
</table>
  <BR /><BR />
</div>
</body>
</html>
