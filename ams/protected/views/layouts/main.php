<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->


	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ams.css" />
    <link rel="stylesheet" type="text/css" href="assets/jqplot/jquery.jqplot.css" />
	<link rel="stylesheet" type="text/css" href="css/tooltipster/tooltipster.css" />

    

 
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->




<script language="javascript" type="text/javascript" src="assets/jqplot/jquery.jqplot.js"></script>

<script type="text/javascript" src="assets/jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="assets/jqplot/plugins/jqplot.cursor.js"></script>
<script type="text/javascript" src="assets/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>

<script type="text/javascript" src="assets/jqplot/plugins/jqplot.json2.min.js"></script>
<script type="text/javascript" src="assets/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="assets/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="assets/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="assets/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="assets/jqplot/plugins/jqplot.barRenderer.min.js"></script>

<script type="text/javascript" src="assets/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="assets/jqplot/plugins/jqplot.donutRenderer.min.js"></script>

<script type="text/javascript" src="assets/jqplot/plugins/jqplot.pointLabels.min.js"></script>

<script type="text/javascript" src="assets/datejs/date.js"></script>
<script type="text/javascript" src="assets/jeditable/jquery.jeditable.js"></script>
<script type="text/javascript" src="assets/tooltipster/jquery.tooltipster.js"></script>


</head>

<body>

<div class="container" id="page" style="width:100%;">
<!--
	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div> header -->
<!--
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav" id="myTab">
      <li><a href="#">จัดการผู้ใช้</a></li>
      <li><a href="index.php?r=site/page&view=about#agency">จัดการ Agency</a></li>
      <li><a href="#ads">จัดการโฆษณา</a></li>
      <li><a href="#program">จัดการรายการโทรทัศน์</a></li>
      <li><a href="#quota">จัดการโควต้า</a></li>
      <li><a href="#schedule">จัดการคิว</a></li>
      <li><a href="#package">จัดการแพคเกจ</a></li>
      <li class="divider-vertical"></li>
      <li><a href="#">รายงาน</a></li>
    </ul>

  </div>
</div> -->

<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse', // null or 'inverse'
	'fixed'=>false,
    'brand'=>'AMS',
    'brandUrl'=>'?r=site/index',
    'collapse'=>true,
	'htmlOptions'=>array( 'style'=>'margin-bottom:0px;'), // requires bootstrap-responsive.css
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Dash Board', 'url'=>array('/site/page', 'view'=>'dashboard')),
                array('label'=>'Management', 'url'=>array('/site/ams')),
                array('label'=>'Administrator', 'url'=>'#', 'items'=>array(
                    array('label'=>'Backup', 'url'=>'#'),
                    array('label'=>'Restore', 'url'=>'#'),
                    array('label'=>'Maintainance Mode', 'url'=>'#'),
                    '---',
                    array('label'=>'Monitor'),
                    array('label'=>'SNMP Setup', 'url'=>'#'),
                    array('label'=>'SNMP Status', 'url'=>'#'),
                )),
            ),
        ),
	

        '<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
    		array('url'=>Yii::app()->getModule('user')->loginUrl, 'label'=>Yii::app()->getModule('user')->t("Login"), 'visible'=>Yii::app()->user->isGuest),
				array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Register"), 'visible'=>Yii::app()->user->isGuest),
				array('url'=>Yii::app()->getModule('user')->profileUrl, 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
				array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>Yii::app()->getModule('user')->t("Logout").' ('.Yii::app()->user->name.')', 'visible'=>!Yii::app()->user->isGuest),
				//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Support', 'url'=>array('/site/page', 'view'=>'support')),
                array('label'=>'Help', 'url'=>'#', 'items'=>array(
                    array('label'=>'Contact', 'url'=>array('/site/contact')),
                    array('label'=>'Send EMail', 'url'=>'#'),
                    array('label'=>'Something else here', 'url'=>'#'),
                    '---',
                    array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
                )),
            ),
        ),
    ),
)); ?>

      

<!--
	<div id="mainmenu">

	</div> mainmenu -->
   

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap-tab.js"></script>
    <script src="assets/bootstrap/js/bootstrap-button.js"></script>   
    
</div><!-- page -->
</body>
</html>
