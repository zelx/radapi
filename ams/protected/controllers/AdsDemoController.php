<?php
/*while(($row=$dataReader->read())!==false) {
		echo $row;
	}
	*/
class AdsDemoController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function japiBreakofday($program,$plan=1,$month=1,$day=0)
    {
		if ($day == 0){
			$connection=Yii::app()->db;
			$sql="SELECT * FROM advertise";
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			return array($rows);
		}else if($day == 1){
			return array(
			'break1' => array('total'=>'120','sum'=>'',
			  'advertise' => array(
			'ad1' => array('name'=>'รถดำนาคูโบต้า','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad2' => array('name'=>'ไบร์วู๊ดมาร์กาเร็ต','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad3' => array('name'=>'กาวซีเมนต์ตราจระเข้','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad4' => array('name'=>'เครื่องเชื่อมเวลโปร','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad5' => array('name'=>'เครื่องเชื่อมเวลโปร','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			'break2' => array('total'=>'180','sum'=>'product1',
			  'advertise' => array(
			'ad1' => array('name'=>'NESCAFE','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad2' => array('name'=>'น้ำมันเครื่องไดเกียว	','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad3' => array('name'=>'เครื่องเชื่อมเวลโปร','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad4' => array('name'=>'ควายทอง','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			'break3' => array('total'=>'180','sum'=>'',
			  'advertise' => array(
			'ad1' => array('name'=>'บีดับเบิ้ลยูเจลลี่','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad2' => array('name'=>'เครื่องเชื่อมเวลโปร','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad3' => array('name'=>'บีดับเบิ้ลยูเจลลี่','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad4' => array('name'=>'เครื่องเชื่อมเวลโปร','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad5' => array('name'=>'คาราบาวแดง1','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			'break4' => array('total'=>'180','sum'=>'',
			  'advertise' => array(
			'ad1' => array('name'=>'คาราบาวแดง2','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			);			
		}else if($day == 2){
			return array(
			'break1' => array('total'=>'180','sum'=>'',
			  'advertise' => array(
			'ad1' => array('name'=>'รถดำนาคูโบต้า','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad2' => array('name'=>'ไบร์วู๊ดมาร์กาเร็ต','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad3' => array('name'=>'กาวซีเมนต์ตราจระเข้','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			'break2' => array('total'=>'180','sum'=>'product1',
			  'advertise' => array(
			'ad1' => array('name'=>'NESCAFE','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad2' => array('name'=>'น้ำมันเครื่องไดเกียว	','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad3' => array('name'=>'ควายทอง','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			'break3' => array('total'=>'180','sum'=>'',
			  'advertise' => array(
			'ad1' => array('name'=>'บีดับเบิ้ลยูเจลลี่','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad2' => array('name'=>'เครื่องเชื่อมเวลโปร','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad3' => array('name'=>'คาราบาวแดง1','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			'break4' => array('total'=>'180','sum'=>'',
			  'advertise' => array(
			'ad1' => array('name'=>'คาราบาวแดง2','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			);
		}else if($day == 3){
			return array(
			'break1' => array('total'=>'150','sum'=>'',
			  'advertise' => array(
			'ad1' => array('name'=>'รถดำนาคูโบต้า','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad2' => array('name'=>'ไบร์วู๊ดมาร์กาเร็ต','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			'break2' => array('total'=>'180','sum'=>'product1',
			  'advertise' => array(
			'ad1' => array('name'=>'NESCAFE','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad3' => array('name'=>'ควายทอง','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			'break3' => array('total'=>'180','sum'=>'',
			  'advertise' => array(
			'ad1' => array('name'=>'บีดับเบิ้ลยูเจลลี่','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
			'ad2' => array('name'=>'เครื่องเชื่อมเวลโปร','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			'break4' => array('total'=>'180','sum'=>'',
			  'advertise' => array(
			'ad1' => array('name'=>'คาราบาวแดง2','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
			);
		}
		/*
        return array(
		'break1' => array('total'=>'180','sum'=>'',
		  'advertise' => array(
		'ad1' => array('name'=>'รถดำนาคูโบต้า','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad2' => array('name'=>'ไบร์วู๊ดมาร์กาเร็ต','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad3' => array('name'=>'กาวซีเมนต์ตราจระเข้','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
		'break2' => array('total'=>'180','sum'=>'product1',
		  'advertise' => array(
		'ad1' => array('name'=>'NESCAFE','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad2' => array('name'=>'น้ำมันเครื่องไดเกียว	','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad3' => array('name'=>'ควายทอง','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
		'break3' => array('total'=>'180','sum'=>'',
		  'advertise' => array(
		'ad1' => array('name'=>'บีดับเบิ้ลยูเจลลี่','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad2' => array('name'=>'เครื่องเชื่อมเวลโปร','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad3' => array('name'=>'คาราบาวแดง1','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
		'break4' => array('total'=>'180','sum'=>'',
		  'advertise' => array(
		'ad1' => array('name'=>'คาราบาวแดง2','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),

		);
		*/
    }
	public function japiTest($required,$optional="abc")
    {
        return array(
		'break1' => array('total'=>'180','sum'=>'',
		  'advertise' => array(
		'ad1' => array('name'=>'รถดำนาคูโบต้า','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad2' => array('name'=>'ไบร์วู๊ดมาร์กาเร็ต','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad3' => array('name'=>'กาวซีเมนต์ตราจระเข้','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
		'break2' => array('total'=>'180','sum'=>'product1',
		  'advertise' => array(
		'ad1' => array('name'=>'NESCAFE','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad2' => array('name'=>'น้ำมันเครื่องไดเกียว	','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad3' => array('name'=>'ควายทอง','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
		'break3' => array('total'=>'180','sum'=>'',
		  'advertise' => array(
		'ad1' => array('name'=>'บีดับเบิ้ลยูเจลลี่','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad2' => array('name'=>'เครื่องเชื่อมเวลโปร','product'=>'product1','agency'=>'agency1','timelen'=>'15'),
		'ad3' => array('name'=>'คาราบาวแดง1','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),
		'break4' => array('total'=>'180','sum'=>'',
		  'advertise' => array(
		'ad1' => array('name'=>'คาราบาวแดง2','product'=>'product1','agency'=>'agency1','timelen'=>'15'),)),

		);
    }
	public function japiDbBreakDemo($required,$optional="abc")
    {
	}
    public function actions()
    {
        return array(
            'japi'=>'JApi',
        );
    }
}