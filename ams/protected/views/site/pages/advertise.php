<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Advertise';
$this->breadcrumbs=array(
	'Advertise',
);

?>

<script>

//--------> Start of Product List Reader------------
	$.ajaxSetup({
		async: false
	});

	$(document).ready(function() {
		$.ajax('?r=adver/japi&action=prodList',{
			type: 'GET',
			dataType: 'json',
			success: function(prodList){
			//var breakid=0;
			$("#adv_prod option").remove();			
				$.each(prodList,function(k,v){ 
				
					$("#adv_prod").append( 
					
							 "<option value="+v.prod_id+">"+v.prod_name+"</option>"
					);
							
				});
			 }
		});
	});
//--------> End of Product List Reader ------------

//--------> Start Reading Advertise DB ------------	
 
	function showAdvtable(start_row,stop_row,search_str){''
			
			$.ajaxSetup({
				async: false
			});
			$.ajax('?r=adver/japi&action=readerProd&start_row='+start_row+'&stop_row='+stop_row+'&search_str='+search_str,{
				type: 'GET',
				dataType: 'json',
				success: function(readerProd){
					
					$("#advtable tbody tr").remove();
					$.each(readerProd,function(kadv,vadv){
						
						$("#advtable tbody").append(
							"<tr  style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
								"<td style='text-align:center;padding:4px'><label class='checkbox inline'>"+
								"<input  style='margin-top:2px' type='checkbox' id='check"+vadv.tape_id+"' value='option"+vadv.tape_id+"'></label></td>"+
								"<td style='text-align:left;padding-top:8px'>"+vadv.prod_name+"</td>" + 
								"<td style='text-align:left;padding-top:8px'>"+vadv.tape_name+"</td>" +
								"<td style='text-align:center;padding-top:8px'>"+vadv.time_len+"</td>" + 
								"<td style='text-align:left;padding-top:8px'>"+vadv.prod_desc+"</td>" +
								"<td style='text-align:center;padding-top:8px'><a title='แก้ไข' onclick=updateadv("+vadv.tape_id+","+vadv.prod_id+");><img src='images/pen.png' style='width:20px;cursor:pointer' align='center' /></a></td>" +
								"<td style='text-align:center;padding-top:8px'><a title='ลบ' onclick=deleteProdAdv("+vadv.tape_id+");><img src='images/delete.png' style='width:20px;cursor:pointer' align='center' /></a></td>" +
								
							"</tr>" 
						);
					});
				 }
		});
		
	}	

//--------> End of Reading Advertise DB ------------	

$(document).ready(function(){
	
	show_product_list(2);
	
});

