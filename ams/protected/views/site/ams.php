
<script type="text/javascript">// <![CDATA[

<!-- jPreLoader script -->
$(document).ready(function() {
	
	setTimeout(function() {
	//$('#preload').stop().animate({'top':'-750'},200);
	$('#preload').stop().animate({'opacity':'0'},200);
	},6000);
	setTimeout(function() {
	$('#preload').hide();
	},6200);
});
// ]]></script>


<div id=preload style="background-color:#333">
	<section>
		<h1 style="color:#FFF"><p align="center">Loading</p>
        </h1>
		
	</section>
	<div id="floatingCirclesG">
<div class="f_circleG" id="frotateG_01">
</div>
<div class="f_circleG" id="frotateG_02">
</div>
<div class="f_circleG" id="frotateG_03">
</div>
<div class="f_circleG" id="frotateG_04">
</div>
<div class="f_circleG" id="frotateG_05">
</div>
<div class="f_circleG" id="frotateG_06">
</div>
<div class="f_circleG" id="frotateG_07">
</div>
<div class="f_circleG" id="frotateG_08">
</div>
</div>

</div>

<style>

#preload{
	position:fixed;
	top:0px;
	left:0px;
	width:100%;
	height:100%;
	z-index:99999999;
	background-color:#333;}

#preload p{
	margin-top:20%;
}

#floatingCirclesG{
width:128px;
height:128px;
margin-left:auto;
margin-right:auto;
-moz-transform:scale(0.6);
-webkit-transform:scale(0.6);
-ms-transform:scale(0.6);
-o-transform:scale(0.6);
transform:scale(0.6);
}

.f_circleG{
position:absolute;
background-color:#999999;
height:23px;
width:23px;
-moz-border-radius:12px;
-moz-animation-name:f_fadeG;
-moz-animation-duration:1.04s;
-moz-animation-iteration-count:infinite;
-moz-animation-direction:linear;
-webkit-border-radius:12px;
-webkit-animation-name:f_fadeG;
-webkit-animation-duration:1.04s;
-webkit-animation-iteration-count:infinite;
-webkit-animation-direction:linear;
-ms-border-radius:12px;
-ms-animation-name:f_fadeG;
-ms-animation-duration:1.04s;
-ms-animation-iteration-count:infinite;
-ms-animation-direction:linear;
-o-border-radius:12px;
-o-animation-name:f_fadeG;
-o-animation-duration:1.04s;
-o-animation-iteration-count:infinite;
-o-animation-direction:linear;
border-radius:12px;
animation-name:f_fadeG;
animation-duration:1.04s;
animation-iteration-count:infinite;
animation-direction:linear;
}

#frotateG_01{
left:0;
top:52px;
-moz-animation-delay:0.39s;
-webkit-animation-delay:0.39s;
-ms-animation-delay:0.39s;
-o-animation-delay:0.39s;
animation-delay:0.39s;
}

#frotateG_02{
left:15px;
top:15px;
-moz-animation-delay:0.52s;
-webkit-animation-delay:0.52s;
-ms-animation-delay:0.52s;
-o-animation-delay:0.52s;
animation-delay:0.52s;
}

#frotateG_03{
left:52px;
top:0;
-moz-animation-delay:0.65s;
-webkit-animation-delay:0.65s;
-ms-animation-delay:0.65s;
-o-animation-delay:0.65s;
animation-delay:0.65s;
}

#frotateG_04{
right:15px;
top:15px;
-moz-animation-delay:0.78s;
-webkit-animation-delay:0.78s;
-ms-animation-delay:0.78s;
-o-animation-delay:0.78s;
animation-delay:0.78s;
}

#frotateG_05{
right:0;
top:52px;
-moz-animation-delay:0.91s;
-webkit-animation-delay:0.91s;
-ms-animation-delay:0.91s;
-o-animation-delay:0.91s;
animation-delay:0.91s;
}

#frotateG_06{
right:15px;
bottom:15px;
-moz-animation-delay:1.04s;
-webkit-animation-delay:1.04s;
-ms-animation-delay:1.04s;
-o-animation-delay:1.04s;
animation-delay:1.04s;
}

#frotateG_07{
left:52px;
bottom:0;
-moz-animation-delay:1.17s;
-webkit-animation-delay:1.17s;
-ms-animation-delay:1.17s;
-o-animation-delay:1.17s;
animation-delay:1.17s;
}

#frotateG_08{
left:15px;
bottom:15px;
-moz-animation-delay:1.3s;
-webkit-animation-delay:1.3s;
-ms-animation-delay:1.3s;
-o-animation-delay:1.3s;
animation-delay:1.3s;
}

@-moz-keyframes f_fadeG{
0%{
background-color:#5EB4DB}

100%{
background-color:#999999}

}

@-webkit-keyframes f_fadeG{
0%{
background-color:#5EB4DB}

100%{
background-color:#999999}

}

@-ms-keyframes f_fadeG{
0%{
background-color:#5EB4DB}

100%{
background-color:#999999}

}

@-o-keyframes f_fadeG{
0%{
background-color:#5EB4DB}

100%{
background-color:#999999}

}

@keyframes f_fadeG{
0%{
background-color:#5EB4DB}

100%{
background-color:#999999}

}

</style>


<?php
/* @var $this SiteController */

//$this->pageTitle=Yii::app()->name;
?>
<?php
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs'=>array(
		'จัดคิวโฆษณา' =>$this->renderPartial('pages/onairtimetable',null,true),
		'รายการโทรทัศน์' =>$this->renderPartial('pages/tvprogram',null,true),
        'เอเจนซี่' =>$this->renderPartial('pages/agency',null,true),
		'โฆษณา' =>$this->renderPartial('pages/advertise',null,true),
		'แพคเกจ' =>$this->renderPartial('pages/package',null,true),
		'โควต้า' =>$this->renderPartial('pages/quota',null,true),
		'รายงาน' =>$this->renderPartial('pages/report',null,true),
        'ผู้ใช้' =>$this->renderPartial('pages/user',null,true),
		'ไทด์อิน' =>$this->renderPartial('pages/tiein',null,true),
        
		//'นำเข้าการสั่งซื้อ' =>$this->renderPartial('pages/importdata',null,true),
//        'Render sample' =>$this->renderPartial('pages/account',null,true),
//        'Ajax sample' =>array('ajax'=>array('ajaxContent','view'=>'_content2')),
    ),
    'options'=>array(
        'collapsible'=>false,
        'selected'=>0,
    ),
    'htmlOptions'=>array(
		 //'style'=>'width:1240px;'
    ),
));
?>
<!--
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>array(
		array('label'=>'ผู้ใช้', 'url'=>'#'),
		array('label'=>'เอเจนซี่', 'url'=>'#'),
        array('label'=>'โฆษณา', 'url'=>'#', 'active'=>true),
        array('label'=>'รายการโทรทัศน์', 'url'=>'#'),
        array('label'=>'โควต้า', 'url'=>'#'),
		array('label'=>'ตารางออกอากาศ', 'url'=>'#'),
		array('label'=>'แพคเกจ', 'url'=>'#'),
		array('label'=>'รายงาน', 'url'=>'#'),
    ),
)); ?>
-->

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>
