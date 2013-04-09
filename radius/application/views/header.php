<? if(!$this->session->userdata('username')) :?>
<? redirect(base_url()."index.php/auth/login");?>
<? exit();?>
<? endif; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Radius Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url()?>assets/css/bootstrap.css" rel="stylesheet">
	 <link href="<?php echo base_url()?>assets/css/style.css" rel="stylesheet">
	<script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>
	<script src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script>
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
	  span.tab{
		padding: 0 10px; /* Or desired space*/
		}
    </style>
    <link href="<?php echo base_url()?>assets/css/bootstrap-responsive.css" rel="stylesheet">

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
		  <?php echo anchor('','Wifi Manager','class="brand"');?>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logged in as &raquo; <?=$this->session->userdata('username')?> | <?php echo anchor('auth/logout','Logout','class="navbar-link"');?><span class="tab"></span>
			  
            </p>
       
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Menu</li>
			  <li><?php echo anchor('','Home');?></li>
			  <li><?php echo anchor('auth/logout','Logout');?></li>
			  <li class="nav-header">Radius Manager</li>
              <li><?php echo anchor('user/register','Register');?></li>
              <li><?php echo anchor('user/generate','Generate Users');?></li>
              <li><?php echo anchor('user','User Manager');?></li>
              <li><?php echo anchor('group','Group Manager');?></li>
             
              <li><a href="#">User Online</a></li>
              <li><a href="#">History</a></li>
              <li><a href="#">Statistic</a></li>
              <li><a href="#">Access Point Status</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li class="nav-header">Server Setting</li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
		        <div class="span9">
          <div class="hero-unit">