<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
   <link href="navbar.css" rel="stylesheet">
   <title>PHP Survey Results</title>
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
              <li><a href="survey.php">Take Survey</a></li>
              <li class="active"><a href="#">View Survey Results</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="survey.php">Emmanuel Jones Home</a></li><!--FIX-->
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>
      
      <div class="jumbotron">
<?php                                              
   //check if data should be written
   if ($_SERVER["REQUEST_METHOD"] == "POST")
   {
      $file = fopen("results.txt", "a");
      $line = htmlspecialchars($_POST["major"] . "|" . $_POST["provider"] 
         . "|" . $_POST["satisfaction"] . "|" . $_POST["recommend"] . "\n");
      fwrite($file, $line);
   }
   //load current data
   $majors = array("Computer Engineering"=>array(), "Computer Information Technology"=>array(), 
      "Computer Science"=>array(), "Electrical Engineering"=>array(), "Software Engineering"=>array(), 
      "Other"=>array());
   $data = file_get_contents("results.txt");
   $lines = explode("\n", $data);
   foreach ($lines as $line)
   {
      $temp = explode("|", $line);
      //load each line into the array that corresponds to its major
      if ($temp[0]!=NULL)
      {
         array_push($majors[$temp[0]], $temp);
      }
   }
   
   //display data
   $out = "";
   foreach($majors as $major)
   {
      if (($cnt = count($major)) != 0)
      {
         $out .= "<h2>$cnt {$major[0][0]} majors say:</h2>";
         $out .= makeTable($major);
      }
   }
   echo $out;
?>
      </div><!--class="jumbotron"-->

<?php

   //function definitions   
   function makeTable($major)
   {      
      $numUsers = array("Linux"=>0,"Macintosh"=>0,"Windows"=>0,"Other"=>0);
      $satisfaction = array("Linux"=>0,"Macintosh"=>0,"Windows"=>0,"Other"=>0);
      $yesCount = array("Linux"=>0,"Macintosh"=>0,"Windows"=>0,"Other"=>0);
      foreach($major as $line)
      {
         $numUsers[$line[1]]++;
         $satisfaction[$line[1]] += $line[2];
         if($line[3]=="Yes") $yesCount[$line[1]]++;
      }
      
      $out = "<table class='table table-bordered'>";
      
      $out .= "<tr><th></th>";
      foreach($numUsers as $name=>$value) 
      {
         $out .= "<th>$name ($value)</th>";
      }
      $out .= "</tr>";
      
      $out .= "<tr>";
      $out .= "<td>Average satisfaction</td>";
      foreach($satisfaction as $name=>$value)
      {
         if ($numUsers[$name]!=0)
         {
            $avg = round($value / $numUsers[$name], 1);
         } else {
            $avg = "N/A";
         }
         $out .= "<td>$avg</td>";
      }
      $out .= "</tr>";
      
      $out .= "<tr>";
      $out .= "<td>Recommenders/Users</td>";
      foreach($yesCount as $name=>$value)
      {
         $out .= "<td>$value/{$numUsers[$name]}</td>";
      }
      $out .= "</tr>";
      
      $out .= "</table>";
      return $out;
   }
?>
   
   </div><!--class="container"-->
</body>

</html>