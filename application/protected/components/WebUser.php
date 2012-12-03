<?php
// this file must be stored in:
// protected/components/WebUser.php
 
class WebUser extends CWebUser {
 
  // Store model to not repeat query.
  private $_model;
 
  // Load user model.
  protected function getModel()
    {
        if($this->_model===null)
        {
            if($this->id!==null)
                $this->_model=Users::model()->findByPk($this->id);
        }
        return $this->_model;
    }
}
?>
