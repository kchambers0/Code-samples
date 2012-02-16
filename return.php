<?php
include("connect.php");
$got=$_GET['type'];
$query="SELECT * FROM Kett_3d WHERE type='".$got."'";//.$got;
$result=mysql_query($query);
if(!$result){
	die("Could not query the database:<br />". mysql_error());
}
switch($got){
case "work":
echo "<h2><a href=\"javascript:;\" id=\"anims\">Animation</a></h2><h2><a href=\"javascript:;\" id=\"modl\">Modeling</a></h2>";
echo "<ul>";
while($row=mysql_fetch_assoc($result)){
?>
<li><a href="img/3d Stills/resize/<?=$row['image'];?>" class="lightbox" rel="shadowbox"><img src="img/3d Stills/thumbs/<?=$row['thumb'];?>" height="100" width="100" /></a></li>
<?php
}
echo "</ul>";
break;
case "anim":
echo "<h2><a href=\"javascript:;\" id=\"anims\">Animation</a></h2><h2><a href=\"javascript:;\" id=\"modl\">Modeling</a></h2>";
echo "<ul>";
while($row=mysql_fetch_assoc($result)){
?>
<li><iframe title="YouTube video player" width="425" height="349" src="http://www.youtube.com/embed/<?=$row['image']?>" frameborder="0" allowfullscreen></iframe></li>
<?php
}
echo "</ul>";
break;

case "demo":
echo "<h2>Demo Reel</h2>";
?>
<iframe title="YouTube video player" width="425" height="349" src="http://www.youtube.com/embed/gDRQP_IQ64I" frameborder="0" allowfullscreen></iframe>
<?php
break;
case "art":
echo "<h2><a href=\"javascript:;\" id=\"lifes\">Life Drawings<a></h2><h2><a href=\"javascript:;\" id=\"art\">Concept Art</a></h2>";
echo "<ul>";
while($row=mysql_fetch_assoc($result)){
?>
<li><a href="img/Concept_Art 01/resize/<?=$row['image'];?>" class="lightbox"><img src="img/Thumbnails/Concept/<?=$row['image'];?>" height="100" width="100" /></a></li>
<?php
}
echo "</ul>";
break;
case "life":
echo "<h2><a href=\"javascript:;\" id=\"lifes\">Life Drawings</a></h2><h2><a href=\"javascript:;\" id=\"art\">Concept Art</a></h2>";
echo "<ul>";
while($row=mysql_fetch_assoc($result)){
?>
<li><a href="img/Life_Drawings/resize/<?=$row['image'];?>" class="lightbox"><img src="img/Thumbnails/<?=$row['image'];?>" height="100" width="100" /></a></li>
<?php
}
echo "</ul>";
break;
case "talk":
echo "<h2>Contact</h2>"
?>
<p>Kett McKenna Jr.</p><br />
<p>KettMcKennaJr@Gmail.com</p><br />
<a href="img/Resume.docx">Word Document Resume</a><br /><br />
<a href="img/Resume.pdf">PDF Resume</a>
<?php
break;

}