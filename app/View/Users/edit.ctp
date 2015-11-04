<?php
/* 
 * Layout add user
 * @package         app.View.Users
 * @author          Nguyen Van Cong
 */
?>
<?php echo $this->Flash->render('auth'); ?>
<div class="container">
    <div class="row page-header">
        <h1 class="pull-left"><?php echo __('Edit Admin User') ?>: <?php echo $this->request->data['User']['username'] ?></h1>
        <div id="toolbars" class="pull-right">
            <?php echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-backward"></i> %s', __('Close')),
                '/users/index',
                array('class' => 'btn btn-default', 'escape' => false)
            );
            ?>
        </div>
    </div>
    
    <div class="bs-example">
        
        <?php echo $this->Form->create('User', array(
            'inputDefaults' => array(
                'class' => 'form-control',
                'div' => array('class' => 'form-group'),
                'label' => array('class' => 'control-label'),
                'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error-message text-danger'))
            )
        )); ?>
            
            <?php echo $this->Form->input('username', array(
                'placeholder' => 'enter a username',
            )); ?>
        
            <?php echo $this->Form->input('email', array(
                'placeholder' => 'enter an email address',
            )); ?>
        
            <?php echo $this->Form->input('password', array(
                'placeholder' => 'if you DO NOT want to change the current password, DO NOT put anything',
            )); ?>
        
            <?php echo $this->Form->button('<i class="glyphicon glyphicon-ok"></i> Save', array(
                'class' => 'btn btn-default',
            )); ?>
        <?php echo $this->Form->end(null); ?>
        </form>
    </div>
</div>