<?
class Tvpro extends CActiveRecord {
	
		/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TblUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
		
		public function tableName()
		{
			return 'tv_program';
		}
		
		
		public function search()
		{
			// Warning: Please modify the following code to remove attributes that
			// should not be searched.
		
			$criteria=new CDbCriteria;
			
			//$criteria->compare('username',$this->username,true);
   			//$criteria->compare('email',$this->email,true);

			return new CActiveDataProvider(get_class($this), array(											   
				'criteria'=>$criteria,
				'sort'=>array(
					'defaultOrder'=>'prog_id ASC',
				),
				'pagination'=>array(
					'pageSize'=>5
				),
			));
		}
}
?>