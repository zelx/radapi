<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Programs Dashbroad';
$this->breadcrumbs=array(
	'Programs Dashbroad',
);
?>
<style>
#ui-datepicker-div {
	width:200px;
	font-size:0.3em;
}
    #toolbar {
        padding: 10px 4px;
    }
</style> 
<div class="row-fluid">
	<div align="center">
     		<div class="row-fluid" align="center">
            	<div class="" align="right" style=" margin-right:2px; margin-top:6px">
					<button id="dash_prog_add" type="button" class="btn btn-primary" style="font-size:0.8em;width:80px" data-loading-text="Loading...">+Program</button>
                    <div id="dash_prog" class="btn-group" data-toggle="buttons-radio" style="font-size:0.8em;margin-left:3px;">
  						
					</div>
                </div>
                <script>
					$("#dash_prog_add").click(function(e){		
						$("#dash_prog").append("<button id='' type='button' class='btn btn-info ui-ams-breakplan'  style='font-size:0.8em;width:80px' data-loading-text='Loading...'></button>");			 
					});
									
                </script>    
            </div>
            <div class="row-fluid" align="center">
            	<div class="span1" align="right" style=" margin-right:2px; margin-top:6px">
					<label for="dateB_title" style="font-size:1em">B:</label>
                </div>
                <div class="span2" align="left" style="margin:2px">
					<?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'my_date_program1',
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
                    ?>	
                </div>
                <div class="span1" align="center" style="margin-top:6px">
					<label for="dateB_to" style="font-size:1em">to</label>
                </div>
                <div class="span2" align="left" style="margin:2px">
           			<?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'my_date_program2',
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
                    ?>		
                </div>
                <div class="span6" align="center" style="margin:2px">
                	<div class="btn-group" data-toggle="buttons-radio">
                      <button type="button" class="btn btn-success">Mon</button>
                      <button type="button" class="btn btn-success">Tue</button>
                      <button type="button" class="btn btn-success">Wed</button>
                      <button type="button" class="btn btn-success">Thu</button>
                      <button type="button" class="btn btn-success">Fri</button>
                      <button type="button" class="btn btn-success">Sat</button>
                      <button type="button" class="btn btn-success">Sun</button>
                    </div>
                </div> 
           	</div>
            
            <div class="row-fluid" style="margin-top:5px">
				<div class="" align="center" >
                	<div class="container"  id="page" style="width:inherit">
                   			<div class="row-fluid">
								<div  align="center" >
                                    <div class="navbar  ">
                                        <div class="navbar-inner">
                                            <ul class="nav">
                                              <li class="active"> <a href="#">All</a> </li>
                                              <li><a href="#">Prime</a></li>
                                              <li><a href="#">Non Prime</a></li>
                                              <li><a href="#">Unsold</a></li>
                                              <li class="divider-vertical"></li>
                                              <li><a href="#">All</a></li>
                                              <li><a href="#">CH7</a></li>
                                              <li><a href="#">Time Rental</a></li>
                                              <li class="divider-vertical"></li>
                                              <div class="btn-group" align="left">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   Agency
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul class="dropdown-menu">
                                                          <li > <a href="#">Agency 1</a></li>
                                                          <li ><a href="#">AGency 2</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="left">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   Client
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul class="dropdown-menu">
                                                          <li > <a href="#">Client 1</a></li>
                                                          <li ><a href="#">Client 2</a></li>  
                                                  </ul>
                                             </div>
                                            </ul>
                                        </div>
                                    </div>
                            	</div>
                         	</div>        
                            <div class="row-fluid">
								<div  align="center"  id="sum_plot"  class="span8">
                            
                                </div>
                                <div  align="center"  id="sum_plot"  class="span4">
                                
                                </div>
                           	</div>
                            
                    </div>
				</div>
            </div>
            
	</div>
</div>                