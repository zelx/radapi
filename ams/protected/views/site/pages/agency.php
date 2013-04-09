<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Agency';
$this->breadcrumbs=array(
	'Agency',
);
?>


<script>


	$(document).ready(function() {
		show_agency_list(2);
	});

	function show_agency_list(index_page,search_str){
		
		var start_row = 1;
		var stop_row = 5;
		
		var current_page = 0;
		var current_id = "agency_page_2";
		var current_val = 1;
		
		var check_page = "agency_page_"+index_page;
		
		stop_row = $("#num_row_agency").attr("value");
		
		if(check_page == "agency_page_1"){
			
			current_id = $("#agency_page").find("li.active").attr('id');
			current_val = $("#"+current_id).attr('value');
			
			if(current_val > "2"){
				
				current_val = parseInt(current_val)-1;
				
				$("#agency_page").find("li.active").removeClass("active");;
				$("#agency_b_"+current_val).addClass("active");	
				
			}else if(current_val == "2" && $("#agency_page_2").text() > "1"){
				
				current_val = 6;
					
				$("#agency_page_2").text(parseInt($("#agency_page_2").text())-5);
				$("#agency_page_3").text(parseInt($("#agency_page_3").text())-5);
				$("#agency_page_4").text(parseInt($("#agency_page_4").text())-5);
				$("#agency_page_5").text(parseInt($("#agency_page_5").text())-5);
				$("#agency_page_6").text(parseInt($("#agency_page_6").text())-5);
				
				$("#agency_page").find("li.active").removeClass("active");;
				$("#agency_b_"+current_val).addClass("active");	
				
			
			}else {
				
				current_val = current_val;
				
				$("#agency_page").find("li.active").removeClass("active");
				$("#agency_b_"+current_val).addClass("active");
			}
			
			current_id = $("#agency_page").find("li.active").attr('value');
			current_page = $("#agency_page_"+current_id).text();
			
			start_row = (parseInt(current_page)-1)*stop_row;
			
			//console.log("startROW"+start_row+" stopROW"+stop_row); 
			
	
		}else if(check_page == "agency_page_7"){
			
			current_id = $("#agency_page").find("li.active").attr('id');
			current_val = $("#"+current_id).attr('value');
			
			if(current_val < "6"){
	
				current_val = parseInt(current_val)+1;
				
				$("#agency_page").find("li.active").removeClass("active");;
				$("#agency_b_"+current_val).addClass("active");		

			}else if(current_val == "6"){
				
				current_val = 2;
					
				$("#agency_page_2").text(parseInt($("#agency_page_2").text())+5);
				$("#agency_page_3").text(parseInt($("#agency_page_3").text())+5);
				$("#agency_page_4").text(parseInt($("#agency_page_4").text())+5);
				$("#agency_page_5").text(parseInt($("#agency_page_5").text())+5);
				$("#agency_page_6").text(parseInt($("#agency_page_6").text())+5);
				
				$("#agency_page").find("li.active").removeClass("active");;
				$("#agency_b_"+current_val).addClass("active");	
				
			}else {
				
				current_val = current_val;
				
				$("#agency_page").find("li.active").removeClass("active");
				$("#agency_b_"+current_val).addClass("active");
			}
			
			current_id = $("#agency_page").find("li.active").attr('value');
			current_page = $("#agency_page_"+current_id).text();
			
			start_row = (parseInt(current_page)-1)*stop_row;
			//console.log("startROW"+start_row+" stopROW"+stop_row); 
			
			
		}else {
						
			$("#agency_page").find("li.active").removeClass("active");;
			$("#agency_b_"+index_page).addClass("active");			
			current_page = $("#agency_page_"+index_page).text();
			
			start_row = (parseInt(current_page)-1)*stop_row;
			//console.log("startROW"+start_row+" stopROW"+stop_row); 
			
		}
		
		var parent_num = 0;
		var agency_id = 0;
		var parent_name = "";
		var agency_tel = "";
		var agency_fax = 0;
		
		$.ajaxSetup({
			async: false
		});

		//$.ajax('?r=agency/japi&action=agencyList&start_row='+start_row+'&stop_row='+stop_row+'',{
		$.ajax('?r=agency/japi&action=agencyList&start_row='+start_row+'&stop_row='+stop_row+'&search_str='+search_str,{
			
			type: 'GET',
			dataType: 'json',
			success: function(agencyList){
			//var breakid=0;
			$("#agentable tbody tr").remove();
				$.each(agencyList[0],function(k,v){ 
					
						if(v.parent_name){
						
							parent_name = v.parent_name;
							
						}else {
							
							parent_name  = 	"";
							
						}
						if(v.agency_tel){
							
							agency_tel = v.agency_tel;
							
						}else{
							
							agency_tel  = "";
							
							
						}
						
						if(v.agency_fax){
							
							agency_fax = v.agency_fax;
							
						}else{
							
							agency_fax  = "";
							
							
						}
						
						$("#agentable tbody").append(
							"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
								"<td style='text-align:center;padding:4px'><label class='checkbox inline'>"+
								"<input style='margin-top:2px' type='checkbox' id='check"+v.agency_id+"' value='option"+v.agency_id+"'></label></td>"+
								"<td style='text-align:left;padding-top:8px'>"+v.agency_name+"</td>" + 
								"<td style='text-align:left;padding-top:8px'id='agency_parent"+v.parent_id+"'>"+parent_name+"</td>" +
								"<td style='text-align:left;padding-top:8px' id='agency_tel"+v.parent_id+"'>"+agency_tel+"</td>" +
								"<td style='text-align:left;padding-top:8px' >"+agency_fax+"</td>" +
								"<td style='text-align:left;padding-top:8px'>"+v.agency_desc+"</td>" +
								
								"<td style='text-align:center;padding-top:8px'><a title='แก้ไข' style='text-align:center' onclick=updateagenOpen("+v.agency_id+");><img src='images/pen.png' style='width:20px;cursor:pointer' align='center' /></a></td>" +
								"<td style='text-align:center;padding-top:8px'><a title='ลบ' onclick=delete_agenOpen("+v.agency_id+");><img src='images/delete.png' style='width:20px;cursor:pointer' align='center' /></a></td>" +
								
							"</tr>" 
						);

						
				});
			 }
		});	
		
		//console.log(num_page);
}

