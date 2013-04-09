<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Report1';
$this->breadcrumbs=array(
	'Report1',
);
?>


<div class="row-fluid" >
	<div class="span2" style="font-size:1em; margin-left:2px;" align="right">
		<div class="row-fluid">
			<div class="span4" align="right">
				<label for="report1_month" style="font-size:1em;margin-top:7px">เดือน:</label>
			</div>
			<div class="span8" align="left">
				<select class="input-small" type="text" name="report1_month" id="report1_month" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
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
	<div class="span2" style="font-size:1em; margin-left:2px" align="left">
		<div class="row-fluid">
			<div class="span4" align="right">
				<label for="report1_year" style="font-size:1em;margin-top:7px">ปี:</label>
			</div>
      		<div class="span8" align="left">
       			<select class="input-small" type="text" name="report1_year" id="report1_year" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
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
    <div class="span8" align="right">
    	
  		

 	</div>
</div>
<div class="container" id="page" style="width:inherit"> 
 <div class="row-fluid">
   	<div class="">
		<table align="center" class="table table-bordered" id="report1_table">
          <thead bgcolor="#99CCCC">
            <tr style="font-size:0.8em;height:25px;border-color:#000;font-stretch:condensed">
            </tr>
          </thead>
          <tbody style="font-size:0.8em">
          </tbody>
        </table>
 	</div>
 </div>
 <!--
 <div class="pagination pagination-right">
  <ul>
    <li ><a href='#'><<</a></li>
    <li class="active"><a href='#'>1</a></li>
    <li ><a href='#'>2</a></li>
    <li ><a href='#'>3</a></li>
    <li ><a href='#'>>></a></li>
  </ul>
 </div>
 
 -->
</div>

<script>

//-----------> Initial Operation of Report 1 ------------->

function LastDayOfMonthRe1(Year, Month) {
	
	var report1_date = new Date( (new Date(Year, Month,1))-1 );
	
    return(report1_date.getDate());
}

function genTaleHeaderRe1(MaxDayofMonth){

	$("#report1_table thead tr th").remove();	
	
	$("#report1_table thead tr").append(
		
		"<th style='width:15%;text-align:left;padding:6px'>รายการ</th>"
	)
	
	for( var count_day = 1; count_day <= MaxDayofMonth; count_day++){
		
		$("#report1_table thead tr").append(
		
			"<th style='text-align:center;padding:4px'>"+count_day+"</th>"
		)		
		
	}		
}


function zeroPad(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}


