<?php

/* 
 * Layout index category
 * @package         app.View.Categories
 * @author          Nguyen Van Cong
 */
?>
<div class="container">    
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Categories manager') ?></h1>   
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
    </colgroup>
    <tr>
      <th><?php echo __('ID') ?></th>
      <th><?php echo __('Name') ?></th>
      <th><?php echo __('Action') ?></th>
    </tr>
           <?php foreach ($items as $id => $item): ?>
    <tr>
      <td><?php echo $id ?> </td>
      <td><?php echo $item ?> </td>
      <td>
                <?php
                if ($allowEdit) :
                    echo $this->Html->link(
                        sprintf('<i class="glyphicon glyphicon-pencil"></i> %s', __('Edit')),
                        array('action' => 'edit', $id),
                        array('escape' => false)
                    );
                endif; 
                ?>
        &nbsp;&nbsp;
                <?php
                if ($allowDelete) :
                    echo $this->Form->postLink(sprintf('<i class="glyphicon glyphicon-remove"></i> %s', __('Remove')), 
                        array('action' => 'delete', $id),
                        array('class'=>'', 'escape' => false, 'confirm' => 'Are you sure?'));
                endif; 
                ?>
      </td>
    </tr>
        <?php endforeach; ?>
    <tfoot>
      <tr>
        <td colspan="5">
                    <?php if(isset($this->params['paging']['Category']['pageCount']) && $this->params['paging']['Category']['pageCount'] > 1) : ?>
                        <?php echo $this->element('pagination') ?>
                    <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
