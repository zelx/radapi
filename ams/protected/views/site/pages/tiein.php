<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Tie-in';
$this->breadcrumbs=array(
	'Tie-in',
);
date_default_timezone_set('Asia/Bangkok');
?>
<style>

#onair_prodlist_sp{ position: absolute; border: 1px solid #e3e3e3; }
div.toolTip{ padding-left: 10px; padding-right: 10px; }
div.toolTip:hover{ border: 1px solid #e3e3e3; background-color: blue; color:black; }


#breakinglist { list-style-type: none; margin: 0; padding: 0; width:auto; }
#breakinglist li { margin: 3px 3px 3px 0; padding: 1px;font-size: 3em; text-align:left; }
#advplan li {font-size:3em;}
#history { list-style-type: none; margin: 0; padding: 0; width:auto; }
#history li { margin: 3px 3px 3px 0; padding: 1px;font-size: 1em; text-align:left; }
#break1 {font-size: 3em;}
/*
#ui-datepicker-div {
	width:200px;
	font-size:0.3em;
}
*/


.tooltipster-shadow {
	border-radius: 5px;
	background: #fff;
	box-shadow: 0px 0px 14px rgba(0,0,0,0.3);
	color: #2c2c2c;
}
.tooltipster-shadow .tooltipster-content {
	font-family: 'Arial', sans-serif;
	font-size: 14px;
	line-height: 16px;
	padding: 8px 10px;
}



.ui-ams-advbrk {font-size: 1.6em;  color: #ffffff;
  background-color: #0088cc;
    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px; 
}

.ui-ams-advbrk2 {font-size: 1.6em;  color: #ffffff;
  background-color: #0088cc;
    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px; 
}
.progress {
	background-color: #0088cc;
    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px;
}

.ui-ams-adv {
	margin: 0;
	height: 25px;
/*    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px;*/ 
}

.ui-ams-advpending{font-size: 5em;  color: #ffffff;
  background-color:#F90;
    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px; 
}

.ui-ams-warning{font-size: 0.95em;  color: #ffffff;
  background-color:#F90;
    -webkit-border-radius: 2px;
     -moz-border-radius: 2px;
          border-radius: 2px; 
		text-decoration: underline;
}

.ui-ams-success{font-size: 0.95em;  color: #ffffff;
  background-color:#0C0;
    -webkit-border-radius: 2px;
     -moz-border-radius: 2px;
          border-radius: 2px; 
}

.ui-ams-danger{font-size: 0.95em;  color: #ffffff;
  background-color:#F03;
    -webkit-border-radius: 2px;
     -moz-border-radius: 2px;
          border-radius: 2px; 
}

.ui-ams-inactive{font-size: 0.95em;  color: #ffffff;
  background-color:#CCC;
    -webkit-border-radius: 2px;
     -moz-border-radius: 2px;
          border-radius: 2px; 
}


.ui-ams-disable-link{   

pointer-events: none;
   cursor: default;
   
}

#toolbar {
 	padding: 10px 4px;
}

#breakinglist > .ui-ams-advbrk > .row-fluid > .span4 > .ui-button-text {
	color:#fff;
	padding: 1px 2px;
}

#tieintable
{
	font-size:14px;}
	