function genOnairProgramR1(year,month){
	
	var programR1 = 0;
	var dayR1 = 01;
	var planR1 = 0;
	var curr_progID = 0;
	var prev_progname = 0;
	var MaxofMonth = 0;
	
	MaxofMonth = LastDayOfMonthRe1(year, month);
	
	$.ajaxSetup({
		async: false
	});

	$.ajax( '?r=report/japi&action=reportActualOnair&program='+programR1+'&year='+year+'&month='+month+'&day='+dayR1+'&plan='+planR1+'', {
	  type: 'GET',
	  dataType: 'json',
	  
		success: function(reportActualOnair) {
			
			$("#report1_table tbody tr").remove();
			var curr_progID = 0;
			// var current_agency = 0;
			var daycount = 0;
			var cnt_date = 0;
			$.each(reportActualOnair, function(k,v){
				
				// console.log("prog_id="+v.prog_id+"program="+v.prog_name+" day="+v.day+" time="+v.onairtime)
				if(v.prog_id ){
					
					curr_progID = v.prog_id;
					prev_progname = v.prog_name;
					current_agency = v.agency_id;
					// console.log('agency: '+current_agency);
					
					var trTable = "<tr  style='height:25px;padding-top:4px;padding-bottom:4px'>";
					trTable += "<td rowspan=2 style='text-align:left;padding:2px'>"+prev_progname+"</td>";
					var trRowSpan = "<tr  style='height:25px;padding-top:4px;padding-bottom:4px'>";
					
					for(var countdate = 1; countdate <= MaxofMonth; countdate++){
						cnt_date = zeroPad(countdate, 2);
							trTable+="<td width='40px' style='text-align:right;padding:2px' id='prog"+curr_progID+"_day"+cnt_date+"''></td>";
							trRowSpan +="<td width='40px' style='text-align:right;padding:2px' id='progest"+curr_progID+"_day"+cnt_date+"''></td>";
					}  
					trTable += "</tr>";
					trRowSpan += "</tr>";
					
					$("#report1_table tbody").append(trTable);
					$("#report1_table tbody").append(trRowSpan);
				
				} else{
					var value1 = 0;
					var onairtime = v.onairtime;
					var n=onairtime.split("."); 
					if(n.length < 2){
						onairtime = (parseFloat(onairtime)/60) + ""; 
						n=onairtime.split(".");
					}
					var hour = n[0];
					var timetoshow;
					if(n.length==1){
						timetoshow = hour+":00";
						value1 = parseFloat(hour);
					}else{
						if(n[1]<10) n[1] = n[1] * 10;
						var minute = seconds2time(3600/(100/n[1])); 
						timetoshow = hour+":"+minute;
						value1 = parseFloat(hour) + parseFloat(minute);
					}
					
					var value_2 = 0;
					var progtime = v.onairtime;
					if(typeof v.progtime != 'undefined'){
						progtime = v.progtime; 
					}
					var n_prog = progtime.split(".");
					if(n_prog.length < 2){
						progtime = (parseFloat(progtime)/60)+"";
						n_prog=progtime.split(".");
					}					 
					var hour_prog = n_prog[0];
					var timetoshow_prog;
					if(n_prog.length==1){
						timetoshow_prog = hour_prog+":00";
						value_2 = parseFloat(hour_prog);
					}else{
						if(n_prog[1]<10) n_prog[1] = n_prog[1] * 10;
						var minute_prog = seconds2time(3600/(100/n_prog[1])); 
						if(minute_prog=="") minute_prog = "00";
						timetoshow_prog = hour_prog+":"+minute_prog;
						value_2 = parseFloat(hour_prog) + parseFloat(minute_prog);
					} 
					
					onairtime = parseFloat(v.onairtime);
					var progtime = parseFloat(v.onairtime);
					if(typeof v.progtime != 'undefined'){
						progtime = parseFloat(v.progtime); 
					} 
					if(value1<value_2){
						$("#report1_table tbody tr td#prog"+curr_progID+"_day"+v.day).css('backgroundColor','#fff47a');
						$("#report1_table tbody tr td#progest"+curr_progID+"_day"+v.day).css('backgroundColor','#fff47a');
					}else if(value1>value_2){
						$("#report1_table tbody tr td#prog"+curr_progID+"_day"+v.day).css('backgroundColor','#ffa9a9');
						$("#report1_table tbody tr td#progest"+curr_progID+"_day"+v.day).css('backgroundColor','#ffa9a9');
					} 
					
					 
					$("#report1_table tbody tr td#prog"+curr_progID+"_day"+v.day).text(timetoshow_prog);
					$("#report1_table tbody tr td#progest"+curr_progID+"_day"+v.day).text(timetoshow);
	
				}
				
			});// Each Reader
		}
	}); 
	
}

function seconds2time (seconds) {
    var hours   = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds - (hours * 3600)) / 60);
    var seconds = seconds - (hours * 3600) - (minutes * 60);
    var time = "";
    if (hours != 0) {
      time = hours+":";
    }
    if (minutes != 0 || time !== "") { 
      minutes = (minutes < 10 && time !== "") ? "0"+minutes : String(minutes);
      time += minutes;//+":"; 
    }
    if (time === "") {
      //time = seconds+"";
    }
    else {
      //time += (seconds < 10) ? "0"+seconds : String(seconds);
    }
    return time;
}

$(document).ready(function() {
	
	var report1_current_date = 0;
	var report1_current_month = 0;
	var report1_current_year = 0;
	
	report1_current_date  =  new Date();
	report1_current_month =  parseInt(report1_current_date.getMonth())+1;	
	report1_current_year = parseInt(report1_current_date.getFullYear())+543;
	
	var readYear = parseInt(report1_current_year)-543;
	
	$("#report1_month").val(report1_current_month);
	$("#report1_year").val(report1_current_year);
	
	genTaleHeaderRe1( LastDayOfMonthRe1(report1_current_year,report1_current_month));
	genOnairProgramR1(readYear,report1_current_month);
});

$("#report1_month").change(function(){
	
	var report1_select_month = parseInt($("#report1_month").attr('value'));
	var report1_select_year = parseInt($("#report1_year").attr('value'))-543;	
	
	genTaleHeaderRe1( LastDayOfMonthRe1(report1_select_year, report1_select_month));
	genOnairProgramR1(report1_select_year,report1_select_month);
	
})

$("#report1_year").change(function(){
	
	var report1_select_month = parseInt($("#report1_month").attr('value'));
	var report1_select_year = parseInt($("#report1_year").attr('value'))-543;	
	
	genTaleHeaderRe1( LastDayOfMonthRe1(report1_select_year, report1_select_month));
	genOnairProgramR1(report1_select_year,report1_select_month);
	
})

//-----------> End Initial Operation of Report 1 ------------->

</script>