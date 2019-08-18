<?php


class User
{

  public $conn;


/**
 * Initiate DB connection
 * @author Khairul Alam - khairul@sajida.org
 * @method __construct
 * @param  [type]      $db [description]
 */

  public function __construct($db)
  {
    $this->conn = $db;
  }

/**
 * Create new user
 * @method create
 * @param  [string] $name     [description]
 * @param  [string] $email    [description]
 * @param  [string] $password [description]
 * @param  [string] $userDept [description]
 * @param  [string] $userType [description]
 * @return [boolean]
 */

  public function create($supervisorId,$name,$email,$password,$userDept,$userType)
  {

      try
      {
        $stmt = $this->conn->prepare("INSERT INTO users (supervisorId, userName, userEmail, userPassword, userDept, userType) VALUES(:supervisorId, :userName, :userEmail, :userPassword, :userDept, :userType)");
        $stmt->bindParam(":supervisorId", $supervisorId);
        $stmt->bindParam(":userName", $name);
        $stmt->bindParam(":userEmail", $email);
        $stmt->bindParam(":userPassword", $password);
        $stmt->bindParam(":userDept", $userDept);
        $stmt->bindParam(":userType", $userType);

        if ($stmt->execute())
        {
          return true;
        }
      }
      catch(Exception $e)
      {
       echo $e->getMessage();
      }

    }

  /**
   * Edit user account
   * @author Khairul Alam
   * @method edit
   * @param  [type] $id [description]
   * @return [type]     [description]
   */

  public function edit($id, $msg)
  {

    try
    {
      $stmt = $this->conn->prepare("UPDATE users SET userStatus=:userStatus WHERE userId=:userId");
      $stmt->bindparam(":userStatus", $msg);
      $stmt->bindParam(":userId", $id);

      if ($stmt->execute())
      {
        return true;
      }

    }
    catch(Exception $e)
    {
      echo $e->getMessage();
    }

  }

  /**
   * Update user profile
   * @author Khairul Alam
   * @method updateProfile()
   */

   public function updateProfile($id, $password)
   {

     try
     {
       $stmt = $this->conn->prepare("UPDATE users SET userPassword = :userPassword WHERE userId=:userId");
       $stmt->bindParam(":userPassword", $password);
       $stmt->bindParam(":userId", $id);

       if ($stmt->execute())
       {
         return true;
       }

     }
     catch(Exception $e)
     {
       echo $e->getMessage();
     }

   }



  /**
   * Get single user information
   * @author Khairul Alam
   * @method showSingle
   */

   public function showSingle($id)
   {
     try
     {
       $stmt = $this->conn->prepare("SELECT * FROM users WHERE userId = :userId");
       $stmt->bindParam(":userId", $id);
       $stmt->execute();

       $data = $stmt->fetch(PDO::FETCH_ASSOC);
       return $data;

     }
     catch(Exception $e)
     {
       echo $e->getMessage();
     }
   }


  /**
   * List of all users
   * @author Khairul Alam
   * @method showAll
   * @return [array]
   */

    public function showAll($userType, $userDept, $supervisorId)
    {
      $userType = $userType;
      $userDept = $userDept;

      if ($userType == 'Administrator')
      {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
      }
      else
      {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE supervisorId = :supervisorId AND userType != 'Administrator' AND userType != 'Executive'");
        $stmt->bindParam(":supervisorId", $supervisorId);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;

      }


    }

    /**
     * Logout from admin
     * @author Khairul Alam
     * @method logout
     * @return [type] [description]
     */

    public function logout()
    {
      session_destroy();
      unset($_SESSION['userId']);
      return true;
    }


}
