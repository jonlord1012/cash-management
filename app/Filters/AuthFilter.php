<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{

   public function before(RequestInterface $request, $arguments = null)
   {
      log_message('debug', 'AuthFilter triggered for: ' . uri_string());
      log_message('debug', 'Session data: ' . print_r(session()->get(), true));
      $auth = \Config\Services::auth();

      if (!$auth->check()) {
         return redirect()->to('/login');
      }

      // Additional group/permission checks can go here
      $user = $auth->user();
      $currentRoute = service('router')->getMatchedRouteOptions();

      if (!$auth->hasAccess(session('user_group'), $currentRoute)) {
         return redirect()->to('/unauthorized');
      }
      return $request;
   }
   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // No action needed
   }
}