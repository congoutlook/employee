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
            <?php
            echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-plus"></i> %s', __('Add new')),
                '/departments/add',
                array('class' => 'btn btn-default', 'escape' => false)
            );
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
                <?php
                echo $this->Html->link(
                    sprintf('<i class="glyphicon glyphicon-pencil"></i> %s', __('Edit')),
                    '/departments/edit/' . $item['Department']['id'],
                    array('escape' => false)
                );
                ?>
                &nbsp;&nbsp;
                <?php
                echo $this->Html->link(
                    sprintf('<i class="glyphicon glyphicon-remove"></i> %s', __('Remove')),
                    '/departments/delete/' . $item['Department']['id'],
                    array('escape' => false, 'onclick' => 'return app.confirmActionDelete(\'Department: '.$item['Department']['name'].'\')')
                );
                ?>
                &nbsp;&nbsp;
                <?php
                echo $this->Html->link(
                    sprintf('<i class="glyphicon glyphicon-eye-open"></i> %s', __('View')),
                    '/departments/view/' . $item['Department']['id'],
                    array('escape' => false)
                );
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <tfoot>
            <tr>
                <td colspan="5">
                    <ul class="pagination pull-right">
                    <?php
                        echo $this->Paginator->first('&laquo;', array('tag' => 'li', 'title' => __('First page'), 'escape' => false));
                        echo $this->Paginator->prev('&lsaquo;', array('tag' => 'li',  'title' => __('Previous page'), 'disabledTag' => 'span', 'escape' => false), null, array('tag' => 'li', 'disabledTag' => 'span', 'escape' => false, 'class' => 'disabled'));
                        echo $this->Paginator->numbers(array('separator' => false, 'tag' => 'li', 'currentTag' => 'span', 'currentClass' => 'active'));
                        echo $this->Paginator->next('&rsaquo;', array('tag' => 'li', 'disabledTag' => 'span', 'title' => __('Next page'), 'escape' => false), null, array('tag' => 'li', 'disabledTag' => 'span', 'escape' => false, 'class' => 'disabled'));
                        echo $this->Paginator->last('&raquo;', array('tag' => 'li', 'title' => __('First page'), 'escape' => false));
                    ?>
                    </ul>
                </td>
            </tr>
        </tfoot>
    </table>
</div>