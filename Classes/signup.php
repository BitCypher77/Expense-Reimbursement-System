<?php

class Signup
{
    private $errors = [];

    // Function to evaluate user input
    public function evaluate($data)
    {
        // Check for empty fields
        foreach ($data as $key => $value) {
            if (empty(trim($value))) {
                $this->errors[] = ucfirst($key) . " cannot be empty.";
            }
        }

        // Check for a valid email address
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid email address.";
        }

        // Check if the first name contains only numbers
        if (isset($data['fname']) && is_numeric($data['fname'])) {
            $this->errors[] = "First name cannot be numeric.";
        }

        // Validate manager type (must be "true" or "false")
        if (!isset($data['managerType']) || !in_array(strtolower($data['managerType']), ['true', 'false'])) {
            $this->errors[] = "Manager type must be 'true' or 'false'.";
        }

        // Validate manager ID (must be a number)
        if (isset($data['managerID']) && !is_numeric($data['managerID'])) {
            $this->errors[] = "Manager ID must be a number.";
        }

        // Return errors, if any
        if (!empty($this->errors)) {
            return $this->errors;
        }

        return true;
    }

    // Function to create a new user in the database
    public function create_user($data)
    {
        // Check for validation errors
        $validationResult = $this->evaluate($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        // If validation passes, proceed with user creation
        include_once("connect.php");
        $db = new Database();
        $conn = $db->connect();

        // Sanitize the input data
        $fname = $conn->real_escape_string($data['fname']);
        $lname = $conn->real_escape_string($data['lname']);
        $email = $conn->real_escape_string($data['email']);
        $userName = $conn->real_escape_string($data['userName']);
        $password = $conn->real_escape_string($data['password']);
        $managerType = strtolower($data['managerType']) === 'true' ? 1 : 0;
        $managerID = (int) $data['managerID'];

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $query = "INSERT INTO employee (first_name, last_name, email, user_name, password, manager_type, manager_id) 
        VALUES ('$fname', '$lname', '$email', '$userName', '$hashed_password', $managerType, $managerID)";

        if ($conn->query($query)) {
            return "User created successfully.";
        } else {
            return "Error creating user: " . $conn->error;
        }
    }
}
?>
