<?php
// Include the necessary files
include 'c:\xampp\htdocs\ERS\Classes\connect.php';
include 'c:\xampp\htdocs\ERS\Classes\signup.php';
include 'c:\xampp\htdocs\ERS\Classes\db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a new instance of the Signup class
    $signup = new Signup();

    // Validate the input data
    $validationResult = $signup->evaluate($_POST);

    if (is_array($validationResult) && !empty($validationResult)) {
        // If there are validation errors, display them
        foreach ($validationResult as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
        exit;
    }

    // Extract user data from POST
    $first_name = trim($_POST['fname']);
    $last_name = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $user_name = trim($_POST['userName']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password
    $manager_type = strtolower(trim($_POST['managerType'])) === "true" ? 1 : 0; // Convert to boolean
    $manager_id = intval($_POST['managerID']);

    // Use the `DB` class for database operations
    $db = new DB();

    // Check if the username or email already exists
    $sqlCheck = "SELECT * FROM employee WHERE user_name = ? OR email = ?";
    $conn = $db->connect(); // Get the database connection from the `DB` class
    $stmt = $conn->prepare($sqlCheck);

    if ($stmt === false) {
        die("<p style='color: red;'>Error preparing statement: " . $conn->error . "</p>");
    }

    $stmt->bind_param("ss", $user_name, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p style='color: red;'>Username or email already exists. Please try again with different credentials.</p>";
    } else {
        // Insert the new user into the database
        $sqlInsert = "INSERT INTO employee (manager_id, first_name, last_name, email, user_name, password, manager_type) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlInsert);

        if ($stmt === false) {
            die("<p style='color: red;'>Error preparing statement: " . $conn->error . "</p>");
        }

        $stmt->bind_param("isssssi", $manager_id, $first_name, $last_name, $email, $user_name, $password, $manager_type);

        if ($stmt->execute()) {
            // Redirect to login page upon successful registration
            header("Location: login.html");
            exit;
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" 
          crossorigin="anonymous">
</head>
<body>
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-sm-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                    <div class="text-center my-5">
                        <img src="https://html.sammy-codes.com/images/small-profile.jpeg" alt="logo" width="100">
                    </div>
                    <div class="card shadow-lg">
                        <div class="card-body p-5">
                            <h1 class="fs-4 card-title fw-bold mb-4">Register</h1>
                            <form method="POST" class="needs-validation" novalidate autocomplete="off">
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="fname">First Name</label>
                                    <input id="fname" type="text" class="form-control" name="fname" required>
                                    <div class="invalid-feedback">
                                        First Name is required
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="lname">Last Name</label>
                                    <input id="lname" type="text" class="form-control" name="lname" required>
                                    <div class="invalid-feedback">
                                        Last Name is required
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="email">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control" name="email" required>
                                    <div class="invalid-feedback">
                                        A valid email is required
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="userName">Username</label>
                                    <input id="userName" type="text" class="form-control" name="userName" required>
                                    <div class="invalid-feedback">
                                        Username is required
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="password">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" required>
                                    <div class="invalid-feedback">
                                        Password is required
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="managerType">Manager Type (True or False)</label>
                                    <input id="managerType" type="text" class="form-control" name="managerType" required>
                                    <div class="invalid-feedback">
                                        Manager Type is required
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="managerID">Manager ID</label>
                                    <input id="managerID" type="number" class="form-control" name="managerID" required>
                                    <div class="invalid-feedback">
                                        Manager ID is required
                                    </div>
                                </div>
                                <div class="align-items-center d-flex">
                                    <button type="submit" class="btn btn-primary ms-auto">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer py-3 border-0">
                            <div class="text-center">
                                Already have an account? <a href="login.html" class="text-dark">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