#tieintable_add
{ font-size:14px;}
</style>
<!-- start page -->
<div class="row-fluid" >
<div>
	<div class="row-fluid" style="margin-top:10px">
        <div class="span8" style="margin-left:20px">
        	<div class="row-fluid">
        		<div class="span1" align="right">
            		<label for="tiein_prog_list" style="font-size:1em; margin-top:3px">รายการ:</label>
                </div>
                <div class="span11" align="left" style="margin-left:10px">
            		<select name="tiein_prog_list" id="tiein_prog_list" style="font-size:1em;width:100;padding-top:3px;padding-bottom:3px" value=""></select>
        		</div>
            </div>
        </div>
        <div class="span2" style="font-size:1em; margin-left:2px;" align="right">
        	<div class="row-fluid">
        		<div class="span4" align="right">
            		<label for="tiein_month_list" style="font-size:1em;margin-top:3px"">เดือน:</label>
                </div>
                <div class="span6" align="left">
                    <select class="input-small" type="text" name="tiein_month_list" id="tiein_month_list" style="font-size:1em;width:120px;padding-top:3px;padding-bottom:3px" value="">
                            <option value="1" >มกราคม</option> 
                            <option value="2" >กุมภาพันธ์</option> 
                            <option value="3" >มีนาคม</option> 
                            <option value="4" >เมษายน</option> 
                            <option value="5" >พฤษภาคม</option> 
                            <option value="6" >มิถุนายน</option> 
                            <option value="7" >กรกฎาคม</option> 
                            <option value="8" >สิงหาคม</option> 
                            <option value="9" >กันยายน</option> 
                            <option value="10" >ตุลาคม</option> 
                            <option value="11" >พฤศจิกายน</option> 
                            <option value="12" >ธันวาคม</option> 
                    </select>
        		</div>
          	</div>
        </div>
        <div class="span2" style="font-size:1em; margin-left:2px" align="right">
        	<div class="row-fluid">
        		<div class="span4" align="right">
            		<label for="tiein_year_list" style="font-size:1em;margin-top:3px"">ปี:</label>
            	</div>
                <div class="span6" align="left">
                    <select class="input-small" type="text" name="tiein_year_list" id="tiein_year_list" style="font-size:1em;width:120px;padding-top:3px;padding-bottom:3px" value="">
                          <option value="2553" >2553</option> 
                          <option value="2554" >2554</option> 
                          <option value="2555" >2555</option> 
                          <option value="2556" >2556</option> 
                          <option value="2557" >2557</option> 
                          <option value="2558" >2558</option> 
                          <option value="2559" >2559</option> 
                          <option value="2560" >2560</option> 
                          <option value="2561" >2561</option> 
                          <option value="2562" >2562</option> 
                          <option value="2563" >2563</option> 
                          <option value="2564" >2564</option> 
                          <option value="2565" >2565</option> 
                          <option value="2566" >2566</option> 
                          <option value="2567" >2567</option> 
                          <option value="2568" >2568</option> 
                          <option value="2569" >2569</option> 
                          <option value="2570" >2570</option> 
                    </select>
            	</div>
        	</div>
        </div>
      </div>
	</div>
	<div style="font-size:0.5em;" id="div_tiein_daytab" class="ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible" >
        <ul id="ul_tiein_daytab" class="nav ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" style="padding:2px 2px 0px 2px; height:32px" >
        </ul>
    </div>
    <!--   content = = ===============================================-->
    <div class="row-fluid"  align="center" style="margin-top:15px">
    	<div class="">
        	<!--- <h4> เพิ่ม tie-in </h4> --->
        	 <table align="center" class="table" id="tieintable_add" >
              <thead align="center">              
                <tr style="font-size:0.8em;height:25px;">
                  <th style="width:20%;text-align:left;padding:6px"><select style=" margin-bottom:0px" name=""><option value="">เลือกเอเจนซี่</option></select></th>
                  <th style="width:20%;text-align:left;padding:6px"><input style=" margin-bottom:0px" name="" type="text" disabled="disabled" placeholder="สินค้า"/></th>
                  <th style="width:20%;text-align:left;padding:6px"><select style=" margin-bottom:0px" name=""><option value="">เลือกแพ็คเกจ</option></select></th>
                  <th style="width:20%;text-align:left;padding:6px"><input style=" margin-bottom:0px" name="" type="text" placeholder="จำนวนเงิน" /></th>
                  <th style="width:12%;text-align:left;padding:6px"><input style=" margin-bottom:0px;width:140px" name="" type="text" placeholder="หมายเหตุ"/></th>
                  <th style="width:12%;text-align:left;padding:6px"><button  type="button" class="btn btn-success" style=" font-size:14px;width:65px" onclick="">เพิ่ม</button></th>
                  
                <!---  <th style="width:8%;text-align:center;padding:6px"><a class="btn btn-small" href="#">
                  <i class="icon-plus-sign"></i></a></th> -->	
                </tr>
              </thead>
            </table>
		</div>
 	</div>
    <div class="row-fluid"  align="center">
    	<div class="">
            <div class="container" id="page" style="width:inherit">
              <div class="row-fluid"  align="left">
                <div class="">
                    <table align="center" class="table table-striped" id="tieintable" >
                     <thead align="center">              
                        <tr style="font-size:0.8em;height:25px;">
                            <th style="width:20%;text-align:left;padding:6px">เอเจนซี่</th>
                            <th style="width:20%;text-align:left;padding:6px">สินค้า</th>
                            <th style="width:15%;text-align:left;padding:6px">แพ็คเกจ</th>
                             <th style="width:15%;text-align:left;padding:6px">จำนวนเงิน</th>
                            <th style="width:15%;text-align:left;padding:6px">หมายเหตุ</th>
                            <th style="width:5%;text-align:center;padding:6px">แก้ไข</th>
                            <th style="width:5%;text-align:center;padding:6px">ลบ</th> 
                        </tr>
                     </thead>
                    <tbody style="font-size:0.8em;height:25px">
                    </tbody>
                   </table>
               	</div>
           	   </div>
           	 </div>
      	</div>
 	</div>
    
