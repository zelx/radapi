<?php
	include("include/class.mysqldb.php");
	include("include/config.inc.php");
	if(!isset($_SESSION['logined'])) {
		?><meta http-equiv="refresh" content="0;url=index.php"><?
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
	<title>-:- Authent!cation -:-</title>
</head>
<body>
	<div id="header-bar">
		<div id="header-logoff">ยินดีต้อนรับ <?= $_SESSION['name'] ?> 
        &raquo; <a href="index2.php">หน้าแรก</a> 
        | <a href="logoff.php">ออกจากระบบ</a></div>
    </div>
    <div id="body">
<h3><a href="index2.php">Authen<span class="gray">t!cation</span> For <span class="gray">School</span></a></h3>
        <div id="left">
        <div id="slogan">ระบบจัดการการพิสูจน์ตัวตนผู้ใช้งานอินเทอร์เน็ต</div>
        <? if(!isset($_REQUEST['option'])) { ?>
            <div id="cpanel">
            	<div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=register2">
                        <img src="images/BlackNeonAgua_038.png" alt="เพิ่มผู้ใช้ใหม่" align="middle" border="0" />
                        <span>เพิ่มผู้ใช้</span></a></div>
              </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=add_user">
                        <img src="images/BlackNeonAgua_003.png" alt="เพิ่มผู้ใช้ใหม่" align="middle" border="0" />
                        <span>เพิ่มผู้ใช้แบบกลุ่ม</span>
                        </a>
                    </div>
                </div>
                
                 <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=register_excel">
                        <img src="images/BlackNeonAgua_224.png" alt="เพิ่มผู้ใช้ Excel" align="middle" border="0" />
                        <span>เพิ่มผู้ใช้ Excel</span>
                        </a>
                    </div>
                </div>
                
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=manage_user">
                        <img src="images/BlackNeonAgua_246.png" alt="จัดการข้อมูลผู้ใช้" align="middle" border="0" />
                        <span>จัดการข้อมูลผู้ใช้</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=manage_group">
                        <img src="images/BlackNeonAgua_009.png" alt="จัดการกลุ่มผู้ใช้" align="middle" border="0" />
                        <span>จัดการกลุ่มผู้ใช้</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=manage_admin">
                        <img src="images/BlackNeonAgua_172.png" alt="จัดการผู้ดูแลระบบ" align="middle" border="0" />
                        <span>เปลี่ยนรหัสผ่าน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=user_online">
                        <img src="images/BlackNeonAgua_033.png" alt="ผู้ที่กำลังใช้งานอยู่" align="middle" border="0" />
                        <span>ผู้ที่กำลังใช้งานอยู่</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=user_history">
                        <img src="images/BlackNeonAgua_013.png" alt="ประวัติการใช้งาน" align="middle" border="0" />
                        <span>ประวัติการใช้งาน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=user_statistic">
                        <img src="images/BlackNeonAgua_060.png" alt="สถิติการใช้งาน" align="middle" border="0" />
                        <span>สถิติการใช้งาน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=manage_interface">
                        <img src="images/BlackNeonAgua_160.png" alt="ปรับแต่งหน้าล็อกอิน" align="middle" border="0" />
                        <span>ปรับแต่งหน้าล็อกอิน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=manage_config">
                        <img src="images/BlackNeonAgua_197.png" alt="แก้ไขค่าระบบ" align="middle" border="0" />
                        <span>แก้ไขค่าระบบ</span>
                        </a>
                    </div>
                </div>
<!--  เพิ่มตรงนี้ครับผมSQUID BLOCK
                -->
				<div style="float:left;">
				        <div class="icon">
                        <a href="index2.php?option=form">
                        <img src="images/BlackNeonAgua_147.png" alt="บล๊อกเว็บ Squid" align="middle" border="0" />
                        <span>บล๊อก เว็บ,โหลด</span>
                        </a>
                    </div>
                </div>
                
                <div style="float:left;">
				        <div class="icon">
                        <a href="index2.php?option=check_accp">
                        <img src="images/BlackNeonAgua_111.png" alt="สถานะ Access Point" align="middle" border="0" />
                        <span>สถานะ AP</span>
                        </a>
                    </div>
                </div>

                <!--
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=basic_data">
                        <img src="images/objects.png" alt="ข้อมูลพื้นฐาน" align="middle" border="0" />
                        <span>ข้อมูลพื้นฐาน</span>
                        </a>
                    </div>
                </div>

                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=import_data">
                        <img src="images/objects.png" alt="นำเข้าข้อมูล" align="middle" border="0" />
                        <span>นำเข้าข้อมูล</span>
                        </a>
                    </div>
                </div>
                -->
                <div style="float:left;">
                  <div class="icon">
                        <a href="index2.php?option=power">
                        <img src="images/BlackNeonAgua_194.png" alt="รีบูท/ปิดระบบ" align="middle" border="0" />
                        <span>รีบูท/ปิดระบบ</span>
                        </a>
                    </div>
                </div>
                
                 <div style="float:left;">
                  <div class="icon">
                        <a href="https://<?=$_SERVER['SERVER_ADDR'];?>:81/" target="_blank">
                        <img src="images/BlackNeonAgua_203.png" alt="รีบูท/ปิดระบบ" align="middle" border="0" />
                        <span>จัดการ Server</span>
                        </a>
                    </div>
                </div>
                
                 <div style="float:left;">
                  <div class="icon">
                        <a href="https://<?=$_SERVER['SERVER_ADDR'];?>:81/mysql" target="_blank">
                        <img src="images/BlackNeonAgua_201.png" alt="รีบูท/ปิดระบบ" align="middle" border="0" />
                        <span>จัดการ MySQL</span>
                        </a>
                    </div>
                </div>
                
                <div style="float:left;">
                  <div class="icon">
                        <a href="index2.php?option=manuals">
                        <img src="images/BlackNeonAgua_248.png" alt="คู่มือการใช้งาน" align="middle" border="0" />
                        <span>คู่มือการใช้งาน</span>
                        </a>
                    </div>
                </div>
                
                

                <div style="clear:both;"> </div>
            </div>
            <?php
				 } else { 
            		include($_REQUEST['option'] . ".php"); 
                 } 
            ?>
        </div>
        <div id="right">
			<h1>เมนูหลัก</h1>
			<ul>
				<li><a href="index2.php">หน้าแรก</a></li>
				<li><a href="logoff.php">ออกจากระบบ</a></li>
			</ul>

			<h1>เมนูลัดจัดการระบบ</h1>
			<ul>
                <li><a href="index2.php?option=register2">เพิ่มผู้ใช้รายบุคคล</a></li>
				<li><a href="index2.php?option=add_user">เพิ่มผู้ใช้แบบกลุ่ม</a></li>
        <li><a href="index2.php?option=register_excel">เพิ่มผู้ใช้จาก Excel</a></li>
				<li><a href="index2.php?option=manage_user">จัดการข้อมูลผู้ใช้</a></li>
				<li><a href="index2.php?option=manage_group">จัดการกลุ่มผู้ใช้</a></li>
				<li><a href="index2.php?option=manage_interface">ปรับแต่งหน้าจอล็อกอิน</a></li>
                <li><a href="index2.php?option=manage_config">แก้ไขค่าระบบ</a></li>
				<li><a href="index2.php?option=user_online">รายชื่อผู้ที่กำลังใช้งาน</a></li>
				<li><a href="index2.php?option=user_history">ประวัติการใช้งาน</a></li>
				<li><a href="index2.php?option=user_statistic">สถิติการใช้งานระบบ</a></li>
				<li><a href="index2.php?option=form">SQUID บล๊อก เว็บ,ดาวน์โหลด </a></li>
                <li><a href="index2.php?option=check_accp">สถานะ AP</a></li> 
				<li><a href="index2.php?option=power">รีบูท/ปิดระบบ</a></li> 
				<li><a href="index2.php?option=manuals">คู่มือการใช้งาน</a></li>
			</ul>
        </div>
    <div id="footer">
            <p>ปรับปรุงเพิ่มเติม: <a href="http://bls.buu.ac.th/">ศูนย์กลุ่มการงานอาชีพและเทคโนโลยี</a> จังหวัดจันทบุรี สพม.17<br />
              ออกแบบและพัฒนาระบบ: <a href="http://bls.buu.ac.th/">ห้องปฏิบัติการวิจัยลีนุกซ์ มหาวิทยาลัยบูรพา</a><br>
            </p>
            <!-- ปรับปรุงล่าสุด 30 มีนาคม 2551 เวลา 4:06:15 น. -->
    </div>
    </div>
</body>
</html>