$(document).ready(function() {
	$("#num_row_agency").change(function(){
	
		show_agency_list(2)	;
	
	});
});

</script>

<div class="row-fluid"  >
	<div class="span6" >
 		<button id="create_agency" type="button" class="btn btn-info" style="font-size:1em;margin-top:4px;margin-bottom:4px;"  data-loading-text="Loading..." onclick=" OpenAddAgency()">เพิ่มเอเจนซี่</button>
	</div>
	<div class="span6" align="right" >
    	<div class="row-fluid"  >
            <div class="span2" align="right">
            </div>
            <div class="span10" align="right" style="margin-left:4px"> 
            	<input type="text" name="search_agency" id="search_agency" size="10" >
            	<div style="float:right; margin-top:0px;font-size:1em;"><input type="button" name="search_agency_clear" id="search_agency_clear" class="btn btn-info" value="ยกเลิก" onclick="$('#search_agency').val(''); show_agency_list(2);"></div>
                <div style="float:right; margin-top:0px;font-size:1em;"><input type="button" name="search_agency_button" id="search_agency_button" class="btn btn-info" value="ค้นหา" onclick="show_agency_list(2,$('#search_agency').val());"></div>           
            </div>
        </div>          
	</div>
</div>
  
<div class="row-fluid"  align="center">
<div class="">
    <div class="container" id="page" style="width:inherit">
      <div class="row-fluid"  align="left">
        <div class="">
            <table align="center" class="table table-striped" id="agentable" >
              <thead align="center">
                <tr style="font-size:0.8em;height:25px;">
                  <th style="width:5%;text-align:center;padding:6px"></th>
                  <th style="width:20%;text-align:left;padding:6px">เอเจนซี่</th>
                  <th style="width:20%;text-align:left;padding:6px">ต้นสังกัด</th>
                  <th style="width:15%;text-align:left;padding:6px">เบอร์โทร</th>
                  <th style="width:15%;text-align:left;padding:6px">เบอร์แฟกซ์</th>
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
     <div class="row-fluid"  align="center">
     	<div class="span6">
        </div>
        <div class="span6">
     		<div class="row-fluid"  align="center">
                 <div class="span3" align="right">
                 </div>
                <div class="span7" align="right" style="margin:2px">
                    <div class="pagination pagination-right pagination-small" style="font-size:1em;;margin-top:4px;margin-bottom:4px;">
                          <ul id="agency_page">
                            <li id="agency_b_1" value="1"><a id="agency_page_1" onclick=show_agency_list("1") style=" color: #39C">Prev</a></li>
                            <li id="agency_b_2" value="2" class="active"><a id="agency_page_2"  style=" color:#39C" onclick=show_agency_list("2")>1</a></li>
                            <li id="agency_b_3" value="3" ><a id="agency_page_3"  style=" color:#39C" onclick=show_agency_list("3")>2</a></li>
                            <li id="agency_b_4" value="4" ><a id="agency_page_4"  style=" color:#39C" onclick=show_agency_list("4")>3</a></li>
                            <li id="agency_b_5" value="5" ><a id="agency_page_5"  style=" color:#39C" onclick=show_agency_list("5")>4</a></li>
                            <li id="agency_b_6" value="6" ><a id="agency_page_6"  style=" color:#39C" onclick=show_agency_list("6")>5</a></li>
                            <li id="agency_b_7" value="7" ><a id="agency_page_7"  style=" color:#39C" onclick=show_agency_list("7")>Next</a></li>
                          </ul>
                    </div>
                </div>
                <div class="span2" align="left">
                        <select  style="font-size:0.8em;width:80px;margin-top:4px;margin-bottom:4px; margin-left:2px" id="num_row_agency" value="" class="input-mini" >
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
 //--------------- Start of Dialog for Adding Agency  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'createAgen',
        'options'=>array(
            'title'=>'เพิ่มเอเจนซี่',
			'width'=>400,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:createAgencyDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" >
        <div class="row-fluid" align="center">
        	<div class="span3" align="right">
            	<label for="create_agency_name" style="margin-top:4px;font-size:1em">เอเจนซี่:</label>
          	</div>
           	<div class="span9" align="left">
            	<input type="text" name="create_agency_name" id="create_agency_name" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>
          	</div>
        </div>
        <div class="row-fluid" align="center" >
        	<div class="span3" align="right">
            	<label for="create_agency_main" style="margin-top:10px;font-size:1em">ต้นสังกัด:</label>
          	</div>
           	<div class="span9" align="left">
                <select name="create_agency_main" id="create_agency_main" style="margin-top:5px;font-size:0.8em;width:230px;" value="">
                </select>
          	</div>
        </div>
        <div class="row-fluid" align="center" >
        	<div class="span3" align="right">
            	<label for="create_agency_tel" style="margin-top:4px;font-size:1em">เบอร์โทร:</label>
          	</div>
           	<div class="span9" align="left">
                <input type="text" name="create_agency_tel" id="create_agency_tel" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>
          	</div>
        </div>
        <div class="row-fluid" align="center" >
        	<div class="span3" align="right">
            	<label for="create_agency_fax" style="margin-top:4px;font-size:1em">เบอร์แฟกซ์:</label>
          	</div>
           	<div class="span9" align="left">
                <input type="text" name="create_agency_fax" id="create_agency_fax" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>
          	</div>
        </div>
        <div class="row-fluid" align="center">
        	<div class="span3" align="right">
            	<label for="create_agency_info" style="margin-top:4px;font-size:1em">หมายเหตุ:</label>
          	</div>
           	<div class="span9" align="left">
            	
                <textarea rows="3" id="create_agency_info" style="margin-top:5px;font-size:0.8em;width:220px; "></textarea>
          	</div>
        </div>
</div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for Adding Agency  --------------
?>

<?php
 //--------------- Start of Dialog for update Agency  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'updateAgenDi',
        'options'=>array(
            'title'=>'แก้ไขเอเจนซี่และเพิ่มผู้ติดต่อ',
			'width'=>1200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:updateAgenDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" >
	<div class="modal-header">
        <div class="row-fluid" align="center">
          	<div class="span3" align="left">
            	<label for="update_agency" style="margin-top:3px;font-size:1em">เอเจนซี่:</label>
            	<input type="text" name="update_agency" id="update_agency" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:250px; " agen_id=""/>
          	</div>
        	<div class="span3" align="left">
            	<label for="update_agency_main" style="margin-top:3px;font-size:1em">ต้นสังกัด:</label>
                <select name="update_agency_main" id="update_agency_main" style="font-size:0.8em;width:260px;" value=""> </select>
          	</div>
          	<div class="span2" align="left">
            	<label for="agency_central_tel" style="margin-top:3px;font-size:1em">เบอร์โทร:</label>
            	<input type="text" name="agency_central_tel" id="agency_central_tel" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:150px; " agen_id=""/>
          	</div>
          	<div class="span2" align="left">
            	<label for="agency_central_fax" style="margin-top:3px;font-size:1em">เบอร์แฟกซ์:</label>
            	<input type="text" name="agency_central_fax" id="agency_central_fax" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:150px; " agen_id=""/>
          	</div>
            <div class="span2" align="left">
            	<label for="update_agency_info" style="margin-top:3px;font-size:1em">หมายเหตุ:</label>
               <textarea rows="2" id="update_agency_info" style="font-size:0.8em;width:150px; "></textarea>
          	</div>
            
        </div>
    </div>
    <div style="margin-bottom:5px;padding:4px" class="modal-body" >
        <div class="row-fluid" align="center" style="margin-bottom:20px;margin-top:20px">
          <div align="left" class="span6"  >
            <h3 style="font-size:1.2em;height:30px; margin-left:50px;margin-top:0px">ผู้ติดต่อ</h3>
          </div>
          <div class="span6" align="right" style="margin-top:10px">
            <button type="submit" class="btn btn-success" style="width:140px" id="update_contact" >บันทึกการเพิ่ม</button>
            <button type="submit" class="btn btn-success" style="width:140px;" id="edit_contact" disabled="disabled">บันทึกการแก้ไข</button>
          </div> 
        </div>
        
        <div class="row-fluid" align="center">
        <div class="span2" align="left" style="margin-top:5px;">
            <label for="update_agency_name" >ชื่อ</label>
            <input type="text" name="update_agency_name" id="update_agency_name" cont_id="" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:150px;"/>             
        </div>
        
        <div class="span2" align="left" style="margin-top:5px;margin-left:0px;">
            <label for="update_contact_position" >ตำแหน่ง</label>
            <input type="text" name="update_contact_position" id="update_contact_position" cont_id="" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:150px;"/>             
        </div>
        
        <div class="span2" align="left" style="margin-top:5px;margin-left:0px;">
            <label for="update_agency_name" >อีเมลล์</label>
            <input type="text" name="update_agency_email" id="update_agency_email" cont_id="" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:150px;"/>             
        </div>
        
        <div class="span2" align="left" style="margin-top:5px;margin-left:0px;">
            <label for="update_contact_birth" >วันเกิด</label>
          
            	<?php
                	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'my_date',
							'id'=>'update_contact_birth',
                            'language'=>Yii::app()->language=='et' ? 'et' : null,
                            'options'=>array(
                                'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
                                'showOn'=>'button', // 'focus', 'button', 'both'
                                'buttonText'=>Yii::t('ui','Select form calendar'),
                                'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
                                'buttonImageOnly'=>true,
								'dateFormat'=>'dd/mm/yy',								
                            ),
                            'htmlOptions'=>array(
                                'style'=>'width:125px;vertical-align:top'
                            ),
                        ));   
               ?>            
        </div>     
        
            <div class="span4" align="left" style="margin-top:5px;margin-left:10px;width:450px">
            
                <div class="span4">
                <label for="update_agency_name" >เบอร์โทร</label>
                <input type="text" name="update_agency_tel" id="update_agency_tel" cont_id="" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:125px;"/>
                </div>    
                
                <div class="span4" style="margin-left:7px;">
                <label for="update_contact_fax" >เบอร์แฟกซ์</label>
                <input type="text" name="update_contact_fax" id="update_contact_fax" cont_id="" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:125px;"/>
                </div>  
                
                <div class="span4" style="margin-left:7px;">
                <label for="update_agency_name" >หมายเหตุ</label>
                <input type="text" name="update_agency_info_cont" id="update_agency_info_cont" cont_id="" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:125px;"/>
                </div>   
            </div>

        </div>
        
          <div class="row-fluid"  align="center">
            <div class="">
                <table align="center" class="table table-striped" id="agentable_contact">
                  <thead align="center">
                    <tr style="font-size:0.8em;height:25px;">
                      <th style="width:20%;text-align:left;padding:6px">ชื่อ</th>
                      <th style="width:15%;text-align:left;padding:6px">ตำแหน่ง</th>
                      <th style="width:15%;text-align:left;padding:6px">อีเมลล์</th>
                      <th style="width:10%;text-align:left;padding:6px">วันเกิด</th>
                      <th style="width:10%;text-align:left;padding:6px">เบอร์โทร</th>
                      <th style="width:10%;text-align:left;padding:6px">เบอร์แฟกซ์</th>
                      <th style="width:10%;text-align:left;padding:6px">หมายเหตุ</th>
                      <th style="width:5%;text-align:center;padding:6px">แก้ไข</th>
                      <th style="width:5%;text-align:center;padding:6px">ลบ</th>  
                    </tr>
                  </thead>
                  <tbody style="font-size:0.8em">
                  </tbody>
                </table>
            </div>
         </div>
  	</div>
