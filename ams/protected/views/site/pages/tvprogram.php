<?php
/* @var $this SiteController */

date_default_timezone_set('Asia/Bangkok');
$this->pageTitle=Yii::app()->name . ' - TV Program';
$this->breadcrumbs=array(
	'TV Program',);
?>

<style>

.ui-ams-disable-link{   

pointer-events: none;
   cursor: default;
   
}

</style>


<script>

	function dayWeekToDay(dayofweek){
		
		dayofweek = parseInt(dayofweek);
		
		switch(dayofweek){
			
			case 0:
				  var day_name = "อาทิตย์";
			  break;
			case 1:
				  var day_name = "จันทร์";
			  break;
			case 2:
				  var day_name = "อังคาร";
			  break;
			 case 3:
				  var day_name = "พุธ";
			  break;
			case 4:
				  var day_name = "พฤหัสบดี";
			  break;
			case 5:
				  var day_name = "ศุกร์";
			  break;
			 case 6:
				  var day_name = "เสาร์";
			  break;
		}
		
		return(day_name);		
		
		
	}

	function filterTimeMinSec(time){
		
		
		
		var timeHourMinSec =  time.split(':');
		var timeMinSec = timeHourMinSec[0]+":"+timeHourMinSec[1];
		
		return timeMinSec;
		
	}



    function money_forchange(input_money)//swordzoro
    {
        if( jQuery.isNumeric(input_money))
        {            
            var nStr = ""+input_money;
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
        else
        {
            //console.log(input_money+":It is'nt numeric data!");
            return input_money;
        }
    }
	
//--------> start Reading TVProgram form DB ------------	

	function sumTimeCntBk(onair_prof_id,time_start,day_week){
		
		var timeBkNum = [];
		
		$.ajaxSetup({
				async: false
		});					   		
		$.ajax('?r=program/japi&action=sumTimeCountBk&onair_prof_id='+onair_prof_id+'&time_start='+time_start+'&day_week='+day_week+'',{
			type: 'GET',
			dataType: 'json',
			success: function(sumTimeCountBk){	
			
				$.each(sumTimeCountBk,function(kpro,vpro){	
				
					timeBkNum = [vpro.total_bktime,vpro.total_bknum];
					
				});
				
			}
		});
	
		return(timeBkNum)	
		
	}



	function showtable(prog_start_row,prog_stop_row,search_progname){


		$.ajaxSetup({
				async: false
		});
									   		
		$.ajax('?r=program/japi&action=reader&prog_start_row='+prog_start_row+'&prog_stop_row='+prog_stop_row+'&search_prog='+search_progname+'',{
			type: 'GET',
			dataType: 'json',
			success: function(reader){
			//var breakid=0;
			$("#progtable tbody tr").remove();	
			
					var current_progID = 0;
					var TimeCntBk = [];
			
					$.each(reader,function(kpro,vpro){
						
						
						TimeCntBk = sumTimeCntBk(vpro.onair_prof_id,vpro.time_start,vpro.dayweek_num);
						var sumTime = TimeCntBk[0];
						var countBK = TimeCntBk[1];
					
						if(current_progID != vpro.prog_id){
							
							current_progID = vpro.prog_id;
					
							var programTable = "<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
									"<td style='text-align:left;padding-top:8px'>"+vpro.prog_name+"</td>"+
									"<td style='text-align:left;padding-top:8px'>"+dayWeekToDay(vpro.dayweek_num)+" ("+filterTimeMinSec(vpro.time_start)+"-"+filterTimeMinSec(vpro.time_end)+")</td>" +
									"<td style='text-align:left;padding-top:8px'>"+vpro.comp_name+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+countBK+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+sumTime+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+money_forchange(vpro.price_minute)+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+money_forchange(vpro.price_pack)+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+vpro.minute_pack+"</td>" +
									"<td style='text-align:left;padding-top:8px'>"+vpro.prog_desc+"</td>" +
									"<td style='text-align:center;padding-top:8px'>"+
										"<a title='แก้ไข' onclick=updateProgram("+vpro.prog_id+","+vpro.onair_prof_id+");>"+
											"<img src='images/pen.png' style='width:20px;margin-right:5px;cursor:pointer' align='center' />"+
										"</a>"+
									"</td>" +
									"<td style='text-align:center;padding-top:8px'>"+
										"<a title='ลบ' onclick=deleteprog("+vpro.prog_id+");>"+
											"<img src='images/delete.png' style='width:20px;margin-right:5px;cursor:pointer' align='center' />"+
										"</a>"+
									"</td>" +
								"</tr>";
								
						}else {
							
							var programTable = "<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
									"<td style='text-align:left;padding-top:8px'></td>"+
									"<td style='text-align:left;padding-top:8px'>"+dayWeekToDay(vpro.dayweek_num)+" ("+filterTimeMinSec(vpro.time_start)+"-"+filterTimeMinSec(vpro.time_end)+")</td>" +
									"<td style='text-align:left;padding-top:8px'>"+vpro.comp_name+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+countBK+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+sumTime+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+money_forchange(vpro.price_minute)+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+money_forchange(vpro.price_pack)+"</td>" + 
									"<td style='text-align:right;padding-top:8px'>"+vpro.minute_pack+"</td>" +
									"<td style='text-align:left;padding-top:8px'>"+vpro.prog_desc+"</td>" +
									"<td style='text-align:center;padding-top:8px'></td>" +
									"<td style='text-align:center;padding-top:8px'></td>" +
								"</tr>";  							
							
							
						}
						
						$("#progtable tbody").append(programTable);

						//deter_breaksum(vpro.prog_id); // -------> Sum break property NEXT STEP insert DATE when using search function
						
					});
			 }
		});
	}
	
	
	
//--------> End Reading Program DB ------------	



$(document).ready(function(){
	
	show_tvprogram_list(2);
	
});


function show_tvprogram_list(prog_index_page,search_progname){
	
		var prog_start_row = 1;
		var prog_stop_row = 5;
		
		var prog_current_page = 0;
		var prog_current_id = "tvprogram_page_2";
		var prog_current_val = 1;
		
		var check_page = "tvprogram_page_"+prog_index_page;
		
		prog_stop_row = $("#num_row_tvprogram").attr("value");
		
		if(check_page == "tvprogram_page_1"){
			
			prog_current_id = $("#tvprogram_page").find("li.active").attr('id');
			prog_current_val = $("#"+prog_current_id).attr('value');
			
			if(prog_current_val > "2"){ // Larger than first page
				
				prog_current_val = parseInt(prog_current_val)-1;
				
				$("#tvprogram_page").find("li.active").removeClass("active");;
				$("#tvprogram_b_"+prog_current_val).addClass("active");	
				
			}else if(prog_current_val == "2" && $("#tvprogram_page_2").text() > "1"){
				
				prog_current_val = 6;
					
				$("#tvprogram_page_2").text(parseInt($("#tvprogram_page_2").text())-5);
				$("#tvprogram_page_3").text(parseInt($("#tvprogram_page_3").text())-5);
				$("#tvprogram_page_4").text(parseInt($("#tvprogram_page_4").text())-5);
				$("#tvprogram_page_5").text(parseInt($("#tvprogram_page_5").text())-5);
				$("#tvprogram_page_6").text(parseInt($("#tvprogram_page_6").text())-5);
				
				$("#tvprogram_page").find("li.active").removeClass("active");;
				$("#tvprogram_b_"+prog_current_val).addClass("active");	
				
			
			}else {
				
				prog_current_val = prog_current_val;
				
				$("#tvprogram_page").find("li.active").removeClass("active");
				$("#tvprogram_b_"+prog_current_val).addClass("active");
			}
			
			prog_current_id = $("#tvprogram_page").find("li.active").attr('value');
			prog_current_page = $("#tvprogram_page_"+prog_current_id).text();
			
			prog_start_row = (parseInt(prog_current_page)-1)*prog_stop_row;
			
			//console.log("startROW"+prog_start_row+" stopROW"+prog_stop_row); 
			
	
		}else if(check_page == "tvprogram_page_7"){
			
			prog_current_id = $("#tvprogram_page").find("li.active").attr('id');
			prog_current_val = $("#"+prog_current_id).attr('value');
			
			if(prog_current_val < "6"){
	
				prog_current_val = parseInt(prog_current_val)+1;
				
				$("#tvprogram_page").find("li.active").removeClass("active");;
				$("#tvprogram_b_"+prog_current_val).addClass("active");		

			}else if(prog_current_val == "6"){
				
				prog_current_val = 2;
					
				$("#tvprogram_page_2").text(parseInt($("#tvprogram_page_2").text())+5);
				$("#tvprogram_page_3").text(parseInt($("#tvprogram_page_3").text())+5);
				$("#tvprogram_page_4").text(parseInt($("#tvprogram_page_4").text())+5);
				$("#tvprogram_page_5").text(parseInt($("#tvprogram_page_5").text())+5);
				$("#tvprogram_page_6").text(parseInt($("#tvprogram_page_6").text())+5);
				
				$("#tvprogram_page").find("li.active").removeClass("active");;
				$("#tvprogram_b_"+prog_current_val).addClass("active");	
				
			}else {
				
				prog_current_val = prog_current_val;
				
				$("#tvprogram_page").find("li.active").removeClass("active");
				$("#tvprogram_b_"+prog_current_val).addClass("active");
			}
			
			prog_current_id = $("#tvprogram_page").find("li.active").attr('value');
			prog_current_page = $("#tvprogram_page_"+prog_current_id).text();
			
			prog_start_row = (parseInt(prog_current_page)-1)*prog_stop_row;
			//console.log("startROW"+prog_start_row+" stopROW"+prog_stop_row); 
			
			
		}else {
						
			$("#tvprogram_page").find("li.active").removeClass("active");;
			$("#tvprogram_b_"+prog_index_page).addClass("active");			
			prog_current_page = $("#tvprogram_page_"+prog_index_page).text();
			
			prog_start_row = (parseInt(prog_current_page)-1)*prog_stop_row;
			//console.log("startROW"+prog_start_row+" stopROW"+prog_stop_row); 
			
		}
		
	showtable(prog_start_row,prog_stop_row,search_progname);
}

</script>
<div class="row-fluid"  >
    <div class="span6">
 		<button id="addPro" type="button" class="btn btn-info" style="font-size:1em;margin-top:4px;margin-bottom:4px;margin-bottom:4px"  data-loading-text="Loading..." onclick="open_add_program()">เพิ่มรายการโทรทัศน์</button>
    </div> 
	<div class="span6" align="right">
    	<div class="row-fluid"  >
            <div class="span2" align="right">
            	<!--<label for="prog_list" style="margin-top:6px;font-size:1em;margin-bottom:4px">ค้นหา:</label>-->
            	
            </div>
            <div class="span10" align="right" style="margin-left:4px"> 
            	<input type="text" name="search_list_progname" id="search_list_progname" size="10" >
            	<div style="float:right; margin-top:0px;font-size:1em;"><input type="button" name="searchprog_clear" id="searchprog_clear" class="btn btn-info" value="ยกเลิก" onclick="$('#search_list_progname').val(''); show_tvprogram_list(2);"></div>
            	<div style="float:right; margin-top:0px;font-size:1em;"><input type="button" name="searchprog_button" id="searchprog_button" class="btn btn-info" value="ค้นหา" onclick="show_tvprogram_list(2,$('#search_list_progname').val());"></div>
            	<!--<select class="input" name="prog_list" id="prog_list" style="font-size:1em;width:230px; margin-left:1px;margin-top:4px;margin-bottom:4px" value="">
            	</select>   -->             
            </div>
        </div>                          
	</div> 
</div>
<div class="row-fluid" >
<div class="">
    <div class="container" id="page" style="width:inherit">
         <div class="row-fluid">
            <div class="">
                <table align="center" class="table table-striped" id="progtable">
                  <thead>
                    <tr style="font-size:0.8em;height:35px;">
                      <th style="width:12%;text-align:left;padding:6px">รายการโทรทัศน์</th>
                      <th style="width:12%;text-align:left;padding:6px">วันออกอากาศ</th>
                      <th style="width:10%;text-align:left;padding:6px">บริษัทสั่งจ่าย</th>
                      <th style="width:7%;text-align:right;padding:6px">จำนวนเบรค</th>
                      <th style="width:9%;text-align:right;padding:6px">เวลารวม(วินาที)</th>
                      <th style="width:13%;text-align:right;padding:6px">ราคาเต็มต่อนาที(บาท)</th>
                      <th style="width:10%;text-align:right;padding:6px">ราคาแพค(บาท)</th>
                      <th style="width:12%;text-align:right;padding:6px">เวลาราคาแพค(นาที)</th>
                      <th style="width:5%;text-align:left;padding:6px">หมายเหตุ</th>
                      <th style="width:5%;text-align:center;padding:6px">แก้ไข</th>
                      <th style="width:5%;text-align:center;padding:6px">ลบ </th>
                    </tr>
                  </thead>
                  <tbody style="font-size:0.8em">
                  </tbody>
                </table>
            </div>
         </div>
     		<div class="row-fluid"  align="center">
             <div class="span6">
             </div>
             <div class="span6">
     			<div class="row-fluid"  align="center">
                    <div class="span3" align="right"></div>
                    <div class="span7" align="right" style="margin:2px">
                        <div class="pagination pagination-right pagination-small" style="font-size:1em;;margin-top:4px;margin-bottom:4px;">
                          <ul id="tvprogram_page">
                            <li id="tvprogram_b_1" value="1"><a id="tvprogram_page_1" onclick=show_tvprogram_list("1") style=" color: #39C">Prev</a></li>
                            <li id="tvprogram_b_2" value="2" class="active"><a id="tvprogram_page_2"  style=" color:#39C" onclick=show_tvprogram_list("2")>1</a></li>
                            <li id="tvprogram_b_3" value="3" ><a id="tvprogram_page_3"  style=" color:#39C" onclick=show_tvprogram_list("3")>2</a></li>
                            <li id="tvprogram_b_4" value="4" ><a id="tvprogram_page_4"  style=" color:#39C" onclick=show_tvprogram_list("4")>3</a></li>
                            <li id="tvprogram_b_5" value="5" ><a id="tvprogram_page_5"  style=" color:#39C" onclick=show_tvprogram_list("5")>4</a></li>
                            <li id="tvprogram_b_6" value="6" ><a id="tvprogram_page_6"  style=" color:#39C" onclick=show_tvprogram_list("6")>5</a></li>
                            <li id="tvprogram_b_7" value="7" ><a id="tvprogram_page_7"  style=" color:#39C" onclick=show_tvprogram_list("7")>Next</a></li>
                          </ul>
                        </div>
                    </div>
                    <div class="span2" align="left">
                        <select  style="font-size:0.8em;width:80px;margin-top:4px;margin-bottom:4px; margin-left:2px" id="num_row_tvprogram" value="" class="input-mini" >
                                    <option selected="selected">50</option>
                                    <option>10</option>            	
                                    <option>20</option>
                                    <option>30</option>
                                    <option>40</option>
                                    <option>50</option>
                                    <option>100</option>
                       </select>               
                    </div>
           		</div>
           	</div>
           </div>
	</div>

<?php
 //--------------- Start of Dialog for Adding Program  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'addprogDi',
        'options'=>array(
            'title'=>'เพิ่มและแก้ไขรายการโทรทัศน์',
			'width'=>1000,
			//'height'=>700,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'เพิ่มรายการ'=>'js:addprogDb',
				'เพิ่มวันออกอากาศ'=>'js:addprogDb',
				'บันทึกการแก้ไข'=>'js:addprogDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" >
  <form class="form-horizontal" style="font-size:1em" > 

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span6">
        
        
            <div class="row-fluid" style="margin-top:15px">
                <div class="span3" align="right" style="margin-top:5px">
                    <label for="tvprogram">รายการ:</label>
                </div>
                <div class="span5" align="left">
                        <input type="text" name="tvprogram" id="addtvprogram" class="ui-ams-input text ui-widget-content ui-corner-all " style="width:240px"/> 
                </div>        
                <div class="span4" align="left" style="margin-top:5px" >
                </div>
            </div> 
            
            
            <div class="row-fluid" style="margin-top:10px">
                <div class="span3" align="right" style="margin-top:5px">
                    <label for="date_break">วันเริ่มออกอากาศ:</label>
                </div>
                <div class="span9" align="left">
                    <?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name'=>'my_date',
                        'id'=>'add_prog_date_start',
                        'value'=>date('d/m/Y'),
                        'language'=>Yii::app()->language=='et' ? 'et' : null,
                        'options'=>array(
                            'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
                            'showOn'=>'button', // 'focus', 'button', 'both'
                            'buttonText'=>Yii::t('ui','Select form calendar'),
                            'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
                            'dateFormat'=>'dd/mm/yy',
                            'buttonImageOnly'=>true,),
                        'htmlOptions'=>array(
                            'style'=>'width:210px;vertical-align:top'),
                        ));   
                    ?>	  
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3" align="right" style="margin-top:5px">
                    <label for="date_break">ถึง:</label>
                </div>
                <div class="span9" align="left">
                    <?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name'=>'my_date',
                        'id'=>'add_prog_date_end',
                        'value'=>date('d/m/Y'),
                        'language'=>Yii::app()->language=='et' ? 'et' : null,
                        'options'=>array(
                            'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
                            'showOn'=>'button', // 'focus', 'button', 'both'
                            'buttonText'=>Yii::t('ui','Select form calendar'),
                            'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
                            'dateFormat'=>'dd/mm/yy',
                            'buttonImageOnly'=>true,),
                        'htmlOptions'=>array(
                            'style'=>'width:210px;vertical-align:top'),
                        ));   
                    ?>	  
                </div>
           </div>

           <div class="row-fluid" style="margin-top:10px">
                <div class="span3" align="right" style="margin-top:5px">
                    <label for="num_break">รอบสัปดาห์:</label>
                </div>
                <div class="span9" align="left" >
                    <div class="btn-group" data-toggle="buttons-checkbox" id="add_prog_daygroup">
                      <button type="button" class="btn btn-info" id="add_prog_day_1" value="1" onclick =togle_daygroup(1)>จ</button>
                      <button type="button" class="btn btn-info" id="add_prog_day_2" value="2" onclick =togle_daygroup(2)>อ</button>
                      <button type="button" class="btn btn-info" id="add_prog_day_3" value="3" onclick =togle_daygroup(3)>พ</button>
                      <button type="button" class="btn btn-info" id="add_prog_day_4" value="4" onclick =togle_daygroup(4)>พฤ</button>
                      <button type="button" class="btn btn-info" id="add_prog_day_5" value="5" onclick =togle_daygroup(5)>ศ</button>
                      <button type="button" class="btn btn-info" id="add_prog_day_6" value="6" onclick =togle_daygroup(6)>ส</button>
                      <button type="button" class="btn btn-info" id="add_prog_day_7" value="0" onclick =togle_daygroup(7)>อา</button>
                    </div>
                </div>
            </div>
           
           
        </div>
        <div class="span6">
        
           <div class="row-fluid" style="margin-top:15px" >
                <div class="span3" align="right" style="margin-top:5px">
                    <label for="break_profile" >เบรคโปรไฟร์:</label>
                </div>
                <div class="span5" align="left" >
                    <input type="text" name="break_profile" id="break_profile" class="ui-ams-input text ui-widget-content ui-corner-all " style="width:240px" />        
                
                <!---
                    <select id="break_profile" style=" width:220px" class="input-small" >
                        <option value="1">รายการปกติ</option>
                        <option value="2">มวยไทย 7 สี</option>
                        <option value="3">มวยสากล</option>            	
                        <option value="4">มวยผู้เช่า</option>
                        <option value="5">ฟุตบอลผู้เช่า</option>
                        <option value="6">วอลเล่ยบอล</option>
                        <option value="7">ฟุตซอล</option>
                    </select>
                --->
                
                </div>        
                <div class="span4" align="left" style="margin-top:5px" >
                    <label id="add_break_prof_alert" style="visibility:hidden">
                        <a title='เบรคโปรไฟร์ใหม่';>
                            <img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' />
                        </a>
                    </label>
                </div>
            </div> 
            
            <div class="row-fluid" style="margin-top:10px">
                <div class="span3" align="right">
                    <label for="break_note">หมายเหตุ:</label>
                </div>
                <div class="span5" align="left">
                    <textarea rows="3" id="add_prog_info" style="width:240px"></textarea>
                </div>        
                <div class="span4" align="left" style="margin-top:5px" >
                    <label></label>
                </div>
            </div> 
                    
        </div>
      </div>
    </div>
  

    <div class="row-fluid" style="margin-top:5px">
    	<div  align="center" style="margin-top:5px;padding-left:20px;padding-right:20px">
        
   			<div class="container" id="page" style="width:inherit"> <!--- contrainner for daytab --->
         	<div class="row-fluid" >        						<!--- contrainner for daytab --->
        
            <ul class="nav nav-pills gray" id="prog_daytab">
              <li  id="prog_daytab_8" value="8"  style="margin-left:60px" class="active" ><a href="#divbreak_day_8" data-toggle="tab">สรุป</a></li>
              <li id="prog_daytab_1" value="1" ><a href="#divbreak_day_1" data-toggle="tab">จ</a></li>
              <li id="prog_daytab_2" value="2" ><a href="#divbreak_day_2" data-toggle="tab">อ</a></li>
              <li id="prog_daytab_3" value="3" ><a href="#divbreak_day_3" data-toggle="tab">พ</a></li>
              <li id="prog_daytab_4" value="4" ><a href="#divbreak_day_4" data-toggle="tab">พฤ</a></li>
              <li id="prog_daytab_5" value="5" ><a href="#divbreak_day_5" data-toggle="tab">ศ</a></li>
              <li id="prog_daytab_6" value="6" ><a href="#divbreak_day_6" data-toggle="tab">ส</a></li>
              <li id="prog_daytab_7" value="7" ><a href="#divbreak_day_7" data-toggle="tab">อา</a></li>
            </ul> 
                
            <div class="tab-content">
              <div class="tab-pane active" id="divbreak_day_8"> <!--- Start Summay BreakTab --->
                    <label for="break_summary" id="summary_program"></label>
                    
                    <div class="container" id="page" style="width:inherit">
                         <div class="row-fluid">
                            <div class="">
                                <table align="center" class="table table-striped" id="summary_program_table">
                                  <thead>
                                    <tr style="font-size:0.8em;height:35px;">
                                      <th style="width:9%;text-align:left;padding:2px"></th>
                                      <th style="width:13%;text-align:center;padding:2px">จ.</th>
                                      <th style="width:13%;text-align:center;padding:2px">อ.</th>
                                      <th style="width:13%;text-align:center;padding:2px">พ.</th>
                                      <th style="width:13%;text-align:center;padding:2px">พฤ.</th>
                                      <th style="width:13%;text-align:center;padding:2px">ศ.</th>
                                      <th style="width:13%;text-align:center;padding:2px">ส.</th>
                                      <th style="width:13%;text-align:center;padding:2px">อา.</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:0.8em">
                                  </tbody>
                                </table>
                            </div>
                         </div>
                    </div>
                        
              </div><!--- End Summay BreakTab --->
            
              <div class="tab-pane" id="divbreak_day_1"> 
              
              
                    <div class="container-fluid">
                      <div class="row-fluid">
                        <div class="span6">
                        
                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day1">ช่วงเวลารายการ:</label>
                                </div>
                                <div class="span6" align="left" style="margin-top:4px">
                                    <label class="checkbox">
                                        <input type="checkbox" value="" id="prog_prime_day1"> Prime Time
                                    </label>
                                </div>        
                                <div class="span2" align="left" style="margin-top:3px" >
                                </div>
                         	</div>                        

                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day1">บริษัทสั่งจ่าย:</label>
                                </div>
                                <div class="span6" align="left">
                                        <input type="text" name="comp_name_day1" id="comp_name_day1" class="text ui-widget-content ui-corner-all company_name"/>
                                </div>        
                                <div class="span2" align="center" style="margin-top:3px;" >
                                    <label id="add_compname_alert_day1" style="visibility:visible">
                                        <a title='บริบัทสั่งจ่ายใหม่';>
                                            <img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' />
                                        </a>
                                    </label>
                                </div>
                             </div>   
                             
                             <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="tvprogram"></label>
                                </div>
                                <div class="span6" align="right" style="margin-top:5px">
                                    <div class="row-fluid" >
                                        <div class="span6" align="left" >	
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day1" name="prog_owner_day1" id="prog_owner_day1" value="รายการสถานี"  checked="checked">รายการสถานี
                                            </label>
                                        </div> 
                                        <div class="span6" align="left">	      
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day1" name="prog_rental_day1" id="prog_rental_day1" value="รายการผู้เช่า" >รายการผู้เช่า
                                            </label>
                                        </div>
                                    </div>           
                                </div> 
                             </div>                   

                            <div class="row-fluid" style="margin-top:5px" >
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="num_break_day1" >จำนวนเบรค:</label>
                                </div>
                                <div class="span6" align="left" >
                                    <select id="num_break_day1" class="input-small num_of_break" style="width:220px" daygroup="1" >
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>            	
                                        <option selected="selected" >4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>10</option>
                                        <option>12</option>
                                        <option>13</option>
                                        <option>14</option>
                                        <option>15</option>
                                        <option>16</option>
                                        <option>17</option>
                                        <option>18</option>
                                        <option>19</option>
                                        <option>20</option>
                                        <option>21</option>
                                        <option>22</option>
                                        <option>23</option>
                                        <option>24</option>
                                        <option>25</option>
                                    </select>
                                </div>        
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                            </div>

                        	<div class="row-fluid" style="margin-top:5px">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="progtime_start_day1">เริ่มออกอากาศเวลา:</label>
                                </div>
                                <div class="span6" align="left">
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <input type="text" name="progtime_start_day1" id="progtime_start_day1" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                        <div class="span2" style="margin-left:2px;margin-top:5px"> ถึง </div>
                                        <div class="span5">
                                            <input type="text" name="progtime_end_day1" id="progtime_end_day1" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                    </div>
                                </div>
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                          	 </div>                          
                        
                        </div>
                        <div class="span6">
                        
                           <div class="row-fluid">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="price_min" >ราคาเต็มต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                        <input type="text" name="price_min_day1" id="price_min_day1" class="text ui-widget-content ui-corner-all prog_price_min"/>
                                </div>        
                              
                           </div>                        
                        
                           <div class="row-fluid">
                            
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="packprice_min">ราคาแพค(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="packprice_min_day1" id="packprice_min_day1" class="text ui-widget-content ui-corner-all prog_price_pack"/>
                                </div>        
                                
                           </div>     
                           <div class="row-fluid">
        
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="min_packprice">เวลาราคาแพค(นาที):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="min_packprice_day1" id="min_packprice_day1" class="text ui-widget-content ui-corner-all prog_min_pricepack"/>
                                </div>        

                           </div> 
                            
                           <div class="row-fluid" style="margin-top:5px" >
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="prog_discount_day1">ส่วนลด(%):</label>
                                </div>
                                <div class="span7" align="left">
                                    <select id="prog_discount_day1" class="input-small prog_discount" style="width:220px" >
                                        <option value="0">0</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>            	
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>        

                           </div>    
                            
                           <div class="row-fluid" style="margin-top:5px">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="netprice_min_day1">ราคาเต็มสุทธิต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="netprice_min_day1" id="netprice_min_day1" class="text ui-widget-content ui-corner-all prog_price_net"/>
                                </div>        
 
                           </div>                                                      
                                
                        </div>
                        
                      </div>
                    </div> 
                    <div class="row-fluid" style="margin-top:15px" >
                        <div align="center" >
                            <table align="center" class="table table-striped" id="break_table_day1">
                              <thead>
                                <tr style="font-size:0.8em">
                                  <th style="width:15%;text-align:right;padding:2px">เบรคที่</th>
                                  <th style="width:20%;text-align:center;padding:2px">เวลาออกอากาศ</th>
                                  <th style="width:35%;text-align:center;padding:2px">ชนิดเบรค</th>
                                  <th style="width:30%;text-align:center;padding:2px">เวลา(วินาที)</th>
                                </tr>
                              </thead>
                              <tbody style="font-size:0.8em" >
                              </tbody>
                            </table>
                        </div>       
                    </div>
              </div>
              
              <div class="tab-pane" id="divbreak_day_2">
                    <div class="container-fluid">
                      <div class="row-fluid">
                        <div class="span6">
                        
                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day2">ช่วงเวลารายการ:</label>
                                </div>
                                <div class="span6" align="left" style="margin-top:4px">
                                    <label class="checkbox">
                                        <input type="checkbox" value="" id="prog_prime_day2"> Prime Time
                                    </label>
                                </div>        
                                <div class="span2" align="left" style="margin-top:3px" >
                                </div>
                         	</div>                        

                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day2">บริษัทสั่งจ่าย:</label>
                                </div>
                                <div class="span6" align="left">
                                        <input type="text" name="comp_name_day2" id="comp_name_day2" class="text ui-widget-content ui-corner-all company_name"/>
                                </div>        
                                <div class="span2" align="center" style="margin-top:3px;" >
                                    <label id="add_compname_alert_day2" style="visibility:visible">
                                        <a title='บริบัทสั่งจ่ายใหม่';>
                                            <img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' />
                                        </a>
                                    </label>
                                </div>
                             </div>   
                             
                             <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="tvprogram"></label>
                                </div>
                                <div class="span6" align="right" style="margin-top:5px">
                                    <div class="row-fluid" >
                                        <div class="span6" align="left" >	
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day2" name="prog_owner_day2" id="prog_owner_day2" value="รายการสถานี"  checked="checked">รายการสถานี
                                            </label>
                                        </div> 
                                        <div class="span6" align="left">	      
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day2" name="prog_rental_day2" id="prog_rental_day2" value="รายการผู้เช่า" >รายการผู้เช่า
                                            </label>
                                        </div>
                                    </div>           
                                </div> 
                             </div>                   

                            <div class="row-fluid" style="margin-top:5px" >
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="num_break_day2" >จำนวนเบรค:</label>
                                </div>
                                <div class="span6" align="left" >
                                    <select id="num_break_day2" class="input-small num_of_break" style="width:220px" daygroup="1" >
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>            	
                                        <option selected="selected" >4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>10</option>
                                        <option>12</option>
                                        <option>13</option>
                                        <option>14</option>
                                        <option>15</option>
                                        <option>16</option>
                                        <option>17</option>
                                        <option>18</option>
                                        <option>19</option>
                                        <option>20</option>
                                        <option>21</option>
                                        <option>22</option>
                                        <option>23</option>
                                        <option>24</option>
                                        <option>25</option>
                                    </select>
                                </div>        
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                            </div>

                        	<div class="row-fluid" style="margin-top:5px">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="progtime_start_day2">เริ่มออกอากาศเวลา:</label>
                                </div>
                                <div class="span6" align="left">
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <input type="text" name="progtime_start_day2" id="progtime_start_day2" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                        <div class="span2" style="margin-left:2px;margin-top:5px"> ถึง </div>
                                        <div class="span5">
                                            <input type="text" name="progtime_end_day2" id="progtime_end_day2" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                    </div>
                                </div>
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                          	 </div>                          
                        
                        </div>
                        <div class="span6">
                        
                           <div class="row-fluid">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="price_min" >ราคาเต็มต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                        <input type="text" name="price_min_day2" id="price_min_day2" class="text ui-widget-content ui-corner-all prog_price_min"/>
                                </div>        
                              
                           </div>                        
                        
                           <div class="row-fluid">
                            
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="packprice_min">ราคาแพค(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="packprice_min_day2" id="packprice_min_day2" class="text ui-widget-content ui-corner-all prog_price_pack "/>
                                </div>        

                                
                           </div>     
                           <div class="row-fluid">
        
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="min_packprice">เวลาราคาแพค(นาที):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="min_packprice_day2" id="min_packprice_day2" class="text ui-widget-content ui-corner-all prog_min_pricepack"/>
                                </div>        

                           </div> 
                            
                           <div class="row-fluid" style="margin-top:5px" >
                           
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="prog_discount_day2">ส่วนลด(%):</label>
                                </div>
                                <div class="span7" align="left">
                                    <select id="prog_discount_day2" class="input-small prog_discount" style="width:220px" >
                                        <option value="0">0</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>            	
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>        

                           </div>    
                            
                           <div class="row-fluid" style="margin-top:5px">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="netprice_min_day2">ราคาเต็มสุทธิต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="netprice_min_day2" id="netprice_min_day2" class="text ui-widget-content ui-corner-all prog_price_net"/>
                                </div>        

                           </div>                                                      
                                
                        </div>
                        
                      </div>
                    </div> 
                    <div class="row-fluid" style="margin-top:15px" >
                        <div align="center" >
                            <table align="center" class="table table-striped" id="break_table_day2">
                              <thead>
                                <tr style="font-size:0.8em">
                                  <th style="width:15%;text-align:right;padding:2px">เบรคที่</th>
                                  <th style="width:20%;text-align:center;padding:2px">เวลาออกอากาศ</th>
                                  <th style="width:35%;text-align:center;padding:2px">ชนิดเบรค</th>
                                  <th style="width:30%;text-align:center;padding:2px">เวลา(วินาที)</th>
                                </tr>
                              </thead>
                              <tbody style="font-size:0.8em" >
                              </tbody>
                            </table>
                        </div>       
                    </div>

              </div>
              <div class="tab-pane" id="divbreak_day_3">
                    <div class="container-fluid">
                      <div class="row-fluid">
                        <div class="span6">
                        
                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day3">ช่วงเวลารายการ:</label>
                                </div>
                                <div class="span6" align="left" style="margin-top:4px">
                                    <label class="checkbox">
                                        <input type="checkbox" value="" id="prog_prime_day3"> Prime Time
                                    </label>
                                </div>        
                                <div class="span2" align="left" style="margin-top:3px" >
                                </div>
                         	</div>                        

                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day3">บริษัทสั่งจ่าย:</label>
                                </div>
                                <div class="span6" align="left">
                                        <input type="text" name="comp_name_day3" id="comp_name_day3" class="text ui-widget-content ui-corner-all company_name"/>
                                </div>        
                                <div class="span2" align="center" style="margin-top:3px;" >
                                    <label id="add_compname_alert_day3" style="visibility:visible">
                                        <a title='บริบัทสั่งจ่ายใหม่';>
                                            <img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' />
                                        </a>
                                    </label>
                                </div>
                             </div>   
                             
                             <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="tvprogram"></label>
                                </div>
                                <div class="span6" align="right" style="margin-top:5px">
                                    <div class="row-fluid" >
                                        <div class="span6" align="left" >	
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day3" name="prog_owner_day3" id="prog_owner_day3" value="รายการสถานี"  checked="checked">รายการสถานี
                                            </label>
                                        </div> 
                                        <div class="span6" align="left">	      
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day3" name="prog_rental_day3" id="prog_rental_day3" value="รายการผู้เช่า" >รายการผู้เช่า
                                            </label>
                                        </div>
                                    </div>           
                                </div> 
                             </div>                   

                            <div class="row-fluid" style="margin-top:5px" >
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="num_break_day3" >จำนวนเบรค:</label>
                                </div>
                                <div class="span6" align="left" >
                                    <select id="num_break_day3" class="input-small num_of_break" style="width:220px" daygroup="1" >
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>            	
                                        <option selected="selected" >4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>10</option>
                                        <option>12</option>
                                        <option>13</option>
                                        <option>14</option>
                                        <option>15</option>
                                        <option>16</option>
                                        <option>17</option>
                                        <option>18</option>
                                        <option>19</option>
                                        <option>20</option>
                                        <option>21</option>
                                        <option>22</option>
                                        <option>23</option>
                                        <option>24</option>
                                        <option>25</option>
                                    </select>
                                </div>        
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                            </div>

                        	<div class="row-fluid" style="margin-top:5px">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="progtime_start_day3">เริ่มออกอากาศเวลา:</label>
                                </div>
                                <div class="span6" align="left">
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <input type="text" name="progtime_start_day3" id="progtime_start_day3" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                        <div class="span2" style="margin-left:2px;margin-top:5px"> ถึง </div>
                                        <div class="span5">
                                            <input type="text" name="progtime_end_day3" id="progtime_end_day3" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                    </div>
                                </div>
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                          	 </div>                          
                        
                        </div>
                        <div class="span6">
                        
                           <div class="row-fluid">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="price_min" >ราคาเต็มต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                        <input type="text" name="price_min_day3" id="price_min_day3" class="text ui-widget-content ui-corner-all prog_price_min"/>
                                </div>        
                                
                           </div>                        
                        
                           <div class="row-fluid">
                            
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="packprice_min">ราคาแพค(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="packprice_min_day3" id="packprice_min_day3" value="" class="text ui-widget-content ui-corner-all prog_price_pack"/>
                                </div>        

                                
                           </div>     
                           <div class="row-fluid">
        
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="min_packprice">เวลาราคาแพค(นาที):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="min_packprice_day3" id="min_packprice_day3" value="" class="text ui-widget-content ui-corner-all prog_min_pricepack"/>
                                </div>        

                           </div> 
                            
                           <div class="row-fluid" style="margin-top:5px" >
                           
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="prog_discount_day3">ส่วนลด(%):</label>
                                </div>
                                <div class="span7" align="left">
                                    <select id="prog_discount_day3" class="input-small prog_discount" style="width:220px" >
                                        <option value="0">0</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>            	
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>        

                           </div>    
                            
                           <div class="row-fluid" style="margin-top:5px">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="netprice_min_day3">ราคาเต็มสุทธิต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="netprice_min_day3" id="netprice_min_day3" class="text ui-widget-content ui-corner-all price_net"/>
                                </div>        

                           </div>                                                      
                                
                        </div>
                        
                      </div>
                    </div> 
                    <div class="row-fluid" style="margin-top:15px" >
                        <div align="center" >
                            <table align="center" class="table table-striped" id="break_table_day3">
                              <thead>
                                <tr style="font-size:0.8em">
                                  <th style="width:15%;text-align:right;padding:2px">เบรคที่</th>
                                  <th style="width:20%;text-align:center;padding:2px">เวลาออกอากาศ</th>
                                  <th style="width:35%;text-align:center;padding:2px">ชนิดเบรค</th>
                                  <th style="width:30%;text-align:center;padding:2px">เวลา(วินาที)</th>
                                </tr>
                              </thead>
                              <tbody style="font-size:0.8em" >
                              </tbody>
                            </table>
                        </div>       
                    </div>

              </div>
              <div class="tab-pane" id="divbreak_day_4">
              
                    <div class="container-fluid">
                      <div class="row-fluid">
                        <div class="span6">
                        
                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day4">ช่วงเวลารายการ:</label>
                                </div>
                                <div class="span6" align="left" style="margin-top:4px">
                                    <label class="checkbox">
                                        <input type="checkbox" value="" id="prog_prime_day4"> Prime Time
                                    </label>
                                </div>        
                                <div class="span2" align="left" style="margin-top:3px" >
                                </div>
                         	</div>                        

                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day4">บริษัทสั่งจ่าย:</label>
                                </div>
                                <div class="span6" align="left">
                                        <input type="text" name="comp_name_day4" id="comp_name_day4" class="text ui-widget-content ui-corner-all company_name"/>
                                </div>        
                                <div class="span2" align="center" style="margin-top:3px;" >
                                    <label id="add_compname_alert_day4" style="visibility:visible">
                                        <a title='บริบัทสั่งจ่ายใหม่';>
                                            <img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' />
                                        </a>
                                    </label>
                                </div>
                             </div>   
                             
                             <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="tvprogram"></label>
                                </div>
                                <div class="span6" align="right" style="margin-top:5px">
                                    <div class="row-fluid" >
                                        <div class="span6" align="left" >	
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day4" name="prog_owner_day4" id="prog_owner_day4" value="รายการสถานี"  checked="checked">รายการสถานี
                                            </label>
                                        </div> 
                                        <div class="span6" align="left">	      
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day4" name="prog_rental_day4" id="prog_rental_day4" value="รายการผู้เช่า" >รายการผู้เช่า
                                            </label>
                                        </div>
                                    </div>           
                                </div> 
                             </div>                   

                            <div class="row-fluid" style="margin-top:5px" >
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="num_break_day4" >จำนวนเบรค:</label>
                                </div>
                                <div class="span6" align="left" >
                                    <select id="num_break_day4" class="input-small num_of_break" style="width:220px" daygroup="1" >
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>            	
                                        <option selected="selected" >4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>10</option>
                                        <option>12</option>
                                        <option>13</option>
                                        <option>14</option>
                                        <option>15</option>
                                        <option>16</option>
                                        <option>17</option>
                                        <option>18</option>
                                        <option>19</option>
                                        <option>20</option>
                                        <option>21</option>
                                        <option>22</option>
                                        <option>23</option>
                                        <option>24</option>
                                        <option>25</option>
                                    </select>
                                </div>        
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                            </div>

                        	<div class="row-fluid" style="margin-top:5px">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="progtime_start_day4">เริ่มออกอากาศเวลา:</label>
                                </div>
                                <div class="span6" align="left">
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <input type="text" name="progtime_start_day4" id="progtime_start_day4" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                        <div class="span2" style="margin-left:2px;margin-top:5px"> ถึง </div>
                                        <div class="span5">
                                            <input type="text" name="progtime_end_day4" id="progtime_end_day4" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                    </div>
                                </div>
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                          	 </div>                          
                        
                        </div>
                        <div class="span6">
                        
                           <div class="row-fluid">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="price_min" >ราคาเต็มต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                        <input type="text" name="price_min_day4" id="price_min_day4" value="" class="text ui-widget-content ui-corner-all price_min"/>
                                </div>        

                              
                           </div>                        
                        
                           <div class="row-fluid">
                            
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="packprice_min">ราคาแพค(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="packprice_min_day4" id="packprice_min_day4" value="" class="text ui-widget-content ui-corner-all price_pack"/>
                                </div>        
                                
                           </div>     
                           <div class="row-fluid">
        
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="min_packprice">เวลาราคาแพค(นาที):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="min_packprice_day4" id="min_packprice_day4" value="" class="text ui-widget-content ui-corner-all min_packprice"/>
                                </div>         
                                
                           </div> 
                            
                           <div class="row-fluid" style="margin-top:5px" >
                           
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="prog_discount_day4">ส่วนลด(%):</label>
                                </div>
                                <div class="span7" align="left">
                                    <select id="prog_discount_day4" class="input-small prog_discount" style="width:220px" >
                                        <option value="0">0</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>            	
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>        

                           </div>    
                            
                           <div class="row-fluid" style="margin-top:5px">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="netprice_min_day4">ราคาเต็มสุทธิต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="netprice_min_day4" id="netprice_min_day4" class="text ui-widget-content ui-corner-all price_net"/>
                                </div>        
 
                           </div>                                                      
                                
                        </div>
                        
                      </div>
                    </div> 
                    <div class="row-fluid" style="margin-top:15px" >
                        <div align="center" >
                            <table align="center" class="table table-striped" id="break_table_day4">
                              <thead>
                                <tr style="font-size:0.8em">
                                  <th style="width:15%;text-align:right;padding:2px">เบรคที่</th>
                                  <th style="width:20%;text-align:center;padding:2px">เวลาออกอากาศ</th>
                                  <th style="width:35%;text-align:center;padding:2px">ชนิดเบรค</th>
                                  <th style="width:30%;text-align:center;padding:2px">เวลา(วินาที)</th>
                                </tr>
                              </thead>
                              <tbody style="font-size:0.8em" >
                              </tbody>
                            </table>
                        </div>       
                    </div>

              </div>
              <div class="tab-pane" id="divbreak_day_5">
              
                    <div class="container-fluid">
                      <div class="row-fluid">
                        <div class="span6">
                        
                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day5">ช่วงเวลารายการ:</label>
                                </div>
                                <div class="span6" align="left" style="margin-top:4px">
                                    <label class="checkbox">
                                        <input type="checkbox" value="" id="prog_prime_day5"> Prime Time
                                    </label>
                                </div>        
                                <div class="span2" align="left" style="margin-top:3px" >
                                </div>
                         	</div>                        

                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day5">บริษัทสั่งจ่าย:</label>
                                </div>
                                <div class="span6" align="left">
                                        <input type="text" name="comp_name_day5" id="comp_name_day5" class="text ui-widget-content ui-corner-all company_name"/>
                                </div>        
                                <div class="span2" align="center" style="margin-top:3px;" >
                                    <label id="add_compname_alert_day5" style="visibility:visible">
                                        <a title='บริบัทสั่งจ่ายใหม่';>
                                            <img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' />
                                        </a>
                                    </label>
                                </div>
                             </div>   
                             
                             <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="tvprogram"></label>
                                </div>
                                <div class="span6" align="right" style="margin-top:5px">
                                    <div class="row-fluid" >
                                        <div class="span6" align="left" >	
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day5" name="prog_owner_day5" id="prog_owner_day5" value="รายการสถานี"  checked="checked">รายการสถานี
                                            </label>
                                        </div> 
                                        <div class="span6" align="left">	      
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day5" name="prog_rental_day5" id="prog_rental_day5" value="รายการผู้เช่า" >รายการผู้เช่า
                                            </label>
                                        </div>
                                    </div>           
                                </div> 
                             </div>                   

                            <div class="row-fluid" style="margin-top:5px" >
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="num_break_day5" >จำนวนเบรค:</label>
                                </div>
                                <div class="span6" align="left" >
                                    <select id="num_break_day5" class="input-small num_of_break" style="width:220px" daygroup="1" >
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>            	
                                        <option selected="selected" >4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>10</option>
                                        <option>12</option>
                                        <option>13</option>
                                        <option>14</option>
                                        <option>15</option>
                                        <option>16</option>
                                        <option>17</option>
                                        <option>18</option>
                                        <option>19</option>
                                        <option>20</option>
                                        <option>21</option>
                                        <option>22</option>
                                        <option>23</option>
                                        <option>24</option>
                                        <option>25</option>
                                    </select>
                                </div>        
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                            </div>

                        	<div class="row-fluid" style="margin-top:5px">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="progtime_start_day5">เริ่มออกอากาศเวลา:</label>
                                </div>
                                <div class="span6" align="left">
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <input type="text" name="progtime_start_day5" id="progtime_start_day5" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                        <div class="span2" style="margin-left:2px;margin-top:5px"> ถึง </div>
                                        <div class="span5">
                                            <input type="text" name="progtime_end_day5" id="progtime_end_day5" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                    </div>
                                </div>
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                          	 </div>                          
                        
                        </div>
                        <div class="span6">
                        
                           <div class="row-fluid">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="price_min" >ราคาเต็มต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                        <input type="text" name="price_min_day5" id="price_min_day5" value="" class="text ui-widget-content ui-corner-all price_min"/>
                                </div>        

                              
                           </div>                        
                        
                           <div class="row-fluid">
                            
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="packprice_min">ราคาแพค(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="packprice_min_day5" id="packprice_min_day5" value="" class="text ui-widget-content ui-corner-all price_pack"/>
                                </div>        

                                
                           </div>     
                           <div class="row-fluid">
        
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="min_packprice">เวลาราคาแพค(นาที):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="min_packprice_day5" id="min_packprice_day5" value="" class="text ui-widget-content ui-corner-all min_pricepack"/>
                                </div>        
 
                           </div> 
                            
                           <div class="row-fluid" style="margin-top:5px" >
                           
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="prog_discount_day5">ส่วนลด(%):</label>
                                </div>
                                <div class="span7" align="left">
                                    <select id="prog_discount_day1" class="input-small prog_discount" style="width:220px" >
                                        <option value="0">0</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>            	
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>        

                           </div>    
                            
                           <div class="row-fluid" style="margin-top:5px">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="netprice_min_day5">ราคาเต็มสุทธิต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="netprice_min_day5" id="netprice_min_day5" class="text ui-widget-content ui-corner-all price_net"/>
                                </div>        

                           </div>                                                      
                                
                        </div>
                        
                      </div>
                    </div> 
                    <div class="row-fluid" style="margin-top:15px" >
                        <div align="center" >
                            <table align="center" class="table table-striped" id="break_table_day5">
                              <thead>
                                <tr style="font-size:0.8em">
                                  <th style="width:15%;text-align:right;padding:2px">เบรคที่</th>
                                  <th style="width:20%;text-align:center;padding:2px">เวลาออกอากาศ</th>
                                  <th style="width:35%;text-align:center;padding:2px">ชนิดเบรค</th>
                                  <th style="width:30%;text-align:center;padding:2px">เวลา(วินาที)</th>
                                </tr>
                              </thead>
                              <tbody style="font-size:0.8em" >
                              </tbody>
                            </table>
                        </div>       
                    </div>

              </div>
              
              <div class="tab-pane" id="divbreak_day_6">
              
                    <div class="container-fluid">
                      <div class="row-fluid">
                        <div class="span6">
                        
                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day6">ช่วงเวลารายการ:</label>
                                </div>
                                <div class="span6" align="left" style="margin-top:4px">
                                    <label class="checkbox">
                                        <input type="checkbox" value="" id="prog_prime_day6"> Prime Time
                                    </label>
                                </div>        
                                <div class="span2" align="left" style="margin-top:3px" >
                                </div>
                         	</div>                        

                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day6">บริษัทสั่งจ่าย:</label>
                                </div>
                                <div class="span6" align="left">
                                        <input type="text" name="comp_name_day6" id="comp_name_day6" class="text ui-widget-content ui-corner-all company_name"/>
                                </div>        
                                <div class="span2" align="center" style="margin-top:3px;" >
                                    <label id="add_compname_alert_day6" style="visibility:visible">
                                        <a title='บริบัทสั่งจ่ายใหม่';>
                                            <img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' />
                                        </a>
                                    </label>
                                </div>
                             </div>   
                             
                             <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="tvprogram"></label>
                                </div>
                                <div class="span6" align="right" style="margin-top:5px">
                                    <div class="row-fluid" >
                                        <div class="span6" align="left" >	
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day6" name="prog_owner_day6" id="prog_owner_day6" value="รายการสถานี"  checked="checked">รายการสถานี
                                            </label>
                                        </div> 
                                        <div class="span6" align="left">	      
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day6" name="prog_rental_day6" id="prog_rental_day6" value="รายการผู้เช่า" >รายการผู้เช่า
                                            </label>
                                        </div>
                                    </div>           
                                </div> 
                             </div>                   

                            <div class="row-fluid" style="margin-top:5px" >
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="num_break_day6" >จำนวนเบรค:</label>
                                </div>
                                <div class="span6" align="left" >
                                    <select id="num_break_day6" class="input-small num_of_break" style="width:220px" daygroup="1" >
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>            	
                                        <option selected="selected" >4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>10</option>
                                        <option>12</option>
                                        <option>13</option>
                                        <option>14</option>
                                        <option>15</option>
                                        <option>16</option>
                                        <option>17</option>
                                        <option>18</option>
                                        <option>19</option>
                                        <option>20</option>
                                        <option>21</option>
                                        <option>22</option>
                                        <option>23</option>
                                        <option>24</option>
                                        <option>25</option>
                                    </select>
                                </div>        
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                            </div>

                        	<div class="row-fluid" style="margin-top:5px">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="progtime_start_day6">เริ่มออกอากาศเวลา:</label>
                                </div>
                                <div class="span6" align="left">
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <input type="text" name="progtime_start_day6" id="progtime_start_day6" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                        <div class="span2" style="margin-left:2px;margin-top:5px"> ถึง </div>
                                        <div class="span5">
                                            <input type="text" name="progtime_end_day6" id="progtime_end_day6" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                    </div>
                                </div>
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                          	 </div>                          
                        
                        </div>
                        <div class="span6">
                        
                           <div class="row-fluid">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="price_min" >ราคาเต็มต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                        <input type="text" name="price_min_day6" id="price_min_day6" class="text ui-widget-content ui-corner-all price_min"/>
                                </div>         
                              
                           </div>                        
                        
                           <div class="row-fluid">
                            
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="packprice_min">ราคาแพค(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="packprice_min_day6" id="packprice_min_day6"  class="text ui-widget-content ui-corner-all"/>
                                </div>        

                                
                           </div>     
                           <div class="row-fluid">
        
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="min_packprice">เวลาราคาแพค(นาที):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="min_packprice_day6" id="min_packprice_day6"  class="text ui-widget-content ui-corner-all min_pricepack"/>
                                </div>        

                           </div> 
                            
                           <div class="row-fluid" style="margin-top:5px" >
                           
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="prog_discount_day6">ส่วนลด(%):</label>
                                </div>
                                <div class="span7" align="left">
                                    <select id="prog_discount_day6" class="input-small prog_discount" style="width:220px" >
                                        <option value="0">0</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>            	
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>        

                           </div>    
                            
                           <div class="row-fluid" style="margin-top:5px">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="netprice_min_day6">ราคาเต็มสุทธิต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="netprice_min_day6" id="netprice_min_day6" class="text ui-widget-content ui-corner-all price_net"/>
                                </div>        
 
                           </div>                                                      
                                
                        </div>
                        
                      </div>
                    </div> 
                    <div class="row-fluid" style="margin-top:15px" >
                        <div align="center" >
                            <table align="center" class="table table-striped" id="break_table_day6">
                              <thead>
                                <tr style="font-size:0.8em">
                                  <th style="width:15%;text-align:right;padding:2px">เบรคที่</th>
                                  <th style="width:20%;text-align:center;padding:2px">เวลาออกอากาศ</th>
                                  <th style="width:35%;text-align:center;padding:2px">ชนิดเบรค</th>
                                  <th style="width:30%;text-align:center;padding:2px">เวลา(วินาที)</th>
                                </tr>
                              </thead>
                              <tbody style="font-size:0.8em" >
                              </tbody>
                            </table>
                        </div>       
                    </div>

              </div>
              <div class="tab-pane" id="divbreak_day_7">
                    <div class="container-fluid">
                      <div class="row-fluid">
                        <div class="span6">
                        
                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day7">ช่วงเวลารายการ:</label>
                                </div>
                                <div class="span6" align="left" style="margin-top:4px">
                                    <label class="checkbox">
                                        <input type="checkbox" value="" id="prog_prime_day7"> Prime Time
                                    </label>
                                </div>        
                                <div class="span2" align="left" style="margin-top:3px" >
                                </div>
                         	</div>                        

                            <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="comp_name_day7">บริษัทสั่งจ่าย:</label>
                                </div>
                                <div class="span6" align="left">
                                        <input type="text" name="comp_name_day7" id="comp_name_day7" class="text ui-widget-content ui-corner-all company_name"/>
                                </div>        
                                <div class="span2" align="center" style="margin-top:3px;" >
                                    <label id="add_compname_alert_day7" style="visibility:visible">
                                        <a title='บริบัทสั่งจ่ายใหม่';>
                                            <img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' />
                                        </a>
                                    </label>
                                </div>
                             </div>   
                             
                             <div class="row-fluid">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="tvprogram"></label>
                                </div>
                                <div class="span6" align="right" style="margin-top:5px">
                                    <div class="row-fluid" >
                                        <div class="span6" align="left" >	
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day7" name="prog_owner_day7" id="prog_owner_day7" value="รายการสถานี"  checked="checked">รายการสถานี
                                            </label>
                                        </div> 
                                        <div class="span6" align="left">	      
                                            <label class="radio">
                                                <input type="radio" class="prog_owner_day7" name="prog_rental_day7" id="prog_rental_day7" value="รายการผู้เช่า" >รายการผู้เช่า
                                            </label>
                                        </div>
                                    </div>           
                                </div> 
                             </div>                   

                            <div class="row-fluid" style="margin-top:5px" >
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="num_break_day7" >จำนวนเบรค:</label>
                                </div>
                                <div class="span6" align="left" >
                                    <select id="num_break_day7" class="input-small num_of_break" style="width:220px" daygroup="1" >
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>            	
                                        <option selected="selected" >4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>10</option>
                                        <option>12</option>
                                        <option>13</option>
                                        <option>14</option>
                                        <option>15</option>
                                        <option>16</option>
                                        <option>17</option>
                                        <option>18</option>
                                        <option>19</option>
                                        <option>20</option>
                                        <option>21</option>
                                        <option>22</option>
                                        <option>23</option>
                                        <option>24</option>
                                        <option>25</option>
                                    </select>
                                </div>        
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                            </div>

                        	<div class="row-fluid" style="margin-top:5px">
                                <div class="span4" align="right" style="margin-top:5px">
                                    <label for="progtime_start_day7">เริ่มออกอากาศเวลา:</label>
                                </div>
                                <div class="span6" align="left">
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <input type="text" name="progtime_start_day7" id="progtime_start_day7" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                        <div class="span2" style="margin-left:2px;margin-top:5px"> ถึง </div>
                                        <div class="span5">
                                            <input type="text" name="progtime_end_day7" id="progtime_end_day7" class="text ui-widget-content ui-corner-all"  style="width:60px" />
                                        </div>
                                    </div>
                                </div>
                                <div class="span2" align="left" style="margin-top:5px" >
                                    <label></label>
                                </div>
                          	 </div>                          
                        
                        </div>
                        <div class="span6">
                        
                           <div class="row-fluid">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="price_min" >ราคาเต็มต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                        <input type="text" name="price_min_day7" id="price_min_day7" class="text ui-widget-content ui-corner-all price_min"/>
                                </div>        

                              
                           </div>                        
                        
                           <div class="row-fluid">
                            
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="packprice_min">ราคาแพค(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="packprice_min_day7" id="packprice_min_day7" class="text ui-widget-content ui-corner-all price_pack"/>
                                </div>        

                                
                           </div>     
                           <div class="row-fluid">
        
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="min_packprice">เวลาราคาแพค(นาที):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="min_packprice_day7" id="min_packprice_day7" value="" class="text ui-widget-content ui-corner-all min_pricepack"/>
                                </div>        

                           </div> 
                            
                           <div class="row-fluid" style="margin-top:5px" >
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="prog_discount_day7">ส่วนลด(%):</label>
                                </div>
                                <div class="span7" align="left">
                                    <select id="prog_discount_day7" class="input-small prog_discount" style="width:220px" >
                                        <option value="0">0</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>            	
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>        

                           </div>    
                            
                           <div class="row-fluid" style="margin-top:5px">
                                
                                <div class="span5" align="right" style="margin-top:5px">
                                    <label for="netprice_min_day7">ราคาเต็มสุทธิต่อนาที(บาท):</label>
                                </div>
                                <div class="span7" align="left">
                                    <input type="text" name="netprice_min_day7" id="netprice_min_day7" class="text ui-widget-content ui-corner-all net_price"/>
                                </div>        
 
                           </div>                                                      
                                
                        </div>
                        
                      </div>
                    </div> 
                    <div class="row-fluid" style="margin-top:15px" >
                        <div align="center" >
                            <table align="center" class="table table-striped" id="break_table_day7">
                              <thead>
                                <tr style="font-size:0.8em">
                                  <th style="width:15%;text-align:right;padding:2px">เบรคที่</th>
                                  <th style="width:20%;text-align:center;padding:2px">เวลาออกอากาศ</th>
                                  <th style="width:35%;text-align:center;padding:2px">ชนิดเบรค</th>
                                  <th style="width:30%;text-align:center;padding:2px">เวลา(วินาที)</th>
                                </tr>
                              </thead>
                              <tbody style="font-size:0.8em" >
                              </tbody>
                            </table>
                        </div>       
                    </div>

              
              </div>
   
            </div>
            
            </div><!--- Close for daytab contrainner --->
            </div><!--- Close for daytab contrainner --->
            
            
        </div>
	</div>
          
  </form>
</div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for Adding Program  --------------
?>
  <script>

  	function default_hiddendaytab(){
		
		for(var tab = 1; tab <= 7; tab++){
			
			
			$("#prog_daytab_"+tab).removeClass("active"); // Disable daytab
			$("#prog_daytab_"+tab).attr("hidden","hidden"); 
			
			$("#divbreak_day_"+tab).removeClass("active"); //Remove active class for DIV day
			
			
		}
		
		$("#prog_daytab_8").removeAttr("hidden"); // Enable Summary daytab
		$("#prog_daytab_8").addClass("active"); // Enable Summary daytab
		
		$("#divbreak_day_"+tab).addClass("active"); //Add active class for DIV of summaryday
	}
	
	
	function break_type_list(daybreak,num_breaklist){
		
		$.ajaxSetup({
			async: false
		});
		
		$.ajax('?r=program/japi&action=breakTypeList',{
			type: 'GET',
			dataType: 'json',
			success: function(breakTypeList){
				
				for(var bkseq=1; bkseq <= num_breaklist; bkseq++ ){
				
					$("#break_type_day"+daybreak+"_"+bkseq+" option").remove();		
					$.each(breakTypeList,function(k,v){
	
						$("#break_type_day"+daybreak+"_"+bkseq).append(
														 
							"<option value='"+v.break_type_id+"'>"+v.break_type_name+"</option>"
									 
						);
								
					});
				
				}
			 }
		});

	}


  	function create_daybreak(daybreak,numBreak){
		
		var total_bk = 0;
		var time_bk = 240;
		
		if(numBreak){
			
			var num_breaklist = numBreak;
			
		}else{
			
			var num_breaklist = $("#num_break_day"+daybreak).attr('value');			
			
		}

	
		$("#break_table_day"+daybreak+" tbody tr").remove();
		
		for(var bk=1; bk<= num_breaklist; bk++ ){
			
			$("#break_table_day"+daybreak).children("tbody").append(
			
				"<tr >"+ 
							"<td style='text-align:right;height:15px;padding:2px'>"+bk+"</td>"+
							"<td style='text-align:center;height:15px;padding:2px'>"+
								"<input type='text' class='input-mini break_onairtime_day' value='00:00' id='break_onairtime_day"+daybreak+"_"+bk+"' daygroup='"+daybreak+"' style='height:12px;width:40px' />"+
                  
							"</td>"+
							"<td  style='text-align:center;height:15px;padding:2px'>"+
								"<select id='break_type_day"+daybreak+"_"+bk+"' name='break_type_day"+daybreak+"_"+bk+"' style='width:160px' class='input-small break_type_day' daygroup='"+daybreak+"' >"+
									
								"</select>"+
							"</td>"+
							"<td style='text-align:center;height:15px;padding:2px'>"+
							"<input type='text' name='time_break' id='break_time_day"+daybreak+"_"+bk+"' class='input-mini break_time_day' value='"+time_bk+"' style='height:12px;width:40px' daygroup='"+daybreak+"' />"+
                  
							"</td>"+
				"</tr>"
			); 
		}
		
		break_type_list(daybreak,num_breaklist);
	}
	
	
	function calculate_totalbk_time(daybreak){
		
		var total_bktime = 0;
		var num_breaklist = $("#num_break_day"+daybreak).attr('value');
		
		for(var time_bk=1; time_bk<= num_breaklist; time_bk++ ){
			
			total_bktime = total_bktime +parseInt($("#break_time_day"+daybreak+"_"+time_bk+"").attr('value'));	
		
		}
		
		$("#break_table_day"+daybreak).children("tbody").append(
		
			"<tr>"+ 
							"<td style='text-align:right;height:15px;padding:2px'>เวลารวม</td>"+
							"<td style='text-align:right;height:15px;padding:2px'></td>"+
							"<td style='text-align:right;height:15px;padding:2px'></td>"+
							"<td style='text-align:center;height:15px;padding:2px'><a title='เวลารวม' id='total_tbk_day"+daybreak+"' value='"+total_bktime+"'>"+total_bktime+"</a></td>"+
							
			"</tr>"
			
		);		
		
		
	}

  
	function togle_daygroup(daygroup){
		
		if($("#add_prog_day_"+daygroup).attr('checked') == "checked"){
			
			//-------- Start control day Button ------------
			
			$("#add_prog_day_"+daygroup).removeAttr("checked");
			$("#add_prog_day_"+daygroup).removeClass("btn-inverse active");
			$("#add_prog_day_"+daygroup).addClass("btn btn-info");
			
			//-------- End control day Button --------------
			
			
			//-------- Start control tab of day and div of day ------------
			
			var daytab_val = $("#prog_daytab_"+daygroup).attr("value");
			
			if( $("#divbreak_day_"+daytab_val).hasClass("active")){
				
				$("#divbreak_day_"+daytab_val).removeClass("active");
				$("#divbreak_day_8").addClass("active");
				$("#prog_daytab_8").addClass("active");
				
			}
			
			$("#prog_daytab_"+daygroup).removeClass("active"); // Disable daytab
			$("#prog_daytab_"+daygroup).attr("hidden","hidden"); // Disable daytab
			
			//-------- End control tab of day and div of day ------------
			
			
		}else{
			
			//-------- Start control day Button ------------
			
			$("#add_prog_day_"+daygroup).attr("checked","checked");
			$("#add_prog_day_"+daygroup).removeClass("btn btn-info"); 
			$("#add_prog_day_"+daygroup).addClass("btn btn-inverse active");
			
			//-------- End control day Button --------------
			
			
			$("#prog_daytab_"+daygroup).removeAttr("hidden"); // Enable daytab
			
			create_daybreak(daygroup);// Create break table for selected day
			calculate_totalbk_time(daygroup) // Calculate break time for selected day

		}
	}


	function autoBreakProfile(){ // Clone from Auto Prod
		
		var sentData = $("#break_profile").val();
		var p = $("#break_profile").position();
			
		var add_BK_ProfName = [];
		var add_BK_ProfID = [];
		var count_Prof = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=program/japi&action=autoBreakProf&prof_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoProgComp){
				$.each(autoProgComp,function(k,v){ 
				
					add_BK_ProfID.push(v.break_prof_name+":"+v.break_prof_id);
					add_BK_ProfName.push(v.break_prof_name);
					//console.log("Prog_id="+v.Prog_id);
					
				});
				
				$("#break_profile").autocomplete({
					
					source:add_BK_ProfName,
					select: function (event, ui) {
						
						$("#break_profile").val(ui.item.label); // display the selected text
						for (var i=0;i < add_BK_ProfID.length ;i++){
									
							var n = add_BK_ProfID[i].split(":"); 
							if (n[0]== $("#break_profile").val()) {
								
								$("#break_profile").attr("prof_name", n[0]);
								$("#break_profile").attr("prof_id", n[1]);
									
							}
						}
						
						$("#add_break_prof_alert").css("visibility", "hidden"); // Disapear the alert NEW Company icon
						
					},
					search: function() {
						
						$("#break_profile").attr("prof_id", "");
						$("#add_break_prof_alert").css("visibility", "visible"); // Show the alert NEW Company icon
						
					}
				});
			}
		});	
	}
	
	$("#break_profile").keyup(function(event){
	
		autoBreakProfile();
		
	});
	
	
	
	
	
	
// -------------> Start old function of generate Onair Date ----------------> 

	function autoProgComp(){ // Clone from Auto Prod
		
		var sentData = $("#comp_name").val();
		var p = $("#comp_name").position();
			
		var add_ProgCompName = [];
		var add_ProgCompNameID = [];
		var count_Prog = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=program/japi&action=autoProgComp&comp_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoProgComp){
				$.each(autoProgComp,function(k,v){ 
				
					add_ProgCompNameID.push(v.name+":"+v.company_id);
					add_ProgCompName.push(v.name);
					//console.log("Prog_id="+v.Prog_id);
					
				});
				
				$("#comp_name").autocomplete({
					
					source:add_ProgCompName,
					select: function (event, ui) {
						
						$("#comp_name").val(ui.item.label); // display the selected text
						for (var i=0;i < add_ProgCompNameID.length ;i++){
									
							var n = add_ProgCompNameID[i].split(":"); 
							if (n[0]== $("#comp_name").val()) {
								
								$("#comp_name").attr("comp_name", n[0]);
								$("#comp_name").attr("comp_id", n[1]);
									
							}
						}
						
						$("#add_compname_alert").css("visibility", "hidden"); // Disapear the alert NEW Company icon
						
					},
					search: function() {
						
						$("#comp_name").attr("comp_id", "");
						$("#add_compname_alert").css("visibility", "visible"); // Show the alert NEW Company icon
						
					}
				});
			}
		});	
	}


	
	
	function datetime(){
		
		var day_month =[];
		var start_date = Date.parseExact($("#add_prog_date_start").attr('value'),"d/M/yyyy");
			start_date = new Date(start_date);	
		
		if($("#add_prog_date_end").attr('value') == ""){
			
			var stop_date = start_date;
			
		}else{
			
			var stop_date = Date.parseExact($("#add_prog_date_end").attr('value'),"d/M/yyyy");
				stop_datenew = new Date(stop_date);
		}
		
		
		var oneDay = 24*60*60*1000;
		var diffDays = Math.round(Math.abs((start_date.getTime() - stop_date.getTime())/(oneDay)));
			diffDays = parseInt(diffDays) + 1;
				
		var this_month = start_date.getMonth(); 
		
		if(start_date){
			
			day_month[0] = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" 13:50:00";
			
		}
		
		for(var day =1; day < diffDays; day++){

			var nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+day);
			
		//	if(nextday.getMonth() == this_month){
		
				if(parseInt(nextday.getDay()) == 0){
					if ($("#add_prog_day_7").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" 13:50:00";
						
						day_month[day] = dayweek; 
						
					}else{}	
				}if(parseInt(nextday.getDay()) == 1){ 
				
					if ($("#add_prog_day_1").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" 13:50:00";
						day_month[day] = dayweek; 
						
					}else{}	
				}if(parseInt(nextday.getDay()) == 2){ 
				
					if ($("#add_prog_day_2").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" 13:50:00";
						day_month[day] = dayweek; 
						
					}else{}		
				}if(parseInt(nextday.getDay()) == 3){
					
					if ($("#add_prog_day_3").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" 13:50:00";
						day_month[day] = dayweek; 
						
					}
				}if(parseInt(nextday.getDay()) == 4){
					
					if ($("#add_prog_day_4").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" 13:50:00";
						day_month[day] = dayweek;
						
					}else{}		
				}if(parseInt(nextday.getDay()) == 5){ 
				
					if ($("#add_prog_day_5").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" 13:50:000";
						day_month[day] = dayweek; 
						
					}else{}		
				}if(parseInt(nextday.getDay()) == 6){ 
				
					if ($("#add_prog_day_6").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" 13:50:00";
						day_month[day] = dayweek; 
						
					}else{}		
				}else {}
			//}
		}
		
		if((day_month == "") && (start_date) ){
			
			day_month = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" 13:50:00";
			
		}else{}
		
		day_month = day_month.filter(function(e){return e}); // Select only valid array
		return (day_month);
		
	}

	
	function gen_dayWeekly(){
		
		var minmonth_array = [];
		var dayofweek = 0;
		var cnt_day = 0;
		var dayweekly = [];
		
		for(var day_count = 0; day_count <= 6;  day_count++){
		
			cnt_day += 1;
			
			dayofweek = $("#add_prog_day_"+cnt_day).hasClass("active");
			
			if(dayofweek != 0){
				
				dayweekly[day_count] = cnt_day;
				
			}else {
				
				dayweekly[day_count] = "";
				
			}
			
		}
		
		dayweekly = dayweekly.filter(function(e){return e});
		var new_dayweekly = [];
	
		for(var new_count = 0; new_count < dayweekly.length; new_count++ ){
			
			new_dayweekly[new_count] = dayweekly[new_count] - 1;
		}	
		
		//console.log("dayweekly= "+new_dayweekly);
		var day_weekly = new_dayweekly.join(',');
		return(day_weekly);	
		
	}
	

  	function show_bklist(){
		
		var total_bk = 0;
		var num_breaklist =$("#num_break").attr('value');
		var time_bk = 240;
	
		$("#break_table tbody tr").remove();
		//$("#break_table tfoot tr").remove();
		for(var bk=1; bk<= num_breaklist; bk++ ){
			
			$("#break_table tbody").append(
				"<tr >"+ 
							"<td style='text-align:right;height:15px;padding:2px'>"+bk+"</td>"+
							"<td style='text-align:center;height:15px;padding:2px' class='time_each_bk'>"+
								"<input type='text' class='input-mini' value='00:00' style='height:12px;width:40px' />"+
                  
							"</td>"+
							"<td  style='text-align:center;height:15px;padding:2px'>"+
								"<select id='break_type_"+bk+"' name='break_type_"+bk+"' style='width:160px' class='input-small'>"+
									"<option value='1'>ปะหัว</option>"+
									"<option value='2'>ระหว่างรายการ</option>"+
									"<option value='3'>ปะท้าย</option>"+
									"<option value='4'>สำรอง</option>"+
								"</select>"+
							"</td>"+
							"<td style='text-align:center;height:15px;padding:2px' class='time_each_bk'>"+
							"<input type='text' name='num_break' id='num_break_"+bk+"' class='input-mini' value='"+time_bk+"' style='height:12px;width:40px' />"+
                  
							"</td>"+
				"</tr>"
			); 
			
			//var time_bk = $("#num_break_"+br+"").attr('value'); 
		}
		
		for(var time_bk=1; time_bk<= num_breaklist; time_bk++ ){
			
			total_bk = total_bk +parseInt($("#num_break_"+time_bk+"").attr('value'));	
		
		}
		
		$("#break_table tbody").append(
			"<tr>"+ 
							"<td style='text-align:right;height:15px;padding:2px'>เวลารวม</td>"+
							"<td style='text-align:right;height:15px;padding:2px'></td>"+
							"<td style='text-align:right;height:15px;padding:2px'></td>"+
							"<td style='text-align:center;height:15px;padding:2px'><a title='เวลารวม' id='total_tbk' value='"+total_bk+"'>"+total_bk+"</a></td>"+
			"</tr>"
		);
		
	}
	
	function show_bklist_change(){
		
		var total_bk = 0;
		var time_bk = [];
		var num_breaklist = $("#num_break").attr('value');
		
		for(var tbk=1; tbk<= num_breaklist; tbk++ ){	
			time_bk[tbk] = $("#num_break_"+tbk+"").attr('value');
		}
		
		$("#break_table tbody tr").remove();
		for(var bk=1; bk<= num_breaklist; bk++ ){
					
			$("#break_table tbody").append(
				"<tr >"+ 
							"<td style='text-align:right;height:15px;padding:2px'>"+bk+"</td>"+
							"<td style='text-align:center;height:15px;padding:2px' class='time_each_bk'>"+
								"<input type='text' class='input-mini' value='00:00' style='height:12px;width:40px' />"+
                  
							"</td>"+
							"<td  style='text-align:center;height:15px;padding:2px'>"+
								"<select id='break_type_"+bk+"' name='break_type_"+bk+"' style='width:160px' class='input-small'>"+
									"<option value='1'>ปะหัว</option>"+
									"<option value='2'>ระหว่างรายการ</option>"+
									"<option value='3'>ปะท้าย</option>"+
									"<option value='4'>สำรอง</option>"+
								"</select>"+
							"</td>"+
							"<td style='text-align:center;height:15px;padding:2px' class='time_each_bk'>"+
							"<input type='text' name='num_break' id='num_break_"+bk+"' class='input-mini' value='"+time_bk[bk]+"' style='height:12px;width:40px' />"+
                  
							"</td>"+
				"</tr>"
			); 
		}		
		
		for(var time_bk=1; time_bk<= num_breaklist; time_bk++ ){	
			total_bk = total_bk +parseInt($("#num_break_"+time_bk+"").attr('value'));	
		}
		
		$("#break_table tbody").append(
			"<tr>"+ 
							"<td style='text-align:right;height:15px;padding:2px'>เวลารวม</td>"+
							"<td style='text-align:right;height:15px;padding:2px'></td>"+
							"<td style='text-align:right;height:15px;padding:2px'></td>"+
							"<td style='text-align:center;height:15px;padding:2px'><a title='เวลารวม' id='total_tbk' value='"+total_bk+"'>"+total_bk+"</a></td>"+
			"</tr>"
		);		
		
	}
	
	
