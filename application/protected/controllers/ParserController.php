<?php

class ParserController extends Controller
{

  public $retailHeaders = array (
     'Customer',
     'Ship To Street',
     'Ship To City',
     'Ship To State',
     'Ship To Zip',
     'Customer Type'
  );
  
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionUploadAndRun()
	{
	  $response = new AjaxResponse();
    $uploadedFile = FileHelper::getUploadedFile('retailFileUpload');
    
    $transaction = Users::beginTransaction();
    try {
    
      if ($uploadedFile->extension != '.csv')
      {
        $response->setStatus(false, 'File must be an .csv');
        $response->addMessage('An excel file can be saved as a .csv from the Excel program');
        echo $response->asJson();
        return;
      }
      $retailDir = Yii::app()->params['retailRoot'];
      $newPath = $masterDir . $uploadedFile->name;
      
      if ($this->verifyRetailCSVHeaders($uploadedFile->temporaryPath)) {
        if (!is_dir($retailDir)) {
            FileHelper::createAllDirsInPath($retailDir);
          }
          if (!is_writable($retailDir)) {
            $response->setStatus(false, 'Retail List file directory is not writable.');
          }
          else {
            //create model
            $modelData = array(
              'filename' => $uploadedFile->name,
              'dateUploaded' => date('Y-m-d H:i:s', time()),
              'userId' => Yii::app()->user->id,
              'numRows' => FileHelper::getCSVLength($uploadedFile->temporaryPath),
            );
            
            $model = RetailLists::createFromArray($modelData);
            if (!$model->save()) {
                $response->setStatus(false, 'Saving file model failed.');
            }
            //move file
            $newFilename = $model->id .'-'.$uploadedFile->name;
            $fileMoved = move_uploaded_file($uploadedFile->temporaryPath, $retailDir .$newFilename);
            
            if ($fileMoved) {
              $model->filename = $newFilename;
              $model->status = 'UPLOADED';
              if ($model->save()) {
                $response->addData('listId', $model->id);
                if (RetailEntries::addFromList($model->id)) {
                  $response->setStatus(true, 'File uploaded successfully');
                  if (RetailEntries::addCodes($model->id)) {
                    $response->addMessage('Codes copied for all identical names');
                    $response->addMessage('Codes copied for all names that follow exceptions');
                  }
                  else {
                    $response->setStatus(false, 'Adding codes for identical names and exceptions failed');
                  }
                }
                else {
                  $response->setStatus(false, 'Saving individual retail list entries failed.');
                }
                
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
        $response->setStatus(false, 'Master List headers must be of the format {Customer, Ship to Street, Ship to City, Ship to State, Ship to Zip, Customer Type}.');
        $response->addMessage('Make sure file has 6 columns and you are creating .csv file with quotes around each field');
      }
      
      if ($response->getStatus()) {
        $transaction->commit();
      }
      else {
        $transaction->rollback();
        if (file_exists($uploadedFile->temporaryPath)) {
          unlink($uploadedFile->temporaryPath);
        }
        if (file_exists($retailDir .$newFilename)) {
          unlink($retailDir .$newFilename);
        }
      }
    }
    catch (Exception $ex) {
      $transaction->rollback();
      $response->setStatus(false, $ex->getMessage());
    }
    echo $response->asJson();
    return;
	}
  
  public function verifyRetailCSVHeaders($filepath)
	{
	  $handle = fopen($filepath,"r");
	  $data = fgetcsv($handle);

    if ($data == $this->retailHeaders) {
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
