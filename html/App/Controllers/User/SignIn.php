<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\View;
use App\Core\Translation;

use App\Utils\Meta;
use App\Utils\Auth;
use App\Utils\Functions;

use App\Models\User\mUser;

/**
 *  User SignIn
 */
class SignIn extends Controller
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

    if ($_POST) {

      $data['errorList'] = mUser::validateSignIn($_POST);

      if (empty($data['errorList'])) {
        $user = mUser::authenticate($_POST);
        //Functions::pre_dump($user);
        if ($user) {
          if (Auth::sessionUp($user))
            self::redirect('/admin');
          //$data['test'] = 'test';
        }
      }
    }



    $args['template'] = 'Basic';
    View::render($args, $meta, $trans, $data);
  }



  protected function after()
  {
  }

  //END-Class
}
