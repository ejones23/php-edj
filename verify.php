<!DOCTYPE html>
<html>
   <head>
      <title>Festival Verification</title>
      <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script src="form.js"></script>
      <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="form.css"/>
      <!--<base href="http://localhost:8880/ejones/festival/"/><!--eliminate for OpenShift-->
      <script>
      function allFilled()
      {
         var form = document.getElementById("festival");
         var errMsg = '';
         var errField;
         for (var i = 0; i < form.length; i++)
         {
            var field = form.elements[i];
            if (field.type == "text")
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
            return false;
         }
         else
         {
            return true;
         }
      }   
      </script>
   </head>
<body>

   <div class="container">
      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="festival.html">Festival Home</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="register.php">Festival Registration</a></li>
              <li class="active"><a href="verify.php">Festival Verification</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php">Emmanuel Jones Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>
      
      <form style="width:400px; margin: 0 auto;" method="GET" action="verify.php" onsubmit="return allFilled()"
         id="festival"> 
           <h1>Verify Registration Info:</h1>
           
           <div class="required-field-block">
               <input type="text" name="firstName" placeholder="First Name" class="form-control">
               <div class="required-icon">
                   <div class="text">*</div>
               </div>
           </div>
           
           <div class="required-field-block">
               <input type="text" name="lastName" placeholder="Last Name" class="form-control">
               <div class="required-icon">
                   <div class="text">*</div>
               </div>
           </div>
           
           <div class="required-field-block">
               <input type="text" name="studentID" placeholder="Student ID" class="form-control">
               <div class="required-icon">
                   <div class="text">*</div>
               </div>
           </div>
           
           <input type="submit" class="btn btn-primary" value="Send"/>
       </form>
<?php

   if(isset($_GET['firstName']) && isset($_GET['lastName']) && isset($_GET['studentID']))
   {
      $firstName = $_GET['firstName'];
      $lastName = $_GET['lastName'];
      $studentID = $_GET['studentID'];
      
      require("dbConnector.php");

      try
      {
         $db = loadDatabase('music_festival');
         
         $statement = $db->prepare("SELECT s.first_name, s.last_name, s.skill_level, i.type, b.name, r.room_num, p.time FROM student s INNER JOIN performance p ON p.student_id=s.student_id INNER JOIN instrument i ON i.id=p.instrument_id INNER JOIN room r ON p.room_id=r.id INNER JOIN building b ON r.building_id=b.id WHERE s.first_name=:firstName AND s.last_name=:lastName AND s.student_id=:studentID");
         $statement->bindValue(":firstName", $firstName, PDO::PARAM_STR);
         $statement->bindValue(":lastName", $lastName, PDO::PARAM_STR);
         $statement->bindValue(":studentID", $studentID, PDO::PARAM_INT);
         $statement->execute();
         
         echo "<br />";
         echo "<div class='col-md-2'></div><!--to scoot it over-->";
         echo '<div class="col-md-9">';
         echo '<div class="jumbotron">';
         
         if($row = $statement->fetch(PDO::FETCH_ASSOC))
         {
            echo "<h2>Showing registry information for " . $row['first_name'] . " " 
               . $row['last_name'] . ":</h2>";
            echo "<p>Performance Type: <strong>" . $row['type'] . "</strong></p>";
            echo "<p>Skill Level: <strong>" . $row['skill_level'] . "</strong></p>";
            echo "<p>Building: <strong>" . $row['name'] . "</strong></p>";
            echo "<p>Room #: <strong>" . $row['room_num'] . "</strong></p>";
            $dateTime = explode(" ", $row['time']);
            echo "<p>Date: <strong>" . $dateTime[0] . "</strong></p>";
            echo "<p>Time: <strong>" . $dateTime[1] . "</strong></p>";
            echo "<p>See any errors? Click <a href='updateRegistry.php'>here</a> to change any information.</p>";
            
         }
         else
         {
            echo "<p>No student with that exact name/ID combination was found in our database.</p>"
               . "<p>Please check the information and try again.</p>"
               . "<p>If you continue to experience difficulties, please contact our office at (735)735-7355.</p>";
         }
         
         echo '</div><!--/.jumbotron-->';
         echo '</div><!--/.col-md-7-->';
      }
      catch (PDOException $ex)
      {
         print "Error!: " . $ex->getMessage() . "<br />";
         die();
      }
   }
?>
   </div><!--/.container-->
</body>
</html>