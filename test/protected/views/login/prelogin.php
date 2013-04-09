<center>If you are not redirected in a few seconds, click 'continue' below<br>
<form name="redirect" action="<?php echo $redirect; ?>" method="post">
<?php	foreach ($_GET as $key => $value) print implode('', array(
			"<input type=\"hidden\" name=\"{$key}\" value=\"{$value}\">",
			"\n"
		));
?>
<input type="submit" value="continue">
</form>
<script language="JavaScript">
<!--
	document.redirect.submit();
//-->
</script></center>