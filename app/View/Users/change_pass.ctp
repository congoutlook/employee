<?php
/* 
 * Layout change pass user
 * @package         app.View.Users
 * @author          Nguyen Van Cong
 */
?>
<?php echo $this->Flash->render('auth'); ?>
<div class="container">
    <div class="row page-header">
        <h1 class="pull-left"><?php echo __('Change password') ?></h1>
        <div id="toolbars" class="pull-right"></div>
    </div>
    
    <div class="bs-example">
        
        <?php echo $this->Form->create('User', array(
            'inputDefaults' => array(
                'class' => 'form-control',
                'div' => array('class' => 'form-group'),
                'label' => array('class' => 'control-label'),
                'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error-message text-danger'))
            ),
            'type' => 'POST',
        ));
        ?>
            
            <?php echo $this->Form->input('password', 
                array(
                    'placeholder' => 'enter a new password',
                ));
            ?>
        
            <?php echo $this->Form->input('password', 
                array(
                    'label' => 'Re-password',
                    'placeholder' => 'password again',
                    'name' => 'data[User][password2]',
                    'id' => 'UserPassword2',
                ));
            ?>
        
            <?php echo $this->Form->button('<i class="glyphicon glyphicon-ok"></i> Change', 
                array(
                    'class' => 'btn btn-default',
            ));
            ?>
        <?php echo $this->Form->end(null); ?>
        </form>
    </div>
</div>
