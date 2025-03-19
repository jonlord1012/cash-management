<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{

   public function before(RequestInterface $request, $arguments = null)
   {
      $auth = \Config\Services::auth();

      if (!$auth->check()) {
         return redirect()->to('/login');
      }

      /*
      // Additional group/permission checks can go here
      $user = $auth->user();
      $currentRoute = service('router')->getMatchedRouteOptions();

      if (!$auth->hasAccess(session('user_group'), $currentRoute)) {
         return redirect()->to('/unauthorized');
      }
      */
      // Check route permissions
      $userGroup = $auth->user()['user_group'];
      $route = $request->getUri()->getPath();

      if (!$auth->hasAccess($userGroup, $route)) {
         return redirect()->to('/login');
      }
      return $request;
   }
   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // No action needed
   }
}