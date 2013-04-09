<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Programs Dashboard';
$this->breadcrumbs=array(
	'Programs Dashboard',
);
?>
<!--<style>
#ui-datepicker-div {
	width:200px;
	font-size:0.3em;
}
    #toolbar {
        padding: 10px 4px;
    }
</style> -->
<div class="row-fluid">
	<div align="center">
            <div class="row-fluid" style="margin-top:10px">
				<div class="" align="center" >
                	<div class="container"  id="page" style="width:inherit">
                			<div class="row-fluid">
                				<div class="span5" style="float:left">
									<div class="span3" align="left" >
										<label for="dateA_title" style="font-size:1em">From:</label>
					                </div>
					                <div class="span9" align="left" style="margin-left:2px" >
										<?php
					                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					                            'name'=>'dash_pro_date_from',
												'value'=>date('01/m/Y'), 
												'id'=>'dash_pro_date_from',
					                            'language'=>Yii::app()->language=='et' ? 'et' : null,
					                            'options'=>array(
					                                'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
					                                'showOn'=>'button', // 'focus', 'button', 'both'
					                                'buttonText'=>Yii::t('ui','Select form calendar'),
					                                'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
					                                'buttonImageOnly'=>true,
													 'dateFormat' => 'dd/mm/yy'
					                            ),
					                            'htmlOptions'=>array(
					                                'style'=>'width:120px;vertical-align:top'
					                            )
					                        ));
					                    ?>
					                </div>
				                </div>
				                <div class="span5" style="float:left">
									<div class="span3" align="left" >
										<label for="dateA_title" style="font-size:1em">To:</label>
					                </div>
					                <div class="span9" align="left" style="margin-left:2px" >
										<?php
					                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					                            'name'=>'dash_pro_date_to',
												'value'=>date('t/m/Y'), 
												'id'=>'dash_pro_date_to',
					                            'language'=>Yii::app()->language=='et' ? 'et' : null,
					                            'options'=>array(
					                                'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
					                                'showOn'=>'button', // 'focus', 'button', 'both'
					                                'buttonText'=>Yii::t('ui','Select form calendar'),
					                                'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
					                                'buttonImageOnly'=>true,
													 'dateFormat' => 'dd/mm/yy'
					                            ),
					                            'htmlOptions'=>array(
					                                'style'=>'width:120px;vertical-align:top'
					                            )
					                        ));   
					                    ?>	
					                </div>
				                </div>
				                
							</div>
                   			<div class="row-fluid">
								<div  align="center" >
                                    <div class="navbar "  >
                                        <div class="navbar-inner ">
                                            <ul class="nav">
                                              <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashprog_spanprog1" > Programs</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashprog_listprog1" class="dropdown-menu">
                                                          <li > <a href="#">Program A</a></li>
                                                          <li ><a href="#">Program B</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashprog_spanprog2" > Programs</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashprog_listprog2" class="dropdown-menu">
                                                          <li > <a href="#">Program A</a></li>
                                                          <li ><a href="#">Program B</a></li>  
                                                  </ul>
                                             </div>
                                            <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashprog_spanprog3" > Programs</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashprog_listprog3" class="dropdown-menu">
                                                          <li > <a href="#">Program A</a></li>
                                                          <li ><a href="#">Program B</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashprog_spanprog4" > Programs</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashprog_listprog4" class="dropdown-menu">
                                                          <li > <a href="#">Program A</a></li>
                                                          <li ><a href="#">Program B</a></li>  
                                                  </ul>
                                             </div>
                                              <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashprog_spanprog5" > Programs</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashprog_listprog5" class="dropdown-menu">
                                                          <li > <a href="#"> Production House A</a></li>
                                                          <li ><a href="#"> Production House B</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashprog_spanunit" > Unit</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashprog_listunit" class="dropdown-menu">
                                                          <li > <a href="money"> ดูราคา</a></li>
                                                          <li ><a href="time"> นาที</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li ></li></ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li ></li></ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li ></li></ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li ></li></ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li class="divider-vertical"></li></ul>
                                             </div>
                                             <div class="btn-group" align="right" >
                                                  <?php/*
														$this->widget('zii.widgets.jui.CJuiDatePicker', array(
															'name'=>'dashprog_date_start',
															'id'=>'dashprog_date_start',
															'value'=>date('d/m/Y'), 
															'language'=>Yii::app()->language=='et' ? 'et' : null,
															'options'=>array(
																'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
																'showOn'=>'button', // 'focus', 'button', 'both'
																'buttonText'=>Yii::t('ui','Select form calendar'),
																'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
																'buttonImageOnly'=>true,
															),
															'htmlOptions'=>array(
																'style'=>'width:120px;vertical-align:top'
															),
														)); */  
													?>	
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li class="divider-vertical"></li></ul>
                                             </div>
                                             <div class="btn-group" align="right">
                                                  <?php
