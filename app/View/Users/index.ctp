<?php

/* 
 * Layout index user
 * @package         app.View.Users
 * @author          Nguyen Van Cong
 */
?>
<div class="container">    
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Users manager') ?></h1>   
    <div id="toolbars" class="pull-right">
            <?php
            if ($allowAdd) :
                echo $this->Html->link(
                    sprintf('<i class="glyphicon glyphicon-plus"></i> %s', __('Add new')),
                    array('action' => 'add'),
                    array('class' => 'btn btn-default', 'escape' => false)
                );
            endif; 
            ?>
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
      <th><?php echo $this->Paginator->sort('title', 'Title'); ?></th>
      <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
      <th><?php echo __('Action') ?></th>
    </tr>
           <?php foreach ($users as $item): ?>
    <tr>
      <td><?php echo $item['User']['id']; ?> </td>
      <td><?php echo h($item['User']['username']); ?> </td>
      <td><?php echo h($item['User']['email']); ?> </td>
      <td>
                <?php
                if ($allowEdit) :
                    echo $this->Html->link(
                        sprintf('<i class="glyphicon glyphicon-pencil"></i> %s', __('Edit')),
                        array('action' => 'edit', $item['User']['id']),
                        array('escape' => false)
                    );
                endif; 
                ?>
        &nbsp;&nbsp;
                <?php
                if ($allowDelete) :
                    echo $this->Form->postLink(sprintf('<i class="glyphicon glyphicon-remove"></i> %s', __('Remove')), 
                        array('action' => 'delete', $item['User']['id']),
                        array('class'=>'', 'escape' => false, 'confirm' => 'Are you sure?'));
                endif; 
                ?>
      </td>
    </tr>
        <?php endforeach; ?>
    <tfoot>
      <tr>
        <td colspan="5">
                    <?php if(isset($this->params['paging']['User']['pageCount']) && $this->params['paging']['User']['pageCount'] > 1) : ?>
                        <?php echo $this->element('pagination') ?>
                    <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
