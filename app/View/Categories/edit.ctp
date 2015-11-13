<?php

/* 
 * Layout edit category
 * @package         app.View.Categories
 * @author          Nguyen Van Cong
 */
?>
<?php echo $this->Flash->render('auth'); ?>
<div class="container">
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Edit a Category') ?></h1>
    <div id="toolbars" class="pull-right">
        <?php echo $this->Html->link(
            sprintf('<i class="glyphicon glyphicon-backward"></i> %s', __('Close')),
            array('action' => 'index'),
            array('class' => 'btn btn-default', 'escape' => false)
        );
        ?>
    </div>
  </div>

  <div class="bs-example">        
        <?php echo $this->Form->create('Category', array(
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
                    'placeholder' => 'enter a category\'s name',
                ));
            ?>

            <?php echo $this->Form->input('parent_id', 
                array(
                    'label' => 'Parent Category',
                    'class' => 'form-control', 
                    'options' => $parents, 
                    'empty' => false,
                    'size' => 15,
                   )
                );
            ?>

            <?php echo $this->Form->button('<i class="glyphicon glyphicon-ok"></i> Save', 
                array(
                    'class' => 'btn btn-default',
            ));
            ?>

        <?php echo $this->Form->end(); ?>

    </form>
  </div>
</div>
