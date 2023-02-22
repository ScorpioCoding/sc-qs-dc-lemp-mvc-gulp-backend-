<?php

namespace App\Controllers\Dashboard;

use App\Core\Controller;
use App\Core\View;
use App\Core\Translation;

use App\Utils\Meta;
use App\Utils\Auth;
use App\Utils\Functions;

use App\Models\mCommon;

/**
 *  Dashboard Dashboard
 */
class Dashboard extends Controller
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
    $data['user_permission'] = Auth::getSession('user_permission');
    $data['user_id'] = Auth::getSession('user_id');





    $args['template'] = 'Backend';
    View::render($args, $meta, $trans, [
      'data' => $data,
    ]);
  }



  protected function after()
  {
  }

  //END-Class
}
