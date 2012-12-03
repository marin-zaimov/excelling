<?php

/**
 * This is the model class for table "retailEntries".
 *
 * The followings are the available columns in table 'retailEntries':
 * @property string $customer
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $customerType
 * @property string $code
 * @property integer $id
 * @property integer $retailListId
 *
 * The followings are the available model relations:
 * @property RetailLists $retailList
 */
class RetailEntries extends IMGModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RetailEntries the static model class
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
		return 'retailEntries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('retailListId', 'required'),
			array('retailListId', 'numerical', 'integerOnly'=>true),
			array('customer, street', 'length', 'max'=>255),
			array('city, state, customerType, code', 'length', 'max'=>45),
			array('zip', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer, street, city, state, zip, customerType, code, id, retailListId', 'safe', 'on'=>'search'),
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
			'retailList' => array(self::BELONGS_TO, 'RetailLists', 'retailListId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer' => 'Customer',
			'street' => 'Street',
			'city' => 'City',
			'state' => 'State',
			'zip' => 'Zip',
			'customerType' => 'Customer Type',
			'code' => 'Code',
			'id' => 'ID',
			'retailListId' => 'Retail List',
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

		$criteria->compare('customer',$this->customer,true);
		$criteria->compare('street',$this->street,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('customerType',$this->customerType,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('id',$this->id);
		$criteria->compare('retailListId',$this->retailListId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getZip()
	{
	  return substr($this->zip,0,5);
	}
	
	public static function addFromList($listId)
	{
	  $model = RetailLists::model()->findByPk($listId);
    if (!empty($model)) {
      $filepath = Yii::app()->params['retailRoot'] . $model->filename;
	    $handle = fopen($filepath,"r");
	    //ignore the first row/headers
	    $firstRow = fgetcsv($handle);
      
      $allSaved = true;
      do {
          if ($data[0]) {
            $attributes = array(
			        'customer',
			        'street',
			        'city',
			        'state',
			        'zip',
			        'customerType',
		        );
		        $modelData = array();
            foreach ($data as $index => $value) {
              $modelData[$attributes[$index]] = $value;
            }
              $entryModel = self::createFromArray($modelData);
              $entryModel->retailListId = $listId;
              if (!$entryModel->save()) {
                $allSaved = false;
                /*var_dump($entryModel->attributes);
                var_dump($entryModel->errors);
                var_dump($data);
                die;*/
              }
          }

      } while ($data = fgetcsv($handle));
    }
    
    return $allSaved;
	}
	
	public static function addCodes($listId)
	{
	  $retailList = RetailLists::model()->findByPk($listId);
	  
	  $masterListModel = Master::model()->findAll();
	  $masterList = array();
	  foreach($masterListModel as $m) {
	    $masterList[] = $m->attributes();
	  }
	  $retailEntries = $retailList->retailEntries;
	  
	  $allSaved = self::addCodesForIdenticalEntriesAndExceptions($retailEntries, $masterList);
	  
	  $retailList->status = "AUTO";
	  if (!$retailList->save()) {
	    $allSaved = false;
	  }
	  return $allSaved;
	}
	
	public static function addCodesForIdenticalEntriesAndExceptions($retailEntries, $masterList)
	{
	  $allSaved = true;
	  
	  foreach ($retailEntries as $r) {
	    foreach ($masterList as $m) {
	      //check exceptions
	      $cleanedName = ParserHelper::cleanupName($r->customer);
        if (ParserHelper::followsHallmarkException($cleanedName)) {
          if (!self::setCodeAndSave($r, ParserHelper::getHallmarkCode())) {
            $allSaved = false;
          }
          break;
        }
        else if (ParserHelper::followsProimageException($cleanedName)) {
          if (!self::setCodeAndSave($r, ParserHelper::getProimageCode())) {
            $allSaved = false;
          }
          break;
        }
        else if (ParserHelper::followsScheelException($cleanedName)) {
          if (!self::setCodeAndSave($r, ParserHelper::getScheelCode())) {
            $allSaved = false;
          }
          break;
        }
        else if (ParserHelper::followsAceHardwareException($cleanedName)) {
          if (!self::setCodeAndSave($r, ParserHelper::getAceHardwareCode())) {
            $allSaved = false;
          }
          break;
        }
        else if (ParserHelper::followsTruevalueException($cleanedName)) {
          if (!self::setCodeAndSave($r, ParserHelper::getTruevalueCode())) {
            $allSaved = false;
          }
          break;
        }
        else if (ParserHelper::followsNebraskaBookCompanyException($cleanedName)) {
          if (!self::setCodeAndSave($r, ParserHelper::getNebraskaBookCompanyCode())) {
            $allSaved = false;
          }
          break;
        }
        //check identical names of univ exception
	      else if (ParserHelper::namesAreSame($r->customer, $m->retailerName) ||
	          ParserHelper::followUnivException($r->customer, $m->retailerName)) {
	        //store is campus local
	        if ($m->distributionChannel == 'CAMP') {
	          if ($r->getZip() == substr($m->zip,0,5)) {
	            if (!self::setCodeAndSave($r, $m->retailerCode)) {
                $allSaved = false;
                /*var_dump($r->attributes);
                var_dump($r->errors);
                die;*/
              }
	            break;
	          }
	        }
	        //not campus local
	        else {
	          if (!self::setCodeAndSave($r, $m->retailerCode)) {
              $allSaved = false;
              /*var_dump($r->attributes);
              var_dump($r->errors);
              die;*/
            }
            break;
	        }
	      }
	    }
	  }
    return $allSaved;
	}
	
	public function setCodeAndSave($model, $code)
	{
	  $model->code = $code;
	  return $model->save();
	}
	
	public function generateNewCodeAndSave()
	{
	  $code = ParserHelper::generateNewCode($this->customer);
	  $this->code = $code;
	  return $this->save();
	}
	
	public function addMigrationEntry()
	{
	  $data = array(
			'retailListId' => $this->retailListId,
			'retailCode' => $this->code,
			'company' => $this->customer,
			'retailerType' => $this->customerType,
			'address' => $this->street,
			'city' => $this->city,
			'state' => $this->state,
			'zip' => $this->zip,
		);
	  $model = UploadListEntries::createFromArray($data);
	  if ($model->save()) {
	    return $model;
	  }
	  else {
	    return false;
	  }
	}
	
	public function addMasterEntry()
	{
	  $data = array(
			'retailerCode' => $this->code,
			'retailerName' => $this->customer,
			'city' => $this->city,
			'state' => $this->state,
			'zip' => $this->zip,
			'origin' => $this->retailList->filename,
			'masterListId' => Master::model()->find()->masterListId,
		);
	  $model = Master::createFromArray($data);
	  if ($model->save()) {
	    return $model;
	  }
	  else {
	    return false;
	  }
	}

	
	
}


