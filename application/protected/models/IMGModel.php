<?php

/**
 * class IMGModel
 */
class IMGModel extends CActiveRecord
{
	public function getLastId()
	{
		return static::model()->lastInserted()->find()->id;
	}

	public function setFromArray($modelDataArray)
	{
		$this->setAttributes($modelDataArray, false);
	}
	
	public function attributes($relationNames = array(), $asObject = true)
	{
		$attributes = parent::getAttributes();
		
		$modelRelations = array();
		
		//get model's relation names
		foreach ($this->relations() as $relationName => $relation) {
			$modelRelations[] = $relationName;
		}
		//add partial relations, if specified
		if (!empty($relationNames) && is_array($relationNames)) {

			foreach ($relationNames as $relationName) {
				if (in_array($relationName, $modelRelations)) {
					$relationAttributes = array();
					$relationModels = $this->$relationName;
					if (is_array($relationModels)) {
						foreach ($relationModels as $model) {
							if ($asObject) {
								$relationAttributes[] = (object) $model->attributes;
							}
							else {
								$relationAttributes[] = $model->attributes;
							}
						}
					}
					else {
						$relationAttributes = $relationModels->attributes;
					}
					if ($asObject) {
						$relationAttributes = $relationAttributes;
					}
					$attributes[$relationName] = $relationAttributes;
				}
				else {
					throw new Exception("The ".get_called_class()." class doesn't have relation [".$relationName ."]");
				}
			}
		}
		//add all relations
		else if ($relationNames === true) {
		
			foreach ($modelRelations as $relationName) {
	
				$relationAttributes = array();
				if (is_array($this->$relationName)) {
					foreach ($this->$relationName as $model) {
						if ($asObject) {
							$relationAttributes[] = (object) $model->attributes;
						}
						else {
							$relationAttributes[] = $model->attributes;
						}
					}
				}
				else {
					$relationAttributes = $this->$relationName->attributes;
				}
				
				if ($asObject) {
					$relationAttributes = $relationAttributes;
				}
				$attributes[$relationName] = $relationAttributes;
			}
			
		}
		
		if ($asObject === true) {
			$attributes = (object) $attributes;
		}
		return $attributes;
	}
	
    public static function getDBTimestamp()
    {
        $sql = "select unix_timestamp(NOW())";
        $cmd = Yii::app()->db->createCommand($sql);
        $results = $cmd->queryColumn();
        return $results[0];
    }
    
	public static function idExists($id)
	{
		$objExists = null;
		try {
			static::getById($id);
			$objExists = true;
		}
		catch (Exception $ex) {
			$objExists = false;
		}
		
		return $objExists;
	}

	// REPOSITORY METHODS ///////
	public static function getAll()
	{
		$allObjs = static::model()->findAll();
		return $allObjs;
	}

	/**
	 *	@return instance of calling class or possibly null when no row found.
	 */
	public static function getById($id, $throwExceptionNoRowFound = true)
	{
		$obj = static::model()->findByPk($id);

		if (!isset($obj) && $throwExceptionNoRowFound) {
			throw new Exception('No ' . get_called_class() . ' found with id: ' . $id);
		}

		return $obj;
	}

	public static function getByIdList(array $idList)
	{
		$objList = static::model()->byIdList($idList)->findAll();
		return $objList;
	}

	// FACTORY METHODS
	public static function createFromArray($data)
	{
		Yii::log("Creating " . get_called_class() . " from array", 'trace');

		$obj = new static();
		$obj->setAttributes($data, false);

		return $obj;
	}
	
	public function beginTransaction()
	{
		return Users::model()->getDbConnection()->beginTransaction();
	}

	// SCOPING METHODS ///////
	public function byIdList($list)
	{
		$obj = new static();
		$obj->getDbCriteria()->addInCondition('id', $list);
		return $obj;
	}

	// SCOPING METHODS ///////
    public function scopes()
    {
          return array(
                'lastInserted' => array(
                      'order' => 'id desc',
					  'limit' => 1
                )
          );
          
    }
	
}