/*														$this->widget('zii.widgets.jui.CJuiDatePicker', array(
															'name'=>'dashprog_date_end',
															'id'=>'dashprog_date_end',
															'value'=>date('d/m/Y'), 
															'language'=>Yii::app()->language=='et' ? 'et' : null,
															'options'=>array(
																'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
																'showOn'=>'button', // 'focus', 'button', 'both'
																'buttonText'=>Yii::t('ui','Select form calendar'),
																'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
																'buttonImageOnly'=>true,
															),
															'htmlOptions'=>array(
																'style'=>'width:120px;vertical-align:top'
															),
														));   
*/													?>
                                             </div>
                                            </ul>
                                        </div>
                                    </div>
                            	</div>
                         	</div>        
                            <div class="row-fluid">
								<div  align="center"  id="dash_prog_chart1"   style="height:400px;width:700px">
                            
                            </div>
<script>
var dashprogsel = [0,0,0,0,0];
var dataTypePro = "time";
function dashprog_select(progli,prog_id){
	var prog_name = 0;
	prog_name = $("#dashprog"+progli+"_id"+prog_id).attr('value')
	$("#dashprog_spanprog"+progli).text(prog_name);
	console.log(progli+" "+prog_id+" "+prog_name);
	// progchart_dashsum(prog_id);
	dashprogsel[progli-1] = prog_id;
	console.log(dashprogsel);
	dashprog_chartshow();
} 

function dashprog_prog_list() {
		$.ajax('?r=onair/japi&action=progList',{
			type: 'GET',
			dataType: 'json',
			success: function(progList){
			$("#dashprog_listprog1 li").remove();
			$("#dashprog_listprog2 li").remove();
			$("#dashprog_listprog3 li").remove();
			$("#dashprog_listprog4 li").remove();
			$("#dashprog_listprog5 li").remove();
			$.each(progList,function(k,v){ 
				$.each(v,function(kon,von){	
					var prog_name = von.prog_name;
					prog_name = prog_name.toString() ;
					$("#dashprog_listprog1").append(
							 "<li >"+
								 "<a id='dashprog1_id"+von.prog_id+"' value="+prog_name+"  onclick=dashprog_select("+1+","+von.prog_id+"); >"+prog_name+"</a>"+
							 "</li>"
					);
					$("#dashprog_listprog2").append(
							 "<li >"+
								 "<a id='dashprog2_id"+von.prog_id+"' value="+prog_name+"  onclick=dashprog_select("+2+","+von.prog_id+"); >"+prog_name+"</a>"+
							 "</li>"
					);
					$("#dashprog_listprog3").append(
							 "<li >"+
								 "<a id='dashprog3_id"+von.prog_id+"' value="+prog_name+"  onclick=dashprog_select("+3+","+von.prog_id+"); >"+prog_name+"</a>"+
							 "</li>"
					);
					$("#dashprog_listprog4").append(
							 "<li >"+
								 "<a id='dashprog4_id"+von.prog_id+"' value="+prog_name+"  onclick=dashprog_select("+4+","+von.prog_id+"); >"+prog_name+"</a>"+
							 "</li>"
					);
					$("#dashprog_listprog5").append(
							 "<li >"+
								 "<a id='dashprog5_id"+von.prog_id+"' value="+prog_name+"  onclick=dashprog_select("+5+","+von.prog_id+"); >"+prog_name+"</a>"+
							 "</li>"
					);
				});		
			});
			 }
		});
}

