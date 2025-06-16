<?php
//Farmer Details
$farmerName= $_POST["farmerName"]; 
$farmName = $_POST["farmName"];
$contactNumber = $_POST["contactNumber"]; 
$emailAddress = $_POST["emailAddress"];
$YearofStudy = $_POST["YearofStudy"];

echo "Form submitted successfully!";

//Livestock Information
$animaID= $_POST["animalID"];
$species = $_POST["species"];
$DateofBirth =$_POST["DateofBirth"];
$breed =$_POST["breed"];

echo "Form submitted successfully!";

//Health Records
$animaID= $_POST["animalID"];
$CheckupDate= $_POST["CheckupDate"];
$healthNotes=$_POST["healthNotes"];

echo "Form submitted successfully!";


//Breeding Information
$animalID= $_POST["animalID"];
$breedingDate = $_POST["breedingDate"];
$sireId =$_POST["sireID"];
$breedingNotes=$_POST["breedingNotes"];

echo "Form submitted successfully!";

//Feeding & Nutrients
$animaID= $_POST["animalID"];
$breedingDate = $_POST["breedingDate"];

echo "Form submitted successfully!";

//Mortality Rates
$animaID= $_POST["animalID"];
$DateofDeath = $_POST["DateofDeath"];
$Cause =$_POST["Cause"];

echo "Form submitted successfully!";

//Movement & Sales Records
$animaID= $_POST["animalID"];
$Movement = $_POST["Movement"];
$Date= $_POST["Details"];

echo "Form submitted successfully!";

?>
