<?php

/* 
 * Layout index user
 * @package         app.View.Users
 * @author          Nguyen Van Cong
 */
$countGroup = count($groups);
$disabled = (!$allowConfig) ? true : false;
?>
<?php echo $this->Form->create('Permission', array(
    'inputDefaults' => array(
        'class' => '',
        'div' => false,
        'label' => false,
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error-message text-danger'))
    )
)); ?>
<div class="container">    
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Users manager') ?></h1>   
    <div id="toolbars" class="pull-right">
      <?php 
        if ($allowConfig) : 
            echo $this->Form->button('<i class="glyphicon glyphicon-ok"></i> Save', array(
                'class' => 'btn btn-default',
            ));
        endif; 
        ?>
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
            <?php 
            $source = $action ? (isset($aclsystem[$logical][$action]) ? $aclsystem[$logical][$action] : array()) : $aclsystem[$logical];
            $checked = (array_search((int)$group['Group']['id'], $source) !== false) ? true : false;
            $idAlias = 'dataPermission' . ucfirst($logical) . ucfirst($action) . $group['Group']['id'];
            ?>
      <td class="text-center">
        <?php
        echo $this->Form->input('checkbox', 
            array(
                'class' => '',
                'empty' => true,
                'type' => 'checkbox',
                'value' => $group['Group']['id'],
                'name' => "data[Permission][$logical][$action][]",
                'id' => $idAlias,
                'checked' => $checked,
                'hiddenField' => false,
                'disabled' => $disabled,
               )
        );
        ?>

      </td>
        <?php endforeach; ?>
      <td></td>
    </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
  </table>
  <?php 
  if ($allowConfig) : 
    echo $this->Form->button('<i class="glyphicon glyphicon-ok"></i> Save', array(
    'class' => 'btn btn-default',
    )); 
  endif; ?>
</div>
<input type="hidden" name="filter" value="<?php echo $filter ?>" />
<?php echo $this->Form->end(null); ?>