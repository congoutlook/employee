<?php
/**
 * Employees Controller
 * 
 * @package         app.Controller
 * @author          Nguyen Van Cong
 */

App::uses('AppController', 'Controller');
App::uses('File', 'Utility');

class EmployeesController extends AppController 
{

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 5,
        'order' => array(
            'Employees.id' => 'asc',
            'Employees.name' => 'asc'
        )
    );
    
    private $_directoryUpload = '';
    
    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->_directoryUpload = 'files' . DS;
    }
    
    /**
     * Hook before filter
     * @return void 
     */
    public function beforeFilter() {
        parent::beforeFilter();
        // Allow index, view
        $this->Auth->allow('view');
    }
    
    /**
     * Show all employees
     * @return void
     */
    public function index() {
        $this->Paginator->settings = $this->paginate;
        $this->set('employees', $this->Paginator->paginate('Employee'));
    }
    
    /**
     * View detail a employee
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function view($id = null) {
        
        // discovery employee
        $id = $id ? $id : (isset($this->request->params['named']['id']) ? $this->request->params['named']['id'] : 0);
        
        // validate employee id
        if (!$id) {
            throw new NotFoundException(__('Employee not found'));
        }

        // find employee from employee id
        $employee = $this->Employee->findById($id);
        if (!$employee) {
            throw new NotFoundException(__('Employee not found'));
        }
        
        // show form
        if (!$this->request->data) {
            $this->request->data = $employee;
        }
    }
    
    /**
     * Add a new employee
     */
    public function add() {
        if ($this->request->is('post')) {
            
            $upload = true;
            $this->Employee->set($this->request->data);
            
            // if file upload does not exist, Do not validate it
            if (!isset($this->request->data['Employee']['photo']['name']) || !$this->request->data['Employee']['photo']['name']) {
                unset($this->Employee->validate['photo']);
                $upload = false;
            }
            
            if ($this->Employee->validates()) {
                // upload file photo
                if (isset($this->request->data['Employee']['photo'])) {
                    if ($upload) {
                        // setup info for uploading file
                        $info = pathinfo($this->request->data['Employee']['photo']['name']);
                        $tmp = $this->request->data['Employee']['photo']['tmp_name'];
                        $this->request->data['Employee']['photo'] = time() . '_' . $info['filename'] . '.' . $info['extension'];
                        $uploadPath = $this->_directoryUpload . $this->request->data['Employee']['photo'];
                        
                        // Upload file
                        $file = new File($tmp);
                        $file->copy($uploadPath);
                        if (!$file->copy($uploadPath)) {
                            $this->Flash->error(__('Sorry, Unable to upload employee\'s photo at this moment. Please upload again.'));
                        }
                    } else {
                        $this->request->data['Employee']['photo'] = '';
                    }
                }
                
                # execute save without validate. return page index if success.
                $this->Employee->create();
                if ($this->Employee->save($this->request->data, false)) {
                    $this->Flash->success(__('The employee has been created.'));
                    return $this->redirect(array('action' => 'index'));
                }
            }
            $this->Flash->error(__('The employee could not be saved. Please, try again.'));
        }
        
        // get department dependent
        $departments = $this->Employee->Department->find('list', array(
            'fields' => array('Department.id', 'Department.name')
        ));
        $this->set('departments', $departments);
    }
    
    /**
     * Edit a employee
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function edit($id = null) {
        
        // discovery employee
        $id = $id ? $id : (isset($this->request->params['named']['id']) ? $this->request->params['named']['id'] : 0);
        
        // validate employee id
        if (!$id) {
            throw new NotFoundException(__('Employee not found'));
        }

        // find employee from employee id
        $employee = $this->Employee->findById($id);
        if (!$employee) {
            throw new NotFoundException(__('Employee not found'));
        }
        
        // execute editing employee when the request is put from Form
        if ($this->request->is(array('post', 'put'))) {
            $this->Employee->id = $id;
            
            $upload = true;
            $this->Employee->set($this->request->data);
            
            // if file upload does not exist, Do not validate it
            if (!isset($this->request->data['Employee']['photo']['name']) || !$this->request->data['Employee']['photo']['name']) {
                unset($this->Employee->validate['photo']);
                $upload = false;
            }
            
            if ($this->Employee->validates()) {
                // upload file photo
                if (isset($this->request->data['Employee']['photo'])) {
                    if ($upload) {
                        // setup info for uploading file
                        $info = pathinfo($this->request->data['Employee']['photo']['name']);
                        $tmp = $this->request->data['Employee']['photo']['tmp_name'];
                        $this->request->data['Employee']['photo'] = time() . '_' . $info['filename'] . '.' . $info['extension'];
                        $uploadPath = $this->_directoryUpload . $this->request->data['Employee']['photo'];
                        
                        // Upload file
                        $file = new File($tmp);
                        $file->copy($uploadPath);
                        if (!$file->copy($uploadPath)) {
                            $this->Flash->error(__('Sorry, Unable to upload employee\'s photo at this moment. Please upload again.'));
                        }
                    } else {
                        $this->request->data['Employee']['photo'] = '';
                    }
                }
                
                # execute save without validate. return page index if success.
                if ($this->Employee->save($this->request->data, false)) {
                    $this->Flash->success(__('Your Employee has been updated.'));
                    return $this->redirect(array('action' => 'index'));
                }
            }
            $this->Flash->error(__('Unable to update your Employee.'));
        }
        
        // get department dependent
        $departments = $this->Employee->Department->find('list', array(
            'fields' => array('Department.id', 'Department.name')
        ));
        $this->set('departments', $departments);
        
        // show form
        if (!$this->request->data) {
            $this->request->data = $employee;
        }
    }
    
    /**
     * Delete a employee
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function delete($id = null) {
        
        # throw exception if method is get
        if($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        
        // discovery employee
        $id = $id ? $id : (isset($this->request->params['named']['id']) ? $this->request->params['named']['id'] : 0);
        
        // validate employee id
        if (!$id) {
            throw new NotFoundException(__('Employee not found'));
        }

        // find employee from employee id
        $employee = $this->Employee->findById($id);
        if (!$employee) {
            throw new NotFoundException(__('Employee not found'));
        }
        
        // execute delete
        if ($this->Employee->delete($id)) {
            $this->Flash->success(
                __('The employee with name: %s has been deleted.', h($employee['Employee']['name']))
            );
        } else {
            $this->Flash->error(
                __('The employee with name: %s could not be deleted.', h($employee['Employee']['name']))
            );
        }

        return $this->redirect(array('action' => 'index'));
    }
}
