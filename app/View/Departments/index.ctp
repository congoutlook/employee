<?php

/* 
 * Layout index department
 * @package         app.View.Departments
 * @author          Nguyen Van Cong
 */
?>
<div class="container">    
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Departments manager') ?></h1>   
    <div id="toolbars" class="pull-right">
      <?php if (isset($this->Session->read('Auth.User')['id'])) : ?>
        <?php
        echo $this->Html->link(
            sprintf('<i class="glyphicon glyphicon-plus"></i> %s', __('Add new')),
            array('controller' => 'departments', 'action' => 'add'),
            array('class' => 'btn btn-default', 'escape' => false)
        );
        ?>
      <?php endif; ?>
    </div>
  </div>

  <table class="table table-striped">
    <colgroup>
      <col class="col-md-1">
      <col class="col-md-5">
      <col class="col-md-3">
      <col class="col-md-3">
    </colgroup>
    <tr>
      <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
      <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
      <th><?php echo __('Office Phone') ?></th>
      <th><?php echo __('Action') ?></th>
    </tr>
           <?php foreach ($departments as $item): ?>
    <tr>
      <td><?php echo $item['Department']['id']; ?> </td>
      <td><?php echo h($item['Department']['name']); ?> </td>
      <td><?php echo h($item['Department']['office_phone']); ?> </td>
      <td>
        <?php if (isset($this->Session->read('Auth.User')['id'])) : ?>
            <?php
                echo $this->Html->link(
                    sprintf('<i class="glyphicon glyphicon-pencil"></i> %s', __('Edit')),
                    array('controller' => 'departments', 'action' => 'edit', 'id' => $item['Department']['id']),
                    array('escape' => false)
                );
                ?>
        &nbsp;&nbsp;
                <?php
                echo $this->Form->postLink(sprintf('<i class="glyphicon glyphicon-remove"></i> %s', __('Remove')), 
                    array('action' => 'delete', $item['Department']['id']),
                    array('class'=>'', 'escape' => false, 'confirm' => 'Are you sure?'));
                ?>
        &nbsp;&nbsp;
        <?php endif; ?>
        <?php
        echo $this->Html->link(
            sprintf('<i class="glyphicon glyphicon-eye-open"></i> %s', __('View')),
            array('controller' => 'departments', 'action' => 'view', 'id' => $item['Department']['id']),
            array('escape' => false)
        );
        ?>
      </td>
    </tr>
        <?php endforeach; ?>
    <tfoot>
      <tr>
        <td colspan="5">
            <?php if(isset($this->params['paging']['Department']['pageCount']) && $this->params['paging']['Department']['pageCount'] > 1) : ?>
                <?php echo $this->element('pagination') ?>
            <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