// -------------> End old function of generate Onair Date ----------------> 	

	
	function open_add_program(){
		
		$('#addprogDi').dialog('open'); 
		
		
		$('#prog_prime').removeAttr("checked");
		$('#prog_rad1').attr("checked","checked");
		$('#comp_name').val('');
		$('#price_min').val('');
		$('#packprice_min').val('');
		$('#prog_promotion').val(0);
		$('#netprice_min').val('');
		$('#prog_Rad3').attr("checked","checked");
		
		
		$('#addtvprogram').val('');
		$('#add_prog_time_start').val('');
		$('#add_prog_time_end').val('');
		$('#add_prog_date_start').val('');
		$('#add_prog_date_end').val('');
		$('#num_break').val(4);
		$('#add_prog_info').val('');
		
		$("#add_prog_daygroup").children("button").each(function(){
		
			$(this).removeClass("btn-inverse active"); 
			$(this).removeAttr("checked");
			$(this).addClass("btn btn-info");
			
		});
		
		$('#summary_program').text('');
		
		default_hiddendaytab(); // Default daytab to be hide only summary still appear
		
		return false;
		
	}
	
	
	$(".num_of_break").change(function(){ // Number of break NEW!
	
		create_daybreak($(this).attr("daygroup"));
		calculate_totalbk_time($(this).attr("daygroup"));
		//console.log("create_daybreak_daygroup="+$("#"+$(this).attr("id")).attr("daygroup"));
		
	});
	
	
	$(".break_time_day").focusout(function(e) {
		
		
		console.log("value of each BKDAY= "+$(this).val());
		
	});
	
	
	
	
	
	
