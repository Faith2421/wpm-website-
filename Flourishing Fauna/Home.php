<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FarmTrack - Livestock Management System</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f9f0;
      color: #333;
    }
    header {
      background: #228B22;
      color: #fff;
      padding: 1rem;
      text-align: center;
    }
    nav {
      display: flex;
      background: #145214;
      justify-content: space-around;
      padding: 0.5rem 0;
    }
    nav a {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
    }
    .container {
      padding: 2rem;
    }
    .section {
      display: none;
    }
    .active {
      display: block;
    }
    label, input, select, textarea {
      display: block;
      margin-bottom: 1rem;
      width: 100%;
      max-width: 500px;
    }
    input, select, textarea {
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background: #228B22;
      color: #fff;
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background: #1a6b1a;
    }
    input[type="submit"]{
        border-radius: 10px 10px 10px 10px;
        width:60px; 
        border: solid 2px #228B22;
    }
  </style>
</head>
<body>
  <header>
    <h1>Flourishing Fauna</h1>
  </header>
  <nav>
    <a href="#" onclick="showSection('farmer')">Farmer Details</a>
    <a href="#" onclick="showSection('livestock')">Livestock Info</a>
    <a href="#" onclick="showSection('health')">Health Records</a>
    <a href="#" onclick="showSection('breeding')">Breeding Info</a>
    <a href="#" onclick="showSection('feeding')">Feeding & Nutrients</a>
    <a href="#" onclick="showSection('mortality')">Mortality Rates</a>
    <a href="#" onclick="showSection('movement')">Movement & Sales</a>
  </nav>

  <div class="container">
    <div id="farmer" class="section active">
      <h2>Farmer Details</h2>
      <form action="livestock_backend.php" method="POST">
      <input type="text"  name="farmerName" placeholder="Farmer Name" />
      <input type="text"  name="farmName" placeholder="Farm Name" />
      <input type="text" name ="contactNumber" placeholder="Contact Number" />
      <input type="email"  name="emailAddress" placeholder="Email Address" /> 
      <br>
      <input type="submit">
    </form>
    </div>

    <div id="livestock" class="section">
      <h2>Livestock Information</h2>
      <form action="livestock_backend.php" method="POST">
      <input type="text" name="animalID" placeholder="Animal ID" />
      <input type="text" name="species"placeholder="Species (e.g. Cattle, Goats)" />
      <input type="date" name="dateOfBirth"placeholder="Date of Birth" />
      <input type="text" name="breed" placeholder="Breed" />
      <br>
      <input type="submit">
    </form>
    </div>

    <div id="health" class="section">
      <h2>Health Records</h2>
      <form action="livestock_backend.php" method="POST">
      <input type="text" name="animalID" placeholder="Animal ID" />
      <input type="date" name="checkupDate" placeholder="Checkup Date" />
      <textarea type="text" name="healthNotes" placeholder="Health Notes"></textarea>  
      <br>
      <input type="submit">
    </form>
    </div>

    <div id="breeding" class="section">
      <h2>Breeding Information</h2>
      <form action="livestock_backend.php" method="POST">
      <input type="text" name="animalID" placeholder="Animal ID" />
      <input type="date" name="breedingDate" placeholder="Breeding Date" />
      <input type="text" name="sireID"placeholder="Sire ID" />
      <textarea name="breedingNotes" placeholder="Breeding Notes"></textarea>
      <br>
      <input type="submit">
    </form>
    </div>

    <div id="feeding" class="section">
      <h2>Feeding & Nutrients</h2>
      <form action="livestock_backend.php" method="POST">
      <input type="text" name="animalID" placeholder="Animal ID" />
      <textarea  name="feedingScheduleNotes" placeholder="Feeding Schedule / Notes"></textarea>
      <br>
      <input type="submit">
    </form>
    </div>

    <div id="mortality" class="section">
      <h2>Mortality Rates</h2>
      <form action="livestock_backend.php" method="POST">
      <input type="text" name="animalID" placeholder="Animal ID" />
      <input type="date"name="dateOfDeath" placeholder="Date of Death" />
      <textarea name="cause" placeholder="Cause / Additional Info"></textarea>
      <br>
      <input type="submit"> 
    </form>
    </div>
    

    <div id="movement" class="section">
      <h2>Movement & Sales Records</h2>
      <form action="livestock_backend.php" method="POST">
      <input type="text" name="animalID" placeholder="Animal ID" />
      <input type="text" name="movement" placeholder="Movement Type (e.g. Sold, Moved)" />
      <input type="date" name="date" placeholder="Date" />  
      <textarea name="details" placeholder="Details"></textarea>
      <br>
      <input type="submit">
    </form>
    </div>
  

  <script>
    function showSection(id) {
      document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));
      document.getElementById(id).classList.add('active');
    }
  </script>
</body>
</html>
