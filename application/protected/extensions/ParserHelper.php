<?php

class ParserHelper
{
  
  public static function namesAreSame($first, $second)
  {
    $nameOne = self::cleanupName($first);
    $nameTwo = self::cleanupName($second);
    
    return ($nameOne == $nameTwo);
  }
  
  public function stripWhiteSpaces($string) {
	  $result = preg_replace('/\s+/', '',$string);
	  return $result;
	}
	
	public function stripSymbols($string)
	{
	  $result = preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
	  return $result;
	}
	
	public function cleanupName($name)
	{
	  $result = self::replaceAnd($name);
	  $result = self::stripSymbols($result);
    $result = self::stripWhiteSpaces($result);
    $result = strtolower($result);
    return $result;
	}
  
  public function replaceAnd($name)
  {
    $result = str_replace('&', 'and', $name);
    return $result;
  }
  
  
  //University = univ exception
  public function followUnivException($first, $second)
  {
    $nameOne = self::cleanupName($first);
    $nameTwo = self::cleanupName($second);
    
    $nameOne = self::replaceUnivWithUniversity($nameOne);
    $nameTwo = self::replaceUnivWithUniversity($nameTwo);
    
    return ($nameOne == $nameTwo);
  }
  
  public function replaceUnivWithUniversity($str)
  {
    $result = $str;
    $univPos = strpos($str, 'univ');
    $universityPos = strpos($str, 'university');
    if ($univPos !== false) {
      if ($univPos !== $universityPos) {
        $result = str_replace('univ', 'university', $str);
      }
    }
    return $result;
  }
  
  
  //Hallmark exception
  public function followsHallmarkException($str)
  {
    if (strpos($str, 'hallmark') !== false) {
      return true;
    }
    return false;
  }
  
  public function getHallmarkCode()
  {
    return 'HALLMK';
  }
  
  
  //Proimage exception
  public function followsProimageException($str)
  {
    if (strpos($str, 'proimage') !== false) {
      return true;
    }
    return false;
  }
  
  public function getProimageCode()
  {
    return 'PROIMG';
  }
  
  
  //Scheel exception
  public function followsScheelException($str)
  {
    if (strpos($str, 'scheel') !== false) {
      return true;
    }
    return false;
  }
  
  public function getScheelCode()
  {
    return 'SCHEEL';
  }
  
  
  //Ace Hardware exception
  public function followsAceHardwareException($str)
  {
    if (strpos($str, 'acehardware') !== false) {
      return true;
    }
    return false;
  }
  
  public function getAceHardwareCode()
  {
    return 'ACEHRD';
  }
  
  
  //Truevalue exception
  public function followsTruevalueException($str)
  {
    $str = self::cleanupName($str);
    if (strpos($str, 'truevalue') !== false) {
      return true;
    }
    return false;
  }
  
  public function getTruevalueCode()
  {
    return 'TRUEVA';
  }
  
  
  //NebraskaBookCompany exception
  public function followsNebraskaBookCompanyException($str)
  {
    $str = self::cleanupName($str);
    if (strpos($str, 'neebo') !== false || strpos($str, 'nebraskabookcompany') !== false) {
      return true;
    }
    return false;
  }
  
  public function getNebraskaBookCompanyCode()
  {
    return 'NBC';
  }
  
  public function namesArePercentSimilar($first, $second, $percent = 50)
  {
    $ratio = $percent/100;
    $nameOne = self::cleanupName($first);
    $nameTwo = self::cleanupName($second);
    
    $subsequence = self::get_longest_common_subsequence($nameOne, $nameTwo);
    
    $lengthOne = strlen($nameOne);
    $lengthTwo = strlen($nameTwo);
    $lengthSub = strlen($subsequence);
    if ($lengthOne > 0) {
      if ($lengthSub/$lengthOne >= $ratio) {
        return true;
      }
    }
    if ($lengthTwo > 0) {
      if ($lengthSub/$lengthTwo >= $ratio) {
        return true;
      }
    }
    return false;
  }
  
  function get_longest_common_subsequence($string_1, $string_2)
  {
    $string_1_length = strlen($string_1);
    $string_2_length = strlen($string_2);
    $return          = "";

    if ($string_1_length === 0 || $string_2_length === 0)
    {
      // No similarities
      return $return;
    }

    $longest_common_subsequence = array();

    // Initialize the CSL array to assume there are no similarities
    for ($i = 0; $i < $string_1_length; $i++)
    {
      $longest_common_subsequence[$i] = array();
      for ($j = 0; $j < $string_2_length; $j++)
      {
        $longest_common_subsequence[$i][$j] = 0;
      }
    }

    $largest_size = 0;

    for ($i = 0; $i < $string_1_length; $i++)
    {
      for ($j = 0; $j < $string_2_length; $j++)
      {
        // Check every combination of characters
        if ($string_1[$i] === $string_2[$j])
        {
          // These are the same in both strings
          if ($i === 0 || $j === 0)
          {
            // It's the first character, so it's clearly only 1 character long
            $longest_common_subsequence[$i][$j] = 1;
          }
          else
          {
            // It's one character longer than the string from the previous character
            $longest_common_subsequence[$i][$j] = $longest_common_subsequence[$i - 1][$j - 1] + 1;
          }

          if ($longest_common_subsequence[$i][$j] > $largest_size)
          {
            // Remember this as the largest
            $largest_size = $longest_common_subsequence[$i][$j];
            // Wipe any previous results
            $return       = "";
            // And then fall through to remember this new value
          }

          if ($longest_common_subsequence[$i][$j] === $largest_size)
          {
            // Remember the largest string(s)
            $return = substr($string_1, $i - $largest_size + 1, $largest_size);
          }
        }
        // Else, $CSL should be set to 0, which it was already initialized to
      }                       
    }

    // Return the list of matches
      return $return;
  }
  
	public function generateNewCode($name)
	{
	  $letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	  $cleanName = ParserHelper::cleanupName($name);
	  $firstSix = strtoupper(substr($cleanName,0,6));
	  if (!Master::idExists($firstSix)) {
	    return $firstSix;
	  }
	  $firstFive = substr($firstSix,0,5);
	  $firstFour = substr($firstSix,0,4);
	  $firstThree = substr($firstSix,0,3);
	  if (!Master::idExists($firstFive)) {
	    return $firstFive;
	  }
	  else if (!Master::idExists($firstFour)) {
	    return $firstFour;
	  }
	  if (!Master::idExists($firstThree)) {
	    return $firstThree;
	  }
	  else {
	    $code = $firstSix;
	    foreach ($letters as $letter) {
        if (!Master::idExists($firstFive.$letter)) {
          return $firstFive.$letter;
	      }
      }
      foreach ($letters as $sixthLetter) {
        $code[5] = $sixthLetter;
        foreach ($letters as $fifthLetter) {
          $code[4] = $fifthLetter;
          if (!Master::idExists($code)) {
            return $code;
	        }
        }
      }
      foreach ($letters as $sixthLetter) {
        $code[5] = $sixthLetter;
        foreach ($letters as $fifthLetter) {
          $code[4] = $fifthLetter;
          foreach ($letters as $fourthLetter) {
            $code[3] = $fourthLetter;
            if (!Master::idExists($code)) {
              return $code;
	          }
	        }
        }
      }
      
      do {
        $randomCode = self::generateRandomCode();
      } while (Master::idExists($randomCode));
      
      return $randomCode;
	  }
	}
	
	public function generateRandomCode()
	{
	  $letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $code = '';
    for ($i = 0; $i < 6; $i++) {
      $code .= $letters[array_rand($letters)];
    }
    return $code;
	}
  

}


