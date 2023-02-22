<?php

return (object) array(

  // USER / SIGNIN & SIGNOUT
  '/admin/user/signin'   => ['lang' => 'en', 'module' => 'User', 'namespace' => 'App\Controllers\User', 'controller' => 'SignIn', 'action' => 'index'],

  '/admin/user/signout'   => ['lang' => 'en', 'module' => 'User', 'namespace' => 'App\Controllers\User', 'controller' => 'SignOut', 'action' => 'index'],



  // USER / USER LISTING
  '/admin/user'   => ['lang' => 'en', 'module' => 'User', 'namespace' => 'App\Controllers\User', 'controller' => 'User', 'action' => 'index'],

  //USER / CRUD
  '/api/user/create'   => ['lang' => 'en', 'module' => 'Api', 'namespace' => 'App\Controllers\Api\User', 'controller' => 'User', 'action' => 'create'],

  '/api/user/update'   => ['lang' => 'en', 'module' => 'Api', 'namespace' => 'App\Controllers\Api\User', 'controller' => 'User', 'action' => 'update'],



);
