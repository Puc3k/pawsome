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

        if ($this->request->isPost()) {

            $validated = $this->validLoginData($this->request->getPost());

            $this->checkIfLoginErrors($validated);

            //redirect back to signup if there was any problem
            if (Session::exists('error')) {
                //pass form data back to login page
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
                    if (isset($user['avatar'])) {
                        Session::put('avatar', $user['avatar']);
                    }

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

        if ($this->request->isPost()) {

            $validated = Auth::validFormPostData($this->request->getPost());

            //Check input values
            Auth::checkIfFormDataErrors($validated);

            //redirect back to register if there was any problem
            if (Session::exists('error')) {
                //pass form data back to login page
                $this->view->render('register', [
                    'email' => $validated['email'] ?? '',
                    'userName' => $validated['userName'] ?? ''
                ]);
            }
            //Check if user exist
            $userExist = User::checkIfUserExist($validated['userName'], $validated['email']);
            if ($userExist) {
                Session::put('error', 'Ten adres e-mail jest już zajęty.');
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
            $this->redirect('/login');
        }

        $this->view->render('register');
    }

    #[NoReturn] public function logout()
    {
        Session::destroy();

        header('Location: ' . $_SERVER['PHP_SELF']);
        die();

    }

    public function validLoginData(array $data): array
    {
        $data['email'] = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['password'] = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        return $data ?? [];
    }

    public function checkIfLoginErrors(array $data)
    {
        if (!$data['email']) {
            Session::put('error', 'Podaj adres email');
        } elseif (!$data['password']) {
            Session::put('error', 'Podaj hasło');
        }
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