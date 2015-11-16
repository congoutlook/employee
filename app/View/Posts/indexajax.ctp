<?php

/* 
 * Layout index employee
 * @package         app.View.Posts
 * @author          Nguyen Van Cong
 */

$this->Paginator->options(
    array(
        'update' => '#employee-content',
        'evalScripts' => true,
    )
);
?>
<table class="table table-striped">
  <colgroup>
    <col class="col-md-1">
    <col class="col-md-2">
    <col class="col-md-2">
    <col class="col-md-2">
    <col class="col-md-2">
    <col class="col-md-3">
  </colgroup>
  <tr>
    <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
    <th><?php echo $this->Paginator->sort('name', 'Title'); ?></th>
    <th><?php echo $this->Paginator->sort('category_id', 'Category'); ?></th>
    <th><?php echo __('Created') ?></th>
    <th><?php echo __('State') ?></th>
    <th><?php echo __('Action') ?></th>
  </tr>
    <?php foreach ($items as $item): ?>
        <?php
        $path = ($item['Post']['photo']) ? $this->webroot . 'files/' . $item['Post']['photo'] : $this->webroot . 'img/nophoto.jpg';
        ?>
  <tr>
    <td><?php echo $item['Post']['id']; ?> </td>
    <td>
      <img class="img-responsive app-photo-list" src="<?php echo $path ?>" alt="<?php echo $item['Post']['title']; ?>'s photo" />
            <?php echo $item['Post']['title']; ?>
    </td>
    <td><?php echo ($item['Category']['name']) ? $item['Category']['name'] : '<span class="text-muted">n/a</span>' ?> </td>
    <td><?php echo $this->Time->format($item['Post']['created'], '%d-%m-%Y %H:%M'); ?> </td>
    <td><?php echo $this->Format->postState($item['Post']['state'], array('bold' => true)) ?> </td>
    <td>
        <?php if (AuthComponent::user('id')) : ?>
            <?php
            echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-pencil"></i> %s', __('Edit')),
                array('action' => 'edit', $item['Post']['id']),
                array('escape' => false)
            );
            ?>
      &nbsp;&nbsp;
            <?php
            echo $this->Form->postLink(sprintf('<i class="glyphicon glyphicon-remove"></i> %s', __('Remove')), 
                array('action' => 'delete', $item['Post']['id']),
                array('class'=>'', 'escape' => false, 'confirm' => 'Are you sure?'));
            ?>
      &nbsp;&nbsp;
        <?php endif; ?>
        <?php
        echo $this->Html->link(
            sprintf('<i class="glyphicon glyphicon-eye-open"></i> %s', __('Preview')),
            array('action' => 'preview', $item['Post']['id']),
            array('escape' => false)
        );
        ?>
    </td>
  </tr>
      <?php endforeach; ?>
  <tfoot>
    <tr>
      <td colspan="7">
            <?php if(isset($this->params['paging']['Post']['pageCount']) && $this->params['paging']['Post']['pageCount'] > 1) : ?>
                <?php echo $this->element('pagination') ?>
            <?php endif; ?>
      </td>
    </tr>
  </tfoot>
</table>
<?php echo $this->Js->writeBuffer(); ?>