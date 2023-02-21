<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Translation;

use App\Utils\Site\Meta;


/**
 *  Admin Admin
 */
class Admin extends Controller
{
  protected function before()
  {
  }

  public function indexAction($args = array())
  {
    //MetaData
    $meta = array();
    //$meta = (new Meta($args))->getMeta();
    // Translation
    $trans = array();
    //$trans = Translation::translate($args);
    // Extra data
    $data = array();



    $args['template'] = 'Basic';
    View::render($args, $meta, $trans, [
      'data' => $data,
    ]);
  }

  protected function after()
  {
  }

  //END-Class
}
