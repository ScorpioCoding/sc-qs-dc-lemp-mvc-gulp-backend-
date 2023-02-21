<?php

namespace App\Controllers\Setup;

use App\Core\Controller;
use App\Core\View;
use App\Core\Translation;

use App\Utils\Meta;
use App\Utils\Auth;
use App\Utils\Functions;

use App\Models\mCommon;
use App\Models\User\mUser;

/**
 *  Setup Setup
 */
class Setup extends Controller
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

    //1. test for connection
    if (!mCommon::testForConnection())
      echo 'No Database connection';

    //2 & 3. read from user table where super
    if (mCommon::testForTable('User')) {
      $res = mUser::readByPermission('super');
      if (empty($res)) {
        //4. Need to create user
        $data['readonly'] = false;
      } else {
        self::redirect('/admin');
        exit();
      }
    }

    if ($_POST) {
      $data['errorList'] = mUser::validate($_POST);
      if (empty($data['errorList'])) {
        $id = mUser::create($_POST);
        if ($id > 0) {
          mCommon::createTables($id);
          self::redirect('/admin');
        }
      }
      if (!empty($data['errorList'])) {
        foreach ($_POST as $key => $value) {
          $data[$key] = $value;
        }
      }
    }



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
