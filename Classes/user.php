<?php

class User
{
    // Function to create a new user in the database
    public function create($data)
    {
        // Include the database class
        include_once("db.php");
        $db = new DB();

        // Sanitize the input data
        $first_name = addslashes($data['first_name']);
        $last_name = addslashes($data['last_name']);
        $email = addslashes($data['email']);
        $user_name = addslashes($data['user_name']);
        $password = addslashes($data['password']);
        $manager_type = filter_var($data['manager_type'], FILTER_VALIDATE_BOOLEAN);
        $manager_id = intval($data['manager_id']);

        // Check if the user already exists
        $checkQuery = "SELECT * FROM employee WHERE email = '$email' OR user_name = '$user_name'";
        $existingUser = $db->read($checkQuery);

        if (!empty($existingUser)) {
            return "User with this email or username already exists.";
        }

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user
        $query = "INSERT INTO employee (first_name, last_name, email, user_name, password, manager_type, manager_id) 
                  VALUES ('$first_name', '$last_name', '$email', '$user_name', '$hashed_password', $manager_type, $manager_id)";

        // Save the user to the database
        $result = $db->save($query);

        if ($result) {
            return "User created successfully.";
        } else {
            return "Error creating user.";
        }
    }

    // Function to read user data from the database
    public function read($id)
    {
        // Include the database class
        include_once("db.php");
        $db = new DB();

        // Sanitize the input ID
        $id = intval($id);

        // SQL query to fetch the user with the given ID
        $query = "SELECT * FROM employee WHERE employee_id = $id";
        $result = $db->read($query);

        if (!empty($result)) {
            return $result[0]; // Return the user data
        } else {
            return "User not found.";
        }
    }

    // Function to update user data in the database
    public function update($id, $data)
    {
        // Include the database class
        include_once("db.php");
        $db = new DB();

        // Sanitize the input data
        $id = intval($id);
        $first_name = addslashes($data['first_name']);
        $last_name = addslashes($data['last_name']);
        $email = addslashes($data['email']);
        $user_name = addslashes($data['user_name']);
        $password = isset($data['password']) ? password_hash(addslashes($data['password']), PASSWORD_DEFAULT) : null;
        $manager_type = filter_var($data['manager_type'], FILTER_VALIDATE_BOOLEAN);
        $manager_id = intval($data['manager_id']);

        // Build the SQL query dynamically
        $query = "UPDATE employee SET 
                  first_name = '$first_name', 
                  last_name = '$last_name', 
                  email = '$email', 
                  user_name = '$user_name', 
                  manager_type = $manager_type, 
                  manager_id = $manager_id";

        if ($password) {
            $query .= ", password = '$password'";
        }

        $query .= " WHERE employee_id = $id";

        // Execute the update query
        $result = $db->save($query);

        if ($result) {
            return "User updated successfully.";
        } else {
            return "Error updating user.";
        }
    }

    // Function to delete a user from the database
    public function delete($id)
    {
        // Include the database class
        include_once("db.php");
        $db = new DB();

        // Sanitize the input ID
        $id = intval($id);

        // SQL query to delete the user with the given ID
        $query = "DELETE FROM employee WHERE employee_id = $id";
        $result = $db->save($query);

        if ($result) {
            return "User deleted successfully.";
        } else {
            return "Error deleting user.";
        }
    }
}
?>
