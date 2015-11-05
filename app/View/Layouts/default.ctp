<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $cakeDescription ?>:
        <?php echo $this->fetch('title'); ?>
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css('/dist/css/bootstrap.min');
    echo $this->Html->css('/dist/css/bootstrap-theme.min');
    echo $this->Html->css('/assets/css/docs.min.css');
    echo $this->Html->css('styles');
    echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js');
    echo $this->Html->script('/dist/js/bootstrap.min.js');
    echo $this->Html->script('app.js');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
</head>
<body>
    
    <nav class="navbar navbar-default">
        <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Employee</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="<?php echo Router::url('/', true) ?>"><i class=" glyphicon glyphicon-home"></i> Home</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class=" glyphicon glyphicon-th-large"></i> Departments <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo Router::url('/departments/add', true) ?>"><i class="glyphicon glyphicon-plus"></i> Add department</a></li>
                  <li><a href="<?php echo Router::url('/departments/index', true) ?>"><i class="glyphicon glyphicon-align-left"></i> All departments</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> Employees <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo Router::url('/employees/add', true) ?>"><i class="glyphicon glyphicon-plus"></i> Add employee</a></li>
                  <li><a href="<?php echo Router::url('/employees/index', true) ?>"><i class="glyphicon glyphicon-align-left"></i> Search for employee</a></li>
                </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
          <ul class="nav navbar-nav navbar-right">
            <?php if (isset($this->Session->read('Auth.User')['id'])) : ?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hello <?php echo $this->Session->read('Auth.User')['username']?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo Router::url('/users/add', true) ?>"><i class="glyphicon glyphicon-plus"></i> Add a new user</a></li>
                  <li><a href="<?php echo Router::url('/users/index', true) ?>"><i class="glyphicon glyphicon-align-left"></i> List all users</a></li>
                </ul>
              </li>
              <li><?php echo $this->Html->link('Logout', '/users/logout'); ?></li>
            <?php else: ?>
              <li><?php echo $this->Html->link('Login', '/users/login'); ?></li>
            <?php endif; ?>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    
    <div id="container">
        <div id="content">
            <?php echo $this->Flash->render(); ?>
            <?php echo $this->fetch('content'); ?>
        </div>
        <div id="footer">
            
        </div>
    </div>
</body>
</html>