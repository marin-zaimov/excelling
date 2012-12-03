<?php

/**
 * This is the model class for table "retailLists".
 *
 * The followings are the available columns in table 'retailLists':
 * @property integer $id
 * @property string $filename
 * @property integer $numRows
 * @property string $dateUploaded
 * @property integer $userId
 * @property string $status
 *
 * The followings are the available model relations:
 * @property RetailEntries[] $retailEntries
 * @property Users $user
 * @property UploadListEntries $uploadListEntries
 */
class RetailLists extends IMGModel
{
  public $retailStatuses = array(
      'UPLOADED' => 'Uploaded',
      'AUTO' => 'Auto run',
      'COMPLETE' => 'Complete',
  );
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RetailLists the static model class
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
		return 'retailLists';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('filename, numRows, dateUploaded, userId', 'required'),
			array('numRows, userId', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>255),
			array('status', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, filename, numRows, dateUploaded, userId, status', 'safe', 'on'=>'search'),
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
			'retailEntries' => array(self::HAS_MANY, 'RetailEntries', 'retailListId'),
			'user' => array(self::BELONGS_TO, 'Users', 'userId'),
			'uploadListEntries' => array(self::HAS_MANY, 'UploadListEntries', 'retailListId'),
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
			'numRows' => 'Num Rows',
			'dateUploaded' => 'Date Uploaded',
			'userId' => 'User',
			'status' => 'Status',
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
		$criteria->compare('numRows',$this->numRows);
		$criteria->compare('dateUploaded',$this->dateUploaded,true);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function removeWithFile()
	{
	  foreach ($this->retailEntries as $m) {
		  $m->delete();
		}
		foreach ($this->uploadListEntries as $m) {
		  $m->delete();
		}
		if (file_exists(Yii::app()->params['retailRoot'] . $this->filename)) {
		  unlink(Yii::app()->params['retailRoot'] . $this->filename);
		}
		$this->delete();
	}
}
