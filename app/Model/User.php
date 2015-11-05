<?php
/**
 * User Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

    public $validate = array(
        'username' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'required' => true,
                'message' => 'Letters and numbers only'
            ),
            'between' => array(
                'rule' => array('lengthBetween', 5, 15),
                'message' => 'Between 5 to 15 characters'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'required' => true,
                'allowEmpty' => false,
                'on' => 'create', // here
                'last' => false,
                'message' => 'This username has already taken'
            )
        ),
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'required' => true,
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'required' => true,
                'allowEmpty' => false,
                'on' => 'create', // here
                'last' => false,
                'message' => 'This email has already taken'
            )
        ),
        'password' => array(
            'minLength' => array(
                'rule' => array('minLength', '6'),
                'message' => 'Minimum 6 characters long'
            )
        )
    );
    
    /**
     * Customize validation rule matchPasswords
     * @param array $field
     * @param array $data
     * @return boolean
     */
    public function matchPasswords($field = null, $data = null) {
        
        if (isset($this->data['User']['password']) && 
            isset($this->data['User']['password2'])
        )
        {
            if ($this->data['User']['password'] == $this->data['User']['password2']) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
    * Called before save operation, after validation.
    *
    * @param array $options Options passed from Model::save().
    * @return bool True if the operation should continue
    */
    public function beforeSave($options = array()) {
        
        if (isset($this->data[$this->alias]['password2'])) {
            $this->data[$this->alias]['is_first'] = 0;
        }
                
        if (isset($this->data[$this->alias]['password']) && 
            strlen($this->data[$this->alias]['password']))
        {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        
        return true;
    }
}
?>