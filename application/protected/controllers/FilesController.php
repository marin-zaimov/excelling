<?php

class FilesController extends Controller
{

  public $masterHeaders = array (
     "Retailer Code",
     "Retailer Name",
     "City",
     "State",
     "ZIP",
     "Distribution Channel"
  );
  
  public $retailHeaders = array (
     'Customer',
     'Ship To Street',
     'Ship To City',
     'Ship To State',
     'Ship To Zip',
     'Customer Type'
  );
  
  public $migrationHeaders = array(
      'Retailer Code',
      'COMPANY',
      'STORE NUMBER',
      'RETAILER TYPE',
      'SUB RETAILER',
      'First Name',
      'Last Name',
      'ADDRESS',
      'ADDRESS2',
      'CITY',
      'ST',
      'ZIP',
      'COUNTRY',
      'PHONE',
      'FAX',
      'URL',
      'Username',
      'Password'
  );
  
  
  
	public function actionMaster()
	{
	  $currentMaster = Master::model()->findAll(array('limit' => 1));
	  $masterLists = MasterLists::model()->findAll(array('order'=>'id desc'));
	  $currentId = null;
	  if (!empty($currentMaster)) {
	    $currentId = $currentMaster[0]->masterListId;
	  }
	  $numRowsNow = Master::model()->count();
		$this->render('master', array('masterLists' => $masterLists, 'currentMaster' => $currentId, 'numRowsNow' => $numRowsNow));
	}

	public function actionNewMaster()
  {
    $response = new AjaxResponse();
    $uploadedFile = FileHelper::getUploadedFile('masterFileUpload');
    
    $transaction = Users::beginTransaction();
    try {
      
      if ($uploadedFile->extension != '.csv')
      {
        $response->setStatus(false, 'File must be an .csv');
        $response->addMessage('An excel file can be saved as a .csv from the Excel program');
        echo $response->asJson();
        return;
      }
      
      $masterDir = Yii::app()->params['masterRoot'];
      $newPath = $masterDir . $uploadedFile->name;
      
      if ($this->verifyMasterCSVHeaders($uploadedFile->temporaryPath)) {
          if (!is_dir($masterDir)) {
            FileHelper::createAllDirsInPath($masterDir);
          }
          if (!is_writable($masterDir)) {
            $response->setStatus(false, 'Master List file directory is not writable.');
          }
          else {
            $modelData = array(
              'filename' => $uploadedFile->name,
              'date' => date('Y-m-d H:i:s', time()),
              'userId' => Yii::app()->user->id,
              'numRows' => FileHelper::getCSVLength($uploadedFile->temporaryPath),
            );
            $model = MasterLists::createFromArray($modelData);
            if (!$model->save()) {
                $response->setStatus(false, 'Saving file model failed.');
            }
            $newFilename = $model->id .'-'.$uploadedFile->name;
            $fileMoved = move_uploaded_file($uploadedFile->temporaryPath, $masterDir .$newFilename);
            if ($fileMoved) {
              $model->filename = $newFilename;
              if ($model->save()) {
                $response->setStatus(true, 'File uploaded successfully.');
                
                if (!Master::userThisMasterList($model->id)) {
                  $response->setStatus(false, 'Saving individual master list entries failed.');
                }
                
                $responseData = array(
                  'tmpName' => $uploadedFile->temporaryName,
                  'name' => $uploadedFile->name
                );
                $response->setData($responseData);
              }
              else {
                $response->setStatus(false, 'Saving file model failed.');
              }
            }
            else { 
              $response->setStatus(false, 'Moving uploaded file failed.');
            }
          }
      }
      else {
        $response->setStatus(false, 'Master List headers must be of the format {Retailer Code,Retailer Name,City,State,ZIP,Distribution Channel}.');
      }
      
      if ($response->getStatus()) {
        $transaction->commit();
      }
      else {
        $transaction->rollback();
      }
    }
    catch (Exception $ex) {
      $transaction->rollback();
      if ($ex->getCode() == 23000) {
        $split = explode( "'", $ex->getMessage());
        $response->setStatus(false, 'You have duplicate codes in your master list. Please fix this.');
      }
      $response->setStatus(false, 'Duplicate Code: ' .$split[1]);
    }
    echo $response->asJson();
    return;
  }
  
