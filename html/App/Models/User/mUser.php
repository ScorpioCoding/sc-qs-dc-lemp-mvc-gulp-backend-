<?php


namespace App\Models\User;

use PDO;
use PDOException;

use App\Core\Database;

use App\Core\NewException;
use RuntimeException;



class mUser extends Database
{

  public function __construct()
  {
    parent::__construct();
  }

  public static function setTable()
  {
    try {
      $query = "CREATE TABLE IF NOT EXISTS User ( 
        id SERIAL PRIMARY KEY,   
        email VARCHAR(255) UNIQUE,
        email_validated BOOLEAN DEFAULT FALSE,
        permission VARCHAR(50) NOT NULL,
        psw_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT NOW()
      )";

      $dB = static::getDb();


      return $dB->exec($query);
    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
  }


  public static function create($args = array())
  {
    $password = password_hash($args['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO `User` (
      `id`, 
      `email`, 
      `email_validated`, 
      `permission`, 
      `psw_hash`,
      `employee_id`
      )
    VALUES (
      :id, 
      :email, 
      :email_validated, 
      :permission, 
      :psw_hash,
      :employee_id
      )";

    $dB = static::getdb();
    $stmt = $dB->prepare($query);

    $stmt->bindValue(':id', null, PDO::PARAM_NULL);
    $stmt->bindValue(':email', $args['email'], PDO::PARAM_STR);
    $stmt->bindValue(':email_validated', $args['email_validated'], PDO::PARAM_BOOL);
    $stmt->bindValue(':permission', $args['permission'], PDO::PARAM_STR);
    $stmt->bindValue(':psw_hash', $password, PDO::PARAM_STR);
    $stmt->bindValue(':employee_id', $args['employee_id'], PDO::PARAM_INT);
    try {
      $stmt->execute();
      return $dB->lastInsertId();
    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
  }


  public static function readByEmail($email)
  {
    try {
      $query = "SELECT * FROM `User` WHERE `email` = :email LIMIT 1";
      $dB = static::getdb();
      $stmt = $dB->prepare($query);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);

      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      $e->getMessage();
      return false;
    }
  }


  public static function readByPermission(string $permission)
  {
    try {
      $query = "SELECT * FROM `User` WHERE `permission` = :permission LIMIT 1";
      $dB = static::getdb();
      $stmt = $dB->prepare($query);
      $stmt->bindValue(':permission', $permission, PDO::PARAM_STR);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);

      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      $e->getMessage();
      return false;
    }
  }


  public static function update($args = array())
  {
    try {
      $password = password_hash($args['password'], PASSWORD_DEFAULT);

      $query = "UPDATE `User` SET 
        `email`=:email, 
        `email_validated`=:email_validated, 
        `permission`=:permission,
        `psw_hash`=:psw_hash,
        `employee_id`=:employee_id
      WHERE `id` = :id";

      $dB = static::getdb();
      $stmt = $dB->prepare($query);

      $stmt->bindValue(':id', $args['id'], PDO::PARAM_INT);
      $stmt->bindValue(':email', $args['email'], PDO::PARAM_STR);
      $stmt->bindValue(':email_validated', $args['email_validated'], PDO::PARAM_INT);
      $stmt->bindValue(':permission', $args['permission'], PDO::PARAM_STR);
      $stmt->bindValue(':psw_hash', $password, PDO::PARAM_STR);
      $stmt->bindValue(':employee_id', $args['employee_id'], PDO::PARAM_INT);


      return $stmt->execute();
    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
  }


  public static function validate($args = array())
  {
    $errorList = array();

    if (filter_var($args['email'], FILTER_VALIDATE_EMAIL) === false)
      $errorList[] = 'Email Invalid!';

    if ($args['password'] == "")
      $errorList[] = 'Password required !';

    if ($args['psw_confirm'] == "")
      $errorList[] = 'Confirmation Password required !';

    if ($args['password'] !== $args['psw_confirm'])
      $errorList[] = 'Confirmation Password must be the same as Password !';

    if (strlen($args['password']) < 6)
      $errorList[] = 'Password must be more than 6 characters!';

    if (preg_match('/.*[a-z]+.*/i', $args['password']) == 0)
      $errorList[] = 'Password needs at least one letter!';

    if (preg_match('/.*\d+.*/i', $args['password']) == 0)
      $errorList[] = 'Password needs at least one number!';

    return $errorList;
  }

  public static function validateSignIn($args = array()): array
  {
    $errorList = array();
    //Email address
    if (filter_var($args['email'], FILTER_VALIDATE_EMAIL) === false)
      $errorList[] = 'Invalid Credentials!';

    if (!self::emailExists($args['email']))
      $errorList[] = 'Invalid Credentials!';

    //Password    
    if (strlen($args['password']) < 6)
      $errorList[] = 'Invalid Credentials!';

    if (preg_match('/.*[a-z]+.*/i', $args['password']) == 0)
      $errorList[] = 'Invalid Credentials!';

    if (preg_match('/.*\d+.*/i', $args['password']) == 0)
      $errorList[] = 'Invalid Credentials!';

    return $errorList;
  }

  public static function validateUpdate($args = array())
  {
    $errorList = array();

    if (filter_var($args['email'], FILTER_VALIDATE_EMAIL) === false)
      $errorList[] = 'Email Invalid!';

    return $errorList;
  }

  public static function emailExists($email)
  {
    return static::getUserByEmail($email) !== false;
  }

  public static function getUserByEmail($email): object
  {
    $query = "SELECT * FROM `User` WHERE `email`=:email";

    $dB = static::getdb();

    $stmt = $dB->prepare($query);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
    $stmt->execute();
    return $stmt->fetch();
  }

  public static function authenticate(array $args)
  {
    $user = static::getUserByEmail($args['email']);

    if ($user) {

      try {
        password_verify($args['password'], $user->psw_hash);
        return $user;
      } catch (RuntimeException $e) {
        echo $e->getMessage();
      }
    }
    return false;
  }

  //
  //END CLASS
}
