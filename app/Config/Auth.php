<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
   // Define user group permissions
   public $permissions = [
      'superadmin' => [
         '*', // Superadmin has access to all routes
      ],
      'ho_accountant' => [
         'accounting/*',
         'reports/*',
      ],
      'branch_accountant' => [
         'logout',
         'admin/branches',
         'admin/branches/*',
         'accounting/journal',
         'reports/branch',
      ],
      'auditor' => [
         'reports/*',
      ],
      'guest' => [
         'login', // Allow guests to access the login page
         'logout', // Allow guests to access the logout route
         'unauthorized', // Allow guests to access the unauthorized page
      ],
   ];

   // Optional: Define default redirect paths for unauthorized access
   public $redirects = [
      'logout' => '/logout', // Redirect to login page if not authenticated
      'login' => '/login', // Redirect to login page if not authenticated
      'unauthorized' => '/unauthorized', // Redirect to unauthorized page if access is denied
   ];

   // Optional: Define user group hierarchy (if applicable)
   public $userGroupHierarchy = [
      'superadmin' => ['ho_accountant', 'branch_accountant', 'auditor'],
      'ho_accountant' => ['branch_accountant'],
   ];

   // Optional: Define default user group for new users
   public $defaultUserGroup = 'guest';

   // Optional: Add other authentication-related settings
   public $authSettings = [
      'sessionKey' => 'auth_user', // Session key for storing authenticated user data
      'rememberMeDuration' => 30 * DAY, // Duration for "remember me" functionality
   ];
}