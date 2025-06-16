<div style="background: linear-gradient(to right, #6a11cb, cyan); color: white;width:100%">
<?php
// Retrieve data
$name = $_POST["name"]; 
$surname = $_POST["surname"];
$age = $_POST["age"]; 
$Course = $_POST["Course"];
$YearofStudy = $_POST["YearofStudy"];
?>
Welcome 
 <?php echo  $_POST["name"];
 ?>
 <?php echo $_POST["surname"]; 
 ?><br>
 You are
 <?php echo $_POST["age"] .  "years old ";
 ?><br>
 You  are in 
 <?php echo $_POST["YearofStudy"] . " year in the bachelor of " . $_POST["Course"];
 ?><br>



 <?php 
 function daysInMonth($month){
    //each month and the days they end eg January end at 31 days //
 $day =[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
 //indicate month is between 1 and 12//(parametre)
 if ($month >= 1 && $month <= 12) {
    //we need the right index// january is the 1st one however we need it to be at index 0(as its an ARRAY)
    return $day[$month-1];
 }else{
   return  "Invalid month. Please enter a number between 1 and 12.";
 }

}
//indicate a month nr ,2 is february which indicates its february which is the determinator of if its a LEAP YEAR//
echo daysInMonth(3,true);//
?><br>
<?php const ID = 25;
echo ID;
?>
