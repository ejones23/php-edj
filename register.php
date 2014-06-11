<?php
   session_start();
   if(!isset($_SESSION['loggedIn']))
   {
      header("Location: login.php");
      die();
   }
?>
<!DOCTYPE html> 
<html>
   <head>
      <title>Festival Registry</title>
      <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script src="form.js"></script>
      <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
      <script>
         function prep()
         {
            populateTimeSlots();
         }
         function populateTimeSlots()
         {
            var slots = "";
            var hr = 7;//starting from 8:00 am (0 represents 1:00)
            var min = ["00", "15", "30", "45"];
            while (hr < 16)//going until 5:00 pm
            {
               for (var i = 0; i < 4; i++)
               {
                  slots += "<option>";
                  slots += (hr % 12) + 1;
                  slots += ":" + min[i];
                  if (hr >= 11)//it's past noon
                     slots += " pm";
                  else 
                     slots += " am";
                     
                  slots += "</option>";
               }
               hr++;
            }
            document.getElementById("timeSlots").innerHTML += slots;
         }
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
               else if (field.type == "select-one" && field.selectedIndex=="0")//nothing is selected yet 
               {
                  errMsg += (field[0].innerHTML.slice(0,-1) + " has no selected value. Please select and try again.\n\n");
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
               //alert("returning true");//BUG: when all text fields are filled but one or more lists are not selected, page submits without even completing this alert. Why?
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
<body onload="prep()">
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
              <li class="active"><a href="register.php">Festival Registration</a></li>
              <li><a href="verify.php">Festival Verification</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php">Emmanuel Jones Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>
      
      <form style="width:400px; margin: 0 auto;" method="POST" action="register.php" onsubmit="return allFilled()"
         id="festival"> 
           <h1>Register Your Student:</h1>
           
           <div class="required-field-block">
               <input type="text" id='firstName' name="firstName" placeholder="First Name" class="form-control">
               <br/>
           </div>
           
           <div class="required-field-block">
               <input type="text" name="lastName" placeholder="Last Name" class="form-control">
               <br/>
           </div>
           
           <div class="required-field-block">
               <input type="text" name="studentID" placeholder="Student ID" class="form-control">
               <br/>
           </div>
           
           <div class="required-field-block">
               <select name="type" class="form-control">
               <option>Instrument type:</option>
<?php
   require("dbConnector.php");

   try
   {
      $db = loadDatabase('music_festival');
      
      $statement = $db->query("SELECT type FROM instrument;");     
      
      while($row = $statement->fetch(PDO::FETCH_ASSOC))
      {
         echo "<option>".$row['type']."</option>\n";
      }
   }
   catch (PDOException $ex)
   {
      print "Error!: " . $ex->getMessage() . "<br />";
      die();
   }
?>
               </select>
               <br/>
           </div>
           
           <div class="required-field-block">
               <select name="level" class="form-control">
               <option>Skill level:</option>
                  <?php
                     $statement = $db->query("SELECT name FROM skill_level;");
                     while($row = $statement->fetch(PDO::FETCH_ASSOC))
                     {
                        echo "<option>".$row['name']."</option>\n";
                     }
                  ?>
               </select>
               <br/>
           </div>
           
           <div class="required-field-block">
               <select name="location" class="form-control">
               <option>Location:</option>
                  <?php
                     $statement = $db->query("SELECT b.name, r.room_num 
                        FROM building b 
                        INNER JOIN room r ON b.id=r.building_id;");    
                     while($row = $statement->fetch(PDO::FETCH_ASSOC))
                     {
                        echo "<option>".$row['name']. " " . $row['room_num'] . "</option>\n";
                     }
                  ?>
               </select>
               <br/>
           </div>
           
           <!--TODO: add "Day" select list-->
           <!--TODO: handle 'pm' entries in DB (e.g., '3:00 pm' should be '15:00:00')-->
           
           <div class="required-field-block">
               <select name="time" id="timeSlots" class="form-control">
                  <option>Time:</option>
               </select>
               <br/>
           </div>
           
           <input type="submit" class="btn btn-primary" value="Send" name="sendData"/>
       </form>
   </div><!--/.container-->
<?php
   if(isset($_POST['sendData']))
   {
      if($_POST['sendData'] == 'Update')
      {
         //delete old performance
         $oldID = $_POST['oldID'];
         $statement = $db->prepare("DELETE FROM performance
            WHERE student_id = (SELECT id FROM student WHERE student_id = :oldID)
            ");
         $statement->bindvalue(":oldID", $oldID, PDO::PARAM_INT);
         $statement->execute();
         // $statement->debugDumpParams();
         // echo var_export($statement->errorInfo());
         
         //delete old student 
         //TODO: use MySQL UPDATE instead. It makes a lot more sense here.
         $statement = $db->prepare("DELETE FROM student WHERE student_id = :oldID");
         $statement->bindvalue(":oldID", $oldID, PDO::PARAM_INT);
         $statement->execute();
         // $statement->debugDumpParams();
         // echo var_export($statement->errorInfo());
      }
      
      try {
         //insert new registry
         $firstName = $_POST['firstName'];
         $lastName = $_POST['lastName'];
         $studentID = $_POST['studentID'];
         $type = $_POST['type'];
         $level = $_POST['level'];
         $location = explode(" ", $_POST['location']);
            $room = $location[count($location) - 1];
            $building = implode(" ", array_slice($location, 0, -1));
         $time = $_POST['time'];
         
         //TODO: separate student insertion and performance insertion in UI for better cohesion
         
         //add student if new
         $statement = $db->prepare("INSERT INTO student(student_id, first_name, last_name, skill_level) 
            VALUES (:studentID, :firstName, :lastName, (SELECT id FROM skill_level WHERE name=:level));");
         $statement->bindvalue(":studentID", $studentID, PDO::PARAM_INT);
         $statement->bindvalue(":firstName", $firstName, PDO::PARAM_STR);
         $statement->bindvalue(":lastName", $lastName, PDO::PARAM_STR);
         $statement->bindvalue(":level", $level, PDO::PARAM_INT);
         $success = $statement->execute();
         
         //$statement->debugDumpParams();
         //echo var_export($statement->errorInfo());
         
         if(!$success) 
         {
            JSAlert('We couldn\'t insert that student\'s information into the database.\n'.
            'Possibly, there already exists a student with that ID.\n'.
            'Please check the information and try again.');
         }
         else 
         {
            //add performance
               $dateOnly='2014-07-14';//stub date
               $timeOnly=explode(" ", $time)[0] . ":00";
               $dateTime=$dateOnly . " " . $timeOnly;
            $statement = $db->prepare("INSERT INTO performance(student_id, room_id, instrument_id, time) 
               VALUES (
                  (SELECT id FROM student s WHERE s.student_id=:studentID), 
                  (SELECT id FROM room WHERE room_num=:room), 
                  (SELECT id FROM instrument WHERE type=:type), 
                  :dateTime);");
            $statement->bindvalue(":studentID", $studentID, PDO::PARAM_INT);
            $statement->bindvalue(":room", $room, PDO::PARAM_STR);
            $statement->bindvalue(":type", $type, PDO::PARAM_STR);
            $statement->bindvalue(":dateTime", $dateTime, PDO::PARAM_INT);
            $success = $statement->execute();
            
            //$statement->debugDumpParams();
            //echo var_export($statement->errorInfo());
            
            if($success) JSAlert('Registration successful! Check the verification page for details.\n'); 
            else JSAlert('Registration failed. Please check the data and try again.\n');//TODO: make this more helpful
         }
      }
      catch (PDOException $ex)
      {
         print "Error!: " . $ex->getMessage() . "<br />";
         die();
      }
   }
?>
</body>
</html>