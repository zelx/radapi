<?php

print '<html>
  <head>
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
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

      <form class="form-signin">
        <h2 class="form-signin-heading">Please Login</h2>
		<input type="hidden" name="chal" value="'.$_GET['challenge'].'">
       <input type="hidden" name="uamip" value="'.$_GET['uamip'].'">
       <input type="hidden" name="uamport" value="'.$_GET['uamport']. '">
       <input type="hidden" name="userurl" value="'.$_GET['userurl']. '">
       <input type="text" class="input-block-level" name="username" placeholder="User Name">
       <input type="password" class="input-block-level" name="password" placeholder="Password">
       <button class="btn btn-large btn-primary" type="submit" value="Login">Log in</button>
      </form>

    </div> 

  </body>
</html>'


?>