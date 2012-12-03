<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/img.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/plugins/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/plugins/bootstrap-responsive.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/plugins/sticky.css" />  
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/site.css" />  

  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/plugins/jquery.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/plugins/sticky.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/plugins/bootstrap.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/plugins/jquery.simplemodal.1.4.3.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/IMGBase.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/plugins/spin.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/plugins/jquery.tmpl.js"></script>
   
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<?php
  $urls = array(
    'master' => array('name' => 'Master Lists', 'url' => Yii::app()->request->baseUrl .'/index.php/files/master'),
    'retail' => array('name' => 'Retail Lists', 'url' => Yii::app()->request->baseUrl .'/index.php/files/retail'),
    'login' => array('name' => 'Login/Register', 'url' => Yii::app()->request->baseUrl .'/index.php/site/login'),
    'logout' => array('name' => 'Logout ('.Yii::app()->user->name.')', 'url' => Yii::app()->request->baseUrl .'/index.php/site/logout'),
    'profile' => array('name' => 'My Profile', 'url' => Yii::app()->request->baseUrl .'/index.php/user/profile'),
    'newUser' => array('name' => 'New User', 'url' => Yii::app()->request->baseUrl .'/index.php/user/newUser'),
  );
    
  function createNavLi($index, $urls) {
    $class = (strpos($_SERVER['REQUEST_URI'], $urls[$index]['url'])===0) ? 'active' : '';

    $result = '<li class="'.$class.'"><a href="'.$urls[$index]['url'].'">'.$urls[$index]['name'].'</a></li>';

    return $result;
  }
?>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php echo Yii::app()->request->baseUrl .'/index.php/site/index'; ?>">IMG Excel Parser</a>
      <div class="nav-collapse">
        <ul class="nav pull-right">
        <?php if (Yii::app()->user->isGuest): ?>
          <?php echo createNavLi('login', $urls); ?>
        <?php else: ?> 
          <li class="divider-vertical"></li>
          <li class="dropdown <?php echo ((strpos($_SERVER['REQUEST_URI'], $urls['master']['url'])===0) || (strpos($_SERVER['REQUEST_URI'], $urls['retail']['url'])===0) || (strpos($_SERVER['REQUEST_URI'], $urls['migration']['url'])===0)) ? 'active' : ''; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-file icon-white"></i> Files<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <?php echo createNavLi('master', $urls); ?>
              <?php echo createNavLi('retail', $urls); ?>
            </ul>
          </li>
          <li class="divider-vertical"></li>
          <li class="dropdown <?php echo ((strpos($_SERVER['REQUEST_URI'], $urls['profile']['url'])===0) || (strpos($_SERVER['REQUEST_URI'], $urls['newUser']['url'])===0)) ? 'active' : ''; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> Users<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <?php echo createNavLi('profile', $urls); ?>
              <?php echo createNavLi('newUser', $urls); ?>
            </ul>
          </li>
          <li class="divider-vertical"></li>
          <?php echo createNavLi('logout', $urls); ?>
        <?php endif; ?>
        </ul>
      </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
  </div><!-- /.navbar-inner -->
</div><!-- /.navbar -->
<div class="container" id="page">

<!--
	<div id="header">
		<div id="logo">asd<?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div>

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div>

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?>
	<?php endif?>
-->
	<?php echo $content; ?>

	<div class="clear"></div>

</div><!-- page -->
<input type="text" id="baseURL" style="display:none" value="<?php echo Yii::app()->request->baseUrl .'/index.php'; ?>" />
<div id="popupModal"></div>
</body>
</html>
