<?php
   session_start();
?>
<!DOCTYPE html> 
<html>
   <head>
      <title>Festival Login</title>
      <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script src="form.js"></script>
      <link rel="stylesheet" href="form.css"/>
      <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
      <?php
         function fail()
         {
            JSAlert('Authentication failed. Please try again.\n');
         }
         function JSAlert($message)
         {
            echo '<script>alert("' . $message . '");</script>';
         }
      ?>
   </head>
<body>
<?php
   require('password.php');
   
   if(isset($_GET['password']))
   {
      $username = $_GET['username'];
      $password = $_GET['password'];
      
      require("dbConnector.php");
      
      try
      {
         $db = loadDatabase('music_festival');
         
         $statement = $db->prepare("SELECT password_hashed FROM user WHERE username=:username;");     
         $statement->bindvalue(':username', $username, PDO::PARAM_STR);
         $statement->execute();
         
         if($row = $statement->fetch(PDO::FETCH_ASSOC))
         {
            if(password_verify($password, $row['password_hashed']))
            {
               $_SESSION['loggedIn'] = TRUE;
               
               //TODO: add logout capability
               
               header("Location: register.php");
               die();
            }
         }
         fail();
      }
      catch (PDOException $ex)
      {
         print "Error!: " . $ex->getMessage() . "<br />";
         die();
      }
   }
?>
   <div class="container">
      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="festival.html">Festival Home</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="login.php">Festival Login</a></li>
              <li><a href="register.php">Festival Registration</a></li>
              <li><a href="verify.php">Festival Verification</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php">Emmanuel Jones Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>
      
      <form style="width:400px; margin: 0 auto;" method="GET" action="login.php" onsubmit="return allFilled()"
         id="festival"> 
           <h1>Please login:</h1>
           
           <div class="required-field-block">
               <input type="text" name="username" placeholder="Username" class="form-control"/>
               <div class="required-icon">
                   <div class="text">*</div>
               </div>
           </div>
           <br />
           
           <div class="required-field-block">
               <input type="password" name="password" placeholder="Password" class="form-control"/>
               <div class="required-icon">
                   <div class="text">*</div>
               </div>
           </div>
           <br />
           <input type="submit" class="btn btn-primary" value="Login"/>
           <br />
           <h4>New user? Click <a href="registerUser.php">here</a> to register.</h4>
       </form>

   </div><!--/.container-->

</body>
</html>