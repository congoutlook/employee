<?php

/**
 * Permission Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */
App::uses('AppModel', 'Model');

class Permission extends AppModel
{
    /**
     * Get list departments
     * @return indexed array
     */
    public function getListPermissions() {
        return $this->find('list', array(
            'fields' => array('Permission.id', 'Permission.alias')
        ));
    }
    
    public function getPermissions() {
        $permissions = $this->find('list', array(
            'fields' => array('Permission.alias', 'Permission.permission')
        ));
        
        if (is_array($permissions)) {
            foreach ($permissions as $key => $permission) {
                $permissions[$key] = json_decode($permission, true);
            }
        }
        
        return $permissions;
    }
}
