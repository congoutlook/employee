<?php

/* 
 * Layout edit department
 * @package         app.View.Departments
 * @author          Nguyen Van Cong
 */

$photo = isset($this->request->data['Post']['photo']) ? $this->webroot.'/files/'.$this->request->data['Post']['photo'] : $this->webroot.'/img/nophoto.jpg';
echo $this->Html->script('/js/editors/tinymce/tinymce.min');
?>
<script type="text/javascript">
    tinymce.init({
        selector: "#PostFulltext"
    });
</script>

<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('Post', array(
    'inputDefaults' => array(
        'class' => 'form-control',
        'div' => array('class' => 'form-group'),
        'label' => array('class' => 'control-label'),
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error-message text-danger'))
    ),
    'type' => 'file',
));
?>
<div class="container">
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Edit a new Post') ?></h1>
    <div id="toolbars" class="pull-right">
            <?php echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-backward"></i> %s', __('Close')),
                array('action' => 'index'),
                array('class' => 'btn btn-default', 'escape' => false)
            );
            ?>
    </div>
  </div>

  <div class="bs-example">
    <div class="panel panel-info">
      <div class="panel-heading">Main Information</div>
      <div class="panel-body">
        <?php echo $this->Form->input('title', 
            array(
                'placeholder' => 'enter an post\'s name',
            ));
        ?>

        <?php echo $this->Form->input('alias', 
            array(
                'placeholder' => 'post\'s alias',
            ));
        ?>

        <?php echo $this->Form->input('category_id', 
            array(
                'placeholder' => 'post\'s department',
            ));
        ?>

        <?php echo $this->Form->input('photo_upload', 
            array(
                'placeholder' => 'upload post\'s photo',
                'label' => 'Photo',
                'type' => 'file',
                'class' => 'btn btn-info',
                'onchange' => 'app.previewImageUpload(this, \'#UserPhotoPreview\')',
                'before' => $this->Form->input('photo', array('type' => 'hidden',)),
                'between' => '<div class="pull-left">
                    <a href="#">
                      <img id="UserPhotoPreview" class="img-responsive" src="'.$photo.'" alt="Photo">
                    </a>
                  </div>',
                'after' => '<div class="clearfix"></div>'
            ));
        ?>

        <?php echo $this->Form->input('fulltext', 
            array(
                'placeholder' => 'post\'s fulltext',
                'rows' => 20,
            ));
        ?>
      </div>
    </div>
    
    <div class="panel panel-info">
      <div class="panel-heading">Publishing</div>
      <div class="panel-body">
        <?php echo $this->Form->input('created', 
            array(
                'div' => array('class' => 'form-group'),
                'type' => 'text',
                'disabled' => true,
            ));
        ?>

        <?php echo $this->Form->input('publish_up', 
            array(
                'div' => array('class' => 'form-group'),
                'placeholder' => 'post\'s created date',
                'class' => 'form-control',
                'type' => 'text',
                'between' => '<div class="input-group">',
                'after' => '<div class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                  </div></div>',
            ));
        ?>

        <?php echo $this->Form->input('publish_down', 
            array(
                'div' => array('class' => 'form-group'),
                'placeholder' => 'post\'s created date',
                'class' => 'form-control',
                'type' => 'text',
                'between' => '<div class="input-group">',
                'after' => '<div class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                  </div></div>',
            ));
        ?>

        <?php echo $this->Form->input('state', 
            array(
                'label' => 'State',
                'class' => 'form-control', 
                'options' => array(
                    '0' => 'Unpulish',
                    '2' => 'Pending',
                    '1' => 'Publish'
                ), 
                'empty' => false, 
                'default' => 0,
               )
            );
        ?>
      </div>
    </div>

    <div class="panel panel-info">
      <div class="panel-heading">SEOs</div>
      <div class="panel-body">
        <?php echo $this->Form->input('meta_keyword', 
              array(
                  'div' => array('class' => 'form-group'),
              ));
          ?>
        <?php echo $this->Form->input('meta_description', 
              array(
                  'div' => array('class' => 'form-group'),
                  'type' => 'textarea',
                  'rows' => 1,
              ));
        ?>
      </div>
    </div>

    <?php echo $this->Form->button('<i class="glyphicon glyphicon-ok"></i> Save', 
        array(
            'class' => 'btn btn-default',
    ));
    ?>
  </div>
</div>
<?php echo $this->Form->end(null); ?>
<?php echo $this->Js->writeBuffer(); ?>