// ------------> Start old function ----------------->	
	
  	$("#num_break").ready(function(){ // Number of break OLD!
								   
		show_bklist();
		
	});
	
	$("#num_break").change(function() {
								  
		show_bklist();						  
									  
	});
	
	$(".time_each_bk").change(function(){
	
		show_bklist_change();
	});
	

	$("#packprice_min").focusout(function(e) {
		
		if( $("#packprice_min").val()){
			var inputPack_pricemin = $("#packprice_min").val();
			inputPack_pricemin = parseFloat(inputPack_pricemin.replace(/,/g, ''));
			inputPack_pricemin = inputPack_pricemin.toFixed(2);
			$("#packprice_min").attr("value",money_forchange(inputPack_pricemin));
		}
		
	});
	
	
	$("#price_min").focusout(function(e) {
		
		if( $("#price_min").val()){
		
			var inputProg_pricemin = $("#price_min").val();
			var inputProg_discount = $("#prog_promotion").val();
			
			inputProg_pricemin = parseFloat(inputProg_pricemin.replace(/,/g, ''));
			var inputProg_netprice = parseInt(inputProg_pricemin)*(1-(parseInt(inputProg_discount)/100));
			inputProg_netprice = inputProg_netprice.toFixed(2);
			
			inputProg_pricemin = inputProg_pricemin.toFixed(2);
			$("#netprice_min").attr("value",money_forchange(inputProg_netprice));
			$("#price_min").attr("value",money_forchange(inputProg_pricemin));
		
		}
		
	});   
	
	$("#prog_promotion").change(function(){
		
		if( $("#price_min").val()){
			
			var inputProg_pricemin = $("#price_min").val();
			var inputProg_discount = $("#prog_promotion").val();
			
			inputProg_pricemin = parseFloat(inputProg_pricemin.replace(/,/g, ''));
			var inputProg_netprice = parseInt(inputProg_pricemin)*(1-(parseInt(inputProg_discount)/100));
			inputProg_netprice = inputProg_netprice.toFixed(2);
			
			inputProg_pricemin = inputProg_pricemin.toFixed(2);
			$("#netprice_min").attr("value",money_forchange(inputProg_netprice));
			$("#price_min").attr("value",money_forchange(inputProg_pricemin));
			
		}
	
	});	
	

	
	$("#netprice_min").focusout(function(e) {
		
		if( $("#netprice_min").val()){
			
			var real_change = $("#netprice_min").attr("value");
			real_change = parseFloat(real_change.replace(/,/g, '')).toFixed(2);
			
			$("#netprice_min").attr("value",money_forchange(real_change));
		}
	});
	
	
	$("#prog_dlist").ready(function(){
		
		var program = 2;
		var year =$("#prog_ylist").attr('value');
		var month=$("#prog_mlist").attr('value');
		var day=$("#prog_dlist").attr('value');
		
		show_tvprogram_list(2);
		
	});
	
	$("#comp_name").keyup(function(event){
	
		autoProgComp();
		
	});
	
