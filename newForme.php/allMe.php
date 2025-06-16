<!DOCTYPE html>
<html lang="en">
<html>
    <a href=""></a>
    
    <body>       
    <form action="meee.php" method="POST">
        Name: <input type="text" name="name" style="border:4px #2e003e;" ><br>
        Surname: <input type="text" name="surname" style="border:4px #2e003e;" width:20px><br>
        Age:<input type="number" name="age" style="border:4pxrgb(52, 0, 69);" width:30px><br>
        Course<input type="text" name="Course" style="border:4px #2e003e;" ><br>
       Select your: <select name="YearofStudy" id="YearofStudy" required style="border:2px #2e003e;"  width:30px border-radius: 0 75px 0 75px;><br>
  <option value="">-- YearofStudy --</option>
  <option value="1st">1st </option>
  <option value="2nd">2nd </option>
  <option value="3rd">3rd </option>
  <option value="4th">4th </option>
</select>
<br>
 <input type="submit">
    </form>
  <div class="container">
    <div class="item">item</div>
  </div>
  </body>
    <style>
      body{
       background-color:#0088ff;
       backdrop-filter: blur(10px);
      }
      form:hover{
        transition: all 2s ease-in-out ;
        transform: translateX(20px) translateY(-50px);
      }

      .container {
  justify-content: center;
  display: flex;
}

.container .item {
  width: 380px;
  height: 550px;
  border: 2px solid cyan;
  box-shadow:0 0 15px #00ffff, 
  0 0 60px #0088ff; 
  position: relative;
  bottom: 545px;
  right: 50px;
  z-index: -1;
  border-radius:25px 25px 25px 25px;
  

  background:linear-gradient(to right, #6a11cb, cyan);
  color: #2e003e;
  opacity: 0.8;
}
 
      form{
        box-sizing: 10px;
        margin-top: 6%;
        flex-wrap: wrap;
        display: flex;
        margin-left:35% ;
         max-width:90%;
         height: 500px;
        border:2px #2e003e;;
        width:350px;
        color:white;
        padding: 25px;
        border-radius: 25px 25px 25px 25px;
        background: linear-gradient(to right, #6a11cb, cyan); 
       box-shadow:
       0 0 15px #00ffff, 
                        0 0 60px #0088ff;
  }

      input[type="text"],
       input[type="number"]{
       text-align: center;
       font-weight: 2px;
       font-size: medium;
        border-radius: 0 70px 0 70px;
        margin:10px 0; 
         width: 95%;
        padding: 10px;
         border: 2px #2e003e;
        background-color: #f0f8ff;
          color: black;
 
      }
        

        input[type="submit"]{
        border-radius: 70px 70px 70px 70px;

    width: 100%;
    padding: 10px;
    margin-top: 15px;
    border: 2px#2e003e; 
    border-radius: 70px;
    background-color:lightskyblue;
    color: black;
    font-weight: bold;
    cursor: pointer;

        }
        label {
    font-weight: bold;
  }

  @media screen and (max-width: 500px) {
    form {
      width: 95%;
      background-color:black;
    }
  }
        </style>
        <div class="hearts">
    <img src="C:\Users\hp\Downloads\gradient-heart-studio.png" alt="Whatsapp">
</div>

   </html>
