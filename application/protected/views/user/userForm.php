
<?php if ($user->isNewRecord): ?>
  <h3 class="page-header">Create a New User Account</h3>
  <p>To create a new account enter the user information below.</p>
<?php else: ?>
  <h3 class="page-header">Edit your profile</h3>
<?php endif; ?>



<form class="user-form form-horizontal" id="userForm" name="input" action="newUser" method="post">
  <?php if (!$user->isNewRecord): ?>
    <input type="hidden" id="userId" name="User[id]" value="<?php echo $user->id; ?>" />
  <?php endif; ?>
  <div class="control-group">
    <label class="control-label" for="inputEmail">Email</label>
    <div class="controls">
      <input type="text" name="User[email]" value="<?php echo $user->email; ?>" id="inputEmail" placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputPw">Password</label>
    <div class="controls">
      <input type="password" name="User[password]" id="inputPw" placeholder="Password"><br>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputPwConfirm">Confirm Password</label>
    <div class="controls">
      <input type="password" name="User[cPassword]" id="inputPwConfirm" placeholder="Confirm Password">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputFName">First Name</label>
    <div class="controls">
      <input type="text" name="User[firstName]" value="<?php echo $user->firstName; ?>" id="inputFName" placeholder="First Name">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputLName">Last Name</label>
    <div class="controls">
      <input type="text" name="User[lastName]" value="<?php echo $user->lastName; ?>" id="inputLName" placeholder="Last Name">
    </div>
  </div>
  
  <input class="btn btn-primary" type="submit" value="Submit">
<form>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/userManager.js"></script>
