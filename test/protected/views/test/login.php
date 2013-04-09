<table width="100%" style="margin-top: 10%;">
	<tr>
	<td align="center" valign="middle">
		<div class="notice" style="color: #c1c1c1; font-size: 9px">Please log on to use the mikrotik hotspot service<br />


</div><br />
	<table width="240" height="200" style="border: 1px solid #cccccc; padding: 0px;" cellpadding="0" cellspacing="0">
	<tr>
	<td align="center" valign="center" height="175" colspan="2">
		<form name="login" action="<?php echo $linkloginonly; ?>" method="post" onSubmit="return doLogin()" >
			<input type="hidden" name="dst" value="<?php echo $linkorig; ?>" />
			<input type="hidden" name="popup" value="true" />
						
			<table width="100" style="background-color: #ffffff">
				<tr><td align="right">login</td>
				<td><input style="width: 80px" name="username" type="text" value="<?php echo $username; ?>"/></td>
				</tr>
				<tr><td align="right">password</td>
				<td><input style="width: 80px" name="password" type="password"/></td>
				</tr>
				<tr><td> </td>
				<td><input type="submit" value="OK" /></td>
				</tr>
			</table>
		</form>
	</td>
	</tr>
	</table>
	
<br /><div style="color: #FF8080; font-size: 9px"><?php echo $error; ?></div>

	</td>
	</tr>
</table>