//----->Start of Add Program in DB


	function addprogDb(){
		
		var delay = 0;
		var n_break = 0;
		var prog_price = 0;
		var pack_price = 0;
		var time_bk = []; 
		var date_time_prog = [];
		var num_breaklist = 0;
		var totaltime_bk = 0;
		var prog_name = 0;
		var prog_desc = 0;
		
		var prog_comp = 1;
		var prog_primetime = 0;
		var prog_datestart = 0;
		var prog_dateend = 0;
			
		var start_date = start_date = Date.parseExact($("#add_prog_date_start").attr('value'),"d/M/yyyy");
			start_date = new Date(start_date);
		var stop_date = Date.parseExact($("#add_prog_date_end").attr('value'),"d/M/yyyy");
			stop_date = new Date(stop_date);
			
		//console.log("start_date= "+start_date+" stop_date="+stop_date);
			
		var oneDay = 24*60*60*1000;
		var diffDays = Math.round(Math.abs((start_date.getTime() - stop_date.getTime())/(oneDay)));
		var start_date_master = 0;
		var stop_date_master = 0;
		
		/* Start Demo MAUY THAI */
		
		if($("#add_prog_date_end").attr('value')){
			
			start_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" 13:50:00";
			stop_date_master = stop_date.getFullYear()+"-"+(stop_date.getMonth()+1)+"-"+stop_date.getDate()+" 14:16:00";
			
		}else {
			
			start_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" 13:50:00";
			stop_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" 14:16:00";
			
		}
		
		
		prog_name = $("#addtvprogram").attr('value');
		comp_name = $("#comp_name").attr('value');
		
		if($("#comp_name").attr("comp_id")){
			
			var add_comp_id = $("#comp_name").attr("comp_id");
			
		}else{
			
			var add_comp_id = "0";
			
		}
		
		if($("#prog_rad1").attr('checked')){
			
			prog_comp = 1; // CH7
			
		}else {
			
			prog_comp = 0; // Rental
		}
		
		date_time_prog = datetime(); //-----> Date to Generatate the Onair Date
		
		console.log("date_time_prog= "+date_time_prog);
		
		prog_desc = $("#add_prog_info").attr('value'); // Desc of program
		
		if(!prog_desc){
			
			prog_desc = "";	
			
		}	
		
		
		/* End Demo MAUY THAI */
		
		/*
		
		if($("#add_prog_date_end").attr('value')){
			
			start_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#add_prog_time_start").attr('value')+":00";
			stop_date_master = stop_date.getFullYear()+"-"+(stop_date.getMonth()+1)+"-"+stop_date.getDate()+" "+$("#add_prog_time_end").attr('value')+":00";
			
		}else {
			
			start_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#add_prog_time_start").attr('value')+":00";
			stop_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#add_prog_time_end").attr('value')+":00";
			
		}
		
		*/
		
		//console.log("Date of Prog ="+start_date_master+" To "+stop_date_master); 
		
		//console.log("Date of Prog ="+$("#add_prog_date_start").attr('value')); 
		//console.log(new Date($("#add_prog_date_start").attr('value')));
		
	
	/*	
		if($("#prog_prime").attr('checked')){
			
			prog_primetime = 1;
			
		}else {
			
			prog_primetime = 0;
		}
			

		
	*/	
	
		//num_breaklist = $("#num_break").attr('value');
		
	//	prog_price = $("#price_min").attr('value');
	//	pack_price = $("#packprice_min").attr('value');
		
	//	prog_price = parseFloat(prog_price.replace(/,/g, ''));
	//	pack_price = parseFloat(pack_price.replace(/,/g, ''));
		
	//	var add_prog_discount = $("#prog_promotion").attr('value'); // Discount
	//	var add_netprice_min  = $("#netprice_min").attr('value'); // Net Price
		
	//	add_netprice_min = parseFloat(add_netprice_min.replace(/,/g, ''));
	//	totaltime_bk = $("#total_tbk").attr('value'); // Total time of break of program
		
		
	/*	
		for(var tbk=0; tbk < num_breaklist; tbk++ ){
			
			n_break = parseInt(tbk)+1;
			time_bk[tbk] = parseInt($("#num_break_"+n_break+"").attr('value'));
		}
	*/	

		//console.log("prog_comp= "+prog_comp +"prog_primetime= "+prog_primetime+" date_time_prog"+date_time_prog);
		
		//var onair_weekly = gen_dayWeekly(); ///
		//var breaktime_list = time_bk.join(',');
		//console.log("onair_weekly= "+onair_weekly);
		//console.log("prog_comp= "+prog_comp+" add_comp_id= "+add_comp_id);

		if(start_date == "" && stop_date == ""){
			
			alert("กรุณาระบุวันออกอากาศให้สมบูรณ์")
			
		}else if(start_date.getTime() >  stop_date.getTime()){
			
			alert("กรุณาแก้ไขวันสิ้นสุดการออกอากาศ")
			
		}else if(comp_name == ""){
			
			alert("กรุณาระบุบริษัทสั่งจ่าย")
			
		} else{
			
			//if((prog_name !== "")&&($("#add_prog_date_start").attr('value') != "") && prog_price != "" && pack_price != ""){
				
			if((prog_name !== "")&&($("#add_prog_date_start").attr('value') != "" )){
			
				$.ajaxSetup({
					async: false
				});
				$.ajax({
					
						type: "POST",
						url: "?r=program/addprog",	
						//data:{'prog_name':prog_name,'prog_desc':prog_desc,'owner_comp':prog_comp, 'minute_price':prog_price,'pack_price':pack_price,'num_break':num_breaklist,'time_break':totaltime_bk,'start_date_master':start_date_master,'stop_date_master':stop_date_master,'primetime':prog_primetime,'time_bk':time_bk, 'date_time':date_time_prog,'comp_name':comp_name,'discount':add_prog_discount,'netprice':add_netprice_min,'onairweekly':onair_weekly,'comp_id':add_comp_id,'breaktime_list':breaktime_list},
						data:{'prog_name':prog_name,'prog_desc':prog_desc,'start_date_master':start_date_master,'stop_date_master':stop_date_master, 'date_time':date_time_prog,'owner_comp':prog_comp,'comp_name':comp_name,'comp_id':add_comp_id },
						
						success: function(data) {
							
							//alert("success");
							
						},
						error: function(data){
							
							alert("มีข้อผิดพลาดเกิดขึ้นระหว่างเพิ่มรายการ กรุณาทำรายการใหม่อีกครั้ง");
					
						}
									   
				});
				
				//console.log("Date of Prog ="+date_time_prog);
				//while(delay < 1000){delay++;}
				
				readprogList(); //--------> Send program list to onair and prepare onair page
				var prog_on = $("#prog_on").attr('value');
				var onair_mon = parseInt($("#onair_mon").attr('value'));
				var onair_year = parseInt($("#onair_year").attr('value'))-543;
				default_daytab(); // Default Class for Daytab
				alarming_daytab(prog_on,onair_year,onair_mon);
				
				var day = $("#ul_daytab").find("li.ui-state-active").text();
				checkBreak(prog_on,onair_year,onair_mon,day);
				//------------------------->

				$("#add_compname_alert").css("visibility", "hidden");
				$(this).dialog("close"); 
				
			}else {
				
				alert("มีข้อผิดพลาดเกิดขึ้นระหว่างเพิ่มรายการ กรุณาตรวจสอบชื่อโปรแกรม วันที่ ราคาต่อนาทีและราคาแพคต่อนาที ");
			}
			
		}	
		
		show_tvprogram_list(2);
		return false;
	}

	
