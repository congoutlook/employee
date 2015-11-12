<?php

/**
 * Group Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */
App::uses('AppModel', 'Model');
App::uses('User', 'Model');

class Group extends AppModel
{

    public $validate = array(
        'name' => array(
            'isUnique' => array(
                'rule'       => 'isUnique',
                'required'   => true,
                'allowEmpty' => false,
                'on'         => 'create', // here
                'last'       => false,
                'message'    => 'This group name has already taken'
            )
        ),
    );

    public function countUserInGroup($groupId)
    {
        return ClassRegistry::init('User')->find('count', array(
                'conditions' => array('User.group_id' => $groupId)
        ));
    }

}