function show_product_list(index_page,search_str){
	
		var start_row = 1;
		var stop_row = 5;
		
		var current_page = 0;
		var current_id = "product_page_2";
		var current_val = 1;
		
		var check_page = "product_page_"+index_page;
		
		stop_row = $("#num_row_product").attr("value");
		
		if(check_page == "product_page_1"){
			
			current_id = $("#product_page").find("li.active").attr('id');
			current_val = $("#"+current_id).attr('value');
			
			if(current_val > "2"){
				
				current_val = parseInt(current_val)-1;
				
				$("#product_page").find("li.active").removeClass("active");;
				$("#product_b_"+current_val).addClass("active");	
				
			}else if(current_val == "2" && $("#product_page_2").text() > "1"){
				
				current_val = 6;
					
				$("#product_page_2").text(parseInt($("#product_page_2").text())-5);
				$("#product_page_3").text(parseInt($("#product_page_3").text())-5);
				$("#product_page_4").text(parseInt($("#product_page_4").text())-5);
				$("#product_page_5").text(parseInt($("#product_page_5").text())-5);
				$("#product_page_6").text(parseInt($("#product_page_6").text())-5);
				
				$("#product_page").find("li.active").removeClass("active");;
				$("#product_b_"+current_val).addClass("active");	
				
			
			}else {
				
				current_val = current_val;
				
				$("#product_page").find("li.active").removeClass("active");
				$("#product_b_"+current_val).addClass("active");
			}
			
			current_id = $("#product_page").find("li.active").attr('value');
			current_page = $("#product_page_"+current_id).text();
			
			start_row = (parseInt(current_page)-1)*stop_row;
			
			//console.log("startROW"+start_row+" stopROW"+stop_row); 
			
	
		}else if(check_page == "product_page_7"){
			
			current_id = $("#product_page").find("li.active").attr('id');
			current_val = $("#"+current_id).attr('value');
			
			if(current_val < "6"){
	
				current_val = parseInt(current_val)+1;
				
				$("#product_page").find("li.active").removeClass("active");;
				$("#product_b_"+current_val).addClass("active");		

			}else if(current_val == "6"){
				
				current_val = 2;
					
				$("#product_page_2").text(parseInt($("#product_page_2").text())+5);
				$("#product_page_3").text(parseInt($("#product_page_3").text())+5);
				$("#product_page_4").text(parseInt($("#product_page_4").text())+5);
				$("#product_page_5").text(parseInt($("#product_page_5").text())+5);
				$("#product_page_6").text(parseInt($("#product_page_6").text())+5);
				
				$("#product_page").find("li.active").removeClass("active");;
				$("#product_b_"+current_val).addClass("active");	
				
			}else {
				
				current_val = current_val;
				
				$("#product_page").find("li.active").removeClass("active");
				$("#product_b_"+current_val).addClass("active");
			}
			
			current_id = $("#product_page").find("li.active").attr('value');
			current_page = $("#product_page_"+current_id).text();
			
			start_row = (parseInt(current_page)-1)*stop_row;
			//console.log("startROW"+start_row+" stopROW"+stop_row); 
			
			
		}else {
						
			$("#product_page").find("li.active").removeClass("active");;
			$("#product_b_"+index_page).addClass("active");			
			current_page = $("#product_page_"+index_page).text();
			
			start_row = (parseInt(current_page)-1)*stop_row;
			//console.log("startROW"+start_row+" stopROW"+stop_row); 
			
		}
		
	showAdvtable(start_row,stop_row,search_str);
}

$(document).ready(function() {
	$("#num_row_product").change(function(){
	
		show_product_list(2);
	
	});
});



</script>

<div class="row-fluid"  >
	<div class="span6">
 		<button id="addAdv" type="button" class="btn btn-info" style="font-size:1em;margin-top:4px;margin-bottom:4px"  data-loading-text="Loading..." onclick="OpenAddTabeDi()">เพิ่มชุดโฆษณา</button>
    </div>
	<div class="span6" align="right">
    	<div class="row-fluid"  >
            <div class="span2" align="right">
            </div>
            <div class="span10" align="right" style="margin-left:4px"> 
            	<input type="text" name="search_list" id="search_list" size="10" >
            	<div style="float:right; margin-top:0px;font-size:1em;"><input type="button" name="search_clear" id="search_clear" class="btn btn-info" value="ยกเลิก" onclick="$('#search_list').val(''); show_product_list(2);"></div>
                <div style="float:right; margin-top:0px;font-size:1em;"><input type="button" name="search_button" id="search_button" class="btn btn-info" value="ค้นหา" onclick="show_product_list(2,$('#search_list').val());"></div>
          
            </div>
        </div>                          
	</div> 
