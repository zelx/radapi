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
		'Tie-in' =>$this->renderPartial('pages/tiein',null,true),
        
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
