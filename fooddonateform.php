<?php
// Start the session
session_start();

if ($_SESSION['email'] == '') {
    header("location: signin.php");
    exit();
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "dswproject");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$donationProcessed = false;
$donationData = [];

if (isset($_POST['submit'])) {
    // Get email from session
    $emailid = $_SESSION['email']; 

    // Get other form data
    $foodname = mysqli_real_escape_string($connection, $_POST['foodname']);
    $meal = mysqli_real_escape_string($connection, $_POST['meal']);
    $category = $_POST['image-choice'];
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
    $phoneno = mysqli_real_escape_string($connection, $_POST['phoneno']);
    $district = mysqli_real_escape_string($connection, $_POST['district']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);

    // Insert into food_donations table
    $query = "INSERT INTO food_donations(email, food, type, category, phoneno, location, address, name, quantity) 
              VALUES('$emailid', '$foodname', '$meal', '$category', '$phoneno', '$district', '$address', '$name', '$quantity')";
    $query_run = mysqli_query($connection, $query);

    // Process if query is successful
    if ($query_run) {
        $donationProcessed = true;
        $donationData = [
            'Food Name' => $foodname,
            'Meal Type' => $meal,
            'Category' => $category,
            'Quantity' => $quantity,
            'Name' => $name,
            'Phone No' => $phoneno,
            'District' => $district,
            'Address' => $address
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body style="background-color: #06C167;">
    <div class="container">
        <div class="regformf">
            <form action="" method="post">
                <p class="logo">Rescue <b style="color: #06C167;">Meals</b></p>
                <!-- Form Fields -->
                <div class="input">
                    <label for="foodname">Food Name:</label>
                    <input type="text" id="foodname" name="foodname" required/>
                </div>
                <div class="radio">
                    <label for="meal">Meal type :</label><br><br>
                    <input type="radio" name="meal" id="veg" value="veg" required/>
                    <label for="veg" style="padding-right: 40px;">Veg</label>
                    <input type="radio" name="meal" id="Non-veg" value="Non-veg" />
                    <label for="Non-veg">Non-veg</label>
                </div>
                <br>

                <div class="input">
                    <label for="food">Select the Category:</label><br><br>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food">
                        <label for="raw-food">
                          <img src="img/raw-food.png" alt="raw-food">
                        </label>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked>
                        <label for="cooked-food">
                          <img src="img/cooked-food.png" alt="cooked-food">
                        </label>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                        <label for="packed-food">
                          <img src="img/packed-food.png" alt="packed-food">
                        </label>
                    </div>
                    <br>
                </div>

                <div class="input">
                    <label for="quantity">Quantity (number of persons / kg):</label>
                    <input type="text" id="quantity" name="quantity" required/>
                </div>
                
                <b><p style="text-align: center;">Contact Details</p></b>
                <div class="input">
                    <div>
                        <label for="name" style="padding-left: 10px;">Name:</label>
                        <input type="text" id="name" name="name" required/><br>
                    </div>
                    <div>
                    <label for="email" style="padding-left: 10px;">Email Address:</label>
                    <input type="email" id="email" name="email" required/><br>
                  </div>
                    <div>
                        <label for="phoneno">Phone No:</label>
                        <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" required />
                    </div>
                </div>

                <div class="input">
                    <label for="district">District:</label><br>
                    <select id="district" name="district" style="padding:10px;">
                        <option value="bareilly" selected>Bareilly</option>
                        <option value="noida" selected>Noida</option>
                        <option value="kota" selected>Kota</option>
                        <!-- Add more cities as needed -->
                    </select><br><br>

                    <label for="address" style="padding-left: 10px;">Address:</label>
                    <input type="text" id="address" name="address" required/><br>
                </div>

                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>

            <!-- Display Donation Data and Success Message -->
            <?php if ($donationProcessed): ?>
                <div style="margin-top: 20px; padding: 10px; background-color: #f2f2f2;">
                    <h3 style="color: #6C167">Donation Processed Successfully!</h3>
                    <p><strong>Your donation details:</strong></p>
                    <ul>
                        <?php foreach ($donationData as $key => $value): ?>
                            <li><strong><?php echo $key; ?>:</strong> <?php echo $value; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p>Thank you for your generous donation!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
