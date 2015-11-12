<?php

/**
 * Users Controller
 * 
 * @package         app.Controller
 * @author          Nguyen Van Cong
 */
App::uses('AppController', 'Controller');

class PermissionsController extends AppController
{

    /**
     * Hook before filter
     * @return void 
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        // Deny users in index page. Allow users to login and logout.
        $this->Auth->deny('index');

        // acl
        $checkAcl = $this->Acl->check($this->Auth, 'permissions', 'access') ||
            $this->Acl->check($this->Auth, 'permissions', 'config');

        if (!$checkAcl) {
            throw new Exception('Access denied');
        }
    }

    /**
     * Manager permission
     */
    public function index()
    {

        $this->loadModel('User');
        
        // filter logical permission
        $filterLogical = (isset($this->request['named']['filter']) && trim($this->request['named']['filter'])) ? $this->request['named']['filter'] : '';
        $filterLogical = $filterLogical ? explode(',', $filterLogical) : array();
        
        if ($this->request->is(array('post', 'put'))) {
            
            if (!$this->Acl->check($this->Auth, 'permissions', 'config')) {
                throw new Exception('Access denied');
            }

            if (isset($this->request->data['Permission'])) {
                # find all permissions

                $permissions = $this->Permission->getListPermissions();
                
                $saveAll = array();

                foreach ($this->request->data['Permission'] as $logical => $dataPermissions) {
                    $findId = array_search($logical, $permissions);

                    $saveAll[] = array(
                        'id'         => (int) $findId,
                        'alias'      => $logical,
                        'permission' => json_encode($dataPermissions)
                    );
                }

                if (count($saveAll)) {
                    $options['validate'] = false;
                    $this->Permission->saveAll($saveAll, $options);
                }

                $this->Flash->success(__('Permission has been changed successful'));
                $this->redirect(array('action' => 'index', 'filter' => implode(',', $filterLogical)));
            }
        }
        
        $aclConfig = $this->Acl->config;
        
        if (is_array($aclConfig) && count($filterLogical)) {
            foreach ($aclConfig as $config => $actions) {
                if (!in_array($config, $filterLogical)) {
                    unset($aclConfig[$config]);
                }
            }
        }
        
        $this->set('filter', implode(',', $filterLogical));
        $this->set('permissions', $aclConfig);
        $this->set('groups', $this->User->Group->find('all'));
        $this->set('aclsystem', $this->Permission->getPermissions());
        $this->set('allowConfig', $this->Acl->check($this->Auth, 'permissions', 'config'));
    }

}
