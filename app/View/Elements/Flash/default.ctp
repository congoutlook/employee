<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div id="<?php echo h($key) ?>Message" class="<?php echo h($class) ?> alert alert-info container"><?php echo h($message) ?></div>
