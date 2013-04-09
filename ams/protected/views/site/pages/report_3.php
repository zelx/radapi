<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Report3';
$this->breadcrumbs=array(
	'Report3',
);
?>
<div class="row-fluid" >
	<div class="span2" style="font-size:1em; margin-left:2px;" align="right">
		<div class="row-fluid">
			<div class="span4" align="right">
				<label for="report3_month" style="font-size:1em;margin-top:7px">เดือน:</label>
			</div>
			<div class="span8" align="left">
				<select class="input-small" type="text" name="report3_month" id="report3_month" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
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
				<label for="report3_year" style="font-size:1em;margin-top:7px">ปี:</label>
			</div>
      		<div class="span8" align="left">
       			<select class="input-small" type="text" name="report3_year" id="report3_year" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
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
		<table align="center" class="table table-bordered" id="report3_table">
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

function LastDayOfMonthRe3(Year, Month) {
	
	var report3_date = new Date( (new Date(Year, Month,1))-1 );
	
    return(report3_date.getDate());
}

function genTaleHeaderRe3(MaxDayofMonth){

	$("#report3_table thead tr th").remove();	
	
	$("#report3_table thead tr").append(
		
		"<th style='width:15%;text-align:center;padding:2px'>บริษัท</th>"
	)
	
	for( var count_day = 1; count_day <= MaxDayofMonth; count_day++){
		
		$("#report3_table thead tr").append(
		
			"<th style='text-align:center;padding:2px'>"+count_day+"</th>"
		)		
		
	}	
	
	$("#report3_table thead tr").append(
		
		"<th style='width:15%;text-align:center;padding:2px'>รวมเป็นเงิน</th>"
	)	
}

function zeroPadR3(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}


