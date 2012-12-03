<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $password
 * @property string $salt
 *
 * The followings are the available model relations:
 * @property UsersRetailFilesRun[] $usersRetailFilesRuns
 */
class Users extends IMGModel
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
			array('email, firstName, lastName, password', 'required'),
			array('email, firstName, lastName, password, salt', 'length', 'max'=>255),
			array('email','email'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, firstName, lastName, password, salt', 'safe', 'on'=>'search'),
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
			'usersRetailFilesRuns' => array(self::HAS_MANY, 'UsersRetailFilesRun', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'password' => 'Password',
			'salt' => 'Salt',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('firstName',$this->firstName,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getByEmail($email) 
	{
		$user = null;
		if(strlen($email) > 0) {
			$user = Users::model()->byEmail($email)->find();
		}
		
		return $user;
	}
      
	// SCOPING METHODS
	public function byEmail($email) 
	{
		$this->getDbCriteria()->addCondition("email = '{$email}'");
		return $this;
	}
	
	public function verifyPassword($pw) 
	{
		$salt = $this->salt;
    $usersPW = $this->password;
		$passwordVerified = false;
		
		if(PasswordHelper::hashPassword($pw, $salt) === $usersPW) {
			$passwordVerified = true;
		}
		
		return $passwordVerified;
	}
}