  public function actionUseThisMaster()
  {
    
    $id = $_POST['id'];
    $response = new AjaxResponse();
    $transaction = Users::beginTransaction();
        
    if (Master::userThisMasterList($id)) {
      $response->setStatus(true, 'The selected Master List will now be used.');
      $transaction->commit();
    }
    else {
      $response->setStatus(false, 'Saving individual master list entries failed.');
      $transaction->rollback();
    }
    echo $response->asJson();
    return;
  }
  
  public function actionDownloadMaster()
  {
    //get all models
    $models = Master::model()->findAll();
    
    $data = array();
    $data[] = array_merge($this->masterHeaders,array('Origin'));
    foreach ($models as $m) {
      $attr = $m->attributes;
      unset($attr['masterListId']);
      $data[] = array_values($attr);
    }
    $filename = 'Master-List-Download-'.date('m-d-Y H:i:s').'.csv';
    
    FileHelper::downloadArrayAsCsv($filename, $data, ',', '"', true);
  }
  
  public function actionDownloadRetail()
  {
    $listId = $_GET['retailId'];
    //get all models
    $list = RetailLists::model()->findByPk($listId);

    $models = $list->retailEntries;
    
    $data = array();
    $data[] = array_merge($this->retailHeaders,array('Code'));
    foreach ($models as $m) {
      $attr = $m->attributes;
      unset($attr['id']);
      unset($attr['retailListId']);
      $data[] = array_values($attr);
    }
    $filename = 'Populated-'.$list->filename;
    
    FileHelper::downloadArrayAsCsv($filename, $data, ',', '"', true);
  }
  
  public function actionDownloadMigration()
  {
    $listId = $_GET['retailId'];
    //get all models
    $list = RetailLists::model()->findByPk($listId);

    $models = $list->uploadListEntries;

    $data = array();
    $data[] = array_merge($this->migrationHeaders);
    foreach ($models as $m) {
      $attr = $m->attributes;
      unset($attr['retailListId']);
      unset($attr['id']);
      $data[] = array_values($attr);
    }
    $filename = 'Migration-'.$list->filename;
    
    FileHelper::downloadArrayAsCsv($filename, $data, ',', '"', true);
  }
  
	public function actionRemoveMasters()
	{
		$lists = MasterLists::model()->findAll();
		foreach ($lists as $l) {
		  $l->deleteWithFile();
		}
		Master::model()->deleteAll();
		$this->redirect('master');
	}
	
	public function actionRemoveRetail()
	{
	  $retailId = $_GET['retailId'];
		$list = RetailLists::model()->findByPk($retailId);
		$list->removeWithFile();
		$this->redirect('retail');
	}


	public function actionRetail()
	{
	  $lists = RetailLists::model()->findAll(array('order'=>'id desc'));
		$this->render('retail', array('retailLists' => $lists));
	}
	
