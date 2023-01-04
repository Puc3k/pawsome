<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Helper;
use App\Helpers\Database;
use App\Helpers\Session;
use App\Model\User;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use Throwable;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkIsUserLogged();
    }

    public function login(array $post)
    {
        if ($this->request->postParam('submit')) {
            $validated = $this->validLoginData();

            if (!$validated['email']) {
                Session::put('error', 'Podaj adres email');
            } elseif (!$validated['password']) {
                Session::put('error', 'Podaj hasło');
            } else {
                try {
                    $db = Database::getInstance()->getConnection();
                    //param binding
                    $query = $db->prepare('
                    SELECT * FROM users WHERE email= :email');
                    $query->execute([
                        'email' => $validated['email'],

                    ]);
                    $data = $query->fetchAll();

                    if ($query->rowCount() == 1) {
                        // convert the record into assoc array
                        $user_record = $data = $query->fetchAll(PDO::FETCH_ASSOC);
                        $db_password = $data['password'];
                        // compare form password with database password
                        if (password_verify($validated['password'], $db_password)) {
                            // set session for access control
                            Session::put('user-id', $user_record['id']);


                        } else {
                            Session::put('error', 'Dane nie są poprawne');
                        }
                    } else {
                        Session::put('error', 'Nie znaleziono takiego użytkownika');
                    }

                } catch (Throwable) {
                    Session::put('error', 'Błąd podczas tworzenia użytkownika. Spróbuj ponownie');

                }
                // if any problem, redirect back to signin page with login data
                if (isset($_SESSION['signin'])) {
                    $_SESSION['signin-data'] = $_POST;
                    $this->view->render('register');
                    die();
                } else {
                    $this->view->render('login');
                    die();
                }
            }
        }


    }


    public function regsiter()
    {
        if ($this->request->postParam('submit')) {
            $validated = $this->validLoginData();

            //Check input values
            if (!$validated['firstName']) {
                Session::put('error', 'Podaj imię');
            } elseif (!$validated['lastName']) {
                Session::put('error', 'Podaj nazwisko');
            } elseif (!$validated['userName']) {
                Session::put('error', 'Podaj nazwę użytkownika');
            } elseif (!$validated['email']) {
                Session::put('error', 'Podaj email');
            } elseif (strlen($validated['createPassword']) < 8 || strlen($validated['confirmPassword']) < 8) {
                Session::put('error', 'Hasło powinno mieć min 8 znaków');
            } elseif (!$validated['avatar']['name']) {
                Session::put('error', 'Proszę dodaj zdjęcie profilowe');
            } else {
                //check if passwords don't match
                if ($validated['createPassword'] !== $validated['confirmPassword']) {
                    Session::put('error', 'Hasła nie są jednakowe');
                } else {
                    //hash password
                    $hashedPassword = password_hash($validated['createPassword'], PASSWORD_DEFAULT);
                    $userExist = User::checkIfUserExist($validated['userName'], $validated['email']);
                    if ($userExist) {
                        Session::put('error', 'Email już był wykorzystany do założenia konta');

                    } else {
                        //work on avatar
                        //rename avatar
                        $time = time(); //make each name unique using current timestamp
                        $avatarName = $time . $validated['avatar']['name'];
                        $avatarTmpName = $validated['avatar']['tmp_name'];
                        $avatarDestinationPath = 'images/' . $avatarName;

                        //make sure file is an image
                        $allowedExtensions = ['png', 'jpg', 'jpeg'];
                        $extension = explode('.', $avatarName);
                        $extension = end($extension);
                        if (in_array($extension, $allowedExtensions)) {
                            //make sure image is not too large (1mb+)
                            if ($validated['avatar']['size'] < 1000000) {
                                //upload avatar
                                move_uploaded_file($avatarTmpName, $avatarDestinationPath);
                            } else {
                                Session::put('error', 'Plik jest zbyt duży, maksymalny rozmiar to 1MB');
                            }
                        } else {
                            Session::put('error', 'Zły format');
                        }

                    }
                }

            }
            //redirect back to signup if there was any problem
            if (isset($_SESSION['error'])) {
                //pass form data back to signup page
                $_SESSION['signup-data'] = $_POST;
                header('location: /signup');
                die();
            } else {
                try {
                    $db = Database::getInstance()->getConnection();
                    //param binding
                    $query = $db->prepare('
                    INSERT INTO users (username, email, password, avatar, is_admin) 
                    VALUES (:firstName, :lastName, :username, :email, :password, :avatar, :isAdmin)');

                    $query->execute([
                        'firstName' => $validated['firstName'],
                        'last_name' => $validated['firstName'],
                        'username' => $validated['firstName'],
                        'email' => $validated['firstName'],
                        'password' => $hashedPassword,
                        'avatar' => $avatarName,
                        'isAdmin' => 0,
                    ]);

                } catch (Throwable) {
                    Session::put('error', 'Błąd podczas tworzenia użytkownika. Spróbuj ponownie');
                }

                Session::put('success', 'Zarejestrowano pomyślnie. Zaloguj się');

                $this->redirect('/register');
            }

        } else {
            //if button wasn't clicked, bounce back to signup page
            $this->redirect('/signup');
        }

    }

    #[NoReturn] public function logout()
    {
        Session::destroy();
        $destination = Helper::getHostname();

        header("Location: $destination/");
        die();

    }

    public function validRegisterData(): array
    {
        $data['firstName'] = filter_var($_POST['firstName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['lastName'] = filter_var($_POST['lastName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['userName'] = filter_var($_POST['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $data['createPassword'] = filter_var($_POST['createPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['confirmPassword'] = filter_var($_POST['confirmPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['avatar'] = $_FILES['avatar'];

        return $data ?? [];
    }

    public function validLoginData(): array
    {
        $data['email'] = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['password'] = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        return $data ?? [];
    }

}