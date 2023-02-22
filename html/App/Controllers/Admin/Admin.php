<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\NewException;
use App\Core\View;
use App\Core\Translation;

use App\Utils\Meta;
use App\Utils\Auth;

use App\Models\mCommon;
use App\Models\User\mUser;


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
    $meta = (new Meta($args))->getMeta();
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

  public function selectAction($args = array())
  {
    try {
      $con = mCommon::testForConnection();
      if ($con) {
        mUser::setTable();
        if (mCommon::testForTable('User')) {
          $res = mUser::readByPermission('super');
          if (empty($res)) {
            self::redirect('/admin/setup');
          } else if (Auth::sessionValide()) {
            self::redirect('/admin/dashboard');
          } else {
            self::redirect('/admin/user/signin');
          }
        }
      } else {
        throw new NewException('Controller: Admin/Admin.php : Database connection failed');
      }
    } catch (NewException $e) {
      echo $e->getErrorMsg();
    }
  }

  protected function after()
  {
  }

  //END-Class
}
