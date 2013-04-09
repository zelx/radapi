<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Package';
$this->breadcrumbs=array(
	'Package',
);
?>

<script>

function package_reader(){
	
	$.ajaxSetup({
		async: false
	});
	$.ajax('?r=pack/japi&action=readerPack',{
		type: 'GET',
		dataType: 'json',
		success: function(readerPack){
		$("#pack_table tbody tr").remove();			
				$.each(readerPack,function(key,value){
					
					$("#pack_table tbody").append(
						"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
							"<td style='text-align:left'>"+value.pkg_name+"</td>" + 
							"<td style='text-align:center'>"+format_datetime(value.pkg_date_start)+"</td>" +
							"<td style='text-align:center'>"+format_datetime(value.pkg_date_end)+"</td>" + 
							"<td style='text-align:left'>"+value.pkg_desc+"</td>" + 
							"<td style='text-align:center'><a title='แก้ไข' onclick=edit_pkg("+value.pkg_id+");><img src='images/pen.png' style='width:20px;cursor:pointer' align='center' /></a></td>" +
							"<td style='text-align:center'><a title='ลบ' onclick=delete_pkg("+value.pkg_id+");><img src='images/delete.png' style='width:20px;cursor:pointer' align='center' /></a></td>" +
								
						"</tr>" 
					);
				});
		}
	});
	
}

</script>
<div class="row-fluid" >
 <div class="span4" align="left">
 		<button id="add_pack" type="button" class="btn btn-info" style="font-size:1em;margin-top:4px;margin-bottom:4px"  data-loading-text="Loading..." onclick="$('#addpackDi').dialog('open'); return false;">เพิ่มแพคเกจ</button>
 </div>
</div>
<div class="container" id="page" style="width:inherit"> 
 <div class="row-fluid">
   	<div class="">
		<table align="center" class="table table-striped" id="pack_table">
          <thead>
            <tr style="font-size:0.8em;height:25px;">
              <th style="width:30%;text-align:left;padding:6px">ชื่อแพคเกจ</th>
              <th style="width:20%;text-align:center;padding:6px">เริ่มต้น</th>
              <th style="width:20%;text-align:center;padding:6px">สิ้นสุด</th>
              <th style="width:20%;text-align:left;padding:6px">หมายเหตุ</th>
              <th style="width:5%;text-align:center;padding:6px">แก้ไข</th>
              <th style="width:5%;text-align:center;padding:6px">ลบ </th>
            </tr>
          </thead>
          <tbody style="font-size:0.8em">
          </tbody>
        </table>
 	</div>
 </div>
 <!---
 <div class="pagination pagination-right">
  <ul>
    <li ><a href='#'><<</a></li>
    <li class="active"><a href='#'>1</a></li>
    <li ><a href='#'>2</a></li>
    <li ><a href='#'>3</a></li>
    <li ><a href='#'>>></a></li>
  </ul>
 </div>
 --->
 
</div>

<?php
 //--------------- Start of Dialog for Adding Package  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'addpackDi',
        'options'=>array(
            'title'=>'เพิ่มแพคเกจ',
			'width'=>400,
			'height'=>350,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:add_package',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
<div class="dialog_input" >
        <div class="row-fluid" align="center">
        	<div class="span3" align="right">
            	<label for="add_pack_name" style="margin-top:5px;font-size:1em">ชื่อกิจกรรม:</label>
          	</div>
           	<div class="span9" align="left">
            	<input type="text" name="add_pack_name" id="add_pack_name" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>
          	</div>
        </div>
        <div class="row-fluid" align="center" >
        	<div class="span3" align="right">
            	<label for="add_start_pack" style="margin-top:10px;font-size:1em">เริ่มต้น:</label>
          	</div>
           	<div class="span9" align="left">
				<?php
                	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'my_date',
							'id'=>'add_start_pack',
							'value'=>date('d/m/Y'),
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
                                'style'=>'width:190px;vertical-align:top'
                            ),
                        ));   
                    ?>
          	</div>
        </div>
        <div class="row-fluid" align="center" >
        	<div class="span3" align="right">
            	<label for="add_end_pack" style="margin-top:10px;font-size:1em">สิ้นสุด:</label>
          	</div>
           	<div class="span9" align="left">
               <?php
                	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'my_date',
							'id'=>'add_end_pack',
							'value'=>date('d/m/Y'),
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
                                'style'=>'width:190px;vertical-align:top'
                            ),
                        ));   
                    ?>
          	</div>
        </div>
        <div class="row-fluid" align="center">
        	<div class="span3" align="right">
            	<label for="add_pack_info" style="margin-top:10px;font-size:1em">หมายเหตุ:</label>
          	</div>
           	<div class="span9" align="left">
                <textarea rows="3" id="add_pack_info" style="margin-top:5px;font-size:0.8em;width:220px; "></textarea>
          	</div>
        </div>
