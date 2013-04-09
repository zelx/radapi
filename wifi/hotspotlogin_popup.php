<?php

#include configuration file and MySQL database class
include("admin/include/class.mysqldb.php");
include("admin/include/config.inc.php");
include("admin/include/class.interface.php");

$inf = new interfaces($link);

# please not change
include("admin/include/configuam.inc.php");
$userpassword=1;


$loginpath = "hotspotlogin.php";
$loginpath_popup = "hotspotlogin_popup.php";

# possible Cases:	
# attempt to login                          login=login
# 1: Login successful                       res=success
# 2: Login failed                           res=failed
# 3: Logged out                             res=logoff
# 4: Tried to login while already logged in res=already
# 5: Not logged in yet                      res=notyet
#11: Popup                                  res=popup1
#12: Popup                                  res=popup2
#13: Popup                                  res=popup3
# 0: It was not a form request              res=""


# initialization
$template = $inf->getTemplate();
$titel = $inf->getTitle();
$headline = '';
$bodytext = '';
$body_onload = '';
$footer_text = $inf->getFooterPopUp();

# process login action
if ($_GET['login'] == login) {
  $hexchal = pack ("H32", $_GET['chal']);
  if (isset ($uamsecret)) {
    $newchal = pack ("H*", md5($hexchal . $uamsecret));
  } else {
    $newchal = $hexchal;
  }
  $sql = "select * from account where username = '".$_GET['UserName']."'";
  $result = mysql_query($sql);
  $data0 = mysql_fetch_object($result);
  if($data0->status == 1) {
  	  $namelog=$data0->firstname;	
	  $sql = "select * from radusergroup where username = '".$_GET['UserName']."'";
	  $result = mysql_query($sql);
	  $data = mysql_fetch_object($result);
	  /*  echo "<script>alert('$data->GroupName'); </script>"; */
	  $sql = "select * from groups where gname = '$data->groupname'";
	  $result = mysql_query($sql);
	  $data2 = mysql_fetch_object($result);
	  if($data2->gstatus == 1) {
		  if($data2->gexpire == "0000-00-00" || $data2->gexpire >= date("Y-m-d")) {
			  /* echo "<script>alert('$data2->gencrypt'); </script>"; */
			  switch($data0->encryption) {
				case 'md5' :  $response = md5("\0" . substr(md5($_GET['Password']),0,15) . $newchal);
							  $newpwd = pack("a32", substr(md5($_GET['Password']),0,15));
							  break;
				case 'clear' : $response = md5("\0" . $_GET['Password'] . $newchal);
							  $newpwd = pack("a32", $_GET['Password']);
							  break;
			  }
		  } else {
			  $newpwd = "groupexpire";
		  }
	  } else {
		  $newpwd = "grouplock";
	  }
   } else {
	  $newpwd = "usernotallow";
   }
  
  $pappassword = implode ("", unpack("H32", ($newpwd ^ $newchal)));
  
	$sql = "select * from configuration where variable = 'redirect'";
	$result = mysql_query($sql);
	$data = mysql_fetch_object($result);
	$redirect = $data->value;
	switch($redirect) {
		case '1' : break;
		case '2' : $_GET['userurl'] = "about:home"; break;
		default : $_GET['userurl'] = $redirect; break;
	}

  $headline = 'Logging in to HotSpot';
  $bodytext = ''; 

  print_header();

  if ((isset ($uamsecret)) && isset($userpassword)) {
    print '<meta http-equiv="refresh" content="0;url=http://' . $_GET['uamip'] . ':' . $_GET['uamport'] . '/logon?username=' . $_GET['UserName'] . '&password=' . $pappassword . '&userurl=' . $_GET['userurl'] . '">'; 
  } else {
    print '<meta http-equiv="refresh" content="0;url=http://' . $_GET['uamip'] . ':' . $_GET['uamport'] . '/logon?username=' . $_GET['UserName'] . '&response=' . $response . '&userurl=' . $_GET['userurl'] . '">';
  }

   print_body();
   print_footer();

}

# 1: Login successful
if ($_GET['res'] == success) {

  $result = 1;
  $headline = 'Logged in to HotSpot';
  $bodytext = 'Welcome';
  $body_onload = 'onLoad="javascript:popUp(' . $loginpath . '?res=popup&uamip=' . $_GET['uamip'] . '&uamport=' . $_GET['uamport'] . '&userurl=' . $_GET['userurl'] . '&timeleft='  . $_GET['timeleft'] . ')"';

  print_header();
  print_body();
   
  if ($reply) { 
    print '<center>' . $reply . '</BR></BR></center>';
  }

  print '<center><a href="http://' . $_GET['uamip'] . ':' . $_GET['uamport'] . '/logoff">Logout</a></center>';

  print_footer();
}

