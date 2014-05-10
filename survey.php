<?php
   session_start();
   if (isset($_SESSION["views"]))
   {
      header("Location: results.php");
      die();
   } else {
      $_SESSION["views"]=1;
   }
?>

<!DOCTYPE html>
<html>
<head>
   <title>PHP Survey</title>
   <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
   <link href="navbar.css" rel="stylesheet">
   <script>
      function checkFields()
      {
         var form = document.getElementById("form");
         for (var i = 0; i < form.length; i++)
         {
            var field = form.elements[i];
            if(field.type=="select-one" && field.value=="Please select:")
            {   
               alert("One or more options have not been selected.\n"
                  + " Please fill out and resubmit.\n");
               return false;
            }
         }
         return true;
      }
   </script>
</head>
<body>
   <div class="container">
      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">PHP Survey</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Take Survey</a></li>
              <li><a href="results.php">View Survey Results</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#">Emmanuel Jones Home</a></li>            <!--FIX THIS!!!-->
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>

   <div class="col-md-3"></div><!--to scoot it over-->
   
   <div class="col-md-7">
      <div class="jumbotron">
         <form id="form" role="form" action="results.php" method="POST" onsubmit="return checkFields()">
            <div class="form-group">
               <h2>OS Satisfaction Survey</h2><br />
               <label for="major">What is your Major?</label>
               <select id="major" name="major">
                  <option>Please select:</option>
                  <option>Computer Engineering</option>
                  <option>Computer Information Technology</option>
                  <option>Computer Science</option>
                  <option>Electrical Engineering</option>
                  <option>Software Engineering</option>
                  <option>Other</option>
               </select>
            </div>
            <div class="form-group">
               <label for="provider">Who is your operating system provider?</label>
               <select id="provider" name="provider">
                  <option>Please select:</option>
                  <option>Linux</option>
                  <option>Macintosh</option>
                  <option>Windows</option>
                  <option>Other</option>
               </select>
            </div>
            <div class="form-group">
               <label for="satisfaction">What is your overall satisfaction with this provider?</label>
               <select id="satisfaction" name="satisfaction">
                  <option>Please select:</option>
                  <?php for ($i=1;$i<=10;$i++) echo "<option>$i</option>";?>
               </select>
            </div>
            <div class="form-group">
               <label for="recommend">Would you recommend this provider to others?</label>
               <select id="recommend" name="recommend">
                  <option>Please select:</option>
                  <option>Yes</option>
                  <option>No</option>
               </select>
            </div>
            <button type="submit" class="btn btn-default">Submit answers</button>
         </form>
      </div>
   </div>
</div>
</body>
</html>