<?php
class User extends CActiveRecord
{
    public function validatePassword($password)
    {
        return crypt($password,$this->password)===$this->password;
    }
 
    public function hashPassword($password)
    {
        return crypt($password, $this->generateSalt());
    }
}
?>