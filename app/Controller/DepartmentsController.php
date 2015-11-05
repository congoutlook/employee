<?php
/**
 * Departments Controller
 * 
 * @package         app.Controller
 * @author          Nguyen Van Cong
 */

App::uses('AppController', 'Controller');

class DepartmentsController extends AppController 
{

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 5,
        'order' => array(
            'Departments.id' => 'asc',
            'Departments.name' => 'asc'
        )
    );
    
    /**
     * Hook before filter
     * @return void 
     */
    public function beforeFilter() {
        parent::beforeFilter();
        // Allow user access to page index, view
        $this->Auth->allow('view');
    }
    
    /**
     * Show all departments
     * @return void
     */
    public function index() {
        $this->Paginator->settings = $this->paginate;
        $departments = $this->Paginator->paginate('Department');
        $this->set('departments', $departments);
    }
    
    /**
     * View detail a department
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function view($id = null) {
        
        // discovery department
        $id = $id ? $id : (isset($this->request->params['named']['id']) ? $this->request->params['named']['id'] : 0);
        
        // validate department id
        if (!$id) {
            throw new NotFoundException(__('Department not found'));
        }

        // find department from department id
        $department = $this->Department->findById($id);
        if (!$department) {
            throw new NotFoundException(__('Department not found'));
        }
        
        // show form
        if (!$this->request->data) {
            $this->request->data = $department;
        }
    }
    
    /**
     * Add a new department
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Department->create();
            if ($this->Department->save($this->request->data)) {
                $this->Flash->success(__('The department has been created'));
                
                // redirect to page index
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The department could not be saved. Please, try again.')
            );
        }
    }
    
    /**
     * Edit a department
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function edit($id = null) {
        
        // discovery department
        $id = $id ? $id : (isset($this->request->params['named']['id']) ? $this->request->params['named']['id'] : 0);
        
        // validate department id
        if (!$id) {
            throw new NotFoundException(__('Department not found'));
        }

        // find department from department id
        $department = $this->Department->findById($id);
        if (!$department) {
            throw new NotFoundException(__('Department not found'));
        }
        
        // execute editing department when the request is put from Form
        if ($this->request->is(array('post', 'put'))) {
            $this->Department->id = $id;
            
            # execute. return page index if success. hold form state if falied 
            if ($this->Department->save($this->request->data)) {
                $this->Flash->success(__('Your Department has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to update your Department.'));
        }
        
        // show form
        if (!$this->request->data) {
            $this->request->data = $department;
        }
    }
    
    /**
     * Delete a department
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function delete($id = null) {
        
        # throw exception if method is get
        if($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        
        // discovery department
        $id = $id ? $id : (isset($this->request->params['named']['id']) ? $this->request->params['named']['id'] : 0);
        
        // validate department id
        if (!$id) {
            throw new NotFoundException(__('Department not found'));
        }

        // find department from department id
        $department = $this->Department->findById($id);
        if (!$department) {
            throw new NotFoundException(__('Department not found'));
        }
        
        // execute delete
        if ($this->Department->delete($id)) {
            $this->Flash->success(
                __('The department with name: %s has been deleted.', h($department['Department']['name']))
            );
        } else {
            $this->Flash->error(
                __('The department with name: %s could not be deleted.', h($department['Department']['name']))
            );
        }

        // redirect to index
        return $this->redirect(array('action' => 'index'));
    }
}
