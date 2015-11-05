<?php
/**
 * Department Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */

App::uses('AppModel', 'Model');

class Department extends AppModel {

    public $validate = array(
        'name' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'required' => true,
                'message' => 'Letters and numbers only'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'required' => true,
                'allowEmpty' => false,
                'on' => 'create', // here
                'last' => false,
                'message' => 'This name has already taken'
            )
        ),
        'office_phone' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'required' => true,
                'allowEmpty' => false,
                'on' => 'create', // here
                'last' => false,
                'message' => 'This phone number has already taken'
            )
        )
    );
    
    /**
    * Called before save operation, after validation.
    *
    * @param array $options Options passed from Model::save().
    * @return bool True if the operation should continue
    */
    public function beforeSave($options = array()) {
        
        return true;
    }
}