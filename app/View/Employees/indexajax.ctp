<?php

/* 
 * Layout index employee
 * @package         app.View.Employees
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
    <col class="col-md-2">
  </colgroup>
  <tr>
    <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
    <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
    <th><?php echo __('Job.Title') ?></th>
    <th><?php echo __('Cellphone') ?></th>
    <th><?php echo __('Email') ?></th>
    <th><?php echo __('Action') ?></th>
  </tr>
    <?php foreach ($employees as $item): ?>
        <?php
        $path = ($item['Employee']['photo']) ? $this->webroot . 'files/' . $item['Employee']['photo'] : $this->webroot . 'img/nophoto.jpg';
        ?>
  <tr>
    <td><?php echo $item['Employee']['id']; ?> </td>
    <td>
      <img class="img-responsive app-photo-list" src="<?php echo $path ?>" alt="<?php echo $item['Employee']['name']; ?>'s photo" />
            <?php echo $item['Employee']['name']; ?>
    </td>
    <td><?php echo $item['Employee']['job_title']; ?> </td>
    <td><?php echo $this->View->formatPhoneNumber($item['Employee']['cellphone']); ?> </td>
    <td><?php echo $item['Employee']['email']; ?> </td>
    <td>
        <?php if (isset($this->Session->read('Auth.User')['id'])) : ?>
            <?php
            echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-pencil"></i> %s', __('Edit')),
                array('action' => 'edit', $item['Employee']['id']),
                array('escape' => false)
            );
            ?>
      &nbsp;&nbsp;
            <?php
            echo $this->Form->postLink(sprintf('<i class="glyphicon glyphicon-remove"></i> %s', __('Remove')), 
                array('action' => 'delete', $item['Employee']['id']),
                array('class'=>'', 'escape' => false, 'confirm' => 'Are you sure?'));
            ?>
      &nbsp;&nbsp;
        <?php endif; ?>
        <?php
        echo $this->Html->link(
            sprintf('<i class="glyphicon glyphicon-eye-open"></i> %s', __('View')),
            array('action' => 'view', $item['Employee']['id']),
            array('escape' => false)
        );
        ?>
    </td>
  </tr>
      <?php endforeach; ?>
  <tfoot>
    <tr>
      <td colspan="7">
            <?php if(isset($this->params['paging']['Employee']['pageCount']) && $this->params['paging']['Employee']['pageCount'] > 1) : ?>
                <?php echo $this->element('pagination') ?>
            <?php endif; ?>
      </td>
    </tr>
  </tfoot>
</table>
<?php echo $this->Js->writeBuffer(); ?>