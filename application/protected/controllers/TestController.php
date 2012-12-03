<?php

class TestController extends Controller
{


  public function actionTestUnivException()
	{
	  $str = 'Alabama + Univ';
	  $str2 = 'Alabama University';
	  /*$str = ParserHelper::cleanupName($str);
	  $str2 = ParserHelper::cleanupName($str2);
	  var_dump($str);
	  var_dump(strpos($str, 'univ'));
	  var_dump(strpos($str, 'university'));
	  var_dump($str2);
	  var_dump(strpos($str2, 'univ'));
	  var_dump(strpos($str2, 'university'));*/
	  var_dump(ParserHelper::followUnivException($str, $str2));
	}
	
	public function actionHallmarkException()
	{
	  $str = 'my hallmark shop 05869)(&^*%&$';
	  
	  var_dump(ParserHelper::followsHallmarkException($str));
	}
	
	public function actionAceExceptionStart()
	{
	  echo '<pre>';
	  $str = 'Ace Hardware/.,?><eee';
	  $str2 = 'Ace Hardware';
	  $str3 = 'Bace Hardware';
	  $cleanedName = ParserHelper::cleanupName($str2);
	  var_dump($cleanedName);
	  if (ParserHelper::followsAceHardwareException($cleanedName)) {
      var_dump(ParserHelper::getAceHardwareCode());
    }
    else {
      echo 'no';
    }
	}
	
	public function actionTestUnivExceptionAgain()
	{
	  echo '<pre>';
	  $str = 'Univ Warehouse';
	  $str2 = 'University Warehouse';
	  
	  var_dump(ParserHelper::followUnivException($str, $str2));
	}
  
  public function actionLongetCommonSubsequence()
  {
    //$string_1 = 'heyheyhey';
    //$string_2 = 'hiheyhi';
    $string_1 = 'someCr9999999azyName';
    $string_2 = 'somecrazyname99999999';
    var_dump(ParserHelper::get_longest_common_subsequence($string_1, $string_2));
  }
  
  public function actionNamesPercentSimilar()
  {
    
    $first = '1234567890';
    $second = '1231231233';
    var_dump(ParserHelper::namesArePercentSimilar($first, $second, 30));
  }
  
  public function actionSubstr()
  {
    $str = '12345';
    var_dump(substr($str,0,3));
  }
  
  public function actionIdExists()
  {
    $id = 'AAEMBR';
    var_dump(Master::idExists($id));
  }
  
  public function actionStrtoupper()
  {
    var_dump(strtoupper('abc678iop'));
  }
  
  public function actionRandomCode()
  {
    var_dump(ParserHelper::generateRandomCode());
  }
  
  public function actionNewCode()
  {
    var_dump(ParserHelper::generateNewCode('AAEMBR'));
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