</div>



<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for Adding Package  --------------
?>

<?php
 //--------------- Start of Dialog for Adding Package  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'editpackDi',
        'options'=>array(
            'title'=>'แก้ไขแพคเกจ',
			'width'=>400,
			'height'=>350,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:edit_packageDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
<div class="dialog_input" >
        <div class="row-fluid" align="center">
        	<div class="span3" align="right">
            	<label for="edit_pack_name" style="margin-top:5px;font-size:1em">ชื่อกิจกรรม:</label>
          	</div>
           	<div class="span9" align="left" id="edit_pkg_id" value="">
            	<input type="text" name="edit_pack_name" id="edit_pack_name" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>
          	</div>
        </div>
        <div class="row-fluid" align="center" >
        	<div class="span3" align="right">
            	<label for="edit_start_pack" style="margin-top:10px;font-size:1em">เริ่มต้น:</label>
          	</div>
           	<div class="span9" align="left">
				<?php
                	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'my_date',
							'id'=>'edit_start_pack',
							'value'=>date('d/m/Y'),
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
                                'style'=>'width:190px;vertical-align:top'
                            ),
                        ));   
                    ?>
          	</div>
        </div>
        <div class="row-fluid" align="center" >
        	<div class="span3" align="right">
            	<label for="edit_end_pack" style="margin-top:10px;font-size:1em">สิ้นสุด:</label>
          	</div>
           	<div class="span9" align="left">
               <?php
                	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'my_date',
							'id'=>'edit_end_pack',
							'value'=>date('d/m/Y'),
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
                                'style'=>'width:190px;vertical-align:top'
                            ),
                        ));   
                    ?>
          	</div>
        </div>
        <div class="row-fluid" align="center">
        	<div class="span3" align="right">
            	<label for="edit_pack_info" style="margin-top:10px;font-size:1em">หมายเหตุ:</label>
          	</div>
           	<div class="span9" align="left">
                <textarea rows="3" id="edit_pack_info" style="margin-top:5px;font-size:0.8em;width:220px; "></textarea>
          	</div>
        </div>
</div>



<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for Adding Package  --------------
?>

