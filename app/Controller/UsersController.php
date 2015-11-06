<?php

/**
 * Users Controller
 * 
 * @package         app.Controller
 * @author          Nguyen Van Cong
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController
{

    public $components = array('Paginator', 'Session');
    public $paginate = array(
        'limit' => 5,
        'order' => array(
            'User.username' => 'asc'
        )
    );

    /**
     * Hook before filter
     * @return void 
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        // Deny users in index page. Allow users to login and logout.
        $this->Auth->deny('index');
        $this->Auth->allow('login', 'logout');
    }

    /**
     * Show form login
     */
    public function login()
    {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    /**
     * Action logout
     * @return void
     */
    public function logout()
    {
        if (!$this->Auth->user()) {
            return $this->redirect(Router::url('/'));
        }

        // after calling logout, auth will return a link redirection.
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Action change pass
     * @return void
     */
    public function change_pass()
    {
        // check user
        if (!isset($this->Auth->user()['id'])) {
            throw new NotFoundException(__('User not found'));
        }

        // update validation password when edit user
        unset($this->User->validate['username']);
        unset($this->User->validate['email']);
        $this->User->validate['password2']                  = $this->User->validate['password'];
        $this->User->validate['password']['matchPasswords'] = array(
            'rule'    => 'matchPasswords',
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

                $Email = new CakeEmail('smtp');
                $Email->template('users_change_pass');
                $Email->viewVars(array(
                    'user'     => $resetUser['username'],
                    'password' => $this->request->data['User']['password']
                ));
                $Email->emailFormat(CakeEmail::MESSAGE_HTML);
                $Email->from(array('admin@employee.com' => 'Employee'));
                $Email->to($resetUser['email']);
                $Email->subject($subject);
                $Email->send();

                $this->Flash->success(__('Your password has been changed.'));
                return $this->redirect(array('action' => 'index'));
            }

            $this->Flash->error(__('Unable to change your password.'));
        }

        // reset field password if this user do not want to change current pass
        if (!$this->request->data) {
            if (isset($resetUser)) {
                unset($resetUser['User']['password']);
                $this->request->data = $resetUser;
            }
        }
    }

    /**
     * Show all user
     * @return void
     */
    public function index()
    {
        $this->Paginator->settings = $this->paginate;
        $this->set('users', $this->Paginator->paginate('User'));
    }

    /**
     * Add a new user
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been created'));

                # send mail
                $subject = '[Employee]-Your account has been registed successful';

                $Email = new CakeEmail('smtp');
                $Email->template('users_register');
                $Email->viewVars(array(
                    'user'     => $this->request->data['User']['username'],
                    'password' => $this->request->data['User']['password']
                ));
                $Email->emailFormat(CakeEmail::MESSAGE_HTML);
                $Email->from(array('admin@employee.com' => 'Employee'));
                $Email->to($this->request->data['User']['email']);
                $Email->subject($subject);
                $Email->send();

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
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function edit($id = null)
    {

        // validate user id
        if (!$id) {
            throw new NotFoundException(__('User not found'));
        }

        // find user from user id
        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException(__('User not found'));
        }

        // update validation password when edit user
        unset($this->User->validate['password']);
        $this->User->validate['username']['isUnique']['on'] = 'update';
        $this->User->validate['email']['isUnique']['on']    = 'update';

        // execute editing user when the request is put from Form
        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $id;

            # check if user do not want to change password
            if (isset($this->request->data['User']['password']) &&
                    !$this->request->data['User']['password']
            ) {
                unset($this->request->data['User']['password']);
            }

            # execute. return page index if success. hold form state if falied 
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('Your user has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to update your user.'));
        }

        // reset field password when the from is disapeared
        if (!$this->request->data) {
            unset($user['User']['password']);
            $this->request->data = $user;
        }
    }

    /**
     * Delete a user
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function delete($id = null)
    {

        # throw exception if method is get
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        // validate user id
        if (!$id) {
            throw new NotFoundException(__('User not found'));
        }

        // find user from user id
        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException(__('User not found'));
        }

        // check user is loging
        if ($user['User']['id'] == $this->Auth->user()['id']) {
            $this->Flash->error(__('Sorry, you cannot delete yourself'));
            return $this->redirect(array('action' => 'index'));
        }

        // execute delete
        if ($this->User->delete($id)) {
            $this->Flash->success(
                    __('The user with name: %s has been deleted.', h($user['User']['username']))
            );
        } else {
            $this->Flash->error(
                    __('The user with name: %s could not be deleted.', h($user['User']['username']))
            );
        }

        // redirect to index
        return $this->redirect(array('action' => 'index'));
    }

}
