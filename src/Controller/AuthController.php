<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Session;

class AuthController extends Controller
{
    public function checkIsUserLogged()
    {
        if (!Session::exists(SESSION_LOGGED_USER)) {
            $this->view->render('login', [
                'firstName' => Session::get('firstName') ?? null,
                'lastName' => Session::get('lastName') ?? null,
                'userName' => Session::get('userName') ?? null,
                'email' => Session::get('email') ?? null,
                'createPassword' => Session::get('createPassword') ?? null,
                'confirmPassword' => Session::get('confirmPassword') ?? null,
            ]);

            Session::destroy();
        }
    }

    public function login(array $post)
    {
        if($this->request->postParam('submit'))
        {
            $firstName = filter_var($_POST['firstName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lastName = filter_var($_POST['lastName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $userName = filter_var($_POST['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $createPassword = filter_var($_POST['createPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $confirmPassword= filter_var($_POST['confirmPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $avatar = $_FILES['avatar'];

            //check input values
            if (!$firstName) {
                $_SESSION['signup'] = "Podaj imię";
            } elseif (!$lastName) {
                $_SESSION['signup'] = "Podaj nazwisko";
            } elseif (!$userName) {
                $_SESSION['signup'] = "Podaj nazwę użytkownika";
            } elseif (!$email) {
                $_SESSION['signup'] = "Podaj email";
            } elseif (strlen($createPassword) < 8 || strlen($confirmPassword) < 8) {
                $_SESSION['signup'] = "Hasło powinno mieć min 8 znaków";
            } elseif (!$avatar['name']) {
                $_SESSION['signup'] = "Proszę dodaj zdjęcie profilowe";
            } else {
                //check if passwords don't match
                if ($createPassword !== $confirmPassword) {
                    $_SESSION['signup'] = "Hasła nie są jednakowe";
                } else {
                    //hash password
                    $hashed_password = password_hash($createPassword, PASSWORD_DEFAULT);
                    //check if userName or emial already exist
                    $user_check_query = "SELECT * FROM users WHERE userName='$userName' OR email='$email'";
                    $user_check_result = mysqli_query($connection, $user_check_query);
                    if (mysqli_num_rows($user_check_result) > 0) {
                        $_SESSION['signup'] = "Email już był wykorzystany do założenia konta";

                    } else {
                        //work on avatar
                        //rename avatar
                        $time = time(); //make each name unique using current timestamp
                        $avatar_name = $time . $avatar['name'];
                        $avatar_tmp_name = $avatar['tmp_name'];
                        $avatar_destination_path = 'images/' . $avatar_name;


                        //make sure file is an image
                        $allowed_files = ['png', 'jpg', 'jpeg'];
                        $extension = explode('.', $avatar_name);
                        $extension = end($extension);
                        if(in_array($extension, $allowed_files)) {
                            //make sure image is not too large (1mb+)
                            if($avatar['size'] < 1000000) {
                                //upload avatar
                                move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                            } else {
                                $_SESSION['signup'] = 'File size too big. Should be less that 1mb';
                            }
                        } else {
                            $_SESSION['signup'] = 'Zły format';
                        }

                    }
                }

            }
            //redirect back to signup if there was any problem
            if(isset($_SESSION['signup'])) {
                //pass form data back to signup page
                $_SESSION['signup-data'] = $_POST;
                header('location: ' . ROOT_URL . 'signup.php');
                die();
            } else {
                //insert new user into db
                $insert_user_query = "INSERT INTO users SET firstName='$firstName', lastName = '$lastName', userName ='$userName', email ='$email', password = '$hashed_password',
         avatar = '$avatar_name', is_admin = 0";

                $insert_user_result = mysqli_query($connection, $insert_user_query);

                if(!mysqli_errno($connection)) {
                    //redirect to login page with succes message
                    $_SESSION['signup-succes'] = "Zarejestrowano pomyślnie. Zaloguj się";
                    header('location' . ROOT_URL . 'signin.php');
                    die();
                }
            }

        } else {
            //if button wasn't clicked, bounce back to signup page
            header('location: ' . ROOT_URL . 'signup.php');
            die();
        }

    }

    public function logout()
    {

    }
}