	public function actionContinueRetail()
	{
	  $listId = $_POST['listId'];
		$list = RetailLists::model()->findByPk($listId);
		$retailEntries = $list->retailEntries;
		$masterList = Master::model()->findAll();
		
		if ($list->status == 'COMPLETE') {
		  echo 'This list is complete';
		  return;
		}
		if ($list->status == 'UPLOADED') {
		  if(!RetailEntries::addCodes($model->id)) {
        echo 'Error automatically adding codes for identical names and exceptions';
        return;
      }
		}
		
		if ($list->status == 'AUTO') {
		  foreach($retailEntries as $entry) {
		    if (empty($entry->code)) {
		      $similarMasters = array();
		      foreach ($masterList as $masterEntry) {
		        if (ParserHelper::namesArePercentSimilar($entry->customer, $masterEntry->retailerName, 60)) {
		          $similarMasters[] = $masterEntry;
		        }
		      }
		      if (empty($similarMasters)) {
		        if ($entry->generateNewCodeAndSave()) {
		          if ($entry->addMigrationEntry() == false) {
		            echo 'Error while adding migration list entry for: '.$entry->customer;
		            return;
		          }
		          $newMastermodel = $entry->addMasterEntry();
              if ($newMastermodel != false) {
                $masterList[] = $newMastermodel;
              }
              else {
                echo 'Error while adding master list entry for: '.$entry->customer;
		            return;
              }
		        }
		        else {
		          echo 'Error while generating a random code for: '.$entry->customer;
		          return;
		        }
		      }
		      else {
		        $newCode = ParserHelper::generateNewCode($entry->customer);
		        $this->renderPartial('pickSimilar', array('model' => $entry, 'similarMasters' => $similarMasters, 'newCode' => $newCode));
		        return;
		      }
		    }
		  }
		  //completed
		  $list->status = "COMPLETE";
	    $list->save();
		  echo 'All retail list entries have been filled with auto generated codes';
		  return;
		}
	}
	
	public function actionSaveCodeToRetail()
	{
	  $retailId = $_POST['retailId'];
	  $masterId = $_POST['masterId'];
	  $response = new AjaxResponse();
	  $retailEntry = RetailEntries::model()->findByPk($retailId);
    $retailEntry->code = $masterId;
    if ($retailEntry->save()) {
      if ($retailEntry->addMigrationEntry() != false) {
        if (!Master::idExists($masterId)) {
          if ($retailEntry->addMasterEntry() != false) {
            $response->setStatus(true, 'The code ['.$masterId.'] was successfully added to ['.$retailEntry->customer.'].');
          }
          else {
            $response->setStatus(false, 'Saving Master List entry failed.');
          }
        }
        $response->setStatus(true, 'The code ['.$masterId.'] was successfully added to ['.$retailEntry->customer.'].');
      }
      else {
        $response->setStatus(false, 'Saving Migration List entry failed.');
      }
    }
    else {
      $response->setStatus(false, 'Saving the code failed.');
    }
    echo $response->asJson();
    return;
	}
	
	public function actionAutoGenAll()
	{
	  $listId = $_POST['listId'];
	  $response = new AjaxResponse();
	  
		$list = RetailLists::model()->findByPk($listId);
		$retailEntries = $list->retailEntries;
		$masterList = Master::model()->findAll();
		
		foreach($retailEntries as $entry) {
	    if (empty($entry->code)) {
        if ($entry->generateNewCodeAndSave()) {
          if ($entry->addMigrationEntry() == false) {
            $response->setStatus(false, 'Error while adding migration list entry for: '.$entry->customer);
            echo $response->asJson();
            return;
          }
          $newMastermodel = $entry->addMasterEntry();
          if ($newMastermodel != false) {
            $masterList[] = $newMastermodel;
          }
          else {
            $response->setStatus(false, 'Error while adding master list entry for: '.$entry->customer);
            echo $response->asJson();
            return;
          }
        }
        else {
          $response->setStatus(false, 'Error while generating a random code for: '.$entry->customer);
          echo $response->asJson();
           return;
        }
      }
    }
    $response->setStatus(true, 'Added new unique codes for all remaining entries without codes');
	  
    echo $response->asJson();
    return;
	}
	
	public function verifyMasterCSVHeaders($filepath)
	{
	  $handle = fopen($filepath,"r");
	  $data = fgetcsv($handle);
    
    if ($data == $this->masterHeaders) {
      return true;
    }
	  return false;
	}

	public function filters()
  {
    return array( 'accessControl' ); // perform access control for CRUD operations
  }

  public function accessRules()
  {
    return array(
      array('allow', // allow authenticated users to access all actions
        'users'=>array('@'),
      ),
      /*array('allow',
        'actions'=>array(),
        'users'=>array('?'),
      ),*/
      array('deny'),
    );
  }
}
