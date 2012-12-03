<?php

/**
 * This is the model class for table "masterList".
 *
 * The followings are the available columns in table 'masterList':
 * @property string $retailerCode
 * @property string $retailerName
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $distributionChannel
 * @property string $origin
 * @property integer $masterListId
 */
class Master extends IMGModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Master the static model class
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
		return 'masterList';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('retailerCode, retailerName, origin, masterListId', 'required'),
			array('masterListId', 'numerical', 'integerOnly'=>true),
			array('retailerCode, city, state', 'length', 'max'=>45),
			array('retailerName, distributionChannel, origin', 'length', 'max'=>255),
			array('zip', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('retailerCode, retailerName, city, state, zip, distributionChannel, origin, masterListId', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'retailerCode' => 'Retailer Code',
			'retailerName' => 'Retailer Name',
			'city' => 'City',
			'state' => 'State',
			'zip' => 'Zip',
			'distributionChannel' => 'Distribution Channel',
			'origin' => 'Origin',
			'masterListId' => 'Master List',
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

		$criteria->compare('retailerCode',$this->retailerCode,true);
		$criteria->compare('retailerName',$this->retailerName,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('distributionChannel',$this->distributionChannel,true);
		$criteria->compare('origin',$this->origin,true);
		$criteria->compare('masterListId',$this->masterListId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getZip()
	{
	  return substr($this->zip,0,5);
	}
	
	public static function userThisMasterList($listId)
	{
	  Master::model()->deleteAll();
	  $model = MasterLists::model()->findByPk($listId);
    if (!empty($model)) {
	    $filepath = Yii::app()->params['masterRoot'] . $model->filename;
	    $handle = fopen($filepath,"r");
	    //ignore the first row/headers
	    $firstRow = fgetcsv($handle);
      
      $allSaved = true;
	    do {
          if ($data[0]) {
            $attributes = array(
			        'retailerCode',
			        'retailerName',
			        'city',
			        'state',
			        'zip',
			        'distributionChannel',
		        );
		        $modelData = array();
            foreach ($data as $index => $value) {
              $modelData[$attributes[$index]] = $value;
            }
              $entryModel = Master::createFromArray($modelData);
              $entryModel->masterListId = $listId;
              $entryModel->origin = $model->filename;
              if (!$entryModel->save()) {
                $allSaved = false;
                var_dump($entryModel->attributes);
                var_dump($entryModel->errors);
                var_dump($data);
                die;
              }
          }

      } while ($data = fgetcsv($handle));
    }
    
    return $allSaved;
	}
	
	
}