//----->End of Add Program in DB -----------------
	
	
	
//-------------> Start Update Program ---------------> 	
	
	function updateProgram(prog_id,onair_prof_id){

		$('#addprogDi').dialog('open'); 
		$('#addprogDi').attr('prog_id',prog_id); 
		$('#addprogDi').attr('onair_prof_id',onair_prof_id); 
		$("#add_break_prof_alert").css("visibility", "hidden"); // Disapear the alert NEW Company icon
		$('#summary_program').text('');
		$('#summary_program').text("ตารางสรุปข้อมูลสำหรับรายการ ");
		
		$("#add_prog_daygroup").children("button").each(function(){
		
			$(this).removeClass("btn-inverse active"); 
			$(this).removeAttr("checked");
			$(this).addClass("btn btn-info");
			
		});
		
		
		default_hiddendaytab(); // Default daytab to be hide only summary still appear
		genSummaryProgram(prog_id,onair_prof_id);
		
		
		//console.log("prog_id= "+prog_id+" onair_prof_id="+onair_prof_id);
		return false;
		
		
	}
	
	function LastDateofMonth(dateinput) {
	
	var Year =  dateinput.getFullYear();
	var Month = parseInt(dateinput.getMonth())+1;
	
	var end_date = new Date( (new Date(Year, Month,1))-1 );
	
    	return(end_date);
	}
	
	
	function enableDaytab(daygroup,onair_prof_id,time_start){
		
		if($("#add_prog_day_"+daygroup).attr("checked") != "checked"){
		
			$("#add_prog_day_"+daygroup).attr("checked","checked");
			$("#add_prog_day_"+daygroup).removeClass("btn btn-info"); 
			$("#add_prog_day_"+daygroup).addClass("btn btn-inverse active");
			
			//-------- End control day Button --------------
			
			
			$("#prog_daytab_"+daygroup).removeAttr("hidden"); // Enable daytab
			
			if(daygroup == "7"){
				
				var dayweek_num = "0";
				
			}else{
				
				var dayweek_num = daygroup;
				
			}
			
			var totalTimeNumBk = sumTimeCntBk(onair_prof_id,time_start,dayweek_num)// Number of break
			$("#num_break_day"+daygroup).val(totalTimeNumBk[1]);// Number of break
			
			create_daybreak(daygroup,totalTimeNumBk[1]);// Create break table for selected day
			//calculate_totalbk_time(daygroup) // Calculate break time for selected day	
		}
	}
	
	
	function genSummaryProgram(prog_id,onair_prof_id){
		
		var sum_programID = 0;
		var sum_programname = 0;
		var sum_datetime_start = 0;
		var sum_datetime_end = 0;
		var sum_bktype_prof_ID = 0;
		var sum_bktype_prof_name = 0;
		var sum_prog_desc = 0;
		
		$.ajaxSetup({
				async: false
		});
									   		
		$.ajax('?r=program/japi&action=summaryProgram&onair_prof_id='+onair_prof_id+'',{
			type: 'GET',
			dataType: 'json',
			success: function(summaryProgram){
				
				var currentBreakSeq = 0;
				var check_daytab = 0;
				
				
				
				//-----------> Start generate Header and OnairTime column of Summary Break Table ---------> 
				
				$("#summary_program_table tbody tr").remove();
				var summaryOnairtTimeTr = "<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
											"<td style='text-align:left;padding-top:8px'>เวลาออกอากาศ</td>";
											
				for(var countday = 1; countday <= 7; countday++){
					summaryOnairtTimeTr += "<td style='text-align:center;padding-top:8px' id='onairDateTime"+countday+"'></td>";
				} 
																
				summaryOnairtTimeTr += "</tr>";
				$("#summary_program_table tbody").append(summaryOnairtTimeTr);	
				
				//-----------> End generate Header and OnairTime column ---------> 
				
				
				$.each(summaryProgram,function(kpro,vpro){
					
					
					//----> Start retrieve main master data from programs database table ----->
					 
					sum_programname = vpro.prog_name;
					sum_datetime_start = Date.parse(vpro.date_start,"yyyy-MM-dd HH:mm:ss");
					//sum_datetime_end = Date.parse(vpro.date_start,"yyyy-MM-dd HH:mm:ss");
					sum_datetime_end = LastDateofMonth(sum_datetime_start);
					
					sum_datetime_start = new Date(sum_datetime_start).toString("dd/MM/yyyy");
					sum_datetime_end =  new Date(sum_datetime_end).toString("dd/MM/yyyy");
					
					sum_bktype_prof_ID = vpro.break_prof_id;
					sum_bktype_prof_name = vpro.break_prof_name;
					sum_prog_desc = vpro.prog_desc;
					
					//----> End retrieve main master data from programs database table ----->
					
					
					if(currentBreakSeq != vpro.break_seq){
						
						//----> Start generate Summary Break Table ---->
						
						currentBreakSeq = vpro.break_seq;
						
						var summaryTable =  "<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
											"<td style='text-align:left;padding-top:8px'>เบรค "+currentBreakSeq+"</td>";
											
						for(var countday = 1; countday <= 7; countday++){
							summaryTable += "<td style='text-align:left;padding-top:8px' id='sum_bkseq"+currentBreakSeq+"day"+countday+"'></td>";
						} 
																
						summaryTable += "</tr>";
						$("#summary_program_table tbody").append(summaryTable);	
						
						//----> End of generating Summary Break Table ---->
						
						
						//----> Start to send data to summary break table ---->
												
						var summaryData =  vpro.break_type_name+","+filterTimeMinSec(vpro.onairtime)+","+vpro.time_len;
						
						if(vpro.dayweek_num == "0"){
							
							var DayofWeek = 7; 
							
						}else {
							
							var DayofWeek = vpro.dayweek_num; 
							
						}
						
						$("#sum_bkseq"+currentBreakSeq+"day"+DayofWeek).text(summaryData);
						$("#sum_bkseq"+currentBreakSeq+"day"+DayofWeek).attr("time_len",vpro.time_len);
						$("#onairDateTime"+DayofWeek).text(filterTimeMinSec(vpro.time_start)+"-"+filterTimeMinSec(vpro.time_end));	
						$("#onairDateTime"+DayofWeek).attr("starttime",filterTimeMinSec(vpro.time_start));
						
						//----> End of sending data to summary break table ---->
						
						
						//------------> Start to generate day tab ----------->
											 
						
						if(DayofWeek){
							
							/*
							
							var totalTimeNumBk = sumTimeCntBk(onair_prof_id,vpro.time_start,vpro.dayweek_num)// Number of break  
							
							if(check_daytab != DayofWeek){
								
								check_daytab = DayofWeek;
								enableDaytab(check_daytab,totalTimeNumBk[1]);// Enable daytab
							//create_daybreak(DayofWeek); // Create break table of day tab	
								
							}
							
							*/

							enableDaytab(DayofWeek,onair_prof_id,vpro.time_start);// Enable daytab, Attribute number of break, create break table 
							
							if(vpro.is_primetime == "1"){
								
								$("#prog_prime_day"+DayofWeek).attr("checked","checked");
								
							}else{
								
								$("#prog_prime_day"+DayofWeek).removeAttr("checked");
								
							} // Prime Time
							
							$("#comp_name_day"+DayofWeek).attr("comp_id",vpro.comp_paid_id);  // Company 
							$("#comp_name_day"+DayofWeek).attr("comp_name",vpro.comp_name);  // Company 
							$("#comp_name_day"+DayofWeek).val(vpro.comp_name);  // Company 
							$("#add_compname_alert_day"+DayofWeek).css("visibility", "hidden");  // Company 
							

							if(vpro.bbtv_group == "1"){
								
								$("#prog_owner_day"+DayofWeek).attr("checked","checked");
								$("#prog_rental_day"+DayofWeek).removeAttr("checked");
								
							}else{
								
								$("#prog_owner_day"+DayofWeek).removeAttr("checked");
								$("#prog_rental_day"+DayofWeek).attr("checked","checked");
								
							} // Owner company
							
							
							$("#progtime_start_day"+DayofWeek).val(filterTimeMinSec(vpro.time_start)); // Start time 
							$("#progtime_end_day"+DayofWeek).val(filterTimeMinSec(vpro.time_end)); // End time 
							
							//----> Start Price ---->
							$("#price_min_day"+DayofWeek).val(vpro.price_minute);
							$("#packprice_min_day"+DayofWeek).val(vpro.price_pack);
							$("#min_packprice_day"+DayofWeek).val(vpro.minute_pack);
							$("#prog_discount_day"+DayofWeek).val(vpro.price_discount);
							$("#netprice_min_day"+DayofWeek).val(vpro.price_net);
							//<---- End Price ----
							
							console.log("break_onairtime_day"+DayofWeek+"_"+vpro.break_seq+" time_len="+vpro.time_len);
							//----------------> Start break of onair table --------->
							$("#break_onairtime_day"+DayofWeek+"_"+vpro.break_seq).val(filterTimeMinSec(vpro.onairtime));
							$("#break_type_day"+DayofWeek+"_"+vpro.break_seq).val(vpro.break_type_id);
							$("#break_time_day"+DayofWeek+"_"+vpro.break_seq).val(vpro.time_len);
							//----------------> End break of onair table --------->
							
						}
						
						//------------> End of day tab generation  ----------->
											
					}else {
						
						
						//----> Start to send data to summary break table ---->
						
						if(vpro.dayweek_num == "0"){
							
							var DayofWeek = 7; 
							
						}else {
							
							var DayofWeek = vpro.dayweek_num; 
							
						}
						
						var summaryData =  vpro.break_type_name+","+filterTimeMinSec(vpro.onairtime)+","+vpro.time_len;
						
						$("#sum_bkseq"+vpro.break_seq+"day"+DayofWeek).text(summaryData);
						$("#sum_bkseq"+currentBreakSeq+"day"+DayofWeek).attr("time_len",vpro.time_len);
						$("#onairDateTime"+DayofWeek).text(filterTimeMinSec(vpro.time_start)+"-"+filterTimeMinSec(vpro.time_end));	
						$("#onairDateTime"+DayofWeek).attr("starttime",filterTimeMinSec(vpro.time_start));
						
						
						//----> End of sending data to summary break table ---->
						
						
						//------------> Start to generate day tab ----------->
						
						if(DayofWeek){
							
							enableDaytab(DayofWeek,onair_prof_id,vpro.time_start);// Enable daytab, Attribute number of break, create break table 
							
							//console.log(DayofWeek)
							if(vpro.is_primetime == "1"){
								
								$("#prog_prime_day"+DayofWeek).attr("checked","checked");
								
							}else{
								
								$("#prog_prime_day"+DayofWeek).removeAttr("checked");
								
							} // Prime Time
							
							$("#comp_name_day"+DayofWeek).attr("comp_id",vpro.comp_paid_id);  // Company 
							$("#comp_name_day"+DayofWeek).attr("comp_name",vpro.comp_name);  // Company 
							$("#comp_name_day"+DayofWeek).val(vpro.comp_name);  // Company 
							$("#add_compname_alert_day"+DayofWeek).css("visibility", "hidden");  // Company 
							

							if(vpro.bbtv_group == "1"){
								
								$("#prog_owner_day"+DayofWeek).attr("checked","checked");
								$("#prog_rental_day"+DayofWeek).removeAttr("checked");
								
								
							}else{
								
								$("#prog_owner_day"+DayofWeek).removeAttr("checked");
								$("#prog_rental_day"+DayofWeek).attr("checked","checked");
								
							} // Owner company
							
							
							$("#progtime_start_day"+DayofWeek).val(filterTimeMinSec(vpro.time_start)); // Start time 
							$("#progtime_end_day"+DayofWeek).val(filterTimeMinSec(vpro.time_end)); // End time 
							
							//----> Start Price ---->
							$("#price_min_day"+DayofWeek).val(vpro.price_minute);
							$("#packprice_min_day"+DayofWeek).val(vpro.price_pack);
							$("#min_packprice_day"+DayofWeek).val(vpro.minute_pack);
							$("#prog_discount_day"+DayofWeek).val(vpro.price_discount);
							$("#netprice_min_day"+DayofWeek).val(vpro.price_net);
							//<---- End Price ----
							
							
							console.log("break_onairtime_day"+DayofWeek+"_"+vpro.break_seq+" time_len="+vpro.time_len);
							//----------------> Start break of onair table --------->
							$("#break_onairtime_day"+DayofWeek+"_"+vpro.break_seq).val(filterTimeMinSec(vpro.onairtime));
							$("#break_type_day"+DayofWeek+"_"+vpro.break_seq).val(vpro.break_type_id);
							$("#break_time_day"+DayofWeek+"_"+vpro.break_seq).val(vpro.time_len);
							//----------------> End break of onair table --------->
							
						}
						
						
						//------------> End of day tab generation  ----------->
						
					}
					
				
				});
				
				
				var summaryTotalTime =  "<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
											"<td style='text-align:left;padding-top:8px'>เวลารวม</td>";
				var TimeCntBk = [];
											
				for(var countday = 1; countday <= 7; countday++){
									
					if(countday == 7){
						
						var dayofweek_num = 0;
						
					}else{
						
						var dayofweek_num = countday;
						
					}
					
					var prog_time_start =  $("#onairDateTime"+countday).attr("starttime");

					if(prog_time_start){
									
						TimeCntBk = sumTimeCntBk(onair_prof_id,prog_time_start,dayofweek_num);
						var sumTime = TimeCntBk[0];
						var countBK = TimeCntBk[1];
						
						summaryTotalTime += "<td style='text-align:right;padding-top:8px' id='sumTimeLength"+countday+"'>"+sumTime+"</td>";
					
					}else {
						
						summaryTotalTime += "<td style='text-align:right;padding-top:8px' id='sumTimeLength"+countday+"'></td>";
						
					}
					
					calculate_totalbk_time(countday); // Calculate total break time for each DayTab
					
				} 
																
				summaryTotalTime += "</tr>";
				$("#summary_program_table tbody").append(summaryTotalTime);			
				
			}
		});
		
		
		$('#addtvprogram').attr("prog_ID",sum_programname);
		$('#addtvprogram').val(sum_programname);
		
		$('#add_prog_date_start').val(sum_datetime_start);
		$('#add_prog_date_end').val(sum_datetime_end);
		
		$('#break_profile').val(sum_bktype_prof_name);
		$("#break_profile").attr("prof_id",sum_bktype_prof_ID);
		$('#add_prog_info').val(sum_prog_desc);
		$('#summary_program').text("ตารางสรุปข้อมูลรวมสำหรับรายการ "+sum_programname);
		
	
	}
	
	
