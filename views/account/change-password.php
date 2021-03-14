<?php
/* @var $this yii\web\View */
use yii\helpers\BaseHtml;
$this->title = "Change Password";
/* @var $passwordChanged boolean */
/* @var $wrongCurrentPassword boolean */
/* @var $newPasswordNotMatch boolean */
/* @var $somethingWentWrong boolean */

?>
<h1>Change Password</h1>
<?php
if($passwordChanged){
    ?>
    <div class="alert alert-success">The password has been successfully updated.</div>
    <?php
}
if($wrongCurrentPassword){
    ?>
    <div class="alert alert-danger">The current password doesn't match our records.</div>
    <?php
}
if($newPasswordNotMatch){
    ?>
    <div class="alert alert-danger">The new password and password confirmation don't match.</div>
    <?php
}
if($somethingWentWrong){
    ?>
    <div class="alert alert-danger">Something went wrong please try to update your password again.</div>
    <?php
}
?>
<?php
echo BaseHtml::beginForm();
?>
<div class="form-group">
<?php
echo BaseHtml::label("Current Password");
echo BaseHtml::passwordInput("current-password","",["class"=>"form-control","required"=>"required"]);
?>
</div>
<div class="form-group">
<?php
echo BaseHtml::label("New Password");
echo BaseHtml::passwordInput("new-password","",["class"=>"form-control","required"=>"required"]);
?>
</div>
<div class="form-group">
<?php
echo BaseHtml::label("New Password Confirmation");
echo BaseHtml::passwordInput("new-password-confirmation","",["class"=>"form-control","required"=>"required"]);
?>
</div>
<?php
echo BaseHtml::submitButton("Save",["class"=>"btn btn-cob"]);
echo BaseHtml::endForm();
