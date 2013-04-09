<form name="register" method="post" action="register">
  Register Form <br>
  <table width="400" border="1" style="width: 400px">
    <tbody>
      <tr>
        <td width="125"> &nbsp;First Name</td>
        <td width="180">
          <input name="FirstName" type="text" size="20">
        </td>
      </tr>
      <tr>
        <td> &nbsp;Last Name</td>
        <td><input name="LastName" type="text" >
        </td>
      </tr>
	  <tr>
        <td> &nbsp;Address</td>
        <td><textarea name="Address" rows="4" cols="40" ></textarea>
        </td>
      </tr>
	        <tr>
        <td> &nbsp;Email</td>
        <td><input name="Email" type="text" >
        </td>
      </tr>
	        <tr>
        <td> &nbsp;Phone</td>
        <td><input name="Phone" type="text" >error
        </td>
      </tr>
	        <tr>
        <td> &nbsp;Mac</td>
        <td><input name="LastName" type="text" value="<?php echo $_POST['mac'];?>">
        </td>
      </tr>

     

    </tbody>
  </table>
  <br>
  <input type="submit" name="Submit" value="Save">
</form>