</div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for update Agency  --------------
?>

<?php
 //--------------- Start of Dialog for update Agency  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'deletecontactDi',
        'options'=>array(
            'title'=>'ลบผู้ติดต่อ',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ใช่'=>'js:delete_contact',
                'ไม่'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" >
        <div class="row-fluid" style="margin-top:30px" align="center" id="dlete_cont_id" value="">
        คุณต้องการลบผู้ติดต่อใช่หรือไม่
        </div>
</div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for update Agency  --------------
?>

<?php
 //--------------- Start of Dialog for update Agency  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'deleteAgencyDi',
        'options'=>array(
            'title'=>'ลบเอเจนซี่',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ใช่'=>'js:delete_agency',
                'ไม่'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" >
        <div class="row-fluid" style="margin-top:30px" align="center" id="dlete_agen_id" value="">
        คุณต้องการลบเอเจนซี่นี้ใช่หรือไม่
        </div>
</div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for update Agency  --------------
?>

<script>
	
	//------- Start of Agency Process ---------

	$.ajaxSetup({
		async: false
	});
	$(document).ready(function() {
		$.ajax('?r=agency/japi&action=agenList',{
			type: 'GET',
			dataType: 'json',
			success: function(agenList){
				
				$("#create_agency_main option").remove();
				$("#create_agency_main").append( 
					"<option value='0'>None</option>"
				)		
				$.each(agenList,function(k,v){
						
					$("#create_agency_main").append( 
						"<option value="+v.agency_id+">"+v.agency_name+"</option>"
					);
								
				});
			}
		});
	});
	
	
	
	function createAgencyDb(){
	
		var delay = 0;
		var cr_agency_name = 0;
		var cr_agency_main = 0;
		var cr_agency_tel = 0;
		var cr_agency_fax = 0;
		var cr_agency_info = 0;
		
		cr_agency_name = $('#create_agency_name').attr('value');
		cr_agency_main = $('#create_agency_main').attr('value');
		cr_agency_tel = $('#create_agency_tel').attr('value');
		cr_agency_fax = $('#create_agency_fax').attr('value');
		cr_agency_info = $('#create_agency_info').attr('value');
		
		if($("#create_agency_name").attr('value') != '' && $("#create_agency_main").attr('value') != '0' ){
				
			$.ajaxSetup({
				async: false
			});
			
			//console.log("parent="+cr_agency_main+"agency="+cr_agency_name)
			$.ajax({
				type: "POST",
				url: "?r=agency/createAgency",
				data:{'cr_agency_name':cr_agency_name, 'cr_agency_main':cr_agency_main, 'cr_agency_info':cr_agency_info,'tel':cr_agency_tel,'fax':cr_agency_fax},
						
					success: function(data) {				
						//alert("การเพิ่มเอเจนซี่เสร็จสมบูรณ์");
					},
					error: function(data){				
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรูณาทำการเพิ่มเอเจนซี่ใหม่อีกครั้ง");			
					}								   
			});	 
	
		show_agency_list(2);
		$(this).dialog("close");
					
		}else if($("#create_agency_name").attr('value') != '' && $("#create_agency_main").attr('value') == '0' ){
			
			//console.log("parent="+cr_agency_name+"agency="+cr_agency_name)

			$.ajaxSetup({
				async: false
			});
			$.ajax({
				type: "POST",
				url: "?r=agency/createAgencyNP",
				data:{'cr_agency_name':cr_agency_name, 'cr_agency_info':cr_agency_info,'tel':cr_agency_tel,'fax':cr_agency_fax},
						
					success: function(data) {				
						//alert("การเพิ่มเอเจนซี่เสร็จสมบูรณ์");
					},
					error: function(data){				
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรูณาทำการเพิ่มเอเจนซี่ใหม่อีกครั้ง");			
					}								   
			});	 

		show_agency_list(2);
		$(this).dialog("close");
			
		}else{
					
			alert("กรุณาใส่ชื่อเอเจนซี่และต้นสังกัดให้ครบถ้วน");
		}
		
	
		return false;
		
	}
	
	
	function OpenAddAgency(){
		
		$('#createAgen').dialog('open'); 
		$('#create_agency_name').val("");
		$('#create_agency_tel').val("");
		$('#create_agency_fax').val("");
		$('#create_agency_info').val("");
		
		return false;
		
	}
	
	
	//------- End of Agency Process ---------

	//------- Start of Agency contact Process ---------
	
	$("#update_contact").click(function(e){

		var up_agency_name = 0;
		var up_agency_tel = 0; 
		var up_agency_email = 0;
		var up_agency_info_cont = 0;
		var agency_id = 0;
		var delay = 0;
		var update_contact_position = 0;
		var update_contact_birth = 0;
		var update_contact_fax = 0;
		
		up_agency_name = $('#update_agency_name').attr('value');
		update_contact_position = $('#update_contact_position').attr('value');
		up_agency_email = $('#update_agency_email').attr('value');
		update_contact_birth =  Date.parseExact($("#update_contact_birth").attr("value"),"d/M/yyyy");
		update_contact_birth = update_contact_birth.toString("yyyy-M-d");
		up_agency_tel = $('#update_agency_tel').attr('value');
		update_contact_fax = $('#update_contact_fax').attr('value');
		up_agency_info_cont = $('#update_agency_info_cont').attr('value');
		agency_id = $('#update_agency').attr('agen_id');
		
		if($('#update_agency_name').attr('value')){
			//console.log("name="+up_agency_name+"tel="+up_agency_tel+"mail="+up_agency_email+"info"+up_agency_info_cont+"id="+agency_id);
			$.ajaxSetup({
				async: false
			});	
			$.ajax({
				type: "POST",
				url: "?r=agency/createcontact",
				data:{'agency_id':agency_id,'up_agency_name':up_agency_name, 'up_agency_tel':up_agency_tel,'up_agency_email':up_agency_email,'up_agency_info_cont':up_agency_info_cont,'position':update_contact_position,'birthday':update_contact_birth,'fax':update_contact_fax},
				
					success: function(data) {		
							
						//alert("การเพิ่มผู้ติดต่อเสร็จสมบูรณ์");
					
					},
					error: function(data){				
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรูณาทำการเพิ่มผู้ติดต่อใหม่อีกครั้ง");			
					}								   
			});	
			
			while(delay < 100){delay++;}
			read_contact(agency_id);
			
			
		}else {
			
			alert("กรุณาระบุชื่อผู้ติดต่อ");
		}
		
		$("#update_agency_name").attr('cont_id',"");
		$('#update_agency_name').attr('value',"");
		$('#update_agency_tel').attr('value',"");
		$('#update_agency_email').attr('value',"");
		$('#update_agency_info_cont').attr('value',"");
		$('#update_contact_position').attr('value',"");
		$('#update_contact_birth').attr('value',"");
		$('#update_contact_fax').attr('value',"");		
		
	});
	
	$("#edit_contact").click(function(e){
		
		var ed_contact_id = 0;
		var ed_firstname = 0;
		var ed_contact_tel = 0; 
		var ed_contact_email = 0;
		var ed_contact_info_cont = 0;
		var agency_id = 0;
		var delay = 0;
		
		$("#edit_contact").attr("disabled", "disabled");
		$("#update_contact").removeAttr("disabled");
		//console.log($("#update_agency_name").attr('cont_id'));
		
		ed_contact_id = $("#update_agency_name").attr('cont_id');
		ed_firstname = $('#update_agency_name').attr('value');
		ed_contact_tel = $('#update_agency_tel').attr('value');
		ed_contact_email = $('#update_agency_email').attr('value');
		ed_contact_info_cont = $('#update_agency_info_cont').attr('value');
		
		agency_id = $('#update_agency').attr('agen_id');
		
		var ed_contact_position = $('#update_contact_position').attr('value');
		var ed_contact_birth =  Date.parseExact($("#update_contact_birth").attr("value"),"d/M/yyyy");
		ed_contact_birth = ed_contact_birth.toString("yyyy-M-d");
		
		var ed_contact_fax = $('#update_contact_fax').attr('value');
		
		if($('#update_agency_name').attr('value')){
			//console.log("name="+up_agency_name+"tel="+up_agency_tel+"mail="+up_agency_email+"info"+up_agency_info_cont+"id="+agency_id);
			$.ajaxSetup({
				async: false
			});	
			$.ajax({
				type: "POST",
				url: "?r=agency/editcontact",
				data:{'agency_id':agency_id,'ed_firstname':ed_firstname, 'ed_contact_tel':ed_contact_tel,'ed_contact_email':ed_contact_email,'ed_contact_info_cont':ed_contact_info_cont,'ed_contact_id':ed_contact_id,'position':ed_contact_position,'birthday':ed_contact_birth,'fax':ed_contact_fax},
				
					success: function(data) {	
								
						//alert("การแก้ไขผู้ติดต่อเสร็จสมบูรณ์");
						
					},
					error: function(data){				
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรูณาทำการแก้ไขผู้ติดต่อใหม่อีกครั้ง");			
					}								   
			});	
			
			while(delay < 100){delay++;}			
			read_contact(agency_id);
			
			
		}else {
			
			alert("กรุณาระบุชื่อผู้ติดต่อ");
		}
		
		
		$("#update_agency_name").attr('cont_id',"");
		$('#update_agency_name').attr('value',"");
		$('#update_agency_tel').attr('value',"");
		$('#update_agency_email').attr('value',"");
		$('#update_agency_info_cont').attr('value',"");
		$('#update_contact_position').attr('value',"");
		$('#update_contact_birth').attr('value',"");
		$('#update_contact_fax').attr('value',"");	
		
	});

	function read_contact(agency_id){
		
		$.ajaxSetup({
			async: false
		});
			
		$.ajax('?r=agency/japi&action=readcontact&agency_id='+agency_id+'',{
			type: 'GET',
			dataType: 'json',
			success: function(readcontact){
				
				$("#agentable_contact tbody tr").remove();			
				$.each(readcontact,function(k,v){ 
					
					$("#agentable_contact tbody").append(
						"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
									
							<!---swordzoro add 9:50--->
							"<td style='text-align:left'>"+v.firstname+"</td>" + 
							"<td style='text-align:left'>"+v.position+"</td>" + 
							"<td style='text-align:left'>"+v.email+"</td>" + 
							<!---swordzoro end 9:50--->		
									
							"<td style='text-align:left'>"+format_datetime(v.birthday)+"</td>" + 
							"<td style='text-align:left'>"+v.tel+"</td>" +
							"<td style='text-align:left'>"+v.fax+"</td>" +
							"<td style='text-align:left'>"+v.desc+"</td>" +
									 
							"<td style='text-align:center'><a title='แก้ไข' style='text-align:center' onclick=edit_contact("+v.contact_id+"); ><img src='images/pen.png' style='width:20px;cursor:pointer' align='center' /></a></td>" +
							"<td style='text-align:center'><a title='ลบ' onclick=delete_contOpen("+v.contact_id+");><img src='images/delete.png' style='width:20px;cursor:pointer' align='center' /></a></td>" +
									
						"</tr>" 
					);		
							
				});
			}
		});
	}

	function updateagenOpen(agency_id){
			
		$('#update_agency').attr('agen_id',agency_id);
		$('#updateAgenDi').dialog('open');
		
		$('#update_agency_name').attr('value',"");
		$('#update_contact_position').attr('value',"");
		$('#update_agency_email').attr('value',"");
		$('#update_contact_birth').attr('value',"");
		$('#update_agency_tel').attr('value',"");
		$('#update_contact_fax').attr('value',"");
		
		//console.log("agency_id= "+$('#update_agency').attr('agen_id'));
		//console.log("agency_id= "+agency_id);
		
		$.ajaxSetup({
			async: false
		});
		$.ajax('?r=agency/japi&action=parentAgency&parent_id='+agency_id+'',{
			type: 'GET',
			dataType: 'json',
			success: function(parentAgency){			
				$.each(parentAgency,function(kparent,vparent){			
					$('#update_agency').attr('value',vparent.agency_name);
					$('#agency_central_tel').attr('value',vparent.agency_tel);
					$('#agency_central_fax').attr('value',vparent.agency_fax);
						
				});
			}
		
		});
		
		$.ajaxSetup({
			async: false
		});
		$.ajax('?r=agency/japi&action=agenList',{
			type: 'GET',
			dataType: 'json',
			success: function(agenList){
				
				$("#update_agency_main option").remove();
				
				$("#update_agency_main").append( 
					
					"<option value='0'>None</option>"
				)
				$.ajaxSetup({
					async: false
				});
	
				$.each(agenList,function(k,v){
							
					$("#update_agency_main").append( 
						"<option value="+v.agency_id+">"+v.agency_name+"</option>"
					);
									
				});

				$.ajaxSetup({
					async: false
				});
	
				$.ajax('?r=agency/japi&action=updateParentAgency&agency_id='+agency_id+'',{
					type: 'GET',
					dataType: 'json',
					success: function(updateParentAgency){			
						$.each(updateParentAgency,function(kpar,vpar){	
						
							$("#update_agency_main").val(vpar.parent_id);
						
						/*
							$("#update_agency_main").append(
								"<option value='"+vpar.parent_id+"'>"+vpar.agency_name+"</option>"
							)	
							
						*/
						});
					}
				});
				
			}

		});
		
		
		$.ajaxSetup({
			async: false
		});
		$.ajax('?r=agency/japi&action=parentAgency&parent_id='+agency_id+'',{
			type: 'GET',
			dataType: 'json',
			success: function(parentAgency){			
				$.each(parentAgency,function(key,value){			
					$('#update_agency_info').attr('value',value.agency_desc);	
				});
			}
		
		});
		
		read_contact(agency_id);
		return false;
	}
	
	function edit_contact(contact_id){
		
		//$("#update_contact").css('visibility','hidden');
		//$("#edit_contact").css('visibility','visible');
		$("#update_contact").attr("disabled", "disabled");
		$("#edit_contact").removeAttr("disabled");

		$.ajaxSetup({
			async: false
		});
		
		$.ajax('?r=agency/japi&action=updatecontact&contact_id='+contact_id+'',{
			type: 'GET',
			dataType: 'json',
			success: function(updatecontact){			
				$.each(updatecontact,function(key,value){
					
					var birthday = Date.parseExact(value.birthday, "yyyy-MM-dd");
					birthday = birthday.toString("dd/MM/yyyy");
					
					$('#update_agency_name').attr('cont_id',value.contact_id);
					$('#update_agency_name').attr('value',value.firstname);
					$('#update_agency_tel').attr('value',value.tel);
					$('#update_agency_email').attr('value',value.email);
					$('#update_agency_info_cont').attr('value',value.desc);
					$('#update_contact_position').attr('value',value.position);
					$('#update_contact_birth').attr('value',birthday);
					$('#update_contact_fax').attr('value',value.fax);
					
				});
			}
		
		});
	}
	
	function delete_contOpen(contact_id){
		
		$('#deletecontactDi').dialog('open');
		$('#dlete_cont_id').attr('value',contact_id);
	}
	
	function delete_contact(){
		
		var del_contact_id = 0;
		var delay = 0;
		
		del_contact_id = $('#dlete_cont_id').attr('value');
		
		$.ajaxSetup({
			async: false
		});	
		$.ajax({
			type: "POST",
			url: "?r=agency/deletecontact",
			data:{'contact_id':del_contact_id},
				
				success: function(data) {				
					//alert("การลบผู้ติดต่อเสร็จสมบูรณ์");
				},
				error: function(data){				
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรูณาทำการลบผู้ติดต่อใหม่อีกครั้ง");			
				}								   
		});	
			
		while(delay < 100){delay++;}
		read_contact($('#update_agency').attr('agen_id'));
		$(this).dialog("close");
		
	}

	//-------End of Agency contact Process ---------
	
	function updateAgenDb(){
			//console.log("agency_id="+$('#update_agency').attr('agen_id')+"agen_name="+$('#update_agency').attr('value')+"main_agency="+$('#update_agency_main').attr('value')+"desc="+$('#update_agency_info').attr('value'));
		
		var delay = 0;
		var edit_agency_id = 0;
		var edit_agency_name = 0;
		var edit_agency_main = 0;
		var edit_agency_info = 0;
		
		edit_agency_id = $('#update_agency').attr('agen_id');
		edit_agency_name = $('#update_agency').attr('value');
		edit_agency_main = $('#update_agency_main').attr('value');
		edit_agency_info = $('#update_agency_info').attr('value');
		edit_agency_tel = $('#agency_central_tel').attr('value');
		edit_agency_fax = $('#agency_central_fax').attr('value');
		
		$.ajaxSetup({
			async: false
		});	
		$.ajax({
			type: "POST",
			url: "?r=agency/editAgency",
			data:{'edit_agency_id':edit_agency_id,'edit_agency_name':edit_agency_name, 'edit_agency_main':edit_agency_main,'edit_agency_info':edit_agency_info,'tel':edit_agency_tel,'fax':edit_agency_fax},
				
				success: function(data) {				
					//alert("การลบผู้ติดต่อเสร็จสมบูรณ์");
				},
				error: function(data){				
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรูณาทำการแก้ไขเอเจนซี่ใหม่อีกครั้ง");			
				}								   
		});	
			
		while(delay < 100){delay++;}
		show_agency_list(2);
		$(this).dialog("close");
		
	}
	
	function delete_agenOpen(agency_id){
		
		$('#deleteAgencyDi').dialog('open');
		$('#dlete_agen_id').attr('value',agency_id);
	}
	
	function delete_agency(){
		
		var del_agency_id = 0;
		var delay = 0;
		
		del_agency_id = $("#dlete_agen_id").attr('value');
		
		$.ajaxSetup({
			async: false
		});	
		$.ajax({
			type: "POST",
			url: "?r=agency/deleteAgency",
			data:{'agency_id':del_agency_id},
				
				success: function(data) {				
					//alert("การลบผู้ติดต่อเสร็จสมบูรณ์");
				},
				error: function(data){				
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรูณาทำการลบผู้ติดต่อใหม่อีกครั้ง");			
				}								   
		});	
			
		while(delay < 100){delay++;}
		show_agency_list(2);
		$(this).dialog("close");
		
	}
	
	function goAdv(){
		alert("Sorry! This function is under construction");
	}
	
	//----------> Start Show Agency Table ------------>
	
	

	
	//<---------- End Show Agency Table <---------------
	
	

</script>           
</div>
</div>            