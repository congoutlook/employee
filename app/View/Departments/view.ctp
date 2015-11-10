<?php

/* 
 * Layout view department
 * @package         app.View.Departments
 * @author          Nguyen Van Cong
 */
?>
<?php echo $this->Flash->render('auth'); ?>
<div class="container">
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Department View detail') ?></h1>
    <div id="toolbars" class="pull-right">
        <?php echo $this->Html->link(
            sprintf('<i class="glyphicon glyphicon-backward"></i> %s', __('Close')),
            array('action' => 'index'),
            array('class' => 'btn btn-default', 'escape' => false)
        );
        ?>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-body">
      <div class="form-group">
        <label>Office Name</label>
        <p><i class="glyphicon glyphicon-th-large"></i> <?php echo $this->request->data['Department']['name'] ?></p>
      </div>
      <div class="form-group">
        <label>Office Phone</label>
        <p><i class="glyphicon glyphicon-phone-alt"></i> <?php echo $this->Format->phoneNumber($this->request->data['Department']['office_phone']) ?></p>
      </div>
    </div>
    <div class="panel-footer">
        <?php echo $this->Html->link(
            sprintf('<i class="glyphicon glyphicon-backward"></i> %s', __('Close')),
            array('action' => 'index'),
            array('class' => 'btn btn-default', 'escape' => false)
        );
        ?>
    </div>
  </div>
</div>
