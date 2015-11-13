<?php

/**
 * Employee Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */
App::uses('AppModel', 'Model');
App::uses('Category', 'Model');

class Post extends AppModel
{

    public $validate  = array(
        'title'         => array(
            'required' => array(
                'required'   => true,
                'allowEmpty' => false,
                'rule'       => 'notBlank',
                'message'    => 'This field cannot be left blank'
            ),
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
    public $belongsTo = array('Category');

    
    public function beforeSave($options = array())
    {
        parent::beforeSave($options);
        
        if (!$this->data[$this->alias]['alias']) {
            $this->data[$this->alias]['alias'] = Inflector::slug($this->data[$this->alias]['title'], '-');
        }
        
        return true;
    }
}