//-------------> End Update Program ---------------> 		
	
	function test2(){
		alert("test");	
	}
	
  </script>   

<?php
	//--------------- Start of Dialog for Update Program  ------------
	
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'update_ProgDi',
        'options'=>array(
            'title'=>'แก้ไขรายการโทรทัศน์',
            'autoOpen'=>false,
			'width'=>600,
			'height'=>700,
            'modal'=>true,
            'buttons'=>array(
				'เพิ่มวันออกอากาศ'=>'js:updateAndAddprogDb',
                'บันทึกการแก้ไข'=>'js:updateprogDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input;" >
  <form class="form-horizontal" style="font-size:1em" > 
	<div class="row-fluid">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="update_tvprogram">รายการ:</label>
       	</div>
  		<div class="span5" align="left">
            	<input type="text" name="update_tvprogram" id="update_tvprogram" update_prog_id="" class="ui-ams-input text ui-widget-content ui-corner-all "/> 
       	</div>        
        <div class="span4" align="left" style="margin-top:5px" >
       		<label class="checkbox">
          		<input type="checkbox" value="" id="update_prog_prime">รายการ Prime Time
        	</label>
    	</div>
  	</div> 
	<div class="row-fluid">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="update_owner_tvprogram"></label>
       	</div>
        <div class="span5" align="right" style="margin-top:5px">
        	<div class="row-fluid" >
    			<div class="span6" align="left" >	
                    <label class="radio">
                        <input type="radio" name="update_owner_tvprogram" id="update_owner_tvprogram_1" value="รายการสถานี"  checked="checked">รายการสถานี
                    </label>
              	</div> 
                <div class="span6" align="left">	      
                    <label class="radio">
                        <input type="radio" name="update_owner_tvprogram" id="update_owner_tvprogram_2" value="รายการผู้เช่า" >รายการผู้เช่า
                    </label>
                </div>
         	</div>           
       	</div>
        <div class="span4" align="right" style="margin-top:5px">
        	<label class="radio"></label>
       	</div>
 	</div>
	<div class="row-fluid">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="update_comp_name">บริษัทสั่งจ่าย:</label>
       	</div>
  		<div class="span5" align="left">
            	<input type="text" name="update_comp_name" id="update_comp_name" comp_id="" value="" class="text ui-widget-content ui-corner-all"/>
       	</div>        
        <div class="span4" align="left" style="margin-top:3px"  >
       		<label id="update_compname_alert" style="visibility:hidden"><a title='บริบัทสั่งจ่ายใหม่';><img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' /></a></label>
    	</div>
  	</div>
	<div class="row-fluid">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="update_price_min">ราคาเต็มต่อนาที:</label>
       	</div>
  		<div class="span5" align="left">
            	<input type="text" name="update_price_min" id="update_price_min" value="" class="text ui-widget-content ui-corner-all"/>
       	</div>        
        <div class="span4" align="left" style="margin-top:5px" >
       		<label></label>
    	</div>
  	</div>     
    <div class="row-fluid">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="update_packprice_min">ราคาแพคต่อนาที:</label>
       	</div>
  		<div class="span5" align="left">
           	<input type="text" name="update_packprice_min" id="update_packprice_min" value="" class="text ui-widget-content ui-corner-all"/>
       	</div>        
        <div class="span4" align="left" style="margin-top:5px" >
       		<label></label>
    	</div>
  	</div>
    <div class="row-fluid" >
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="up_discount_prog">ส่วนลด(%):</label>
       	</div>
  		<div class="span5" align="left">
            <select id="up_discount_prog" class="input-small" >
            	<option value="0">0</option>
         		<option value="5">5</option>
      	 		<option value="10">10</option>
             	<option value="15">15</option>            	
             	<option value="20">20</option>
             	<option value="25">25</option>
              	<option value="30">30</option>
              	<option value="35">35</option>
              	<option value="40">40</option>
             	<option value="45">45</option>
              	<option value="50">50</option>
        	</select>
       	</div>        
        <div class="span4" align="left" style="margin-top:5px" >
       		<label></label>
    	</div>
  	</div> 
    <div class="row-fluid">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="update_netprice_min">ราคาเต็มสุทธิต่อนาที:</label>
       	</div>
  		<div class="span5" align="left">
           	<input type="text" name="update_netprice_min" id="update_netprice_min" class="text ui-widget-content ui-corner-all"/>
       	</div>        
        <div class="span4" align="left" style="margin-top:5px" >
       		<label></label>
    	</div>
  	</div> 
    <div class="row-fluid" style="margin-top:5px">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="update_progtime_start">เริ่มออกอากาศเวลา:</label>
       	</div>
  		<div class="span2" align="left">
        	<input type="text" name="update_progtime_start" id="update_progtime_start" value="" class="text ui-widget-content ui-corner-all"  style="width:70px" />
       	</div>
        <div class="span1" align="center" style="margin-top:5px; margin-left:11px">
        	<label for="update_progtime_end"> ถึง </label>
       	</div>
  		<div class="span2" align="left" style="margin-left:7px">
        	<input type="text" name="update_progtime_end" id="update_progtime_end" value="" class="text ui-widget-content ui-corner-all"  style="width:70px" />

       	</div>
  	</div>
    
    <div class="row-fluid" style="margin-top:5px">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="update_date_break">วันเริ่มออกอากาศ:</label>
       	</div>
  		<div class="span9" align="left">
        	<?php
            	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name'=>'my_date',
				'id'=>'up_prog_date_start',
				'value'=>date('d/m/Y'),
                'language'=>Yii::app()->language=='et' ? 'et' : null,
                'options'=>array(
                	'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
                	'showOn'=>'button', // 'focus', 'button', 'both'
                	'buttonText'=>Yii::t('ui','Select form calendar'),
                	'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
					'dateFormat'=>'dd/mm/yy',
                	'buttonImageOnly'=>true,),
                'htmlOptions'=>array(
                	'style'=>'width:177px;vertical-align:top'),
                ));   
        	?>	  
       	</div>
  	</div>
    <div class="row-fluid">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="update_date_break">ถึง:</label>
       	</div>
  		<div class="span9" align="left">
        	<?php
            	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name'=>'my_date',
				'id'=>'up_prog_date_end',
				'value'=>date('d/m/Y'),
                'language'=>Yii::app()->language=='et' ? 'et' : null,
                'options'=>array(
                	'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
                	'showOn'=>'button', // 'focus', 'button', 'both'
                	'buttonText'=>Yii::t('ui','Select form calendar'),
                	'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
					'dateFormat'=>'dd/mm/yy',
                	'buttonImageOnly'=>true,),
                'htmlOptions'=>array(
                	'style'=>'width:177px;vertical-align:top'),
                ));   
        	?>	  
       	</div>
  	</div>
    <div class="row-fluid" style="margin-top:5px">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="up_prog_daygroup">รอบสัปดาห์:</label>
       	</div>
        <div class="span5" align="left" >
            <div class="btn-group" data-toggle="buttons-checkbox" id="up_prog_daygroup">
              <button type="button" class="btn btn-info" id="up_prog_day_1" value="1" onclick =up_togle_daygroup(1)>จ</button>
              <button type="button" class="btn btn-info" id="up_prog_day_2" value="2" onclick =up_togle_daygroup(2)>อ</button>
              <button type="button" class="btn btn-info" id="up_prog_day_3" value="3" onclick =up_togle_daygroup(3)>พ</button>
			  <button type="button" class="btn btn-info" id="up_prog_day_4" value="4" onclick =up_togle_daygroup(4)>พฤ</button>
              <button type="button" class="btn btn-info" id="up_prog_day_5" value="5" onclick =up_togle_daygroup(5)>ศ</button>
              <button type="button" class="btn btn-info" id="up_prog_day_6" value="6" onclick =up_togle_daygroup(6)>ส</button>
              <button type="button" class="btn btn-info" id="up_prog_day_7" value="0" onclick =up_togle_daygroup(7)>อา</button>
            </div>
       	</div>
	</div>
    <div class="row-fluid" style="margin-top:5px">
    	<div class="span3" align="right" style="margin-top:5px">

        	<label for="update_num_break" >จำนวนเบรค:</label>
       	</div>
  		<div class="span5" align="left" >
           	<select id="update_num_break" value="" class="input-small" >
                <option>1</option>
				<option>2</option>
                <option>3</option>            	
				<option selected="selected">4</option>
                <option>5</option>
				<option>6</option>
                <option>7</option>
				<option>8</option>
				<option>10</option>
				<option>12</option>
        	</select>
       	</div>        
        <div class="span4" align="left" style="margin-top:5px" >
       		<label></label>
    	</div>
  	</div>      
   	<div class="row-fluid" style="margin-top:5px">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="break_list"></label>
       	</div>
  		<div class="span5" align="left">
        
       		<table align="center" class="table table-striped" id="update_break_table">
              <thead>
                <tr style="font-size:0.8em">
                  <th style="width:40%;text-align:right;padding:2px">เบรคที่</th>
                  <th style="width:60%;text-align:right;padding:2px">เวลา(วินาที)</th>
                </tr>
              </thead>
              <tfoot>
              </tfoot>
              <tbody style="font-size:0.8em" class="up_time_each_bk">
              </tbody>
        	</table>
        
       	</div>        
        <div class="span4" align="left" style="margin-top:5px" >
       		<label></label>
    	</div>
  	</div> 
    <div class="row-fluid">
    	<div class="span3" align="right">
        	<label for="update_break_note">หมายเหตุ:</label>
       	</div>
  		<div class="span5" align="left">
			<textarea rows="3" id="update_prog_info"></textarea>
       	</div>        
        <div class="span4" align="left" style="margin-top:5px" >
       		<label></label>
    	</div>
  	</div>
 </form>   
