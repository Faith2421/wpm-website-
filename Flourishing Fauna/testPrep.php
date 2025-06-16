<!DOCTYPE html>
<html lang="en">
<form action ="testPrep.php" method="POST">
Name:<input type ="text" name="name"><br>
<p> Gender:<br/>
<input type ="radio" name ="Female" value="female">female<br>
<input type ="radio" name = "Male" value="Male">Male<br></p>
<p>Feeling:<br/>
<input type= "checkbox" name ="happy" option="happy">happy<br>
<input type="checkbox" name="sad" option="sad">Sad<br></p>
Select:<select name = "moodNumber" id="moodNumber" required style ><br>
    <option value="">--moodNumber--</option>
    <option value ="10">10</option>
    <option value="1">1</option>
    </select>