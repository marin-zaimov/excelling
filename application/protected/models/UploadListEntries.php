<?php

/**
 * This is the model class for table "uploadListEntries".
 *
 * The followings are the available columns in table 'uploadListEntries':
 * @property integer $retailListId
 * @property string $retailCode
 * @property string $company
 * @property string $storeNumber
 * @property string $retailerType
 * @property string $subRetailer
 * @property string $firstname
 * @property string $lastName
 * @property string $address
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property string $phone
 * @property string $fax
 * @property string $url
 * @property string $username
 * @property string $password
 * @property integer $id
 *
 * The followings are the available model relations:
 * @property RetailLists $retailList
 */
class UploadListEntries extends IMGModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UploadListEntries the static model class
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
		return 'uploadListEntries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('retailListId, retailCode, company', 'required'),
			array('retailListId', 'numerical', 'integerOnly'=>true),
			array('retailCode, firstname, lastName, city, state, zip, phone, fax, username', 'length', 'max'=>45),
			array('company, storeNumber, retailerType, subRetailer, address, address2, country, password', 'length', 'max'=>255),
			array('url', 'length', 'max'=>1024),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('retailListId, retailCode, company, storeNumber, retailerType, subRetailer, firstname, lastName, address, address2, city, state, zip, country, phone, fax, url, username, password, id', 'safe', 'on'=>'search'),
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
			'retailListId' => 'Retail List',
			'retailCode' => 'Retail Code',
			'company' => 'Company',
			'storeNumber' => 'Store Number',
			'retailerType' => 'Retailer Type',
			'subRetailer' => 'Sub Retailer',
			'firstname' => 'Firstname',
			'lastName' => 'Last Name',
			'address' => 'Address',
			'address2' => 'Address2',
			'city' => 'City',
			'state' => 'State',
			'zip' => 'Zip',
			'country' => 'Country',
			'phone' => 'Phone',
			'fax' => 'Fax',
			'url' => 'Url',
			'username' => 'Username',
			'password' => 'Password',
			'id' => 'ID',
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

		$criteria->compare('retailListId',$this->retailListId);
		$criteria->compare('retailCode',$this->retailCode,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('storeNumber',$this->storeNumber,true);
		$criteria->compare('retailerType',$this->retailerType,true);
		$criteria->compare('subRetailer',$this->subRetailer,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('id',$this->id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
