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

      // Check route permissions
      $userGroup = $auth->user()['user_group'];

      $currentUri = $request->getUri()->getPath();
      $routeOptions = service('router')->getMatchedRouteOptions();


      if (!$auth->hasAccess($userGroup, $currentUri, $routeOptions)) {
         return redirect()->to('/unauthorized');
      }
      return $request;
   }
   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // No action needed
   }
}
class SanitizeInput implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      $uri = service('uri');
      $segments = $uri->getSegments(); // Get all segments of the URL
      $param = $uri->getSegment(3); // Adjust based on your route segment
      if (!ctype_alnum($param)) { // Check if alphanumeric (adjust if needed)
         throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid parameter');
      }

      // Sanitize each segment
      foreach ($segments as $key => $segment) {
         // Strip HTML tags
         $segments[$key] = strip_tags($segment);

         // Convert special characters to HTML entities
         $segments[$key] = htmlspecialchars($segments[$key], ENT_QUOTES, 'UTF-8');

         // Optional: Enforce alphanumeric validation
         if (!ctype_alnum(str_replace(['-', '_'], '', $segments[$key]))) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid segment detected');
         }
      }
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Nothing to do here
   }
}