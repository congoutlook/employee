<?php

/**
 * Groups Controller
 * 
 * @package         app.Controller
 * @author          Nguyen Van Cong
 */
App::uses('AppController', 'Controller');

class GroupsController extends AppController
{

    public $components = array('Paginator', 'Acl');
    public $helpers    = array('Format');
    public $uses       = array('Group', 'Employee');
    public $paginate   = array(
        'limit' => 5,
        'order' => array(
            'Groups.id'   => 'asc',
            'Groups.name' => 'asc'
        )
    );

    /**
     * Hook before filter
     * @return void 
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        // Allow user access to page index, view
        $this->Auth->allow('view');
        
        // acl
        $checkAcl = true;        
        switch ($this->action) {
            case 'add':
                $checkAcl = $this->Acl->check($this->Auth, 'groups', 'add');
                break;
            case 'edit':
                $checkAcl = $this->Acl->check($this->Auth, 'groups', 'edit');
                break;
            case 'delete':
                $checkAcl = $this->Acl->check($this->Auth, 'groups', 'delete');
                break;
            case 'index':
            default:
                $checkAcl = $this->Acl->check($this->Auth, 'groups', 'access');
                break;
        }
        
        if (!$checkAcl) {
            throw new Exception('Access denied');
        }
    }

    /**
     * Show all groups
     * @return void
     */
    public function index()
    {
        $this->Paginator->settings = $this->paginate;
        $groups                    = $this->Paginator->paginate('Group');
        $this->set('items', $groups);
        
        // set acl allow
        $this->set('allowAdd', $this->Acl->check($this->Auth, 'groups', 'add'));
        $this->set('allowEdit', $this->Acl->check($this->Auth, 'groups', 'edit'));
        $this->set('allowDelete', $this->Acl->check($this->Auth, 'groups', 'delete'));
    }

    /**
     * Add a new group
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Group->create();
            if ($this->Group->save($this->request->data)) {
                $this->Flash->success(__('The group has been created'));

                // redirect to page index
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The group could not be saved. Please, try again.')
            );
        }
    }

    /**
     * Edit a group
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function edit($id = null)
    {

        // validate group id
        if (!$id) {
            throw new NotFoundException(__('Group not found'));
        }

        // find group from group id
        $group = $this->Group->getById($id);
        if (!$group) {
            throw new NotFoundException(__('Group not found'));
        }

        // execute editing group when the request is put from Form
        if ($this->request->is(array('post', 'put'))) {
            $this->Group->id = $id;
            # execute. return page index if success. hold form state if falied 
            if ($this->Group->save($this->request->data)) {
                $this->Flash->success(__('Your Group has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }

            $this->Flash->error(__('Unable to update your Group.'));
        }

        // show form
        if (!$this->request->data) {
            $this->request->data = $group;
        }
    }

    /**
     * Delete a group
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

        // validate group id
        if (!$id) {
            throw new NotFoundException(__('Group not found'));
        }

        // find group from group id
        $group = $this->Group->getById($id);
        if (!$group) {
            throw new NotFoundException(__('Group not found'));
        }

        // check count employees
        $countUser = $this->Group->countUserInGroup($id);
        if ($countUser > 0) {
            $this->Flash->error(
                __('The group with name: %s could not be deleted. This group still contains users', h($group['Group']['name']))
            );
            return $this->redirect(array('action' => 'index'));
        }

        // execute delete
        if ($this->Group->delete($id)) {
            $this->Flash->success(
                __('The group with name: %s has been deleted.', h($group['Group']['name']))
            );
        } else {
            $this->Flash->error(
                __('The group with name: %s could not be deleted.', h($group['Group']['name']))
            );
        }

        // redirect to index
        return $this->redirect(array('action' => 'index'));
    }

}