function genOnairProgramR3(year,month){
	
	var programR3 = 11;
	var dayR3 = 02;
	var planR3 = 0;
	var curr_progID = 0;
	var prev_progname = 0;
	var MaxofMonth = 0;
	
	 MaxofMonth = LastDayOfMonthRe3(year, month);
	
	$.ajaxSetup({
		async: false
	});
	
	$.ajax( '?r=report/japi&action=reportQuotaUsage&program='+programR3+'&year='+year+'&month='+month+'&day='+dayR3+'&plan='+planR3+'', {
	  type: 'GET',
	  dataType: 'json',
	  
		success: function(reportQuotaUsage) {
			
			var current_agency = 0;
			var current_price = 0;
			
			$("#report3_table tbody tr").remove();
			$.each(reportQuotaUsage[0], function(k,v){
				
				//console.log("agency_name="+v.agency_name+" day="+v.day+" onairtime="+v.onairtime+" price="+v.price+" quota="+v.quota));

				if(current_agency != v.agency_id){
					
					var current_agencyname = v.agency_name;
						current_price = 0;
						current_price = v.price;
						current_agency = v.agency_id;
					
					var trTable = "<tr  style='height:25px;padding-top:4px;padding-bottom:4px'>";
					trTable += "<td  rowspan=2 style='text-align:left;padding:2px'>"+current_agencyname+"</td>";
				      var trRowSpan = "<tr  style='height:25px;padding-top:4px;padding-bottom:4px'>";
					
					 for(var countdate = 1; countdate <= MaxofMonth; countdate++){
						
						cnt_date = zeroPadR3(countdate, 2);
						
							trTable+="<td  style='text-align:right;padding:2px' id='agency"+current_agency+"_day"+cnt_date+"''></td>" 
	                    
	                        trRowSpan +="<td style='text-align:right;padding:2px' id='quota"+current_agency+"_day"+cnt_date+"''></td>" 
			
					}
					trRowSpan += "</tr>";
					trTable += "<td  rowspan=2  style='text-align:right;padding:2px' id='agencyPrice"+current_agency+"'></td>";
					trTable += "</tr>";
					//trRowSpan += "</tr>";
					
					$("#report3_table tbody").append(trTable);
					$("#report3_table tbody").append(trRowSpan);
					
					
					var onairtime = v.onairtime;
					console.log("test"+v.onairtime);
					
					var n= onairtime.split("."); 
					var hour = n[0];
					var timetoshow;
					if(n.length==1){
						timetoshow = hour+":00";
					}else{
						if(n[1]<10) n[1] = n[1] * 10;
						var minute = seconds2time(3600/(100/n[1])); 
						timetoshow = hour+":"+minute;
					}
					
					var progtime = v.onairtime;
					if(typeof v.progtime != 'undefined'){
						progtime = v.progtime; 
					} 
					var n_prog=progtime.split("."); 
					var hour_prog = n_prog[0];
					var timetoshow_prog;
					if(n_prog.length==1){
						timetoshow_prog = hour_prog+":00";
					}else{
						timetoshow_prog = n_prog[1];
						if(n_prog[1]<10) n_prog[1] = n_prog[1] * 10;
						var minute_prog = seconds2time(3600/(100/n_prog[1])); 
						timetoshow_prog = hour_prog+":"+minute_prog;
					} 
					
		
				
					
					if(onairtime>progtime){
						$("#report3_table tbody tr td#agency"+current_agency+"_day"+v.day).css('backgroundColor','#ffa9a9');
						$("#report3_table tbody tr td#quota"+current_agency+"_day"+v.day).css('backgroundColor','#ffa9a9');
					}else{
						$("#report3_table tbody tr td#agency"+current_agency+"_day"+v.day).css('backgroundColor','#fff47a');
						$("#report3_table tbody tr td#quota"+current_agency+"_day"+v.day).css('backgroundColor','#fff47a');
					} 
					
					$("#report3_table tbody tr td#agency"+current_agency+"_day"+v.day).text(convert_time(v.onairtime));
					//$("#report3_table tbody tr td#quota"+current_agency+"_day"+v.day).text(convert_time(v.onairtime));
				}else{
					
					current_price = parseInt(current_price) + parseInt(v.price);
			
				var onairtime = v.onairtime;
					var n=onairtime.split("."); 
					var hour = n[0];
					var timetoshow;
					if(n.length==1){
						timetoshow = hour+":00";
					}else{
						if(n[1]<10) n[1] = n[1] * 10;
						var minute = seconds2time(3600/(100/n[1])); 
						timetoshow = hour+":"+minute;
					}
					
					var progtime = v.onairtime;
					if(typeof v.progtime != 'undefined'){
						progtime = v.progtime; 
					} 
					var n_prog=progtime.split("."); 
					var hour_prog = n_prog[0];
					var timetoshow_prog;
					if(n_prog.length==1){
						timetoshow_prog = hour_prog+":00";
					}else{
						timetoshow_prog = n_prog[1];
						if(n_prog[1]<10) n_prog[1] = n_prog[1] * 10;
						var minute_prog = seconds2time(3600/(100/n_prog[1])); 
						timetoshow_prog = hour_prog+":"+minute_prog;
					} 
					
		
				
					
					if(onairtime>progtime){
						$("#report3_table tbody tr td#agency"+current_agency+"_day"+v.day).css('backgroundColor','#ffa9a9');
						$("#report3_table tbody tr td#quota"+current_agency+"_day"+v.day).css('backgroundColor','#ffa9a9');
					}else{
						$("#report3_table tbody tr td#agency"+current_agency+"_day"+v.day).css('backgroundColor','#fff47a');
						$("#report3_table tbody tr td#quota"+current_agency+"_day"+v.day).css('backgroundColor','#fff47a');
					} 
				    $("#report3_table tbody tr td#agency"+current_agency+"_day"+v.day).text(timetoshow_prog);
					//$("#report3_table tbody tr td#quota"+current_agency+"_day"+v.day).text(timetoshow);
					//console.log("price="+current_price+" v.price="+v.price);
				}
				
				$("#report3_table tbody tr td#agencyPrice"+current_agency).text(chkNum(current_price));
				
				
				//console.log("prog_id="+v.prog_id+"program="+v.prog_name+" day="+v.day+" time="+v.onairtime)
				
			});// Each Reader
			$.each(reportQuotaUsage[1], function(ku,vu){
				var current_agency = vu.agency_id;
				monthly = vu.monthly.split(",");
				$.each(monthly,function(km,vm){
					day = zeroPadR3(km+1, 2);
					var n_prog=vm.split("."); 
					var hour_prog = n_prog[0];
					var timetoshow_prog;
					if(n_prog.length==1){
						timetoshow_prog = hour_prog+":00";
					}else{
						timetoshow_prog = n_prog[1];
						if(n_prog[1]<10) n_prog[1] = n_prog[1] * 10;
						var minute_prog = seconds2time(3600/(100/n_prog[1])); 
						timetoshow_prog = hour_prog+":"+minute_prog;
					} 

					$("#report3_table tbody tr td#quota"+current_agency+"_day"+day).text(timetoshow_prog);
					//console.log(current_agency+","+km+","+timetoshow_prog);
					});
/*				for(var countdate = 1; countdate <= MaxofMonth; countdate++){
					
					cnt_date = zeroPadR3(countdate, 2);
					
					$("#report3_table tbody tr td#quota"+current_agency+"_day"+v.day).text(timetoshow);
				
				}
*/
			});
			
		}
	});
	
}


    function convert_time(time){  	
    		var onairtime = time;
					var n=onairtime.split("."); 
					var hour = n[0];
					var timetoshow;
					if(n.length==1){
						timetoshow = hour+":00";
					}else{
						if(n[1]<10) n[1] = n[1] * 10;
						var minute = seconds2time(3600/(100/n[1])); 
						timetoshow = hour+":"+minute;
					}
					
    	return timetoshow;
       
        // throw 'bad date';
    }
    

$(document).ready(function() {
	
	var report3_current_date = 0;
	var report3_current_month = 0;
	var report3_current_year = 0;
	
	report3_current_date  =  new Date();
	report3_current_month =  parseInt(report3_current_date.getMonth())+1;	
	report3_current_year = parseInt(report3_current_date.getFullYear())+543;
	
	var readYear = parseInt(report3_current_year)-543;
	
	$("#report3_month").val(report3_current_month);
	$("#report3_year").val(report3_current_year);
	
	genTaleHeaderRe3( LastDayOfMonthRe3(report3_current_year,report3_current_month));
	genOnairProgramR3(readYear,report3_current_month);
});


$("#report3_month").change(function(){
	
	var report3_select_month = parseInt($("#report3_month").attr('value'));
	var report3_select_year = parseInt($("#report3_year").attr('value'))-543;	
	
	genTaleHeaderRe3( LastDayOfMonthRe3(report3_select_year, report3_select_month));
	genOnairProgramR3(report3_select_year,report3_select_month);
	
})

$("#report3_year").change(function(){
	
	var report3_select_month = parseInt($("#report3_month").attr('value'));
	var report3_select_year = parseInt($("#report3_year").attr('value'))-543;	
	
	genTaleHeaderRe3( LastDayOfMonthRe3(report3_select_year, report3_select_month));
	genOnairProgramR3(report3_select_year,report3_select_month);
	
})


</script>
