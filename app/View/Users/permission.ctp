<?php

/* 
 * Layout index user
 * @package         app.View.Users
 * @author          Nguyen Van Cong
 */
$countGroup = count($groups);
?>
<?php echo $this->Form->create('Permission', array(
    'inputDefaults' => array(
        'class' => 'form-control',
        'div' => array('class' => 'form-group'),
        'label' => array('class' => 'control-label'),
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error-message text-danger'))
    )
)); ?>
<div class="container">    
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Users manager') ?></h1>   
    <div id="toolbars" class="pull-right">
      <?php echo $this->Form->button('<i class="glyphicon glyphicon-ok"></i> Save', array(
        'class' => 'btn btn-default',
    )); ?>
    </div>
  </div>


  <table class="table table-striped table-hover table-bordered">
    <tr>
      <th class="col-sm-2"><?php echo __('Permissions') ?></th>
      <?php foreach ($groups as $group): ?>
      <th class="col-sm-1 text-center"><?php echo $group['Group']['name'] ?></th>
      <?php endforeach; ?>
      <th></th>
    </tr>
    <?php foreach ($permissions as $logical => $actions): ?>
    <tr>
      <th colspan="<?php echo $countGroup + 2 ?>"><?php echo $logical ?> <span class="caret"></span></th>
    </tr>
        <?php foreach ($actions as $action): ?>
    <tr>
      <td>
        &nbsp;&nbsp;<?php echo $action ?>
      </td>
        <?php foreach ($groups as $group): ?>
            <?php $checked = isset($aclsystem[$logical][$action][$group['Group']['id']]) ? 'checked="true"' : '' ?>
      <td class="text-center">
        <input <?php echo $checked ?> type="checkbox" name="data[Permissions][<?php echo $logical ?>][<?php echo $action ?>][<?php echo $group['Group']['id'] ?>]" value="<?php echo $group['Group']['id'] ?>" />
      </td>
        <?php endforeach; ?>
      <td></td>
    </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
  </table>
  <?php echo $this->Form->button('<i class="glyphicon glyphicon-ok"></i> Save', array(
    'class' => 'btn btn-default',
)); ?>
</div>
<?php echo $this->Form->end(null); ?>