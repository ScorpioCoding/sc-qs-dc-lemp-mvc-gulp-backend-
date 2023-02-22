<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\View;
use App\Core\Translation;

use App\Utils\Meta;
use App\Utils\Auth;


/**
 *  User SignOut
 */
class SignOut extends Controller
{
  protected function before()
  {
  }

  public function indexAction($args = array())
  {
    //MetaData
    $meta = array();
    $meta = (new Meta($args))->getMeta();
    // Translation
    $trans = array();
    //$trans = Translation::translate($args);
    // Extra data
    $data = array();

    if (Auth::sessionDown())
      self::redirect('/');

    $args['template'] = 'Basic';
    View::render($args, $meta, $trans, $data);
  }

  protected function after()
  {
  }

  //END-Class
}
