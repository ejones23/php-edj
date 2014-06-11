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
      <title>Festival Registry Modification</title>
      <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script src="form.js"></script>
      <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="form.css"/>
      <script>
         //TODO: integrate this page into 'register.php', since it's very similar
         function prep()
         {
            populateTimeSlots();
            getSelections();
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
         function getSelections()
         {
            selectInstType();
            selectSkillLevel();
            selectLocation();
            selectTime();
         }
         function selectInstType()
         {
            document.getElementById('type').value="<?php echo $_GET['type']; ?>";
         }
         function selectSkillLevel()
         {
            document.getElementById('level').value="<?php echo $_GET['level']; ?>";
         }
         function selectLocation()
         {
            document.getElementById('location').value="<?php echo $_GET['building'] . ' ' . $_GET['roomNum']; ?>";
         }
         function selectTime()
         {
            //TODO: this is messy... to improve, try using PHP date/time functions everywhere
            var time = "<?php 
               $timeAll = explode(':', $_GET['timeOnly']);
               $hour = $timeAll[0];
               $min = $timeAll[1];
               if($hour[0] == '0') $hour = substr($hour,1);
               $out = $hour . ':' . $min;
               if($hour < 12 && $hour > 7)
               {
                  $out .= ' am';
               }
               else
               {
                  $out .= ' pm';
               }
               echo $out; 
            ?>";
            document.getElementById('timeSlots').value=time;
         }
      </script>
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
              <li><a href="register.php">Festival Registration</a></li>
              <li><a href="verify.php">Festival Verification</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php">Emmanuel Jones Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div><!--/.navbar navbar-default-->
      
      <form style="width:400px; margin: 0 auto;" method="POST" action="register.php" 
         onsubmit="return allFilled()" id="festival"> 
           <h1>Updating Registration:</h1>
           
           <div class="required-field-block">
               <label for='firstName'>First Name:</label>
               <input type="text" id='firstName' name="firstName" placeholder="First Name" class="form-control"
               value="<?php echo $_GET['firstName']; ?>" />
               <br/>
           </div>
           
           <div class="required-field-block">
               <label for='lastName'>Last Name:</label>
               <input type="text" id='lastName' name="lastName" placeholder="Last Name" class="form-control"
               value="<?php echo $_GET['lastName']; ?>" />
               <br/>
           </div>
           
           <div class="required-field-block">
               <label for='studentID'>Student ID:</label>
               <input type="text" id="studentID" name="studentID" placeholder="Student ID" class="form-control"
               value='<?php echo $_GET['studentID']; ?>'/>
               <br/>
           </div>
           
           <div class="required-field-block">
               <select name="type" id='type' class="form-control">
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
               <select name="level" id='level' class="form-control">
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
               <select name="location" id='location' class="form-control">
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
           
           <div class="required-field-block">
               <select name="time" id="timeSlots" class="form-control">
                  <option>Time:</option>
               </select>
               <br/>
           </div>
           
           <input type="submit" class="btn btn-primary" value="Update" name="sendData"/>
           <input type="reset" class="btn btn-primary" value="Cancel" name="cancel"/>
           <input type='hidden' name='oldID' value="<?php echo $_GET['studentID']; //the old student ID,
                                                                                 //in case it was updated ?>"/>
           
       </form>
   </div><!--/.container-->
</body>
</html>