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

    public $validate  = array(
        'name'         => array(
            'humanName' => array(
                'rule'     => array('custom', '~^(?:[\p{L}\p{Mn}\p{Pd}\'\x{2019}]+\s[\p{L}\p{Mn}\p{Pd}\'\x{2019}]+\s?)+$~u'),
                'required' => true,
                'message'  => 'Letters only'
            )
        ),
        'cellphone'    => array(
            'required' => array(
                'required'   => true,
                'allowEmpty' => false,
                'last'       => true,
                'rule'       => 'notBlank',
                'message'    => 'This field cannot be left blank'
            ),
            'between'  => array(
                'rule'    => array('lengthBetween', 10, 11),
                'message' => 'Between 10 to 11 characters'
            ),
            'phone'    => array(
                'rule'    => array('phone', '/^[0-9 ]+$/'),
                'last'    => true,
                'message' => 'Phone Numbers only'
            ),
        ),
        'email'        => array(
            'email'    => array(
                'rule'     => 'email',
                'required' => true,
            ),
            'isUnique' => array(
                'rule'       => 'isUnique',
                'required'   => true,
                'allowEmpty' => false,
                'on'         => 'create', // here
                'last'       => false,
                'message'    => 'This email has already taken'
            )
        ),
        'photo_upload' => array(
            'uploadError' => array(
                'rule'       => 'uploadError',
                'message'    => 'Something went wrong with the file upload',
                'required'   => false,
                'allowEmpty' => true,
            ),
            'extension'   => array(
                'rule'    => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
                'message' => 'Please supply a valid image',
            ),
            'mimeType'    => array(
                'rule'    => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
                'message' => 'Invalid file, only images allowed',
            ),
            'fileSize'    => array(
                'rule'    => array('fileSize', '<=', '1MB'),
                'message' => 'Image must be less than 1MB',
            )
        ),
    );
    // employee belongs to department
    public $belongsTo = array('Department');

}
