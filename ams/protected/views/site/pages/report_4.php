<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Report4';
$this->breadcrumbs=array(
	'Report4',
);
?>
<div class="row-fluid" >
	<div class="span6" style="font-size:1em; margin-left:2px;" align="left">
		<div class="row-fluid">
			<div class="span2" align="right">
				<label for="report4_month" style="font-size:1em;margin-top:4px">รายการ:</label>
			</div>
			<div class="span10" align="left">
            	<select name="report4_proglist" id="report4_proglist" style="font-size:1em;width:100;padding-top:3px;padding-bottom:3px" value=""></select>
			</div>
    	</div>
	</div>
	<div class="span6" style="font-size:1em; margin-left:2px" align="right">
		<?php
        	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
            	'name'=>'my_date',
				'id'=>'report4_date',
				'value'=>date('d/m/Y'),
           		'language'=>Yii::app()->language=='et' ? 'et' : null,
             	'options'=>array(
                	'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
                	'showOn'=>'button', // 'focus', 'button', 'both'
                 	'buttonText'=>Yii::t('ui','Select form calendar'),
                	'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
					'dateFormat'=>'dd/mm/yy',
                 	'buttonImageOnly'=>true
                 ),
               	'htmlOptions'=>array(
                	'style'=>'width:180px;vertical-align:top'
           		)
      		));   
  		?>	
  	</div>
    
   <!---
    <div class="span8" align="right">
    	<input type="text" name="report4_search_prog" id="report4_search_prog" size="10" >
		<div style="float:right; margin-top:0px;font-size:1em;"><input type="button" name="report4_search_clear" id="report4_search_clear" class="btn btn-info" value="ยกเลิก" onclick="$('#search_list').val(''); show_product_list(2);"></div>
  		<div style="float:right; margin-top:0px;font-size:1em;"><input type="button" name="report4_search" id="report4_search" class="btn btn-info" value="ค้นหา" onclick="show_product_list(2,$('#search_list').val());"></div>  

 	</div>
    
   --->
</div>
<div class="container" id="page" style="width:inherit"> 
 <div class="row-fluid">
   	<div class="">
		<table align="center" class="table table-bordered" id="report4_table">
          <thead bgcolor="#99CCCC">
            <tr style="font-size:0.8em;height:25px;border-color:#000;font-stretch:condensed">
             	<th style="width:10%;text-align:center;padding:6px">วันออกอากาศ</th>
             	<th style="width:5%;text-align:center;padding:6px"></th>
            	<th style="width:30%;text-align:center;padding:6px">สินค้า/ชุด</th>
              	<th style="width:5%;text-align:center;padding:6px">วินาที</th>
              	<th style="width:10%;text-align:center;padding:6px">PACK</th>
             	<th style="width:10%;text-align:center;padding:6px">บริษัท</th>
            	<th style="width:8%;text-align:center;padding:6px">ออกอากาศ</th>
              	<th style="width:15%;text-align:center;padding:6px">รายการ</th>
              	<th style="width:7%;text-align:center;padding:6px">หมายเหตุ</th>
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

	function readReport4ProgList(){ 
	
		$.ajaxSetup({
			async: false
		});
		$.ajax('?r=onair/japi&action=progList',{
			type: 'GET',
			dataType: 'json',
			success: function(progList){
				$("#report4_proglist option").remove();			
				$.each(progList,function(k,v){ 
					$.each(v,function(kon,von){
						$("#report4_proglist").append(
														 
							"<option value='"+von.prog_id+"'>"+von.prog_name+"</option>"
						);
					});
								
				});
			}
	
		});
	}
	
	function report4PrpgramQueue(prog_id, year, month, day, plan){
		
		//console.log("prog_id="+prog_id+"year"+year+"month"+month+"day"+day)
		
		$.ajaxSetup({
			async: false
		});
		
		$.ajax( '?r=report/japi&action=reportProgramQueue&program='+prog_id+'&year='+year+'&month='+month+'&day='+day+'&plan='+plan+'', {
		  type: 'GET',
		  dataType: 'json',
		  
			success: function(reportProgramQueue) {
				
				$("#report4_table tbody tr").remove();

				$.each(reportProgramQueue, function(k,v){
					
					//console.log("v.prod_name="+v.prod_name)
					
					$("#report4_table tbody").append(
					
						"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
							"<td style='text-align:center'>"+format_datetime(v.onairdate)+"</td>" + 
							"<td style='text-align:center'>เทป</td>" +
							"<td style='text-align:left'>"+v.prod_name+" ("+v.tape_name+")</td>" +
							"<td style='text-align:right'>"+v.adv_time_len+"</td>" + 
							"<td style='text-align:left'>"+v.price_type+"</td>" +
							"<td style='text-align:left'>"+v.agency_name+"</td>" +
							"<td style='text-align:right'>"+v.onairtime+"</td>" +
							"<td style='text-align:left'>"+v.prog_name+"</td>" + 
							"<td style='text-align:left'>Break"+v.break_seq+"</td>" +
						"</tr>" 
					);	
					
				});// Each Reader
			}
		});
	}
	

	$(document).ready(function() {
		
		var report4_current_date = 0;
		var report4_current_month = 0;
		var report4_current_year = 0;
		var report4_program = 0;
		
		readReport4ProgList();
		
		
		var report4_current_date =  Date.parseExact($("#report4_date").val(),"d/M/yyyy");
			report4_current_date =  new Date(report4_current_date);
		
		if(report4_current_date == "Invalid Date"){
			
			report4_current_date  =  new Date();
			
		}	
		
		report4_current_day = report4_current_date.getDate();
		report4_current_month =  parseInt(report4_current_date.getMonth())+1;	
		report4_current_year = report4_current_date.getFullYear();
		//$("#report4_date").val(report4_current_day+"/"+report4_current_month+"/"+report4_current_year);
		report4_current_program = $("#report4_proglist").val();
		
		//console.log("prog_idFirst="+report4_current_program)
		
		report4PrpgramQueue(report4_current_program,report4_current_year,report4_current_month,report4_current_day,0);
		
	});
	
	$("#report4_proglist").change(function(){
		
		//add_pkg_start = add_pkg_start.toString("yyyy-M-d");
		
		
		var report4_select_date =  Date.parseExact($("#report4_date").val(),"d/M/yyyy");
			report4_select_date =  new Date(report4_select_date);
		
		if(report4_select_date == "Invalid Date"){
			
			report4_select_date  =  new Date();
			
		}		
				
		var report4_select_day = report4_select_date.getDate();
		var report4_select_month =  parseInt(report4_select_date.getMonth())+1;	
		var report4_select_year = report4_select_date.getFullYear();
		var report4_select_program = $("#report4_proglist").val();
		
		report4PrpgramQueue(report4_select_program, report4_select_year, report4_select_month, report4_select_day, 0);		

	})
	
	$("#report4_date").change(function(){
		
		var report4_select_date =  Date.parseExact($("#report4_date").val(),"d/M/yyyy");
			report4_select_date =  new Date(report4_select_date);
		
		if(report4_select_date == "Invalid Date"){
			
			report4_select_date  =  new Date();
			
		}		
				
		var report4_select_day = report4_select_date.getDate();
		var report4_select_month =  parseInt(report4_select_date.getMonth())+1;	
		var report4_select_year = report4_select_date.getFullYear();
		var report4_select_program = $("#report4_proglist").val();
		
		report4PrpgramQueue(report4_select_program, report4_select_year, report4_select_month, report4_select_day, 0);
		
		
	})
	

</script>