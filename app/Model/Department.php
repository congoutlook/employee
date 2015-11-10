<?php

/**
 * Department Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */
App::uses('AppModel', 'Model');
App::uses('Employee', 'Model');

class Department extends AppModel
{

    public $validate = array(
        'name'         => array(
            'alphaNumeric' => array(
                'rule'       => 'alphaNumeric',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Letters and numbers only'
            ),
            'isUnique'     => array(
                'rule'    => 'isUnique',
                'on'      => 'create', // here
                'last'    => false,
                'message' => 'This name has already taken'
            )
        ),
        'office_phone' => array(
            'required' => array(
                'required'   => true,
                'allowEmpty' => false,
                'rule'       => 'notBlank',
                'message'    => 'This field cannot be left blank'
            ),
            'between'  => array(
                'rule'    => array('lengthBetween', 10, 11),
                'message' => 'Between 10 to 11 characters'
            ),
            'phone'    => array(
                'rule'    => array('phone', '/^[0-9 ]+$/'),
                'message' => 'Phone Numbers only'
            ),
            'isUnique' => array(
                'rule'    => 'isUnique',
                'on'      => 'create', // here
                'last'    => true,
                'message' => 'This phone number has already taken'
            )
        )
    );
    public $hasOne   = array(
        'Manager' => array(
            'className'  => 'Employee',
            'conditions' => array('Manager.state' => '1', 'Manager.is_manager' => 1)
        )
    );

    /**
     * 
     * @param int $departmentId
     * @param array $options
     * @return array|null Array of records, or Null on failure.
     */
    public function getEmployeesByDepartmentId($departmentId, $options = array())
    {
        $options = array_merge(
            array(
            'fields'     => array('Employee.id', 'Employee.name'),
            'conditions' => array('Employee.department_id' => $departmentId),
            ), (array) $options
        );

        return ClassRegistry::init('Employee')->find('list', array(
                'fields'     => $options['fields'],
                'conditions' => $options['conditions']
        ));
    }

    /**
     * get Number of employee in a department
     * @param int $departmentId
     * @return int
     */
    public function countEmployeeInDepartment($departmentId)
    {
        return ClassRegistry::init('Employee')->find('count', array(
                'conditions' => array('Employee.department_id' => $departmentId)
        ));
    }

}
