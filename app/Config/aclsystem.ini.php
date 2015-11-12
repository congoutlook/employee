;<?php exit() ?>
;/**
; * ACL Permission Configuration
; *
; * @copyright     Nguyen Van Cong
; */

; acl.permission.ini.php - CakePHP ACL Configuration
; ---------------------------------------------------------------------
; Use this file to specify user permissions.
; aco = access control object (something in your application)

; [Logical]
; permissions = group1, group2, group3
;
; Group records are added in a similar manner:
;
; [Controller]
; permissions = aco1, aco2, aco3
;
;
; ---------------------------------------------------------------------

;-------------------------------------
; Admin
;-------------------------------------

[admin]
permissions = access, login, logout

;-------------------------------------
; Controller
;-------------------------------------

[users]
permissions = access, add, edit, delete, permission

[departments]
permissions = access, add, edit, delete

[employees]
permissions = access, add, edit, delete

