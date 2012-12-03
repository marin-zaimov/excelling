<?php

/**
 * This is the model class for table "masterLists".
 *
 * The followings are the available columns in table 'masterLists':
 * @property integer $id
 * @property string $filename
 * @property string $date
 * @property integer $userId
 * @property integer $numRows
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class MasterLists extends IMGModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MasterLists the static model class
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
		return 'masterLists';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('filename, date, userId, numRows', 'required'),
			array('userId, numRows', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, filename, date, userId, numRows', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'filename' => 'Filename',
			'date' => 'Date',
			'userId' => 'User',
			'numRows' => 'Num Rows',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('numRows',$this->numRows);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function deleteWithFile()
	{
	  if (file_exists(Yii::app()->params['masterRoot'] . $this->filename)) {
	    unlink(Yii::app()->params['masterRoot'] . $this->filename);
	  }
	  return $this->delete();
	}
}
