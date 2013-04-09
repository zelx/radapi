<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Report2';
$this->breadcrumbs=array(
	'Report2',
);
?>

<div class="row-fluid" >
	<div class="span2" style="font-size:1em; margin-left:2px;" align="right">
		<div class="row-fluid">
			<div class="span4" align="right">
				<label for="report2_month" style="font-size:1em;margin-top:7px">เดือน:</label>
			</div>
			<div class="span8" align="left">
				<select class="input-small" type="text" name="report2_month" id="report2_month" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
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
				<label for="report2_year" style="font-size:1em;margin-top:7px">ปี:</label>
			</div>
      		<div class="span8" align="left">
       			<select class="input-small" type="text" name="report2_year" id="report2_year" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
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
		<table align="center" class="table table-bordered" id="report2_table">
          <thead bgcolor="#99CCCC">
            <tr style="font-size:0.8em;height:25px;border-color:#000;font-stretch:condensed">
             	<th style="width:30%;text-align:center;padding:6px">รายการ</th>
             	<th style="width:15%;text-align:center;padding:6px">วันที่ออกอากาศ</th>
            	<th style="width:15%;text-align:center;padding:6px">เวลาที่ออกอากาศ</th>
              	<th style="width:20%;text-align:center;padding:6px">จำนวนโฆษณารวม(นาที)</th>
              	<th style="width:20%;text-align:center;padding:6px">จำนวนรายได้รวม(บาท)</th>
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


function zeroPadR2(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}


function genOnairProgramR2(year,month){
	
	var programR2 = 0;
	var dayR2 = 01;
	var planR2 = 0;
	
	//console.log("year="+year+" month="+month);

	
	$.ajaxSetup({
		async: false
	});
	
	$.ajax( '?r=report/japi&action=reportIncomeByProgram&program='+programR2+'&year='+year+'&month='+month+'&day='+dayR2+'&plan='+planR2+'', {
	  type: 'GET',
	  dataType: 'json',
	  
		success: function(reportIncomeByProgram) {
			
			$("#report2_table tbody tr").remove();
			$.each(reportIncomeByProgram, function(k,v){
				
				//console.log("prog_id="+v.prog_id+"program="+v.prog_name+" day="+v.day+" time="+v.onairtime)
				//console.log("v="+v.prog_name);
				
				$.each(v.onairweekly.split(','),function(k,v){
						console.log(v);
					});
				var daystr = "";
				if(v.onairweekly == "0,6"){
					daystr = "เสาร์ - อาทิตย์";
				}else if(v.onairweekly == "1,2,3,4,5"){
					daystr = "จันทร์ - ศุกร์";
				}else{
					$.each(v.onairweekly.split(','),function(k,v){
						switch (v){
							case "0":
								daystr += "อาทิตย์";
							break;
							case "1":
								daystr += "จันทร์";
							break;
							case "2":
								daystr += "อังคาร";
							break;
							case "3":
								daystr += "พุทธ";
							break;
							case "4":
								daystr += "พฦหัส";
							break;
							case "5":
								daystr += "ศุกร์";
							break;
							case "6":
								daystr += "เสาร์";
							break;
						}
					});
				}
				$("#report2_table tbody").append(
					"<tr  style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
						"<td style='text-align:left;padding-top:8px'>"+v.prog_name+"</td>" + 
						"<td style='text-align:center;padding-top:8px'>"+daystr+"</td>" +
						"<td style='text-align:right;padding-top:8px'>"+v.onairstart+"</td>" +
						"<td style='text-align:right;padding-top:8px'>"+chkNum(v.adv_time)+"</td>" + 
						"<td style='text-align:right;padding-top:8px'>"+chkNum(v.price)+"</td>" +
					"</tr>" 
				);

				
			});// Each Reader
		}
	});
	
}

$(document).ready(function() {
	
	var report2_current_date = 0;
	var report2_current_month = 0;
	var report2_current_year = 0;
	
	report2_current_date  =  new Date();
	report2_current_month =  parseInt(report2_current_date.getMonth())+1;	
	report2_current_year = parseInt(report2_current_date.getFullYear())+543;
	
	var readYear = parseInt(report2_current_year)-543;
	
	$("#report2_month").val(report2_current_month);
	$("#report2_year").val(report2_current_year);
	
	//genTaleHeaderRe2( LastDayOfMonthRe1(report1_current_year,report1_current_month));
	genOnairProgramR2(readYear,report2_current_month);
});


$("#report2_month").change(function(){
	
	var report2_select_month = parseInt($("#report2_month").attr('value'));
	var report2_select_year = parseInt($("#report2_year").attr('value'))-543;	
	
	//genTaleHeaderRe1( LastDayOfMonthRe2(report2_select_year, report2_select_month));
	genOnairProgramR2(report2_select_year,report2_select_month);
	
})

$("#report2_year").change(function(){
	
	var report2_select_month = parseInt($("#report2_month").attr('value'));
	var report2_select_year = parseInt($("#report2_year").attr('value'))-543;	
	
	//genTaleHeaderRe1( LastDayOfMonthRe2(report2_select_year, report2_select_month));
	genOnairProgramR2(report2_select_year,report2_select_month);
	
})


function addCommas(nStr){
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function chkNum(num){
	var num1 = parseFloat(num);
	return addCommas(num1.toFixed(2));
}


//-----------> End Initial Operation of Report 1 ------------->

</script>
