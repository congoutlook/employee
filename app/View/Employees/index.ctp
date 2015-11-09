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

$this->Html->scriptBlock(
    '$(function(){
        $(\'.btn-clear\').click(function(){
            $(this).parent("form").find(\'input[type="text"]\').val(\'\');
            $(this).parent("form").find(\'select\').val(null);
        });
    });',
    array('inline' => false)
);
?>
<div class="container">    
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Employees Manager') ?></h1>   
    <div id="toolbars" class="pull-right">
      <?php if (AuthComponent::user('id')) : ?>
        <?php
        echo $this->Html->link(
            sprintf('<i class="glyphicon glyphicon-plus"></i> %s', __('Add new')),
            array('action' => 'add'),
            array('class' => 'btn btn-default', 'escape' => false)
        );
        ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="panel panel-info">
    <div class="panel-heading">Filter</div>
    <div class="panel-body">
        <?php
        echo $this->Form->create('Filter', array(
            'inputDefaults' => array(
                'class' => 'form-control filter',
                'div' => array('class' => 'form-group'),
                'label' => array('class' => 'sr-only'),
                'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error-message text-danger'))
            ),
            'class' => 'form-inline',
            'url' => array('controller' => 'employees', 'action' => 'index'),
        )) . PHP_EOL;
        echo $this->Form->input('search', array('placeholder' => 'Search...')) . PHP_EOL;        
        echo $this->Form->input('department_id', array('class' => 'form-control', 'options' => $departments, 'empty' => '- - Department - -', 'default' => '')) . PHP_EOL;        
        echo $this->Form->button('<i class="glyphicon glyphicon-search"></i> Filter', array('class' => 'btn btn-info')) . PHP_EOL;        
        echo $this->Form->button('<i class="glyphicon glyphicon-erase"></i> Clear', array('class' => 'btn btn-default btn-clear', 'type' => 'button')) . PHP_EOL;        
        echo $this->Form->end();
        ?>
    </div>
  </div>

  <div id="employee-content">
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
        <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
        <th><?php echo $this->Paginator->sort('department_id', 'Department'); ?></th>
        <th><?php echo __('Job.Title') ?></th>
        <th><?php echo __('Cellphone') ?></th>
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
        <td><?php echo ($item['Department']['name']) ? $item['Department']['name'] : '<span class="text-muted">n/a</span>' ?> </td>
        <td><?php echo $item['Employee']['job_title']; ?> </td>
        <td><?php echo $this->View->formatPhoneNumber($item['Employee']['cellphone']); ?> </td>
        <td>
        <?php if (AuthComponent::user('id')) : ?>
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
          <td colspan="8">
            <?php if(isset($this->params['paging']['Employee']['pageCount']) && $this->params['paging']['Employee']['pageCount'] > 1) : ?>
                <?php echo $this->element('pagination') ?>
            <?php endif; ?>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<?php echo $this->Js->writeBuffer(); ?>