<?php
/* 
 * Layout add user
 * @package         app.View.Users
 * @author          Nguyen Van Cong
 */
?>
<div class="container">    
    <div class="row page-header">
        <h1 class="pull-left"><?php echo __('Users manager') ?></h1>   
        <div id="toolbars" class="pull-right">
            <?php
            echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-plus"></i> %s', __('Add new')),
                '/users/add',
                array('class' => 'btn btn-default', 'escape' => false)
            );
            ?>
        </div>
    </div>
    
    <table class="table table-striped">
        <tr>
            <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
            <th><?php echo $this->Paginator->sort('title', 'Title'); ?></th>
            <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
            <th><?php echo __('Action') ?></th>
        </tr>
           <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['User']['id']; ?> </td>
            <td><?php echo h($user['User']['username']); ?> </td>
            <td><?php echo h($user['User']['email']); ?> </td>
            <td>
                <?php
                echo $this->Html->link(
                    sprintf('<i class="glyphicon glyphicon-pencil"></i> %s', __('Edit')),
                    '/users/edit/' . $user['User']['id'],
                    array('escape' => false)
                );
                ?>
                &nbsp;&nbsp;
                <?php
                echo $this->Html->link(
                    sprintf('<i class="glyphicon glyphicon-remove"></i> %s', __('Remove')),
                    '/users/delete/' . $user['User']['id'],
                    array('escape' => false, 'onclick' => 'return app.confirmActionDelete(\'User: '.$user['User']['username'].'\')')
                );
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>