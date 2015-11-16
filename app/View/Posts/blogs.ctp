<?php

/* 
 * Layout index post
 * @package         app.View.Posts
 * @author          Nguyen Van Cong
 */
$this->Paginator->options(
    array(
        'update' => '#posts-content',
        'evalScripts' => true,
    )
);
?>
<div class="container">    
  <div class="row page-header">
    <h1 class="pull-left"><?php echo __('Posts in ') . $category['Category']['name'] ?></h1>    
  </div>

  <div id="posts-content">
    <?php foreach ($items as $item): ?>
        <?php
        $path = ($item['Post']['photo']) ? $this->webroot . 'files/' . $item['Post']['photo'] : $this->webroot . 'img/nophoto.jpg';
        ?>
    <div class="media">
      <div class="media-body">
        <h4 class="media-heading">
            <?php
            echo $this->Html->link(
                $item['Post']['title'],
                array('action' => 'preview', $item['Post']['id']),
                array('escape' => false)
            );
            ?>
        </h4>
        <?php echo $item['Post']['introtext']; ?>
      </div>
    </div>
      <?php endforeach; ?>
      <?php if(isset($this->params['paging']['Post']['pageCount']) && $this->params['paging']['Post']['pageCount'] > 1) : ?>
            <?php echo $this->element('pagination') ?>
        <?php endif; ?>
  </div>
</div>
<?php echo $this->Js->writeBuffer(); ?>