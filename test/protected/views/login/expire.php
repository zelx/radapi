<script>
$(document).ready(function(){
  $("button").click(function(){
    $.post("AddExpire",
    {
      mac:"<?php echo $_GET['mac'] ?>"
    },
    function(data,status){
      alert("Data: " + data + "\nStatus: " + status);
	  window.location.replace("http://google.co.th");
    });
  });
});
</script>

<button>Renew Expire</button>


