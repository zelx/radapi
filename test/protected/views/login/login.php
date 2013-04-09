<script>
$(document).ready(function(){
  $("button").click(function(){
    $.post("login/AddExpire",
    {
      mac:"<?php echo $mac  ?>",
      city:"Duckburg"
    },
    function(data,status){
      alert("Data: " + data + "\nStatus: " + status);
	  window.location.replace("http://google.co.th");
    });
  });
});
</script>

<button>Renew Expire</button>