# 2: Login failed
if ($_GET['res'] == failed) {

  $result = 2;
  $headline = 'HotSpot Login Failed';
  $bodytext = 'Sorry, try again<br>';
   
  print_header();
  print_body();

  if ($_GET['reply']) {
    print '<center>' . $_GET['reply'] . '</center>';
  }
   
  print_login_form();
  print_footer();

}

# 3: Logged out
/* if ($_GET['res'] == logoff) {

  $result = 3;
  $headline = 'Logged out from HotSpot';
  $bodytext = '<a href="http://' . $_GET['uamip'] . ':' . $_GET['uamport'] . '/prelogin">Login</a>';
   
  print_header();
  print_body();
  print_footer();

}
*/

# 4: Tried to login while already logged in
if ($_GET['res'] == already) {

  $result = 4;
  $headline = 'Already logged in to HotSpot';
  $bodytext = '<a href="http://' . $_GET['uamip'] . ':' . $_GET['uamport'] . '/logoff">Logout</a>';
   
  print_header();
  print_body();
  print_footer();

}

# 5: Not logged in yet
if ($_GET['res'] == notyet || $_GET['res'] == logoff) {

  $result = 5;
  $headline = 'Logged out from HotSpot';
  $bodytext = $inf->getTextPleaseLogin();
   
  print_header();
  print_body();
  print_login_form();
  print_footer();

}

#11: Popup1
if ($_GET['res'] == popup1) {

  $result = 11;
  $headline = 'Logged in to HotSpot';
  $bodytext = 'please wait...';
   
  print_header();
  print_body();
  print_footer();
}

#12: Popup2
if ($_GET['res'] == popup2) {

  $result = 12;
  $headline = 'Logged in to HotSpot';
  $bodytext = '';
   
  print_header();
  print_body();
  print_footer();
  
}

#13: Popup3
if ($_GET['res'] == popup3) {

  $result = 13;
  $headline = 'Logged out from HotSpot';
  $bodytext = '<a href="http://' . $_GET['uamip'] . ':' . $_GET['uamport'] . '/prelogin">Login</a>';
   
  print_header();
  print_body();
  print_footer();

}

# 0: It was not a form request
# Send out an error message
if ($_GET['res'] == "") {

  $result = 0;
  $headline = 'HotSpot Login Failed';
  $bodytext = 'Login must be performed through ChilliSpot daemon!';

  print_header();
  print_body();
  print_footer();

}

# --------------------
# functions defination
# --------------------
function debuging() {
# begin debugging
  print '<center>THE INPUT (for debugging):<br>';

    foreach ($_GET as $key => $value) {
      print $key . '=' . $value . '<br>';
    }

  print '<br></center>';
# end debugging
}

