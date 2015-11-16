<?php
/* 
 * Layout view department
 * @package         app.View.Posts
 * @author          Nguyen Van Cong
 */
?>
<?php echo $this->Flash->render('auth'); ?>
<div class="container">
    <div class="row page-header">
        <h1 class="pull-left"><?php echo $this->request->data['Post']['title'] ?></h1>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <div class="form-group">
                        <p><i class="glyphicon glyphicon-time"></i> <?php echo $this->Time->format($this->request->data['Post']['created'], '%d-%m-%Y %H:%M'); ?></p>
                    </div>
                    <div class="form-group">
                        <p><i class=" glyphicon glyphicon-pencil"></i> <?php echo $this->request->data['Post']['author'] ?></p>
                    </div>
                    <div class="form-group">
                        <p><i class="glyphicon glyphicon-th-large"></i> <?php echo $this->request->data['Category']['name'] ?></p>
                    </div>
                    <div class="form-group">
                        <?php echo $this->request->data['Post']['introtext'] ?>
                        <?php echo $this->request->data['Post']['fulltext'] ?>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                  <img class="img-responsive" src="<?php echo $this->webroot ?>files/<?php echo $this->request->data['Post']['photo']; ?>" alt="<?php echo $this->request->data['Post']['title'] ?>'s Photo" />
                </div>
            </div>
        </div>
    </div>
</div>
