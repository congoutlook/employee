<?php

/**
 * Users Controller
 * 
 * @package         app.Controller
 * @author          Nguyen Van Cong
 */
App::uses('AppController', 'Controller');

class CategoriesController extends AppController
{

    /**
     * Hook before filter
     * @return void 
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        
        // Check Acl
        $checkAcl = true;
        switch ($this->action) {
            case 'add':
                $checkAcl = $this->Acl->check($this->Auth, 'categories', 'add');
                break;
            case 'edit':
                $checkAcl = $this->Acl->check($this->Auth, 'categories', 'edit');
                break;
            case 'delete':
                $checkAcl = $this->Acl->check($this->Auth, 'categories', 'delete');
                break;
            case 'index':
            default:
                $checkAcl = $this->Acl->check($this->Auth, 'categories', 'access');
                break;
        }

        if (!$checkAcl) {
            throw new Exception('Access denied');
        }
    }

    /**
     * Manager permission
     */
    public function index()
    {

        // get categories tree
        $tree = $this->Category->generateTreeList(null, null, null, '|____');
        $this->set('items', $tree);

        // set acl allow
        $this->set('allowAdd', $this->Acl->check($this->Auth, 'categories', 'add'));
        $this->set('allowEdit', $this->Acl->check($this->Auth, 'categories', 'edit'));
        $this->set('allowDelete', $this->Acl->check($this->Auth, 'categories', 'delete'));
    }

    public function add()
    {

        // execute editing group when the request is put from Form
        if ($this->request->is('post')) {
            $this->Category->create();
            if ($this->Category->save($this->request->data)) {
                $this->Flash->success(__('The category has been created'));

                // redirect to page index
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The category could not be saved. Please, try again.')
            );
        }

        // get data form
        $parents = $this->Category->generateTreeList(null, null, null, '|____');
        $this->set('parents', $parents);
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
            throw new NotFoundException(__('Category not found'));
        }

        // find group from group id
        $group = $this->Category->getById($id);
        if (!$group) {
            throw new NotFoundException(__('Category not found'));
        }

        // execute editing group when the request is put from Form
        if ($this->request->is(array('post', 'put'))) {
            $this->Category->id = $id;
            # execute. return page index if success. hold form state if falied 
            if ($this->Category->save($this->request->data)) {
                $this->Flash->success(__('Your Category has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }

            $this->Flash->error(__('Unable to update your Category.'));
        }

        // get data form
        $parents = $this->Category->generateTreeList(null, null, null, '|____');
        $this->set('parents', $parents);

        // show form
        if (!$this->request->data) {
            $this->request->data = $group;
        }
    }

    public function delete($id = null)
    {
        // validate post type
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        // validate id
        if (!$id) {
            throw new NotFoundException();
        }

        // validate row data
        $category = $this->Category->getById($id);
        if (!$category) {
            throw new NotFoundException();
        }

        $childCountDirect = $this->Category->childCount($id, true);
        if ($childCountDirect > 0) {
            $this->Flash->error(
                __('The category with name: %s could not be deleted. This category still contains other categories', h($category['Category']['name']))
            );
            return $this->redirect(array('action' => 'index'));
        }

        // execute delete
        if ($this->Category->delete($id)) {
            $this->Flash->success(
                __('The category with name: %s has been deleted.', h($category['Category']['name']))
            );
        } else {
            $this->Flash->error(
                __('The category with name: %s could not be deleted.', h($category['Category']['name']))
            );
        }

        // redirect to index
        return $this->redirect(array('action' => 'index'));
    }

}