<script>

	$(document).ready(function() {
		
		var onair_current_date = 0;
		var onair_current_month = 0;
		var onair_current_year = 0;
		var onair_current_day = 0;
		
		onair_current_date  =  new Date();
		onair_current_month =  parseInt(onair_current_date.getMonth())+1;	
		onair_current_year = parseInt(onair_current_date.getFullYear())+543;
		onair_current_day = onair_current_date.getDate();
			
		$("#tiein_month_list").val(onair_current_month);
		$("#tiein_year_list").val(onair_current_year);
	
			
		for(var i = 1; i < 32; i++){
				
				$("#ul_tiein_daytab").append("<li id='li_tiein_daytab"+i+"' class='disable' ><a id='a_tiein_daytab' class='ui-ams-daytab ui-ams-disable-link' style='padding:5px 3px 5px 3px;'>"+i+"</a></li>");
					
		}
		
		readProgTieinList() // Show the list of program
		
	
		var tiein_prog = $("#tiein_prog_list").attr('value');
		var tiein_month = parseInt($("#tiein_month_list").attr('value'));
		var tiein_year = parseInt($("#tiein_year_list").attr('value'))-543;
			
		alarming_Tiein_Daytab(tiein_prog,tiein_year,tiein_month);	
		
	});



	function default_Teiin_Daytab(){
		
		for(var i = 1; i < 32; i++){
			
		  $("#li_tiein_daytab"+parseInt(i)).children("a#a_tiein_daytab").addClass("ui-ams-daytab ui-ams-disable-link");
					
		}
	}


	function readProgTieinList(){ 
	
		$.ajaxSetup({
			async: false
		});
		$.ajax('?r=onair/japi&action=progList',{
			type: 'GET',
			dataType: 'json',
			success: function(progList){
				//var breakid=0;
				$("#tiein_prog_list option").remove();			
				$.each(progList,function(k,v){ 
					$.each(v,function(kon,von){
						$("#tiein_prog_list").append(
														 
							"<option value='"+von.prog_id+"'>"+von.prog_name+"</option>"
							
						);
					});
				});			
			}
		});
	}


//------------>Start Alarming DayTab --------------->

	function alarming_Tiein_Daytab(prog_on,onair_year,onair_mon){
		
			console.log("alarming_Tiein_Daytab")
			console.log("prog_on= "+prog_on+" onair_year= "+onair_year+" onair_mon= "+onair_mon);
		
			$.ajaxSetup({
				async: false
			});
			
			$.ajax( '?r=onair/japi&action=DailyUsageTimeofMonth&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day=1', {
			  type: 'GET',
			  dataType: 'json',
				  success: function(DailyUsageTimeofMonth) {
					
					console.log("DailyUsageTimeofMonth= "+DailyUsageTimeofMonth)
					
					
					$("#ul_tiein_daytab li").removeClass();
					$.each(DailyUsageTimeofMonth, function(k,v){
						
						$("#li_tiein_daytab"+parseInt(v.day)).removeClass();
						$("#li_tiein_daytab"+parseInt(v.day)).children("a#a_tiein_daytab").removeClass("ui-ams-disable-link");
						
						if(parseInt(k) == 0){
							
							$("#li_tiein_daytab"+parseInt(v.day)).addClass("ui-tabs-selected ui-state-active");
							
						}
						
						if(parseInt(v.total_advqueue_time) > parseInt(v.total_break_time)){
							
							$("#li_tiein_daytab"+parseInt(v.day)).addClass("ui-ams-warning");
							
						}else if((parseInt(v.total_advqueue_time) == parseInt(v.total_break_time)) && (!parseInt(v.total_advpending_time))){
							
							$("#li_tiein_daytab"+parseInt(v.day)).addClass("ui-ams-success");
							
						}else {
							
							$("#li_tiein_daytab"+parseInt(v.day)).addClass("ui-ams-danger");
							
						}
						
						
					});
				}
		  });		
	}

//<------------End of Alarming DayTab <--------------

</script>
</div>
</div>