<?php
 //--------------- Start of Dialog for Adding Package  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'deltepackDi',
        'options'=>array(
            'title'=>'ลบแพคเกจ',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ใช่'=>'js:delete_pkgDb',
                'ไม่'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
<div class="dialog_input" >
	<div class="row-fluid" align="center" id="del_pkg_id" value="">
    	คุณต้องการลบแพคเกจนี้ใช่หรือไม่
    </div>
</div>    


<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for Adding Package  --------------
?>

<script>

	package_reader(); // Reading all pakage 
	
	//--------> start to Add Package --------> 
	function add_package(){
		
		var delay = 0;
		var add_pkg_name = 0;
		var add_pkg_start = 0;
		var add_pkg_end = 0;
		var add_pkg_desc = 0;
		
		add_pkg_name = $("#add_pack_name").attr('value');
		add_pkg_start = Date.parseExact($("#add_start_pack").attr('value'),"d/M/yyyy");
		add_pkg_end = Date.parseExact($("#add_end_pack").attr('value'),"d/M/yyyy");
		add_pkg_desc = $("#add_pack_info").attr('value');
		
		add_pkg_start = add_pkg_start.toString("yyyy-M-d");
		add_pkg_end = add_pkg_end.toString("yyyy-M-d");
		
		//console.log("name="+add_pkg_name+"start="+add_pkg_start+"stop="+add_pkg_end+"desc="+add_pkg_desc);
		
		$.ajaxSetup({
			async: false
		});
		if(add_pkg_name != "" && add_pkg_start != "" && add_pkg_end != ""){
			//alert("completed");
			$.ajax({
				type: "POST",
				url: "?r=pack/addPack",
				data:{'add_pkg_name':add_pkg_name, 'add_pkg_start':add_pkg_start, 'add_pkg_end':add_pkg_end, 'add_pkg_desc':add_pkg_desc},
					success: function(data) {				
						//alert("กรุณาทำการแยกชุดโฆษณาเสร็จสมบูรณ์");
					},
					error: function(data){				
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาทำการเพิ่มแพคเกจใหม่อีกครั้ง");		
					}								   
			});	
			while(delay < 100){delay++;}
	 		$(this).dialog("close");
			
		}else{
			
			alert("กรุณากรอกชื่อ วันเริ่มต้นและวันสิ้นสุด ");
		}
		
	 package_reader();
	
	}
	//--------> End to Add Package -----------> 
	
	//--------> Start to Edit Package -------->
	
	function edit_pkg(pkg_id){
		
		var edit_st_date = 0;
		var edit_en_date = 0;
		
		$('#editpackDi').dialog('open');
		$('#edit_pkg_id').attr('value',pkg_id);
		
		$.ajaxSetup({
			async: false
		});
		$.ajax('?r=pack/japi&action=readPackId&pkg_id='+pkg_id+'',{
			type: 'GET',
			dataType: 'json',
			success: function(readPackId){
							
				$.each(readPackId,function(key,val){
					
					edit_st_date = Date.parseExact(val.pkg_date_start,"yyyy-MM-dd");
					edit_en_date = Date.parseExact(val.pkg_date_end, "yyyy-MM-dd");
					
					edit_st_date = edit_st_date.toString("dd/MM/yyyy");
					edit_en_date = edit_en_date.toString("dd/MM/yyyy");
						
					$("#edit_pack_name").attr('value',val.pkg_name);
					$("#edit_start_pack").attr('value',edit_st_date);
					$("#edit_end_pack").attr('value',edit_en_date);
					$("#edit_pack_info").attr('value',val.pkg_desc);	
						
				});
			 }
		});
			
		
	}
	
	function edit_packageDb(){
		
		var delay = 0;
		var edit_pkg_id = 0;
		var edit_pkg_name = 0;
		var edit_pkg_start = 0;
		var edit_pkg_end = 0;
		var edit_pkg_desc = 0;
		
		edit_pkg_id = $('#edit_pkg_id').attr('value');
		edit_pkg_name = $("#edit_pack_name").attr('value');
		edit_pkg_start = Date.parseExact($("#edit_start_pack").attr('value'),"d/M/yyyy").toString("yyyy-MM-dd");
		edit_pkg_end = Date.parseExact($("#edit_end_pack").attr('value'),"d/M/yyyy").toString("yyyy-MM-dd");
		edit_pkg_desc = $("#edit_pack_info").attr('value');
		
		//console.log("name="+edit_pkg_name+"start="+edit_pkg_start+"stop="+edit_pkg_end+"desc="+edit_pkg_desc);
		
		$.ajaxSetup({
			async: false
		});
		if(edit_pkg_name != "" && edit_pkg_start != "" && edit_pkg_end != ""){
			//alert("completed");
			$.ajax({
				type: "POST",
				url: "?r=pack/editPack",
				data:{'edit_pkg_id':edit_pkg_id,'edit_pkg_name':edit_pkg_name, 'edit_pkg_start':edit_pkg_start, 'edit_pkg_end':edit_pkg_end, 'edit_pkg_desc':edit_pkg_desc},
				
					success: function(data) {				
						//alert("กรุณาทำการแยกชุดโฆษณาเสร็จสมบูรณ์");
					},
					error: function(data){				
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาทำการแก้ไขแพคเกจใหม่อีกครั้ง");		
					}								   
			});	
			while(delay < 100){delay++;}
	 		$(this).dialog("close");
			
		}else{
			
			alert("กรุณากรอกชื่อ วันเริ่มต้นและวันสิ้นสุด ");
		}		
			
	 package_reader();
		
	}
	
	//--------> End to Edit Package ---------->
	
	//--------> Start to Delete Package ------->
	function delete_pkg(pkg_id){
				
		$('#deltepackDi').dialog('open');
		$('#del_pkg_id').attr('value',pkg_id);
	}
	
	function delete_pkgDb(){
		
		var delay = 0;
		var del_pack_id = 0;
		
		del_pack_id = $('#del_pkg_id').attr('value');
		
		$.ajaxSetup({
			async: false
		});

		$.ajax({
			type: "POST",
			url: "?r=pack/deletePack",
			data:{'del_pkg_id':del_pack_id},
				
				success: function(data) {				
					//alert("กรุณาทำการแยกชุดโฆษณาเสร็จสมบูรณ์");
				},
				error: function(data){				
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาทำการลบแพคเกจใหม่อีกครั้ง");		
				}								   
		});	
		while(delay < 100){delay++;}
		package_reader();
	 	$(this).dialog("close");
	}
	
	//--------> End to Delte Package --------->
	

</script>