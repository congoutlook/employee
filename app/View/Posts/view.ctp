<?php
/* 
 * Layout view department
 * @package         app.View.Employees
 * @author          Nguyen Van Cong
 */
?>
<?php echo $this->Flash->render('auth'); ?>
<div class="container">
    <div class="row page-header">
        <h1 class="pull-left"><?php echo __('Employee View detail') ?></h1>
        <div id="toolbars" class="pull-right">
          <?php if (AuthComponent::user('id')) : ?>
            <?php
                echo $this->Html->link(
                    sprintf('<i class="glyphicon glyphicon-pencil"></i> %s', __('Edit')),
                    array('action' => 'edit', $this->request->data['Employee']['id']),
                    array('class' => 'btn btn-default', 'escape' => false)
                );
                ?>
                &nbsp;&nbsp;
            <?php endif; ?>
            <?php echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-backward"></i> %s', __('Close')),
                array('action' => 'index'),
                array('class' => 'btn btn-default', 'escape' => false)
            );
            ?>
        </div>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 col-md-4">
                  <img class="img-responsive" src="<?php echo $this->webroot ?>files/<?php echo $this->request->data['Employee']['photo']; ?>" alt="<?php echo $this->request->data['Employee']['name'] ?>'s Photo" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <div class="form-group">
                        <label>Name</label>
                        <p><i class="glyphicon glyphicon-user"></i> <?php echo $this->request->data['Employee']['name'] ?></p>
                    </div>
                    <div class="form-group">
                        <label>Cellphone</label>
                        <p><i class="glyphicon glyphicon-phone"></i> <?php echo $this->Format->phoneNumber($this->request->data['Employee']['cellphone']) ?></p>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <p><i class=" glyphicon glyphicon-send"></i> <?php echo $this->request->data['Employee']['email'] ?></p>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <p><i class="glyphicon glyphicon-th-large"></i> <?php echo $this->request->data['Department']['name'] ?></p>
                    </div>
                    <div class="form-group">
                        <label>Job Title</label>
                        <p><i class="glyphicon glyphicon-flash"></i> <?php echo $this->request->data['Employee']['job_title'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <?php echo $this->Html->link(
                sprintf('<i class="glyphicon glyphicon-backward"></i> %s', __('Close')),
                array('action' => 'index'),
                array('class' => 'btn btn-default', 'escape' => false)
            );
            ?>
        </div>
    </div>
</div>
