<table width="95%" align="center" border="0" cellspacing="10" cellpadding="0" class="header">
  <tr>
    <td width="12%" align="center"><img src="<?php echo base_url()?>assets/img/system.png" alt="" width="48" height="48" /></td>
    <td width="63%">System Information</td>
    <td width="25%" align="right"></td>
  </tr>
</table>
<hr>



<table class="system" width=100%>
<tr> <td><b>Operating system</b></td>
<td><?php echo php_uname('s')." ".php_uname('n')." on ".php_uname('m') ?></td> </tr>
<tr> <td><b>Time on system</b></td>
<td><?php 
date_default_timezone_set('Asia/Bangkok');
$format = 'DATE_RFC822';
$time = time();

echo standard_date($format, $time); ?></td> </tr>
<tr> <td><b>xx</b></td>
<td>xx</td> </tr>
</table>
