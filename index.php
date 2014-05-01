<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link rel="stylesheet" type="text/css" href="base.css"/>
  <title>Home Page: Emmanuel Jones</title>
  <script>
   function showHobby(show)
   {
      var img = document.getElementById("image");
      if (show == 'true') img.src="Telescope.JPG";
      else img.src="Piano.jpg";
   }
  </script>
</head>
<body>
   <h1>Emmanuel Jones' Home Page</h1><hr/>
   <div class="menu">
      <a href="index.php">About me</a><br/>
      <a href="assignments.html">Assignments</a>
   </div>
   <div class="right">
      <img id="image" src="Piano.jpg" alt="My new baby!" width="200" height="200"
         onmouseover="showHobby('true')" onmouseout="showHobby('false')"/>
      <br/>
      <span>Hover to see my new baby!</span>
   </div>
   <div class="middle">
      <p class="bio">My name is Emmanuel Jones, and I am a Computer Science major at 
            Brigham Young University, Idaho. <br/><br/>
         Now, if what <i>you</i> like to do with <i>your</i> spare time is read what 
            <i>other</i> people like to do with <i>their</i> spare time - well, then you've 
            come to the right place!<br/><br/>
         <b>Hobby #1:</b> Looking at planets through my telescope. Talk about stellar!<br/><br/>
         <b>Hobby #2:</b> Foraging for wild and edible plants. (Hey, I've got to cut the college food budget somewhere...)<br/><br/>
         <b>Hobby #3:</b> Playing piano. Classical pieces ROCK! (Not literally, of course...)<br/><br/>
         <b>Hobby #4:</b> Coding for the Web! I love seeing changes reflected so fast.<br/><br/>
         Click on the links to the left to explore the site. Please, enjoy your visit!<br/><br/>
      </p>
   </div>
</body>
</html>