</div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for update Program  --------------
?>


<script>

//----------->  Start of UPDATE Program in DB ------------------


	function up_togle_daygroup(daygroup){
		
		if($("#up_prog_day_"+daygroup).attr('checked') == "checked"){
			
			$("#up_prog_day_"+daygroup).removeAttr("checked");
			$("#up_prog_day_"+daygroup).removeClass("btn-inverse active");
			$("#up_prog_day_"+daygroup).addClass("btn btn-info");
			
		}else{

			$("#up_prog_day_"+daygroup).attr("checked","checked");
			$("#up_prog_day_"+daygroup).removeClass("btn btn-info"); 
			$("#up_prog_day_"+daygroup).addClass("btn btn-inverse active");

		}
	}
	
	function up_datetime(){
		
		var day_month =[];
		var start_date = Date.parseExact($("#up_prog_date_start").attr('value'),"d/M/yyyy");
			start_date = new Date(start_date);	
		
		if($("#up_prog_date_end").attr('value') == ""){
			
			var stop_date = start_date;
			
		}else{
			
			var stop_date = Date.parseExact($("#up_prog_date_end").attr('value'),"d/M/yyyy");
				stop_datenew = new Date(stop_date);
		}
		
		
		var oneDay = 24*60*60*1000;
		var diffDays = Math.round(Math.abs((start_date.getTime() - stop_date.getTime())/(oneDay)));
			diffDays = parseInt(diffDays) + 1;
				
		var this_month = start_date.getMonth(); 
		
		if(start_date){
			
			day_month[0] = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#update_progtime_start").attr('value')+":00";
			
		}
		
		for(var day =1; day < diffDays; day++){

			var nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+day);
			
		//	if(nextday.getMonth() == this_month){
		
				if(parseInt(nextday.getDay()) == 0){
					if ($("#up_prog_day_7").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" "+$("#update_progtime_start").attr('value')+":00";
						
						day_month[day] = dayweek; 
						
					}else{}	
				}if(parseInt(nextday.getDay()) == 1){ 
				
					if ($("#up_prog_day_1").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" "+$("#update_progtime_start").attr('value')+":00";
						day_month[day] = dayweek; 
						
					}else{}	
				}if(parseInt(nextday.getDay()) == 2){ 
				
					if ($("#up_prog_day_2").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" "+$("#update_progtime_start").attr('value')+":00";
						day_month[day] = dayweek; 
						
					}else{}		
				}if(parseInt(nextday.getDay()) == 3){
					
					if ($("#up_prog_day_3").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" "+$("#update_progtime_start").attr('value')+":00";
						day_month[day] = dayweek; 
						
					}
				}if(parseInt(nextday.getDay()) == 4){
					
					if ($("#up_prog_day_4").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" "+$("#update_progtime_start").attr('value')+":00";
						day_month[day] = dayweek;
						
					}else{}		
				}if(parseInt(nextday.getDay()) == 5){ 
				
					if ($("#up_prog_day_5").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" "+$("#update_progtime_start").attr('value')+":00";
						day_month[day] = dayweek; 
						
					}else{}		
				}if(parseInt(nextday.getDay()) == 6){ 
				
					if ($("#up_prog_day_6").attr("checked") == "checked"){
						
						var dayweek = nextday.getFullYear()+"-"+(nextday.getMonth()+1)+"-"+nextday.getDate()+" "+$("#update_progtime_start").attr('value')+":00";
						day_month[day] = dayweek; 
						
					}else{}		
				}else {}
			//}
		}
		
		if((day_month == "") && (start_date) ){
			
			day_month = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#update_progtime_start").attr('value')+":00";
			
		}else{}
		
		day_month = day_month.filter(function(e){return e}); // Select only valid array
		
		return (day_month);
		
	}

  	function show_bklist_update(){
		
		var total_bk = 0;
		var num_breaklist =$("#update_num_break").attr('value');
		var time_bk = 240;
	
		$("#update_break_table tbody tr").remove();
		//$("#break_table tfoot tr").remove();
		for(var bk=1; bk<= num_breaklist; bk++ ){
			
			$("#update_break_table tbody").append(
				"<tr >"+ 
							"<td style='text-align:right;height:15px;padding:2px'>"+bk+"</td>"+
							"<td style='text-align:right;height:15px;padding:2px'>"+
							"<input type='text' name='update_num_break' id='update_num_break_"+bk+"' class='input-mini' value='"+time_bk+"' style='height:12px;width:40px' />"+
                  
							"</td>"+
				"</tr>"
			); 
			
			//var time_bk = $("#num_break_"+br+"").attr('value'); 
		}
		
		for(var time_bk=1; time_bk<= num_breaklist; time_bk++ ){
			
			total_bk = total_bk +parseInt($("#update_num_break_"+time_bk+"").attr('value'));	
		
		}
		
		$("#update_break_table tbody").append(
			"<tr>"+ 
							"<td style='text-align:right;height:15px;padding:2px'>เวลารวม</td>"+
							"<td style='text-align:right;height:15px;padding:2px'><a title='เวลารวม' id='update_total_tbk' value='"+total_bk+"'>"+total_bk+"</a></td>"+
			"</tr>"
		);
		
	}
	
	function show_bklist_update_cng(){
		
		var total_bk = 0;
		var time_bk = [];
		var num_breaklist = $("#update_num_break").attr('value');
		
		for(var tbk=1; tbk<= num_breaklist; tbk++ ){	
			time_bk[tbk] = $("#update_num_break_"+tbk).attr('value');
		}
		
		$("#update_break_table tbody tr").remove();
		for(var bk=1; bk<= num_breaklist; bk++ ){
					
			$("#update_break_table tbody").append(
				"<tr >"+ 
							"<td style='text-align:right;height:15px;padding:2px'>"+bk+"</td>"+
							"<td style='text-align:right;height:15px;padding:2px'>"+
							"<input type='text' name='update_num_break' id='update_num_break_"+bk+"' class='input-mini' value='"+time_bk[bk]+"' style='height:12px;width:40px' />"+
                  
							"</td>"+
				"</tr>"
			); 
		}		
		
		for(var time_bk=1; time_bk<= num_breaklist; time_bk++ ){	
			total_bk = total_bk +parseInt($("#update_num_break_"+time_bk+"").attr('value'));	
		}
		
		$("#update_break_table tbody").append(
			"<tr>"+ 
							"<td style='text-align:right;height:15px;padding:2px'>เวลารวม</td>"+
							"<td style='text-align:right;height:15px;padding:2px'><a title='เวลารวม' id='update_total_tbk' value='"+total_bk+"'>"+total_bk+"</a></td>"+
			"</tr>"
		);		
		
	}
	
	
	
  	$("#update_num_break").ready(function(){
								   
		show_bklist_update();
		
	});
	
		
	$("#update_num_break").change(function() {
								  
		show_bklist_update();						  
									  
	});
	
	$(".up_time_each_bk").change(function(){
	
		show_bklist_update_cng();
		
		
		console.log("break =");
	});
	

	$("#update_packprice_min").focusout(function(e) {
		
		var inputPack_pricemin = $("#update_packprice_min").val();
		inputPack_pricemin = parseFloat(inputPack_pricemin.replace(/,/g, ''));
		inputPack_pricemin = inputPack_pricemin.toFixed(2);
		$("#update_packprice_min").attr("value",money_forchange(inputPack_pricemin));
		
	});
	
	
	$("#update_price_min").focusout(function(e) {
		
		if( $("#update_price_min").val()){
		
			var inputProg_pricemin = $("#update_price_min").val();
			var inputProg_discount = $("#up_discount_prog").val();
			
			inputProg_pricemin = parseFloat(inputProg_pricemin.replace(/,/g, ''));
			var inputProg_netprice = parseInt(inputProg_pricemin)*(1-(parseInt(inputProg_discount)/100));
			inputProg_netprice = inputProg_netprice.toFixed(2);
			
			inputProg_pricemin = inputProg_pricemin.toFixed(2);
			$("#update_netprice_min").attr("value",money_forchange(inputProg_netprice));
			$("#update_price_min").attr("value",money_forchange(inputProg_pricemin));
		
		}
		
	});   
	
	$("#up_discount_prog").change(function(){
		
		if( $("#update_price_min").val()){
			
			var inputProg_pricemin = $("#update_price_min").val();
			var inputProg_discount = $("#up_discount_prog").val();
			
			inputProg_pricemin = parseFloat(inputProg_pricemin.replace(/,/g, ''));
			var inputProg_netprice = parseInt(inputProg_pricemin)*(1-(parseInt(inputProg_discount)/100));
			inputProg_netprice = inputProg_netprice.toFixed(2);
			
			inputProg_pricemin = inputProg_pricemin.toFixed(2);
			$("#update_netprice_min").attr("value",money_forchange(inputProg_netprice));
			$("#update_price_min").attr("value",money_forchange(inputProg_pricemin));
			
		}
	
	});	
	
	$("#update_netprice_min").focusout(function(e) {
		
		if( $("#update_netprice_min").val()){
			
			var real_change = $("#update_netprice_min").attr("value");
			real_change = parseFloat(real_change.replace(/,/g, '')).toFixed(2);
			
			$("#update_netprice_min").attr("value",money_forchange(real_change));
		}
	});
	
	

	function autoProgComp_update(){ // Clone from Auto Prog
		
		var sentData = $("#update_comp_name").val();
		var p = $("#update_comp_name").position();
	
			
		var add_ProgCompName = [];
		var add_ProgCompNameID = [];
		var count_Prog = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=program/japi&action=autoProgComp&comp_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoProgComp){
				$.each(autoProgComp,function(k,v){ 
				
				
					add_ProgCompNameID.push(v.name+":"+v.company_id);
					add_ProgCompName.push(v.name);
					
					//console.log("Prog_id="+v.Prog_id);
					
				});
				
				$("#update_comp_name").autocomplete({
					
					source:add_ProgCompName,
					select: function (event, ui) {
						
						$("#update_comp_name").val(ui.item.label); // display the selected text
						
						
						
						for (var i=0;i < add_ProgCompNameID.length ;i++){
									
							var n = add_ProgCompNameID[i].split(":"); 
							if (n[0]== $("#update_comp_name").val()) {
								
								$("#update_comp_name").attr("comp_name", n[0]);
								$("#update_comp_name").attr("comp_id", n[1]);
									
								$("#update_compname_alert").css("visibility", "hidden"); // Disapear the alert NEW Company icon	
									
							}
						}	
						
					},
					search: function() {
						
						$("#update_comp_name").attr("comp_id", "");
						$("#update_compname_alert").css("visibility", "visible"); // Show the alert NEW Company icon
						
					}
				});
			}
		});	
	}
	
	
	$("#update_comp_name").keyup(function(event){
	
		autoProgComp_update();
		
	});
	
	function update_day_weekly(day_Week){
		
		var dayOfweek = [];
		
		 dayOfweek = day_Week.split(',');
		 
		 var index_day = 0;
		
		for(var day = 1; day < 8; day++ ){ // Clear day button first
		
			$("#up_prog_day_"+day).removeAttr("checked");
			$("#up_prog_day_"+day).removeClass("btn-inverse active");
			$("#up_prog_day_"+day).addClass("btn btn-info");
		}
		
		$.each(dayOfweek, function(k,v){ // Enable day button 
		
			var index_day = parseInt(v)+1;
			$("#up_prog_day_"+index_day).attr("checked","checked");
			$("#up_prog_day_"+index_day).removeClass("btn btn-info"); 
			$("#up_prog_day_"+index_day).addClass("btn btn-inverse active");

		});		
		
		
	}
	
	
	function update_time_list(timelist){
		
		var timelist_array = [];
		
		 timelist_array = timelist.split(',');
		 
		 var index_day = 0;
		
		$.each(timelist_array, function(k,v){
			//console.log("v= "+v);
			var index_time = parseInt(k)+1;
			
			$("#update_num_break_"+index_time).attr("value",v);

		});		
		
	}
	
	function updateprog(prog_id){
		
		//console.log("prog_id= "+prog_id);
		
		$('#update_ProgDi').dialog('open');
		$("#update_tvprogram").attr('update_prog_id',prog_id);
		
		
		$.ajaxSetup({
			async: false
		});

		$.ajax('?r=program/japi&action=readUpdateProg&prog_id='+prog_id+'',{
			type: 'GET',
			dataType: 'json',
			success: function(readUpdateProg){
				
				console.log("ProgDat= "+readUpdateProg);	
				
				$.each(readUpdateProg,function(k,v){

					$("#update_tvprogram").attr("value",v.prog_name);
					$("#update_price_min").attr("value",money_forchange(v.minute_price));
					$("#update_packprice_min").attr("value",money_forchange(v.pack_price));
					$("#update_prog_info").attr("value",v.prog_desc);
					
					$("#update_netprice_min").val(money_forchange(v.net_price));
					$("#up_discount_prog").val(v.discount);
					
					//console.log("discount= "+v.discount);
					
					$("#update_comp_name").attr("comp_id",v.company_id);
					$("#update_comp_name").attr("value",v.name);
					
					//console.log("progName= "+v.prog_name+"minutePrice"+v.minute_price);
					
					
					var update_progdate_st = Date.parse(v.date_start,"yyyy-MM-dd HH:mm:ss");
					var update_progdate_en = Date.parse(v.date_end,"yyyy-MM-dd HH:mm:ss");
					
					var time_start = update_progdate_st.toString("HH:mm");
					var time_end = update_progdate_en.toString("HH:mm");
					
					var date_start = new Date(update_progdate_st).toString("dd/MM/yyyy");
					var date_end =  new Date(update_progdate_en).toString("dd/MM/yyyy");
					
					$("#update_progtime_start").attr("value",time_start);
					$("#update_progtime_end").attr("value",time_end);
					
					$("#up_prog_date_start").attr("value",date_start);
					$("#up_prog_date_end").attr("value",date_end);
					
					
					//console.log("update_progdate_st= "+v.date_start);
					//console.log("update_progdate_st= "+update_progdate_st);
					
					var day_weekly = v.onairweekly;
					update_day_weekly(day_weekly);					
					
					$("#update_num_break option:selected").text(v.num_break);
					show_bklist_update();
					
					var timelist = v.breaktime_list;
					update_time_list(timelist);
					show_bklist_update_cng();
					
					//console.log("timelist="+v.breaktime_list);
					//show_bklist_update();
					
					if(v.primetime == "1"){
						
						$("#update_prog_prime").attr("checked","checked");
						
					}else {
						
						$("#update_prog_prime").removeAttr("checked");
						
					}
							
					if(v.self_owner == "1"){
									
						$("#update_owner_tvprogram_1").attr("checked","checked");
									
					}else {
									
						$("#update_owner_tvprogram_2").attr("checked","checked");
					}
								
					$("#update_comp_name").attr("value",v.name);
					$("#update_comp_name").attr("comp_id",v.company_id);
									
				});
			 }
		});

				
		return false;	
	} 
	
		
	function gen_dayWeekly_update(){
		
		var minmonth_array = [];
		var dayofweek = 0;
		var cnt_day = 0;
		var dayweekly = [];
		
		for(var day_count = 0; day_count <= 6;  day_count++){
		
			cnt_day += 1;
			
			dayofweek = $("#up_prog_day_"+cnt_day).hasClass("active");
			
			if(dayofweek != 0){
				
				dayweekly[day_count] = cnt_day;
				
			}else {
				
				dayweekly[day_count] = "";
				
			}
			
		}
		
		dayweekly = dayweekly.filter(function(e){return e});
		var new_dayweekly = [];
	
		for(var new_count = 0; new_count < dayweekly.length; new_count++ ){
			
			new_dayweekly[new_count] = dayweekly[new_count] - 1;
		}	
		
		//console.log("dayweekly= "+new_dayweekly);
		
		var day_weekly = new_dayweekly.join(',');
		return(day_weekly);	
		
	}
		
	function updateprogDb(){
		
		var delay = 0;
		var update_prog_id = 0;
		var update_prog_name = 0;
		var update_prog_prime = 0;
		var update_prog_owner = 0;
		var update_prog_minprice = 0;
		var update_prog_packprice = 0;
		var update_prog_info = 0;
		var update_comp_name = 0;
		var update_comp_id = 0;
			
		update_prog_name = $("#update_tvprogram").attr("value");
		update_prog_minprice = $("#update_price_min").attr("value");
		update_prog_minprice = parseFloat(update_prog_minprice.replace(/,/g, ''));
		update_prog_packprice = $("#update_packprice_min").attr("value");
		update_prog_packprice = parseFloat(update_prog_packprice.replace(/,/g, ''));
		update_prog_info = $("#update_prog_info").attr("value");
		update_comp_name = $("#update_comp_name").attr("value");
		
		if($("#update_comp_name").attr("comp_id")){
		
			update_comp_id = $("#update_comp_name").attr("comp_id");	
			
		}else {
			
			update_comp_id = "0";
			
		}
		
		
		
		update_prog_id = $("#update_tvprogram").attr('update_prog_id');
		
		var update_net_price = $("#update_netprice_min").val();
		update_net_price = parseFloat(update_net_price.replace(/,/g, ''));
		var update_discount = $("#up_discount_prog").val();
		
		if($("#update_prog_prime").attr("checked")){
			
			update_prog_prime = 1;
			
		}else {
			
			update_prog_prime = 0;
		}
			
		if($("#update_owner_tvprogram_1").attr("checked")){
			
			update_prog_owner = 1; // CH7
			
		}else {
			
			update_prog_owner = 0; // Rental
		}
		
		var start_date = start_date = Date.parseExact($("#up_prog_date_start").attr('value'),"d/M/yyyy");
			start_date = new Date(start_date);
		var stop_date = Date.parseExact($("#up_prog_date_end").attr('value'),"d/M/yyyy");
			stop_date = new Date(stop_date);
			
			
		if($("#up_prog_date_end").attr('value')){
			
			start_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#up_prog_date_start").attr('value')+":00";
			stop_date_master = stop_date.getFullYear()+"-"+(stop_date.getMonth()+1)+"-"+stop_date.getDate()+" "+$("#up_prog_date_end").attr('value')+":00";
			
		}else {
			
			start_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#up_prog_date_start").attr('value')+":00";
			stop_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#up_prog_date_start").attr('value')+":00";
			
		}
		
		var num_breaklist = $("#update_num_break").val()
		var update_time_bk = [];
		var update_n_break = 0;
		var totaltime_bk = 0;
		
		totaltime_bk = $("#update_total_tbk").attr("value");
		
		var dayweekly = gen_dayWeekly_update()
		
		for(var tbk=0; tbk < num_breaklist; tbk++ ){
			
			update_n_break = parseInt(tbk)+1;
			update_time_bk[tbk] = parseInt($("#update_num_break_"+update_n_break+"").attr('value'));
		}	
		
		var breaktime_list = update_time_bk.join(',');
		console.log("dayweekly= "+dayweekly+" breaktime_list"+breaktime_list);
		
		//console.log("up_datetime= "+up_datetime());// Date time to generate Onair Schechdule
		
		
		$.ajaxSetup({
			async: false
		});
		$.ajax({
			type: "POST",
			url: "?r=program/updateProg",	
			data:{'prog_id':update_prog_id,'prog_name':update_prog_name,'primetime':update_prog_prime,'owner_comp':update_prog_owner,'minute_price':update_prog_minprice,'pack_price':update_prog_packprice,'prog_desc':update_prog_info,'comp_name':update_comp_name,'comp_id':update_comp_id,'netprice':update_net_price,'discount':update_discount,'start_date_master':start_date_master,'stop_date_master':stop_date_master,'num_break':num_breaklist,'time_break':update_time_bk,'dayweekly':dayweekly,'breaktime_list':breaktime_list,'totaltime_bk':totaltime_bk},
			success: function(data) {
							
				//alert("success");
				
			},
			error: function(data){
							
				alert("มีข้อผิดพลาดเกิดขึ้นระหว่างเพิ่มรายการ กรุณาทำรายการใหม่อีกครั้ง");
					
			}
		});

		
		//while(delay < 1000){delay++;}
		
		readprogList(); //--------> Send program list to onair and prepare onair page
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		default_daytab(); // Default Class for Daytab
		alarming_daytab(prog_on,onair_year,onair_mon);
				
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		checkBreak(prog_on,onair_year,onair_mon,day);
				//------------------------->
		
		$("#update_compname_alert").css("visibility", "hidden"); // Disapear the alert NEW Company icon	
		
		$(this).dialog("close");
		show_tvprogram_list(2);
		return false;
		
	}

	function up_alert(prog_id){
		$('#up_alertDi').dialog('open');
	
		return false;	
	}
	
