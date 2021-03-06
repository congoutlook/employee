<?php

/**
 * Posts Controller
 * 
 * @package         app.Controller
 * @author          Nguyen Van Cong
 */
App::uses('AppController', 'Controller');
App::uses('File', 'Utility');
App::uses('CakeTime', 'Utility');

class PostsController extends AppController
{

    const DIRECTORY_UPLOAD = 'files';

    public $components = array('Paginator', 'RequestHandler');
    public $helpers    = array('Js' => 'Jquery', 'Format', 'Time');
    public $paginate   = array(
        'limit' => 5,
        'order' => array(
            'Posts.id'    => 'asc',
            'Posts.title' => 'asc'
        )
    );

    /**
     * Hook before filter
     * @return void 
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        // Allow index, view
        $this->Auth->allow('view');

        // Check Acl
        $checkAcl = true;
        switch ($this->action) {
            case 'add':
                $checkAcl = $this->Acl->check($this->Auth, 'posts', 'add');
                break;
            case 'edit':
                $checkAcl = $this->Acl->check($this->Auth, 'posts', 'edit');
                break;
            case 'delete':
                $checkAcl = $this->Acl->check($this->Auth, 'posts', 'delete');
                break;
            case 'index':
            default:
                $checkAcl = $this->Acl->check($this->Auth, 'posts', 'access');
                break;
        }

        if (!$checkAcl) {
            throw new Exception('Access denied');
        }
    }

    /**
     * Show all posts
     * @return void
     */
    public function index()
    {
        $this->loadModel('Category');

        // get params filter
        $conditions        = array();
        $whiteFieldsFilter = array('category_id', 'state');

        if (($this->request->is(array('post', 'put'))) && isset($this->data['Filter'])) {
            $filterUrl['controller'] = $this->request->params['controller'];
            $filterUrl['action']     = $this->request->params['action'];
            $filterUrl['page']       = 1;

            // build filter url
            foreach ($this->data['Filter'] as $name => $value) {
                if (is_numeric($value)) {
                    $filterUrl[$name] = urlencode($value);
                } elseif ($value) {
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
                            array('Post.title LIKE' => '%' . $value . '%'),
                            array('Post.alias LIKE' => '%' . $value . '%')
                        );
                    } else {
                        if (in_array($paramName, $whiteFieldsFilter)) {
                            $conditions['Post.' . $paramName] = $value;
                        }
                    }
                    $this->request->data['Filter'][$paramName] = $value;
                }
            }

