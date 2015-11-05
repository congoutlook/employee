<ul class="pagination pull-right">
<?php
    echo $this->Paginator->first('&laquo;', array('tag' => 'li', 'title' => __('First page'), 'escape' => false));
    echo $this->Paginator->prev('&lsaquo;', array('tag' => 'li',  'title' => __('Previous page'), 'disabledTag' => 'span', 'escape' => false), null, array('tag' => 'li', 'disabledTag' => 'span', 'escape' => false, 'class' => 'disabled'));
    echo $this->Paginator->numbers(array('separator' => false, 'tag' => 'li', 'currentTag' => 'span', 'currentClass' => 'active'));
    echo $this->Paginator->next('&rsaquo;', array('tag' => 'li', 'disabledTag' => 'span', 'title' => __('Next page'), 'escape' => false), null, array('tag' => 'li', 'disabledTag' => 'span', 'escape' => false, 'class' => 'disabled'));
    echo $this->Paginator->last('&raquo;', array('tag' => 'li', 'title' => __('First page'), 'escape' => false));
?>
</ul>