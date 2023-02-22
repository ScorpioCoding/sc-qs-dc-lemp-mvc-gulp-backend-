<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\View;
use App\Core\Translation;

use App\Utils\Meta;
use App\Utils\Auth;
use App\Utils\Functions;

use App\Models\mCommon;

/**
 *  User Dashboard
 */
class User extends Controller
{
  protected function before()
  {
    if (!Auth::sessionValide())
      self::redirect('/admin');
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
    $data['user_permission'] = Auth::getSession('user_permission');
    $data['user_id'] = Auth::getSession('user_id');

    if (mCommon::testForTable('User')) {
      $user = mCommon::readTable('User');
    }




    $args['template'] = 'Backend';
    View::render($args, $meta, $trans, [
      'data' => $data,
      'user' => $user,
    ]);
  }



  protected function after()
  {
  }

  //END-Class
}
