<?php
    include_once 'Dbh.php';
    class UserAuth extends Dbh{
        protected $db;
        public function __construct(){
            $this->db = new Dbh();

        }

        #checkEmailExist method
        public function checkEmailExist($email){
            $conn = $this->db->connect();
            $sql = "SELECT * FROM students WHERE email = '$email'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                return TRUE;
            } else {
                return false;
            }
        }

        #confirm password method
        public function confirmPasswordMatch($password, $confirmPassword){
            if($password === $confirmPassword){
                return true;
            } else {
                return false;
            }
        }
        
        #register method
        public function register($fullnames, $email, $password, $confirmPassword, $country, $gender){
            $conn = $this->db->connect();
            if($this->checkEmailExist($email)){
                echo "User already exist" . "<br>" . "Please wait redirecting ... to register page!";
                header("refresh: 0.5; ./forms/register.php");
            } else {
                if($this->confirmPasswordMatch($password, $confirmPassword)){
                    $sql = "INSERT INTO Students (full_names, country, email, gender, password) VALUES ('$fullnames', '$country', '$email', '$gender', '$password')";
                    if($conn->query($sql)){
                        echo "User Successfully registered" . "<br>". "Please wait ... redirecting to login page!";
                        header("refresh: 0.5; ./forms/login.php");
                    }
                } else {
                    echo "Oops! password do not match!" . "<br>" . "Please wait redirecting ... to register page!";
                    header("refresh: 0.5; ./forms/register.php");
                }
            }
        }

        #login method
        public function login($email, $password){
            $conn = $this->db->connect();
            $sql = "SELECT * FROM students WHERE email='$email' AND `password`='$password'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $_SESSION['email'] = $email;
                echo "Please wait ... redirecting to dashboard page!";
                header("refresh: 0.5; ./dashboard.php");
                exit;
            } else {
                echo "Incorrect username or password" . "<br>". "Please wait ... redirecting to login page!";
                header("refresh: 0.5; ./forms/login.php");
                exit;
            }
        }

        #show all records method
        public function getAllUsers(){
            $conn = $this->db->connect();
            $sql = "SELECT * FROM Students";
            $result = $conn->query($sql);
            echo"<html>
            <head>
            <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
            </head>
            <body>
            <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
            <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
            <tr style='height: 40px'>
                <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th>
            </thead></tr>";
            if($result->num_rows > 0){
                while($data = mysqli_fetch_assoc($result)){
                    #show data
                    echo "<tr style='height: 20px'>".
                        "<td style='width: 50px; background: gray'>" . $data['id'] . "</td>
                        <td style='width: 150px'>" . $data['full_names'] .
                        "</td> <td style='width: 150px'>" . $data['email'] .
                        "</td> <td style='width: 150px'>" . $data['gender'] . 
                        "</td> <td style='width: 150px'>" . $data['country'] . 
                        "</td>
                        <td style='width: 150px'> 
                        <form action='action.php' method='post'>
                        <input type='hidden' name='id'" .
                        "value=" . $data['id'] . ">".
                        "<button class='btn btn-danger' type='submit', name='delete'> DELETE </button> </form> </td>".
                        "</tr>";
                    }
                echo "</table></table></center></body></html>";
            }
        }

        #delete user account by id
        public function deleteUser($id){
            $conn = $this->db->connect();
            $sql = "DELETE FROM students WHERE id = '$id'";
            if($conn->query($sql) === TRUE){
                echo "Record deleted successfull" . "<br>" . "Please wait ... redirecting to dashbaord page!";
                header("refresh: 0.5; ./dashboard.php?all");
            } else {
                echo "Record not deleted" .  "<br>" . "Please wait ... redirecting to dashbaord page!";
                header("refresh: 0.5; ./dashboard.php?all");
            }
        }

        #logout method
        public function logout(){
            session_destroy();
            echo "Logging you out" . "<br>" . "Please wait redirecting ... to login page!";
            header("refresh: 0.5; ./index.php?all");
        }

        #reset password
        public function updatePassword($email, $password){
            $conn = $this->db->connect();
            if($this->checkEmailExist($email)){
                $sql = "UPDATE students SET password = '$password' WHERE email = '$email'";
                if($conn->query($sql) === TRUE){
                    echo "Password successfully updated" . "<br>". "Please wait ... redirecting to login page!";
                    header("refresh: 0.5; ./forms/login.php?all");
                }
            } else {
                echo "Oops invalid Email" .  "<br>" . "Please wait ... redirecting to reset password page!";
                header("refresh: 0.5; ./forms/resetpassword.php?all");
            }
        }
    }
?>