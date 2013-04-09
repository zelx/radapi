<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url()?>assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
		padding-top: 80px;
        padding-bottom: 40px;
       background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
		font-size: 22px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
  
  </head>

  <body>

    <div class="container">

      <form class="form-signin" method="post" action="<?=$_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading">Administrator Login</h2>
        <input type="text" class="input-block-level" placeholder="User Name" name="user">
        <input type="password" class="input-block-level" placeholder="Password" name="password">
		<p class="text-error" align=center><?=$message ?></p>
        <button class="btn btn-large btn-primary" type="submit" >Sign in</button> 

		
      </form>

    </div> 

  </body>
</html>