            // check multi categories
            if (isset($this->request->data['Filter']['category_id']) && $this->request->data['Filter']['category_id']) {
                $children = $this->Category->children($this->request->data['Filter']['category_id'], false, 'id');
                $children = Hash::extract($children, '{n}.Category.id');
                if (!empty($children)) {
                    unset($conditions['Post.category_id']);
                    $children                       = array_merge(array($this->request->data['Filter']['category_id']), $children);
                    $conditions['Post.category_id'] = $children;
                }
            }
        }

        // get items
        $this->paginate['conditions'] = $conditions;
        $this->Paginator->settings    = $this->paginate;
        $this->set('items', $this->Paginator->paginate('Post'));

        // render view ajax
        if ($this->request->is("ajax")) {
            $this->render('indexajax');
        }

        // build data filter form, get department dependent
        $this->set('categories', $this->Category->generateTreeList(null, null, null, '|____'));
        $this->set('states', array(0 => 'Unpublish', 1 => 'Publish', 2 => 'Pending'));
    }

    /**
     * Add a new post
     */
    public function add()
    {
        if ($this->request->is('post')) {

            $upload = true;
            $data   = $this->request->data;
            $this->Post->set($data);

            // if file upload does not exist, Do not validate it
            if (!isset($data['Post']['photo_upload']['name']) || !$data['Post']['photo_upload']['name']) {
                unset($this->Post->validate['photo_upload']);
                $upload = false;
            }

            if ($this->Post->validates()) {
                // upload file photo
                if (isset($data['Post']['photo_upload'])) {
                    if ($upload) {
                        // setup info for uploading file
                        $info                  = pathinfo($this->request->data['Post']['photo_upload']['name']);
                        $tmp                   = $this->request->data['Post']['photo_upload']['tmp_name'];
                        $data['Post']['photo'] = time() . '_' . $info['filename'] . '.' . $info['extension'];
                        $uploadPath            = self::DIRECTORY_UPLOAD . DS . $data['Post']['photo'];

                        // Upload file
                        $file = new File($tmp);
                        $file->copy($uploadPath);
                        if (!$file->copy($uploadPath)) {
                            $this->Flash->error(__('Sorry, Unable to upload post\'s photo at this moment. Please upload again.'));
                        }
                    } else {
                        $data['Post']['photo'] = '';
                    }
                }

                // auto field
                $data['Post']['created'] = CakeTime::dayAsSql(time(), 'Asia/Jakarta');

                # execute save without validate. return page index if success.
                $this->Post->create();
                if ($this->Post->save($data, false)) {
                    $this->Flash->success(__('The post has been created.'));
                    return $this->redirect(array('action' => 'index'));
                }
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }

        // get department dependent
        $this->loadModel('Category');
        $this->set('categories', $this->Category->generateTreeList(null, null, null, '|____'));
    }

    /**
     * Edit a post
     * @param int $id
     * @return void
     * @throws NotFoundException
     */
    public function edit($id = null)
    {

        // validate post id
        if (!$id) {
            throw new NotFoundException(__('Post not found'));
        }

        // find post from post id
        $post = $this->Post->getById($id);
        if (!$post) {
            throw new NotFoundException(__('Post not found'));
        }

        $data = $this->request->data;

        $data['Post']['photo'] = $post['Post']['photo'];
        $oldPhoto              = $post['Post']['photo'];

        // execute editing post when the request is put from Form
        if ($this->request->is(array('post', 'put'))) {
            $this->Post->id = $id;

            $upload = true;
            $this->Post->set($data);

            // if file upload does not exist, Do not validate it
            if (!isset($data['Post']['photo_upload']['name']) || !$data['Post']['photo_upload']['name']) {
                unset($this->Post->validate['photo_upload']);
                $upload = false;
            }

            if ($this->Post->validates()) {
                // upload file photo
                if (isset($data['Post']['photo_upload'])) {
                    if ($upload) {
                        // setup info for uploading file
                        $info = pathinfo($data['Post']['photo_upload']['name']);
                        $tmp  = $data['Post']['photo_upload']['tmp_name'];

                        $data['Post']['photo'] = time() . '_' . $info['filename'] . '.' . $info['extension'];
                        $uploadPath            = self::DIRECTORY_UPLOAD . DS . $data['Post']['photo'];

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
                            $this->Flash->error(__('Sorry, Unable to upload post\'s photo at this moment. Please upload again.'));
                        }
                    } else {
                        $data['Post']['photo'] = $post['Post']['photo'];
                    }
                }

                unset($data['Post']['photo_upload']);

                # execute save without validate. return page index if success.
                if ($this->Post->save($data, false)) {

                    $this->Flash->success(__('Your Post has been updated.'));
                    return $this->redirect(array('action' => 'index'));
                }
            }
            $this->Flash->error(__('Unable to update your Post.'));
        }

        // get department dependent
        $this->loadModel('Category');
        $this->set('categories', $this->Category->generateTreeList(null, null, null, '|____'));

        // show form
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    /**
     * Delete a post
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

        // validate post id
        if (!$id) {
            throw new NotFoundException(__('Post not found'));
        }

        // find post from post id
        $post = $this->Post->getById($id);
        if (!$post) {
            throw new NotFoundException(__('Post not found'));
        }

        // execute delete
        if ($this->Post->delete($id)) {
            $this->Flash->success(
                __('The post with name: %s has been deleted.', h($post['Post']['title']))
            );

            // delete file from storage
            $file = new File(self::DIRECTORY_UPLOAD . DS . $post['Post']['photo']);
            if ($file->exists()) {
                $file->delete();
            }
        } else {
            $this->Flash->error(
                __('The post with name: %s could not be deleted.', h($post['Post']['title']))
            );
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function preview($id)
    {
        // validate post id
        if (!$id) {
            throw new NotFoundException(__('Post not found'));
        }

        // find post from post id
        unset($this->Post->virtualFields);
        $post = $this->Post->getById($id);
        if (!$post) {
            throw new NotFoundException(__('Post not found'));
        }

        // show form
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function blogs($id)
    {

        // validate id
        if (!$id) {
            throw new NotFoundException();
        }

        $this->loadModel('Category');

        // validate row data
        $category = $this->Category->getById($id);
        if (!$category) {
            throw new NotFoundException();
        }

        $conditions = array();
        $conditions['Post.category_id'] = $id;

        // check multi categories
        $children = $this->Category->children($id, false, 'id');
        $children = Hash::extract($children, '{n}.Category.id');
        
        if (!empty($children) && is_array($children) && count($children)) {
            unset($conditions['Post.category_id']);
            $children                       = array_merge(array($id), $children);
            $conditions['Post.category_id'] = $children;
        }
        
        // publish
        $conditions['Post.state'] = 1;
        $fields = array('Post.id', 'Post.category_id', 'Post.title', 'Post.alias', 'Post.introtext', 'Post.publish_up', 'Post.publish_down', 'Post.user_id', 'Post.author', 'Post.photo', 'Category.id', 'Category.name');

        // get items
        $this->paginate['conditions'] = $conditions;
        $this->paginate['fields']     = $fields;
        $this->Paginator->settings    = $this->paginate;
        $this->set('items', $this->Paginator->paginate('Post'));
        $this->set('category', $category);
        
        // render view ajax
        if ($this->request->is("ajax")) {
            $this->render('blogsajax');
        }
    }

}
