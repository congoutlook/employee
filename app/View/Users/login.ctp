<?php
/* 
 * Layout user login
 * @package         app.View.Users
 * @author          Nguyen Van Cong
 */
?>
<div class="container">
    <h1>Login</h1>
    <div class="bs-example">
        <?php echo $this->Flash->render('auth'); ?>
        <?php echo $this->Form->create('User'); ?>
            
            <?php echo $this->Form->input('username', array(
                'div' => 'form-group',
                'class' => 'form-control',
                'placeholder' => 'Username',
            )); ?>
        
            <?php echo $this->Form->input('password', array(
                'div' => 'form-group',
                'class' => 'form-control',
                'placeholder' => 'Password',
            )); ?>
        <?php echo $this->Form->end(array(
            'label' => 'Login',
            'class' => 'btn btn-default',
        )); ?>
        </form>
    </div>
</div>