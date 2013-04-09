<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name . ' - Dash Board';
$this->breadcrumbs=array(
	'Dash Board',
);
?>
<?php
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs'=>array(
        'SUMMARY' =>$this->renderPartial('pages/dash_summary',null,true),
        'COMPARE' =>$this->renderPartial('pages/dash_compare',null,true),
        'PROGRAMS' =>$this->renderPartial('pages/dash_program',null,true),
        'AGENCY' =>$this->renderPartial('pages/dash_agency',null,true),
		//'RATING' =>$this->renderPartial('pages/dash_rating',null,true),

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
