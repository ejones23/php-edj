<!DOCTYPE html> 
<html>
   <head>
      <title>Festival User Registration</title>
      <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script src="form.js"></script>
      <link rel="stylesheet" href="form.css"/>
      <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
      <script>
         function validate()
         {
            return (allFilled() && passwordsMatch());
         }
         function allFilled()
         {
            var form = document.getElementById("form");
            var errMsg = '';
            var errField;
            for (var i = 0; i < form.length; i++)
            {
               var field = form.elements[i];
               if (field.type == "text" || field.type == "password")
               {  
                  if (field.value == '')
                  {
                     if (errMsg == '')
                     {
                        errField = field;//first field of error
                     }
                     errMsg += (field.placeholder + " is blank. Please fill out and try again.\n\n");
                  }
               } 
            }
            if (errMsg != '')
            {
               alert(errMsg);
               errField.select();
               //alert("returning false");
               return false;
            }
            else
            {
               //alert("returning true");
               return true;
            }
         }  
         function passwordsMatch()
         {
            var newPassword = document.getElementById("newPassword").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            if (confirmPassword != newPassword)
            {
               alert("Passwords do not match. Please try again.\n");
               return false;
            }
            else
            {
               return true;
            }
         }
      </script>
      <?php
         function JSAlert($message)
         {
            echo '<script>alert("' . $message . '");</script>';
         }
      ?>
   </head>
<body>
<?php
   if(isset($_GET['newPassword']))
   {
      $username = $_GET['username'];
      $newPassword = $_GET['newPassword'];
      $passwordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
      
      require("dbConnector.php");
      
      try
      {
         $db = loadDatabase('music_festival');
         
         $statement = $db->prepare("INSERT INTO user(username, password_hashed) 
            VALUES (:username, :passwordHashed);");     
         $statement->bindvalue(':username', $username, PDO::PARAM_STR);
         $statement->bindvalue(':passwordHashed', $passwordHashed, PDO::PARAM_STR);
         $success = $statement->execute();
         
         if($success)
         {
            JSAlert("Registration successful! You may now proceed to the login page.");
         }
         else
         {
            JSAlert("We couldn't insert that information into the database. " . 
               "Most likely, that username is already being used. " . 
               "Please choose another username and try again.");
         }
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
              <li><a href="login.php">Festival Login</a></li>
              <li><a href="register.php">Festival Registration</a></li>
              <li><a href="verify.php">Festival Verification</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php">Emmanuel Jones Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>
      
      <form style="width:400px; margin: 0 auto;" method="GET" action="registerUser.php" 
         onsubmit="return validate()"
         id="form"> 
           <h1>New user registry:</h1>
           
           <div class="required-field-block">
               <input type="text" name="username" placeholder="Username" class="form-control"/>
               <div class="required-icon">
                   <div class="text">*</div>
               </div>
           </div>
           <br />
           
           <div class="required-field-block">
               <input type="password" name="newPassword" id="newPassword" 
                  placeholder="New password" class="form-control"/>
               <div class="required-icon">
                   <div class="text">*</div>
               </div>
           </div>
           <br />
           
           <div class="required-field-block">
               <input type="password" name="confirmPassword" id="confirmPassword" 
                  placeholder="Confirm password" class="form-control"/>
               <div class="required-icon">
                   <div class="text">*</div>
               </div>
           </div>
           <br />
           
           <input type="submit" class="btn btn-primary" value="Register"/>
       </form>

   </div><!--/.container-->
</body>
</html>