<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

    public $components = array('Paginator', 'Session');

    public $paginate = array(
        'limit' => 25,
        'order' => array(
            'User.username' => 'asc'
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
        // Allow users to register and logout.
        $this->Auth->deny('index');
        $this->Auth->allow('login', 'logout');
    }
    
    /**
     * Show form login
     */
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }
    
    /**
     * action logout
     * @return void
     */
    public function logout() {
        if (!$this->Auth->user()) {
            return $this->redirect(Router::url('/'));
        }
        
        // after calling logout, auth will return a link redirection.
        return $this->redirect($this->Auth->logout());
    }
    
    /**
     * action change pass
     */
    public function change_pass() {
        // check user
        if (!isset($this->Auth->user()['id'])) {
            throw new NotFoundException(__('User not found'));
        }
        
        // update validation password when edit user
        unset($this->User->validate['username']);
        unset($this->User->validate['email']);
        $this->User->validate['password2'] = $this->User->validate['password'];
        $this->User->validate['password']['matchPasswords'] = array(
            'rule' => 'matchPasswords',
            'message' => 'Re-Password does not match'
        );
        
        // execute editing user when the request is put from Form
        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $this->Auth->user()['id'];
            if ($this->User->save($this->request->data)) {
                
                # Reset session auth
                $resetUser = $this->User->findById($this->User->id);
                $resetUser = $resetUser['User'];
                unset($resetUser['password']);
                #$this->Session->renew();
		$this->Session->write(AuthComponent::$sessionKey, $resetUser);
                
                # send mail
                $subject = '[Employee]-Your pass has been changed successful';
                $content = 'New Password: ' . $this->request->data['User']['password'];
                
                $Email = new CakeEmail('smtp');
                $Email->from(array('admin@employee.com' => 'Employee'));
                $Email->to($resetUser['email']);
                $Email->subject($subject);
                $Email->send($content);
                
                $this->Flash->success(__('Your password has been changed.'));
                return $this->redirect(array('action' => 'index'));
            }
            
            $this->Flash->error(__('Unable to change your password.'));
        }
        
        // reset field password if this user do not want to change current pass
        if (!$this->request->data) {
            $user['User']['password'] = '';
            $this->request->data = $user;
        }
    }
    
    public function index() {
        $this->set('users', $this->User->find('all'));
    }
    
    /**
     * Add a new user
     */
    public function add() {
        if ($this->request->is('post')) {
            
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                
                # send mail
                $subject = '[Employee]-Your account has been registed successful';
                'Username: ' . $this->request->data['User']['username']
                    . '. Password: ' . $this->request->data['User']['password'];
                
                $Email = new CakeEmail('smtp');
                $Email->from(array('admin@employee.com' => 'Employee'));
                $Email->to($this->request->data['User']['email']);
                $Email->subject($subject);
                $Email->send($content);
        
                // redirect to page index
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        }
    }
    
    /**
     * Edit a user
     */
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('User not found'));
        }

        // find user
        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException(__('User not found'));
        }
        
        // update validation password when edit user
        unset($this->User->validate['password']);
        $this->User->validate['username']['isUnique']['on'] = 'update';
        $this->User->validate['email']['isUnique']['on'] = 'update';
        
        // execute editing user when the request is put from Form
        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $id;
            
            # check if user do not want to change password
            if (isset($this->request->data['User']['password']) && 
                !$this->request->data['User']['password']
            ) {
                unset($this->request->data['User']['password']);
            }
            
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('Your user has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to update your user.'));
        }
        
        // reset field password if this user do not want to change current pass
        if (!$this->request->data) {
            $user['User']['password'] = '';
            $this->request->data = $user;
        }
    }
    
    public function delete($id = null) {
        if (!$id) {
            throw new NotFoundException(__('User not found'));
        }

        // find user
        $user = $this->User->findById($id);
        
        if (!$user) {
            throw new NotFoundException(__('User not found'));
        }
        
        // check user is loging
        if ($user['User']['id'] == $this->Auth->user()['id']) {
            $this->Flash->error(__('Sorry, you cannot delete yourself'));
            return $this->redirect(array('action' => 'index'));
        }

        if ($this->User->delete($id)) {
            $this->Flash->success(
                __('The post with name: %s has been deleted.', h($user['User']['username']))
            );
        } else {
            $this->Flash->error(
                __('The post with name: %s could not be deleted.', h($user['User']['username']))
            );
        }

        return $this->redirect(array('action' => 'index'));
    }
}
