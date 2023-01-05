<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Helpers\Database;
use App\Helpers\Session;
use App\Model\User;
use JetBrains\PhpStorm\NoReturn;
use Throwable;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $this->checkIsUserLogged();

        Session::delete('error');
        Session::delete('success');

        if ($this->request->isPost()) {

            $validated = $this->validLoginData($this->request->getPost());

            $this->checkIfLoginErrors($validated);

            //redirect back to signup if there was any problem
            if (Session::exists('error')) {
                //pass form data back to signup page
                Session::put('error', 'Dane nie są poprawne');
                $this->view->render('login', [
                    'email' => $validated['email'] ?? '',
                    'userName' => $validated['userName'] ?? ''
                ]);
            }

            try {
                $user = User::getUser($validated);
                if (!$user) {
                    Session::put('error', 'Nie znaleziono takiego użytkownika lub podane dane są błędne');
                    $this->view->render('login', [
                        'email' => $validated['email'] ?? '',
                        'userName' => $validated['userName'] ?? ''
                    ]);
                }

                $dbPassword = $user['password'];
                // compare form password with database password
                if (password_verify($validated['password'], $dbPassword)) {
                    // set session for access control
                    Session::put('user-id', $user['id']);
                    Session::put('role', $user['role']);
                    Session::put('logged', true);
                    $this->redirect('/home');
                }
            } catch (Throwable) {
                Session::put('error', 'Błąd podczas tworzenia użytkownika. Spróbuj ponownie');

            }
        }

        $this->view->render('login');
    }


    public function register()
    {
        $this->checkIsUserLogged();

        Session::delete('error');
        if ($this->request->isPost()) {

            $validated = $this->validRegisterData($this->request->getPost());

            //Check input values
            $this->checkIfRegisterErrors($validated);

            //Check if user exist
            $userExist = User::checkIfUserExist($validated['userName'], $validated['email']);
            if ($userExist) {
                Session::put('error', 'Email już był wykorzystany do założenia konta');
            }

            //redirect back to signup if there was any problem
            if (Session::exists('error')) {
                //pass form data back to signup page
                $this->view->render('register', [
                    'email' => $validated['email'],
                    'userName' => $validated['userName']
                ]);
            }

            //hash password
            $hashedPassword = password_hash($validated['createPassword'], PASSWORD_DEFAULT);

            $registerUser = $this->registerUser($validated, $hashedPassword);

            if (!$registerUser) {
                Session::put('error', 'Błąd podczas tworzenia użytkownika. Spróbuj ponownie');
                $this->view->render('register', [
                    'email' => $validated['email'],
                    'userName' => $validated['userName']
                ]);
            }

            Session::put('success', 'Zarejestrowano pomyślnie. Zaloguj się');
            $this->view->render('login');
        }

        $this->view->render('register');
    }

    #[NoReturn] public function logout()
    {
        Session::destroy();

        header('Location: ' . $_SERVER['PHP_SELF']);
        die();

    }

    public function validRegisterData(array $data): array
    {
        $data['userName'] = filter_var($_POST['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $data['createPassword'] = filter_var($_POST['createPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['confirmPassword'] = filter_var($_POST['confirmPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        $data['avatar'] = $_FILES['avatar'];

        return $data ?? [];
    }

    public function validLoginData(array $data): array
    {
        $data['email'] = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['password'] = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        return $data ?? [];
    }

    public function checkIfRegisterErrors(array $data)
    {
        if ($data) {
            //Check input values
            if (!$data['userName']) {
                Session::put('error', 'Podaj nazwę użytkownika');
            } elseif (!$data['email']) {
                Session::put('error', 'Podaj email');
            } elseif (strlen($data['createPassword']) < 8 || strlen($data['confirmPassword']) < 8) {
                Session::put('error', 'Hasło powinno mieć min 8 znaków');
            } elseif ($data['createPassword'] !== $data['confirmPassword']) {
                Session::put('error', 'Hasła nie są jednakowe');
            }
        }
    }

    public function checkIfLoginErrors(array $data)
    {
        if (!$data['email']) {
            Session::put('error', 'Podaj adres email');
        } elseif (!$data['password']) {
            Session::put('error', 'Podaj hasło');
        }
    }

    public function checkAvatar(array $data): string
    {
        //rename avatar
        $time = time(); //make each name unique using current timestamp
        $avatarName = $time . $data['avatar']['name'];
        $avatarTmpName = $data['avatar']['tmp_name'];
        $avatarDestinationPath = 'images/' . $avatarName;

        //make sure file is an image
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $extension = explode('.', $avatarName);
        $extension = end($extension);
        if (in_array($extension, $allowedExtensions)) {
            //make sure image is not too large (1mb+)
            if ($data['avatar']['size'] < 1000000) {
                //upload avatar
                move_uploaded_file($avatarTmpName, $avatarDestinationPath);
            } else {
                Session::put('error', 'Plik jest zbyt duży, maksymalny rozmiar to 1MB');
            }
        } else {
            Session::put('error', 'Zły format');
        }

        return $avatarName;
    }

    public function registerUser(array $data, string $hashedPassword): bool
    {
        try {
            $db = Database::getInstance()->getConnection();
            //param binding
            $query = $db->prepare('
                    INSERT INTO users (username, email, password, role) 
                    VALUES (:username, :email, :password, :role)');

            return $query->execute([
                'username' => $data['userName'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'role' => 'user'
            ]);

        } catch (Throwable) {
            return false;
        }
    }

    public function checkIsUserLogged()
    {
        $isUserLogged = Auth::isUserLogged();
        if ($isUserLogged) {
            $this->redirect('/home');
        }
    }
}