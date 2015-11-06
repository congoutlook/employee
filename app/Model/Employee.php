<?php
/**
 * Employee Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */

App::uses('AppModel', 'Model');

class Employee extends AppModel 
{

    public $validate = array(
        'name' => array(
            'humanName' => array(
                'rule' => array('custom', '/^[\p{L}\p{P}\p{Zs}]+$/'),
                'required' => true,
                'message' => 'Letters only'
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
        'job_title' => array(
            'humanName' => array(
                'rule' => array('custom', '/^[\p{L}\p{P}\p{Zs}]+$/'),
                'required' => true,
                'message' => 'Letters and numbers only'
            )
        ),
        'cellphone' => array(
            'alphaNumeric' => array(
                'rule' => 'numeric',
                'required' => true,
                'message' => 'Letters and numbers only'
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
        'photo' => array(
            'uploadError' => array(
                'rule' => 'uploadError',
                'message' => 'Something went wrong with the file upload',
                'required' => false,
                'allowEmpty' => true,
            ),
            'extension' => array(
                'rule' => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
                'message' => 'Please supply a valid image',
            ),
            'mimeType' => array(
                'rule' => array('mimeType', array('image/gif','image/png','image/jpg','image/jpeg')),
                'message' => 'Invalid file, only images allowed',
            ),
            'fileSize' => array(
                'rule' => array('fileSize', '<=', '1MB'),
                'message' => 'Image must be less than 1MB',
            )
        ),        
    );
    
    // employee belongs to department
    public $belongsTo = array('Department');
}
