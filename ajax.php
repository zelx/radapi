<html>
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	$("#btn1").click(function(){
			$.ajax({ 
				url: "http://192.168.70.131/test/radapi" ,
				type: "POST",
				data: $("#txtCountryCode").val()
			})
			.success(function(result) { 

					$("#div1").empty();

				var obj = jQuery.parseJSON(result);
				  $.each(obj, function(key, val) {

					   $("#div1").append('<hr />');
					   $("#div1").append('[' + key + '] ' + 'userid=' + val["userid"] +'<br />');
			

				  });

			});

		});
	});
</script>
</head>
<body>
test json
<input type="text" id="txtCountryCode">
<input type="button" id="btn1" value="Search">
<div id="div1"></div>

</body>
</html>
