<?php

/* 
 * Layout index group
 * @package         app.View.Groups
 * @author          Nguyen Van Cong
 */
?>
<div class="container">    
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Groups Manager') ?></h1>   
    <div id="toolbars" class="pull-right">
      <?php if (AuthComponent::user('id')) : ?>
        <?php
        if ($allowAdd) : 
            echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-plus"></i> %s', __('Add new')),
                array('controller' => 'groups', 'action' => 'add'),
                array('class' => 'btn btn-default', 'escape' => false)
            );
        endif; 
        ?>
      <?php endif; ?>
    </div>
  </div>

  <table class="table table-striped">
    <colgroup>
      <col class="col-md-1">
      <col class="col-md-3">
      <col class="col-md-4">
    </colgroup>
    <tr>
      <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
      <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
      <th><?php echo __('Action') ?></th>
    </tr>
           <?php foreach ($items as $item): ?>
    <tr>
      <td><?php echo $item['Group']['id']; ?> </td>
      <td><?php echo h($item['Group']['name']); ?> </td>
      <td>
        <?php if (AuthComponent::user('id')) : ?>
            <?php
                if ($allowEdit) : 
                    echo $this->Html->link(
                        sprintf('<i class="glyphicon glyphicon-pencil"></i> %s', __('Edit')),
                        array('controller' => 'groups', 'action' => 'edit', $item['Group']['id']),
                        array('escape' => false)
                    );
                endif; 
                ?>
        &nbsp;&nbsp;
                <?php
                if ($allowDelete) : 
                    echo $this->Form->postLink(sprintf('<i class="glyphicon glyphicon-remove"></i> %s', __('Remove')), 
                        array('action' => 'delete', $item['Group']['id']),
                        array('class'=>'', 'escape' => false, 'confirm' => 'Are you sure?'));
                endif; 
                ?>
        &nbsp;&nbsp;
        <?php endif; ?>
        &nbsp;&nbsp;
        <?php
        echo $this->Html->link(
            sprintf('<i class="glyphicon glyphicon-user"></i> %s', __('View users')),
            array('controller' => 'users', 'action' => 'index', 'group_id' => $item['Group']['id']),
            array('escape' => false, 'target' => '_blank')
        );
        ?>
      </td>
    </tr>
        <?php endforeach; ?>
    <tfoot>
      <tr>
        <td colspan="6">
            <?php if(isset($this->params['paging']['Group']['pageCount']) && $this->params['paging']['Group']['pageCount'] > 1) : ?>
                <?php echo $this->element('pagination') ?>
            <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
