<div class="left_part order_menu">
    <h3 class="no_mt">Мой данные</h3>
<?php 
$const_contracor = 3;
if(UserModule::isAdmin()) {
?>
<?php echo CHtml::link(UserModule::t('Manage User'),array('/user/admin')); ?>
<?php echo CHtml::link(UserModule::t('List User'),array('/user')); ?>
<?php
}
?>
<?php echo CHtml::link(UserModule::t('Profile'),array('/user/profile')); ?>
<?php echo CHtml::link(UserModule::t('Edit'),array('edit')); ?>
<?php echo CHtml::link(UserModule::t('Change password'),array('changepassword')); ?>
    
<?
    $userObject = Yii::app()->getModule('user')->user();
    $profileObject = $userObject->profile;
    $right = $profileObject->user_right;
    if ($right == 3) {?>
                                       <br/><br/>
                                    <?php echo CHtml::link(UserModule::t('Виды деятельности'),array('/user/profile/category')); ?>
                                    <?php echo CHtml::link(UserModule::t('Logout'),array('/user/logout')); ?>
<?                               
                }
?>
    


<div class="order_menu_footer"></div>
</div>