</div>
<div class="row-fluid" >
<div>
    <div class="container" id="page" style="width:inherit">
     <div class="row-fluid">
        <div class="">
            <table align="center" class="table table-striped" id="advtable">
              <thead thead align="center">
                <tr style="font-size:0.8em;height:25px;">
                  <th style="width:5%;text-align:center;padding:6px"></th>
                  <th style="width:33%;text-align:left;padding:6px">สินค้า</th>
                  <th style="width:30%;text-align:left;padding:6px">ชุดโฆษณา</th>
                  <th style="width:12%;text-align:center;padding:6px">เวลา(วินาที)</th>
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
     <div class="row-fluid">
      <div class="span6">
      </div> 
      <div class="span6">
     		<div class="row-fluid"  align="center">
             <div class="span3" align="right">
             </div>
            <div class="span7" align="right" style="margin:2px">
                <div class="pagination pagination-right pagination-small" style="font-size:1em;;margin-top:4px;margin-bottom:4px;">
                  <ul id="product_page">
                    <li id="product_b_1" value="1"><a id="product_page_1" onclick=show_product_list("1") style=" color: #39C">Prev</a></li>
                    <li id="product_b_2" value="2" class="active"><a id="product_page_2"  style=" color:#39C" onclick=show_product_list("2")>1</a></li>
                    <li id="product_b_3" value="3" ><a id="product_page_3"  style=" color:#39C" onclick=show_product_list("3")>2</a></li>
                    <li id="product_b_4" value="4" ><a id="product_page_4"  style=" color:#39C" onclick=show_product_list("4")>3</a></li>
                    <li id="product_b_5" value="5" ><a id="product_page_5"  style=" color:#39C" onclick=show_product_list("5")>4</a></li>
                    <li id="product_b_6" value="6" ><a id="product_page_6"  style=" color:#39C" onclick=show_product_list("6")>5</a></li>
                    <li id="product_b_7" value="7" ><a id="product_page_7"  style=" color:#39C" onclick=show_product_list("7")>Next</a></li>
                  </ul>
                </div>
            </div>
            <div class="span2" align="left">
                <select  style="font-size:0.8em;width:80px;margin-top:4px;margin-bottom:4px; margin-left:2px" id="num_row_product" value="" class="input-mini" >
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
 //--------------- Start of Dialog for Adding Advertise  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'addAdv_di',
        'options'=>array(
            'title'=>'เพิ่มชุดโฆษณา',
            'autoOpen'=>false,
			'width'=>500,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:addAdvDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" >
 <form >
 
	<div class="row-fluid" align="center" >
		<div class="span4" align="right">
            	<label for="add_advprod" style="margin-top:4px;font-size:1em">สินค้า:</label>
		</div>
		<div class="span8" align="left">
        		<input type="text" name="add_advprod" id="add_advprod" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>
                
		</div>
	</div>
    <div class="row-fluid" align="center" >
		<div class="span4" align="right">
            	<label for="add_advname" style="margin-top:4px;font-size:1em">ชื่อชุดโฆษณา:</label>
		</div>
		<div class="span8" align="left">
                <input type="text" name="add_advname" id="add_advname" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>
		</div>
	</div>
    <div class="row-fluid" align="center" >
		<div class="span4" align="right">
            	<label for="add_advtime" style="margin-top:4px;font-size:1em">ความยาว(วินาที):</label>
		</div>
		<div class="span8" align="left">
               <!--- <input type="text" name="add_advtime" id="add_advtime" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>  --->
                
                
			<select class='text ui-widget-content ui-corner-all' style="font-size:1em;width:230px; "name="add_advtime" id="add_advtime" >
				<option value='15'>15</option>
				<option value='30'>30</option>
				<option value='45'>45</option>
				<option value='60'>60</option>
				<option value='75'>75</option>
				<option value='90'>90</option>
				<option value='105'>105</option>
				<option value='120'>120</option>
				<option value='135'>135</option>
				<option value='150'>150</option>
				<option value='165'>165</option>
				<option value='180'>180</option>
				<option value='195'>195</option>
				<option value='210'>210</option>
				<option value='125'>125</option>
				<option value='240'>240</option>
			</select>
                
		</div>
	</div>
	<div class="row-fluid" align="center">
		<div class="span4" align="right">
            	<label for="add_advinfo" style="margin-top:4px;font-size:1em">หมายเหตุ:</label>
		</div>
		<div class="span8" align="left">
            	
                <textarea rows="3" id="add_advinfo" style="margin-top:5px;font-size:0.8em;width:220px; "></textarea>
		</div>
	</div>
</form>   
</div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for Adding Advertise  --------------
?>

<script>

//----------->Start of Add Advertise in DB ---------------------	

	function showTip_addProductTape(){
		
		var sentData = $("#add_advprod").val();
		var p = $("#add_advprod").position();
	
			
		var add_ProdName = [];
		var add_ProdNameID = [];
		var count_prod = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=adver/japi&action=autoProdName&prod_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoProdName){
				$.each(autoProdName,function(k,v){ 
				
					add_ProdNameID.push(v.prod_name+":"+v.prod_id);
					add_ProdName.push(v.prod_name);
					
				});
				
				$("#add_advprod").autocomplete({
					
					source:add_ProdName,
					select: function (event, ui) {
						
						$("#add_advprod").val(ui.item.label); // display the selected text
						for (var i=0;i < add_ProdNameID.length ;i++){
									
							var n = add_ProdNameID[i].split(":"); 
							if (n[0]== $("#add_advprod").val()) {
											
								$("#add_advprod").attr("prod_name", n[0]);
								$("#add_advprod").attr("prod_id", n[1]);
									
							}
						}	
						
					},
					search: function() {
						
						$("#add_advprod").attr("prod_id","");
						
					}
				});
			}
		});	
	}

	$("#add_advprod").keyup(function(event){
	
		showTip_addProductTape();
		
	});
	
	
	function OpenAddTabeDi(){
		
		 $('#addAdv_di').dialog('open'); 
		 
		 $("#add_advprod").val("");
		 $("#add_advname").val("");
		 $("#add_advtime").val("");
		 $("#add_advinfo").val("");
		 
		 return false;
	}

	function addAdvDb(){
		
		var delay =0;
		var add_advprod = 0;
		var add_advname = 0;
		var add_advtime = 0;
		var add_advinfo = 0;
		
		add_advprod = $("#add_advprod").attr('value');
		add_advname = $("#add_advname").attr('value');
		add_advtime = $("#add_advtime").attr('value');
		add_advinfo = $("#add_advinfo").attr('value');
		add_advprodID = $("#add_advprod").attr('prod_id');
		
		//console.log("add_advprodID= "+add_advprodID+"add_advprod= "+add_advprod)

		if((add_advprod != "") && (add_advname != "") && (add_advtime != "") ){
			
			if(add_advprodID != ""){
			
				//console.log("add_advagency= "+add_advagency+" add_advprod="+add_advprod+" add_advname="+add_advname+" add_advtime"+add_advtime+" add_advinfo="+add_advinfo);
				
				$.ajaxSetup({
					async: false
				});
				$.ajax({
					type: "POST",
					url: "?r=adver/addUpdateProd",	
					data:{'prod_id':add_advprodID,'prod_name':add_advprod, 'tape_name':add_advname, 'timelen':add_advtime, 'adv_info':add_advinfo},
					success: function(data) {
						//alert("success");
					},
					error: function(data){
						
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการเนื่องจากชื่อชุดโฆษณานี้อาจจะมีอยู่แล้ว กรุณาทำการเพิ่มชุดโฆษณาใหม่อีกครั้ง");	
					}
				});
				while(delay < 1000){delay++;}
				$(this).dialog("close");
				
				show_product_list(2);
				
			}else {
				
				
				$.ajaxSetup({
					async: false
				});
				$.ajax({
					type: "POST",
					url: "?r=adver/add",	
					data:{'prod_name':add_advprod, 'tape_name':add_advname, 'timelen':add_advtime, 'adv_info':add_advinfo},
					success: function(data) {
						//alert("success");
					},
					error: function(data){
						
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ(ควรคลิกเลือกสินค้าที่มีอยู่ในรายการ) กรุณาทำการเพิ่มชุดโฆษณาใหม่อีกครั้ง");	
					}
				});
				while(delay < 1000){delay++;}
				$(this).dialog("close");
				
				show_product_list(2);
	
			}
				
		}else{
			
			alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาตรวจสอบการกรอกชื่อสินค้าและชุดโฆษณา");	
		}
		
		
		return false;
	}
