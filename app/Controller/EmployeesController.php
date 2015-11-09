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

    const DIRECTORY_UPLOAD = 'files';

    public $components = array('Paginator', 'RequestHandler');
    public $helpers    = array('Js' => 'Jquery', 'View');
    public $paginate   = array(
        'limit' => 5,
        'order' => array(
            'Employees.id'   => 'asc',
            'Employees.name' => 'asc'
        )
    );

    public function __construct($request = null, $response = null)
    {
        parent::__construct($request, $response);
    }

    /**
     * Hook before filter
     * @return void 
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        // Allow index, view
        $this->Auth->allow('view');
    }

    /**
     * Show all employees
     * @return void
     */
    public function index()
    {
        // get params filter
        $conditions = array();
        $whiteFieldsFilter = array('department_id');

        if (($this->request->is('post') || $this->request->is('put')) && isset($this->data['Filter'])) {
            $filterUrl['controller'] = $this->request->params['controller'];
            $filterUrl['action']     = $this->request->params['action'];
            $filterUrl['page']       = 1;

            // build filter url
            foreach ($this->data['Filter'] as $name => $value) {
                if ($value) {
                    $filterUrl[$name] = urlencode($value);
                }
            }

            // redirect via filter url
            return $this->redirect($filterUrl);
        } else {
            // build conditions array
            foreach ($this->params['named'] as $paramName => $value) {
                // Don't apply the default named parameters used for pagination
                if (!in_array($paramName, array('page', 'sort', 'direction', 'limit'))) {
                    if ($paramName == 'search') {
                        $conditions['OR'] = array(
                            array('Employee.name LIKE' => '%' . $value . '%'),
                            array('Employee.email LIKE' => '%' . $value . '%')
                        );
                    } else {
                        if (in_array($paramName, $whiteFieldsFilter)) {
                            $conditions['Employee.' . $paramName] = $value;
                        }
                    }
                    $this->request->data['Filter'][$paramName] = $value;
                }
            }
        }

        // build data filter form
        $departments = $this->Employee->Department->find('list', array(
            'fields' => array('Department.id', 'Department.name')
        ));
        $this->set('departments', $departments);

        $this->paginate['conditions'] = $conditions;

        $this->Paginator->settings = $this->paginate;
        $this->set('employees', $this->Paginator->paginate('Employee'));

        // render view ajax
        if ($this->request->is("ajax")) {
            $this->render('indexajax');
        }
    }

    /**
     * View detail a employee
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function view($id = null)
    {

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
    public function add()
    {
        if ($this->request->is('post')) {

            $upload = true;
            $data   = $this->request->data;
            $this->Employee->set($data);

            // if file upload does not exist, Do not validate it
            if (!isset($data['Employee']['photo_upload']['name']) || !$data['Employee']['photo_upload']['name']) {
                unset($this->Employee->validate['photo_upload']);
                $upload = false;
            }

            if ($this->Employee->validates()) {
                // upload file photo
                if (isset($data['Employee']['photo_upload'])) {
                    if ($upload) {
                        // setup info for uploading file
                        $info                      = pathinfo($this->request->data['Employee']['photo_upload']['name']);
                        $tmp                       = $this->request->data['Employee']['photo_upload']['tmp_name'];
                        $data['Employee']['photo'] = time() . '_' . $info['filename'] . '.' . $info['extension'];
                        $uploadPath                = self::DIRECTORY_UPLOAD . DS . $data['Employee']['photo'];

                        // Upload file
                        $file = new File($tmp);
                        $file->copy($uploadPath);
                        if (!$file->copy($uploadPath)) {
                            $this->Flash->error(__('Sorry, Unable to upload employee\'s photo at this moment. Please upload again.'));
                        }
                    } else {
                        $data['Employee']['photo'] = '';
                    }
                }

                # execute save without validate. return page index if success.
                $this->Employee->create();
                if ($this->Employee->save($data, false)) {
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
    public function edit($id = null)
    {

        // validate employee id
        if (!$id) {
            throw new NotFoundException(__('Employee not found'));
        }

        // find employee from employee id
        $employee = $this->Employee->findById($id);
        if (!$employee) {
            throw new NotFoundException(__('Employee not found'));
        }

        $data = $this->request->data;

        $data['Employee']['photo'] = $employee['Employee']['photo'];
        $oldPhoto                  = $employee['Employee']['photo'];

        // execute editing employee when the request is put from Form
        if ($this->request->is(array('post', 'put'))) {
            $this->Employee->id = $id;

            $upload = true;
            $this->Employee->set($data);

            // if file upload does not exist, Do not validate it
            if (!isset($data['Employee']['photo_upload']['name']) || !$data['Employee']['photo_upload']['name']) {
                unset($this->Employee->validate['photo_upload']);
                $upload = false;
            }

            if ($this->Employee->validates()) {
                // upload file photo
                if (isset($data['Employee']['photo_upload'])) {
                    if ($upload) {
                        // setup info for uploading file
                        $info = pathinfo($data['Employee']['photo_upload']['name']);
                        $tmp  = $data['Employee']['photo_upload']['tmp_name'];

                        $data['Employee']['photo'] = time() . '_' . $info['filename'] . '.' . $info['extension'];
                        $uploadPath                = self::DIRECTORY_UPLOAD . DS . $data['Employee']['photo'];

                        // remove old file
                        if ($oldPhoto) {
                            $oldPhotoFile = new File(self::DIRECTORY_UPLOAD . DS . $oldPhoto);
                            if ($oldPhotoFile->exists()) {
                                $oldPhotoFile->delete();
                            }
                        }

                        // Upload file
                        $file = new File($tmp);
                        $file->copy($uploadPath);
                        if (!$file->copy($uploadPath)) {
                            $this->Flash->error(__('Sorry, Unable to upload employee\'s photo at this moment. Please upload again.'));
                        }
                    } else {
                        $data['Employee']['photo'] = $employee['Employee']['photo'];
                    }
                }

                unset($data['Employee']['photo_upload']);

                # execute save without validate. return page index if success.
                if ($this->Employee->save($data, false)) {
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
    public function delete($id = null)
    {

        # throw exception if method is get
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

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

            // delete file from storage
            $file = new File(self::DIRECTORY_UPLOAD . DS . $employee['Employee']['photo']);
            if ($file->exists()) {
                $file->delete();
            }
        } else {
            $this->Flash->error(
                __('The employee with name: %s could not be deleted.', h($employee['Employee']['name']))
            );
        }

        return $this->redirect(array('action' => 'index'));
    }

}
