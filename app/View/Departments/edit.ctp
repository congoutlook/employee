<?php
/* 
 * Layout edit department
 * @package         app.View.Departments
 * @author          Nguyen Van Cong
 */
?>
<?php echo $this->Flash->render('auth'); ?>
<div class="container">
    <div class="row page-header">
        <h1 class="pull-left"><?php echo __('Edit a new Department') ?></h1>
        <div id="toolbars" class="pull-right">
            <?php echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-backward"></i> %s', __('Close')),
                '/departments/index',
                array('class' => 'btn btn-default', 'escape' => false)
            );
            ?>
        </div>
    </div>
    
    <div class="bs-example">        
        <?php echo $this->Form->create('Department', array(
            'inputDefaults' => array(
                'class' => 'form-control',
                'div' => array('class' => 'form-group'),
                'label' => array('class' => 'control-label'),
                'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error-message text-danger'))
            ),
            'type' => 'POST',
        ));
        ?>
            
            <?php echo $this->Form->input('name', 
                array(
                    'placeholder' => 'enter a department\'s name',
                ));
            ?>
        
            <?php echo $this->Form->input('office_phone', 
                array(
                    'placeholder' => 'enter a department\'s office phone number',
                ));
            ?>
        
            <?php echo $this->Form->button('<i class="glyphicon glyphicon-ok"></i> Save', 
                array(
                    'class' => 'btn btn-default',
            ));
            ?>
        <?php echo $this->Form->end(null); ?>
        </form>
    </div>
</div>