//----->End of Add Advertise in DB -----

</script>


<?php
 //--------------- Start of Dialog for updating Advertise  --------------
 
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'update_AdvDi',
        'options'=>array(
            'title'=>'แก้ไขชุดโฆษณา',
			'width'=>500,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:update_AdvDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" >
 <form >
	<div class="row-fluid" align="center" >
		<div class="span4" align="right">
            	<label for="update_advprod" style="margin-top:4px;font-size:1em">สินค้า:</label>
		</div>
		<div class="span8" align="left">
        		<input type="text" name="update_advprod" id="update_advprod" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>
                
		</div>
	</div>
    <div class="row-fluid" align="center" >
		<div class="span4" align="right">
            	<label for="update_advname" style="margin-top:4px;font-size:1em">ชื่อชุดโฆษณา:</label>
		</div>
		<div class="span8" align="left">
                <input type="text" name="update_advname" id="update_advname" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/>
		</div>
	</div>
    <div class="row-fluid" align="center" >
		<div class="span4" align="right">
            	<label for="update_advtime" style="margin-top:4px;font-size:1em">ความยาว(วินาที):</label>
		</div>
		<div class="span8" align="left">
                <!--- <input type="text" name="update_advtime" id="update_advtime" class="text ui-widget-content ui-corner-all"  style="font-size:0.8em;width:220px; "/> --->
                
			<select class='text ui-widget-content ui-corner-all' style="font-size:1em;width:230px; "name="update_advtime" id="update_advtime" >
							<option value='15'>15</option>
							<option value='30'>30</option>
							<option value='45'>45</option>
							<option value='60'>60</option>
							<option value='75'>75</option>
							<option value='90'>90</option>
							<option value='105'>105</option>
							<option value='120'>120</option>
							<option value='135'>135</option>
							<option value='150'>150</option>
							<option value='165'>165</option>
							<option value='180'>180</option>
							<option value='195'>195</option>
							<option value='210'>210</option>
							<option value='125'>125</option>
							<option value='240'>240</option>
			</select>
                                
         
		</div>
	</div>
	<div class="row-fluid" align="center">
		<div class="span4" align="right">
            	<label for="update_advinfo" style="margin-top:4px;font-size:1em">หมายเหตุ:</label>
		</div>
		<div class="span8" align="left">
            	
                <textarea rows="3" id="update_advinfo" style="margin-top:5px;font-size:0.8em;width:220px; "></textarea>
		</div>
	</div>
</form>   
</div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for updating Advertise  --------------
?>
<script>
	
	function updateadv(update_tapeid,update_prod_id){
				
		$('#update_AdvDi').dialog('open'); 
		$("#update_advprod").attr('prodID',update_prod_id);
		$("#update_advname").attr('tapeID',update_tapeid);
		
			
			$.ajaxSetup({
				async: false
			});
			$.ajax('?r=adver/japi&action=readUpdateAdv&tape_id='+update_tapeid+'&prod_id='+update_prod_id+'',{
				type: 'GET',
				dataType: 'json',
				success: function(readUpdateAdv){
					
					$.each(readUpdateAdv,function(kadv,vadv){
						
						$("#update_advprod").val(vadv.prod_name);
						$("#update_advname").val(vadv.tape_name);
						$("#update_advtime").val(vadv.time_len);
						$("#update_advinfo").val(vadv.prod_desc);
						
					});
				
				 }
			});

		return false;
	}
	
	function update_AdvDb(){
		
		var delay =0;
		var update_prod_id = 0;
		var update_adv_id = 0;
		var update_advprod = 0;
		var update_advname = 0;
		var update_advtime = 0;
		var update_advinfo = 0;
		var update_Tape_time = 0;
		var update_tape_id = 0;
		
		update_prod_id = $("#update_advprod").attr('prodID');
		update_advprod = $("#update_advprod").attr('value');
		update_advname = $("#update_advname").attr('value');
		update_advinfo = $("#update_advinfo").attr('value');
		update_tape_id = $("#update_advname").attr('tapeID');
		update_Tape_time = $("#update_advtime").attr('value');
		
		if((update_advprod != "") && (update_advname != "") && (update_Tape_time != "") ){
			
			$.ajaxSetup({
				async: false
			});
			
			$.ajax({
				
				type: "POST",
				url: "?r=adver/updateProdAdv",	
				data:{'prod_id':update_prod_id,'tape_id':update_tape_id, 'prod_name':update_advprod, 'tape_name':update_advname,'tape_timelen':update_Tape_time, 'adv_info':update_advinfo},
				success: function(data) {
					
					//alert("success");
					
				},
				error: function(data){
					
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาทำการแก้ไขชุดโฆษณาใหม่อีกครั้ง");	
				}
				
			});
			//while(delay < 1000){delay++;}
			
			//--------> Update Onair ------------------>
			
			var prog_on = $("#prog_on").attr('value');
			var onair_mon = parseInt($("#onair_mon").attr('value'));
			var onair_year = parseInt($("#onair_year").attr('value'))-543;
			default_daytab(); // Default Class for Daytab
			alarming_daytab(prog_on,onair_year,onair_mon);
				
			var day = $("#ul_daytab").find("li.ui-state-active").text();
			checkBreak(prog_on,onair_year,onair_mon,day);
			
			//<----------- End of Update -----------------
			
			$(this).dialog("close");
			
			show_product_list(2);
				
		}else{
			
			alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาตรวจสอบการกรอกชื่อสินค้าและชุดโฆษณา");	
		}
		
	}

</script>

<?php
 //--------------- Start of Dialog for deleting Advertise  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'del_ProdAdvDi',
        'options'=>array(
            'title'=>'ลบชุดโฆษณา',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:del_ProdAdvDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

    <div class="dialog_input" id="delete_advTape" >
        <div class="controls" style="margin-top:30px" align="center">
        	<label class="control-label" for="createbreaktime" >คุณต้องลบชุดโฆษณานี้ใช่หรือไม่</label>
        </div>
    </div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for deleting Advertise  --------------
?>

<script>

	function deleteProdAdv(delete_tape_id){
		
		$("#del_ProdAdvDi").dialog('open'); 
		$("#delete_advTape").attr('del_tapeID',delete_tape_id);
	
	}
	
	function del_ProdAdvDb(){
		
			var delay = 0;
			var delAdv_id = 0;
			var delTape_id = 0;
			
			delTape_id =  $("#delete_advTape").attr('del_tapeID');
		
			$.ajaxSetup({
				async: false
			});
			
			$.ajax({
				
				type: "POST",
				url: "?r=adver/deleteProdAdv",	
				data:{'tape_id':delTape_id},
				success: function(data) {
					
					//alert("success");
					
				},
				error: function(data){
					
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาทำการลบชุดโฆษณาใหม่อีกครั้ง");	
				}
				
			});
			
			while(delay < 1000){delay++;}
			$(this).dialog("close");
			
			show_product_list(2);
				
	}

</script>


<?php
 //--------------- Start of Dialog for deleting Advertise  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'delete_all_advDi',
        'options'=>array(
            'title'=>'ลบชุดโฆษณา',
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                //'ตกลง'=>'js:addAdvDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>


<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for deleting Advertise  --------------
?>

<script>

function goQ(){
				alert("Sorry! This function is under construction");
}


</script>            
</div>
</div>            