function print_header(){
  global $titel;
  global $template;
  global $loginpath;
  global $loginpath_popup;

  $uamip = $_GET['uamip'];
  $uamport = $_GET['uamport'];

  print "
  <html>
    <head>
<script language=\"javascript\">
var win = null;
function NewWindow(mypage,myname,w,h,scroll){
LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
settings =
'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
win = window.open(mypage,myname,settings)
}

</script>
	
      <title>$titel</title>
        <meta http-equiv=\"Cache-control\" content=\"no-cache\">
        <meta http-equiv=\"Pragma\" content=\"no-cache\">
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
		<link rel=\"stylesheet\" href=\"admin/templates/".$template."/css/system.css\" type=\"text/css\" />		
		<script language=\"JavaScript\">
			var blur = 0;
			var starttime = new Date();
			var startclock = starttime.getTime();
			var mytimeleft = 0;
		
		
			function doTime() {
			  window.setTimeout( \"doTime()\", 1000 );
			  t = new Date();
			  time = Math.round((t.getTime() - starttime.getTime())/1000);
			  if (mytimeleft) {
				time = mytimeleft - time;
				if (time <= 0) {
				  window.location = \"$loginpath?res=popup3&uamip=$uamip&uamport=$uamport\";
				}
			  }
			  if (time < 0) time = 0;
			  hours = (time - (time % 3600)) / 3600;
			  time = time - (hours * 3600);
			  mins = (time - (time % 60)) / 60;
			  secs = time - (mins * 60);
			  if (hours < 10) hours = \"0\" + hours;
			  if (mins < 10) mins = \"0\" + mins;
			  if (secs < 10) secs = \"0\" + secs;
			  title = \"เวลาที่คุณใช้งานอยู่ในระบบ: \";
			  title_time =  hours + \":\" + mins + \":\" + secs;
			  if (mytimeleft) {
				title = \"เหลือเวลาก่อนออกจากระบบ: \" ;
			  }
			  if(document.all || document.getElementById){
				// document.title = title;
				 document.getElementById('TitleBox').innerHTML  = title;
				 document.getElementById('TimeBox').value = title_time;
			  }
			  else {   
				self.status = title + title_time;
				
			  }
			}
		
		
			function popUp(URL) {
			  if (self.name != \"chillispot_popup\") {
				chillispot_popup = window.open(URL, 'chillispot_popup', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=375');
			  }
			}
		
			function doOnLoad(result, URL, userurl, redirurl, timeleft) {
			  if (timeleft) {
				mytimeleft = timeleft;
			  }
			  if ((result == 1) && (self.name == \"chillispot_popup\")) {
				doTime();
			  }
			  if ((result == 1) && (self.name != \"chillispot_popup\")) {
				chillispot_popup = window.open(URL, 'chillispot_popup', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=375');
			  }
			  if ((result == 2) || result == 5) {
				document.form1.UserName.focus()
			  }
			  if ((result == 2) && (self.name != \"chillispot_popup\")) {
				// chillispot_popup = window.open('', 'chillispot_popup', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=400,height=200');
				// chillispot_popup.close();
			  }
			  if ((result == 12) && (self.name == \"chillispot_popup\")) {
				doTime();
				if (redirurl) {
				  opener.location = redirurl;
				}
				else if (opener.home) {
				  opener.home();
				}
				else {
				  opener.location = userurl;
				}
				self.focus();
				blur = 0;
			  }
			  if ((result == 13) && (self.name == \"chillispot_popup\")) {
				self.focus();
				blur = 1;
			  }
			}
		
			function doOnBlur(result) {
			  if ((result == 12) && (self.name == \"chillispot_popup\")) {
				if (blur == 0) {
				  blur = 1;
				  self.focus();
				}
			  }
			}
			 function doOnUnLoad() {
            		var browser=navigator.appName;
            		var b_version=navigator.appVersion;
            		var version=parseFloat(b_version);
            		if (browser!=\"Microsoft Internet Explorer\") {
               			self.opener.location = 'http://" . $_GET['uamip'] . ":" . $_GET['uamport'] . "/logoff';
            			} else {
						self.location = 'http://" . $_GET['uamip'] . ":" . $_GET['uamport'] . "/logoff';
            			}
                        alert(\"คุณออกจากระบบเรียบร้อยแล้วครับ     \");   //ใหม่
            			self.close();
                          }
                                </script>";
}

function print_body(){
  global $headline;
  global $bodytext;
  global $body_onload;
  global $result;
  global $loginpath;
  global $loginpath_popup;
  
  $uamip = $_GET['uamip'];
  $uamport = $_GET['uamport'];
  $userurl = $_GET['userurl'];
  $redirurl = $_GET['redirurl'];
  $userurldecode = $_GET['userurl'];
  $redirurldecode = $_GET['redirurl'];
  $timeleft = $_GET['timeleft'];
  
  print "
  </head>
  <body onUnLoad=\"javascript:doOnUnLoad()\" onLoad=\"javascript:doOnLoad($result, '".$loginpath_popup."?res=popup2&uamip=$uamip&uamport=$uamport&userurl=$userurl&redirurl=$redirurl&timeleft=$timeleft','$userurldecode', '$redirurldecode', '$timeleft')\" onBlur = \"javascript:doOnBlur($result)\" >
  
  	<div id=\"wraperpop\">
  		<div id=\"popup\">
			<div id=\"loginform\">
				<div id=\"message\">$bodytext</div>
						 <form><br><div id=\"TitleBox\" ></div>
						 <input type=\"text\" id=\"TimeBox\" name=\"TimeBox\" value=\"\" >
						 <input type=\"button\" value=\"ออกจากระบบ\" class=\"button\" onclick=\"window.location='http://" . $_GET['uamip'] . ":" . $_GET['uamport'] . "/logoff'\">
						 </form>";
//	debuging();

}

function print_login_form(){
  global $loginpath;
  print '<FORM name="form1" METHOD="get" action="' . $loginpath . '?">
          <INPUT TYPE="HIDDEN" NAME="chal" VALUE="' . $_GET['challenge'] . '">
          <INPUT TYPE="HIDDEN" NAME="uamip" VALUE="' . $_GET['uamip'] . '">
          <INPUT TYPE="HIDDEN" NAME="uamport" VALUE="' . $_GET['uamport'] . '">
          <INPUT TYPE="HIDDEN" NAME="userurl" VALUE="' . $_GET['userurl'] . '">
          <input type="HIDDEN" NAME="login" value="login">
			<p>
			ชื่อผู้ใช้: <input type="text" name="UserName" class="inputbox"><BR>
			รหัสผ่าน: <input type="password" name="Password"  class="inputbox"><BR>
			<input type="submit" name="button" value="เข้าสู่ระบบ" class="button">
			</p>
     
      </form>';
}

function print_footer(){
  global $footer_text;
  print "
					
					<div class=\"clear\"></div>
				</div>
		     	<div id=\"footer\">$footer_text</div>
			</div>
			</div>
</body>
		</html>
		";
  exit(0);
}

exit(0);
//<a href=\"admin/password2.php\" onClick=\"NewWindow(this.href,'name','350','320','no');return false\"> [ Change Password ] </a>
?>


