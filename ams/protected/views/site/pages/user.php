<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - User';
$this->breadcrumbs=array(
	'User',
);
?>
<h1>User</h1>

<p>This is a "static" page. You may change the content of this page
by updating the file <code><?php echo __FILE__; ?></code>.</p>

<!-- Modal -->
<a href="#myModal" role="button" class="btn" data-toggle="modal">What's new</a>
 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">What's new</h3>
  </div>
  <div class="modal-body">
    <p>Reconsign list</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary">Save changes</button>
  </div>
</div>