//<-----End of UPDATE Program in DB


//<----------- Update and Add onair date of program --------

	function updateAndAddprogDb(){
		
		var delay = 0;
		var update_prog_id = 0;
		var update_prog_name = 0;
		var update_prog_prime = 0;
		var update_prog_owner = 0;
		var update_prog_minprice = 0;
		var update_prog_packprice = 0;
		var update_prog_info = 0;
		var update_comp_name = 0;
		var update_comp_id = 0;
		var update_datetime_prog = []; 
			
		
		update_prog_name = $("#update_tvprogram").attr("value");
		update_prog_minprice = $("#update_price_min").attr("value");
		update_prog_minprice = parseFloat(update_prog_minprice.replace(/,/g, ''));
		update_prog_packprice = $("#update_packprice_min").attr("value");
		update_prog_packprice = parseFloat(update_prog_packprice.replace(/,/g, ''));
		update_prog_info = $("#update_prog_info").attr("value");
		update_comp_name = $("#update_comp_name").attr("value");
		
		if($("#update_comp_name").attr("comp_id")){
		
			update_comp_id = $("#update_comp_name").attr("comp_id");	
			
		}else {
			
			update_comp_id = "0";
			
		}
		
		update_prog_id = $("#update_tvprogram").attr('update_prog_id');
		
		var update_net_price = $("#update_netprice_min").val();
		update_net_price = parseFloat(update_net_price.replace(/,/g, ''));
		var update_discount = $("#up_discount_prog").val();
		
		
		
		
		if($("#update_prog_prime").attr("checked")){
			
			update_prog_prime = 1;
			
		}else {
			
			update_prog_prime = 0;
		}
			
		if($("#update_owner_tvprogram_1").attr("checked")){
			
			update_prog_owner = 1; // CH7
			
		}else {
			
			update_prog_owner = 0; // Rental
		}
		
		var start_date = start_date = Date.parseExact($("#up_prog_date_start").attr('value'),"d/M/yyyy");
			start_date = new Date(start_date);
		var stop_date = Date.parseExact($("#up_prog_date_end").attr('value'),"d/M/yyyy");
			stop_date = new Date(stop_date);
			
			
		if($("#up_prog_date_end").attr('value')){
			
			start_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#up_prog_date_start").attr('value')+":00";
			stop_date_master = stop_date.getFullYear()+"-"+(stop_date.getMonth()+1)+"-"+stop_date.getDate()+" "+$("#up_prog_date_end").attr('value')+":00";
			
		}else {
			
			start_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#up_prog_date_start").attr('value')+":00";
			stop_date_master = start_date.getFullYear()+"-"+(start_date.getMonth()+1)+"-"+start_date.getDate()+" "+$("#up_prog_date_start").attr('value')+":00";
			
		}
		
		var num_breaklist = $("#update_num_break").val()
		var update_time_bk = [];
		var update_n_break = 0;
		var totaltime_bk = 0;
		
		totaltime_bk = $("#update_total_tbk").attr("value");
		
		var dayweekly = gen_dayWeekly_update()
		
		for(var tbk=0; tbk < num_breaklist; tbk++ ){
			
			update_n_break = parseInt(tbk)+1;
			update_time_bk[tbk] = parseInt($("#update_num_break_"+update_n_break+"").attr('value'));
		}	
		
		var breaktime_list = update_time_bk.join(',');
		//console.log("dayweekly= "+dayweekly+" breaktime_list"+breaktime_list);
		
		update_datetime_prog = up_datetime();
		
		//console.log("Extend onair datetime= "+up_datetime());// Date time to generate Onair Schechdule
		
		
		$.ajaxSetup({
			async: false
		});
		$.ajax({
			type: "POST",
			url: "?r=program/updateAddProg",	
			data:{'prog_id':update_prog_id,'prog_name':update_prog_name,'primetime':update_prog_prime,'owner_comp':update_prog_owner,'minute_price':update_prog_minprice,'pack_price':update_prog_packprice,'prog_desc':update_prog_info,'comp_name':update_comp_name,'comp_id':update_comp_id,'netprice':update_net_price,'discount':update_discount,'start_date_master':start_date_master,'stop_date_master':stop_date_master,'num_break':num_breaklist,'time_break':update_time_bk,'dayweekly':dayweekly,'breaktime_list':breaktime_list,'totaltime_bk':totaltime_bk,'date_time':update_datetime_prog},
			success: function(data) {
							
				//alert("success");
				
			},
			error: function(data){
							
				alert("มีข้อผิดพลาดเกิดขึ้นระหว่างเพิ่มรายการ กรุณาทำรายการใหม่อีกครั้ง");
					
			}
		});

		readprogList(); //--------> Send program list to onair and prepare onair page
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		default_daytab(); // Default Class for Daytab
		alarming_daytab(prog_on,onair_year,onair_mon);
				
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		checkBreak(prog_on,onair_year,onair_mon,day);
				//------------------------->
		$("#update_compname_alert").css("visibility", "hidden"); // Disapear the alert NEW Company icon					
				
		$(this).dialog("close");

		show_tvprogram_list(2);
		
		return false;
		
	}
	
	

	
//<---------- End Update and Adding onair date -----------

</script>


<?php

 //--------------- Start of Dialog for Delete Advertise  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'deleteprogDi',
        'options'=>array(
            'title'=>'ลบรายการโทรทัศน์',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ใช่'=>'js:deleteprogDb',
                'ไม่'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
	
?>

    <div class="dialog_input" id="del_program_di" del_program_id="">
        <div class="controls" style=" margin-top:30px" align="center">
        	<label class="control-label" for="createbreaktime" >คุณต้องการลบรายการโทรทัศน์นี้ใช่หรือไม่</label>
        </div>
    </div>
    
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for Delete Advertise  --------------
?>

<script>

//----->Start to DELETE Program ------------------ 

	function deleteprog(prog_id){

		
		$('#deleteprogDi').dialog('open');
		$("#del_program_di").attr('del_program_id',prog_id);
		
		return false;	
	}

	function deleteprogDb(prog_id){
		
		var delay = 0;
		var del_program_id = $("#del_program_di").attr('del_program_id');
		
		$.ajaxSetup({
			async: false
		});
		$.ajax({
				   type: "POST",
				   url: "?r=program/deleteProg",	
				   data:{'prog_id':del_program_id},
				   
				   success: function(data) {
							
							//alert("success");
					},
					error: function(data){
							
							alert("มีข้อผิดพลาดเกิดขึ้นระหว่างเพิ่มรายการ กรุณาทำรายการใหม่อีกครั้ง");
					
					}
		});
		
		readprogList(); //--------> Send program list to onair and prepare onair page
	
		while(delay < 1000){delay++;}
		$(this).dialog("close");
		show_tvprogram_list(2);
		return false;
  	}
//----->End of DELETE Program in DB 


	function test(prog_id){
		alert("Sorry! This function is under construction");
	}
	
// -------------> Paginatio nfunction 	
	
$("#num_row_tvprogram").change(function(){
	 
		show_tvprogram_list(2);
	
});

</script>


</div></div>

