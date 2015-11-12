<?php

/**
 * Group Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */
App::uses('AppModel', 'Model');

class Group extends AppModel
{

    public $actsAs = array('Acl' => array('type' => 'requester'));

    public function parentNode()
    {
        return null;
    }

}
