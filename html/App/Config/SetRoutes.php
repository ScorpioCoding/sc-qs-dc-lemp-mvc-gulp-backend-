<?php

namespace App\Config;

class SetRoutes
{
  private $modules = array();
  private $routes = array();

  public function __construct()
  {
    self::loadModules();
    self::loadRoutes();
  }


  private function loadModules()
  {
    $mods = include_once('modules.php');

    foreach ($mods as $mod => $params) {
      $this->modules[$mod] = $params;
    };
    //var_dump($this->modules);
  }


  /** Routes
   * 
   */
  public function loadRoutes()
  {
    //foreach module get the routes
    foreach ($this->modules as $mod) {
      $data = include_once(PATH_ROUTES . $mod['routes']);
      foreach ($data as $key => $value) {
        $this->routes[$key] = $value;
        //print_r($this->routes[$key]);
      };
    };
  }
  public function getRoutes()
  {
    return $this->routes;
  }





  //END-Class
}
