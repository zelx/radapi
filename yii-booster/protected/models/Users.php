<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $UserID
 * @property string $FirstName
 * @property string $LastName
 * @property string $Address
 * @property string $Phone
 * @property string $Email
 * @property string $Mac
 * @property string $RegisDate
 * @property integer $Status
 */
class Users extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('FirstName, LastName, Address, Phone, Email, Mac, RegisDate, Status', 'required'),
			array('Status', 'numerical', 'integerOnly'=>true),
			array('FirstName, LastName, Address, Email', 'length', 'max'=>200),
			array('Email','email'),
			array('Phone', 'length', 'max'=>32),
			array('Phone', 'match', 'pattern'=>'/^([+]?[0-9 ]+)$/'),
			array('Mac', 'length', 'max'=>17),
			array('Mac','unique','message' =>"This MAC address is already in use."),
		
		// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('UserID, FirstName, LastName, Address, Phone, Email, Mac, RegisDate, Status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			 'radcheck'=>array(self::HAS_MANY,'Radcheck',array("id"=>"id"))
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'UserID' => '#',
			'FirstName' => 'First Name',
			'LastName' => 'Last Name',
			'Address' => 'Address',
			'Phone' => 'Phone',
			'Email' => 'Email',
			'Mac' => 'MAC address',
			'RegisDate' => 'Regis Date',
			'Status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('UserID',$this->UserID,true);
		$criteria->compare('FirstName',$this->FirstName,true);
		$criteria->compare('LastName',$this->LastName,true);
		$criteria->compare('Address',$this->Address,true);
		$criteria->compare('Phone',$this->Phone,true);
		$criteria->compare('Email',$this->Email,true);
		$criteria->compare('Mac',$this->Mac,true);
		$criteria->compare('RegisDate',$this->RegisDate,true);
		$criteria->compare('Status',$this->Status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function getExpire()
	{
		
	$criteria=$this->getSearchCriteria();
    $criteria->select='SUM(somecolumn)';
    return $this->commandBuilder->createFindCommand($this->getTableSchema(),$criteria)->queryScalar();
		

	}

}