function dashprog_chartshow(){
	var proglist = "";
	var proglistcnt = 1;
	$.each(dashprogsel, function(k,v){
		pglist = k + 1;
		proglist += "&prog"+pglist+"="+v+"";
	});
	console.log(proglist);
		var dStart = $("#dash_pro_date_from").val().split("/");
		var dEnd = $("#dash_pro_date_to").val().split("/");
		var fromDate = dStart[2]+"-"+dStart[1]+"-"+dStart[0];
		var toDate = dEnd[2]+"-"+dEnd[1]+"-"+dEnd[0];
		$.ajax( '?r=dashSum/japi&action=DashProgram'+proglist+'&data_type='+dataTypePro+'&date_start='+fromDate+'&date_end='+toDate, 
	{
	type: 'GET',
	dataType: 'json',

	success: function(DashProgram){
		var tickstr = new Array();
		var line1 = [0,0,0,0,0];//[10,20,30];
		var line2 = [0,0,0,0,0];//[30,20,10];
		//var line1 = new Array();
		//var line2 = new Array();
	
		$.each(DashProgram, function(k,v){
			console.log("itme k "+k+" v "+v.spot_time+" v.prog_nam "+v.break_time);
			if (typeof(v.prog_name) != "undefined")
			{
				tickstr.push(v.prog_name);
				//line1.push(parseInt(v.spot_time));
				//line1.push(20); 
				//line2.push(parseInt(v.break_time)-parseInt(v.spot_time)); 
				
				//line2.push(10); 
				if(dataTypePro == "time"){
					line1[k]=parseInt(v.spot_time);
					line2[k]=parseInt(v.break_time)-parseInt(v.spot_time);
				}else{
					line1[k]=parseInt(v.net_price);
					line2[k]=parseInt(v.break_time)*parseInt(v.minute_price)-parseInt(v.net_price);
				}
			}
		});
				console.log("test");
				console.log(line1);
	
		console.log("itme ticks->"+tickstr);
		console.log("itme line1->"+line1);
		console.log("itme line2->"+line2);
		  $('#dash_prog_chart1').empty();
	  var strFormat = "%s นาที";
	  	  if(dataTypePro == "time"){
	  	  	strFormat = "%s นาที";
	  	  }else{
	  	  	strFormat = "%s บาท";
	  	  }
	  var plot1b = $.jqplot('dash_prog_chart1', [line1 ,line2], {
		title: 'Program Chart',
		stackSeries: true,
		//series:[{renderer:$.jqplot.BarRenderer}],
		seriesDefaults:{
		  renderer:$.jqplot.BarRenderer,
		  rendererOptions: {
			  highlightMouseDown: true,
			  showDataLabels: true
		  },
		  pointLabels: {show: true,formatString:strFormat}
		},
		legend: { show:true, xoffset:10, yoffset:10},
		axesDefaults: {
			tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
			tickOptions: {
			  fontFamily: 'Georgia',
			  fontSize: '10pt',
			  angle: -30
			}
		},
		series:[
            {label:'Sold'},
            {label:'Unsold'}
        ],
		axes: {
		  xaxis: {
			renderer: $.jqplot.CategoryAxisRenderer,
			ticks: tickstr
		  },
		  yaxis: {
			// Don't pad out the bottom of the data range.  By default,
			// axes scaled as if data extended 10% above and below the
			// actual range to prevent data points right on grid boundaries.
			// Don't want to do that here.
			padMin: 0
		  }
		}
	  });
	}
	});
}

$(document).ready(function(){
	//var ticks = new Array();
	dashprog_prog_list();
	dashprog_chartshow();
	$("#dash_pro_date_from").change(function(){
		dashprog_chartshow();
	});
	$("#dash_pro_date_to").change(function(){
		dashprog_chartshow();
	});
	$("#dashprog_listunit a").click(function() {
		$("#dashprog_spanunit").html($(this).html());
		//alert($(this).attr("href"));
		$(".nav .btn-group").removeClass("open");
		dataTypePro = $(this).attr("href");
		dashprog_chartshow();
		return false;
	});
});
</script>   
                                
                           	</div>
                            
                    </div>
				</div>
            </div>
            
	</div>
</div>      