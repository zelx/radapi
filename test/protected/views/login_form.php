/* css code */
#registration_form {
  background: #f0f0f0;
  color: #333;
}
#registration_form input[type="text"],
#registration_form input[type="password"] {
  border: 1px solid #ccc;
  width: 200px;
}
#registration_form input {
  display: block;
  margin-bottom: 8px;
}
#registration_form input[type="text"]:focus,
#registration_form input[type="password"]:focus {
  background: #F4EDB6;
}

<!-- html code -->
<form action="#registration_url" method="post" id="registration_form">
  <fieldset>
    <label for="register_email">email:</label>
    <input type="text" name="email" id="register_email" />
    <label for="register_password">password:</label>
    <input type="password" name="password" id="register_password" />
    <label for="register_password_confirmation">password confirmation:</label>
    <input type="password" name="register_password_confirmation" id="register_password_confirmation" />
    <input type="submit" value="Register" />
  </fieldset>
</form>