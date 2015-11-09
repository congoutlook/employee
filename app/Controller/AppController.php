<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public $components = array(
        'Flash',
        'Auth' => array(
            'loginRedirect'  => array(
                'controller' => 'departments',
                'action'     => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'pages',
                'action'     => 'index',
                'home'
            ),
            'authenticate'   => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            )
        ),
        'DebugKit.Toolbar',
    );

    public function beforeFilter()
    {
        $this->Auth->allow('index', 'view');

        // if user loged is in the first time, require change password
        if (isset($this->Auth->user()['is_first']) &&
            $this->Auth->user()['is_first'] &&
            (strtolower($this->request->params['action']) != 'change_pass')
        ) {
            $this->Flash->success(__('You have to change your password for the first time'));
            return $this->redirect('/users/change_pass');
        }
    }

    public function beforeRender()
    {
        parent::beforeRender();
        
        $this->layout = ($this->request->is("ajax")) ? "ajax" : "default";
    }

}
