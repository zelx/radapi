<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Report';
$this->breadcrumbs=array(
	'Report',
);
?>


<?php
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs'=>array(
        'ตารางออกอากาศจริง' =>$this->renderPartial('pages/report_1',null,true),
        'รายได้โฆษณา' =>$this->renderPartial('pages/report_2',null,true),
        'โควต้าการซื้อโฆษณา' =>$this->renderPartial('pages/report_3',null,true),
	    'คิวโฆษณา' =>$this->renderPartial('pages/report_4',null,true),
        'การซื้อโฆษณาของเอเจนซี่(นาที)' =>$this->renderPartial('pages/report_agency',null,true),
		// array('label'=>'About', 'url'=>array('/site/page', 'view'=>'report_5')),
		
/*		'รายงานที่ 5' =>$this->renderPartial('pages/report_5',null,true),
		'รายงานที่ 6' =>$this->renderPartial('pages/report_6',null,true),
		'รายงานที่ 7' =>$this->renderPartial('pages/report_7',null,true),*/

//        'Render sample' =>$this->renderPartial('pages/account',null,true),
//        'Ajax sample' =>array('ajax'=>array('ajaxContent','view'=>'_content2')),

		
    ),
    'options'=>array(
        'collapsible'=>false,
        'select'=>0,
    ),
    'htmlOptions'=>array(
    ),
));
?>


