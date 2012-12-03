<?php

class UserController extends Controller
{
	public function actionProfile()
	{
	  $user = Yii::app()->user->model;
		$this->render('userForm', array('user' => $user));
	}
	
	public function actionNewUser()
	{
	  $user = new Users;
		$this->render('userForm', array('user' => $user));
	}
	
	public function actionSaveUser()
	{
	    $userData = $_POST['User'];

	    $response = new AjaxResponse;
		  try 
		  {	        
		    if (!($userData['password'] == $userData['cPassword'])) {
	        $response->setStatus(false, 'Make sure Password and Confirm Password fields are the same.');
	      }
	      
	      if (isset($userData['id'])) {
	        $user = Users::model()->findByPk($userData['id']);
	        if (!empty($userData['password'])) {
            if (PasswordHelper::isValidPasswordPattern($userData['password'])) {
			        $user->salt = PasswordHelper::generateRandomSalt();
			        $user->password = PasswordHelper::hashPassword($userData['password'], $user->salt);
		        }
		        else {
		          $response->setStatus(false, 'Password must contain at least one uppercase, one lowercase, one number, and be at least 9 characters long.');
		        }
	        }
			    unset($userData['password']);
			    $user->setFromArray($userData);
	      }
	      else {
	        
		      $user = Users::createFromArray($userData);
		      
			    if (PasswordHelper::isValidPasswordPattern($userData['password'])) {
				    $user->salt = PasswordHelper::generateRandomSalt();
				    $user->password = PasswordHelper::hashPassword($userData['password'], $user->salt);
			    }
			    else {
			      $response->setStatus(false, 'Password must contain at least one uppercase, one lowercase, one number, and be at least 9 characters long.');
			    }
			  }
			  $messages = $response->getMessages();
			  if (empty($messages)) {
		      if ($user->save()) {
	          $response->setStatus(true, 'User saved successfully');
		      }
		      else {
	          $response->setStatus(false, $user->errors);
		      }
		    }
		  }
		  catch (Exception $ex) {
			  $response->setStatus(false, 'User could not be saved.');
		  }
		  echo $response->asJson();
		  return;
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
