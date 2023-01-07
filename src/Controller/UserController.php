<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Helpers\Database;
use App\Helpers\Session;
use App\Model\User;
use Throwable;

class UserController extends Controller
{

    public function index()
    {
        $isLogged = Auth::isUserLogged();
        $userId = User::getUserIdFromSession();

        if (!$isLogged || !$userId) {
            $this->redirect('/');
        }

        $user = User::getUserById($userId);
        if (!$user) {
            Session::put('error', 'Nie znaleziono użytkownika');
            $this->view->render('index');
        }

        $this->view->render('user-profile', [
            'user' => $user
        ]);
    }

    public function editProfile()
    {
        $isLogged = Auth::isUserLogged();
        $userId = User::getUserIdFromSession();

        if (!$isLogged || !$userId) {
            $this->redirect('/');
        }

        $user = User::getUserById($userId);
        if (!$user) {
            Session::put('error', 'Nie znaleziono użytkownika');
            $this->view->render('index');
        }

        if ($this->request->isPost()) {
            $validated['userName'] = filter_var($this->request->postParam('userName'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                $avatar = $this->checkAvatar($_FILES['avatar']);
            }

            if (!($user['username'] == $validated['userName']) && User::checkIfUserExistForUserName($validated['userName'])) {
                Session::put('error', 'Nazwa użytkownika zajęta');
            }

            if (!$validated['userName']) {
                Session::put('error', 'Podaj nazwę użytkownika');
            }

            if (Session::exists('error')) {
                //pass form data back to login page
                $this->view->render('edit-user-profile', [
                    'user' => [
                        'username' => $validated['userName'] ?? '',
                        'avatar' => $user['avatar'] ?? ''
                    ]
                ]);
            }


            $updateUsername = $this->updateUsername($userId, $validated['userName']);
            if (isset($avatar)) {
                $updateAvatar = $this->updateAvatar($userId, $avatar);
            }

            if (!$updateUsername || isset($avatar) && !$updateAvatar) {
                Session::put('error', 'Błąd podczas edycji profilu użytkownika. Spróbuj ponownie');
                $this->view->render('register');
            }

            Session::put('success', 'Profil został pomyślnie zaktualizowany');
            $this->redirect('/user-profile');

        }

        $this->view->render('edit-user-profile', [
            'user' => $user
        ]);
    }

    public function getUsersList()
    {
        if (Auth::admin()) {
            $usersList = User::getAllUsers();
            $this->view->render('users-list', ['usersList' => $usersList]);
        }
        $this->view->render('index');
    }

    public function checkAvatar(array $data): string
    {
        //rename avatar
        $time = time(); //make each name unique using current timestamp
        $avatarName = $time . $data['name'];
        $avatarTmpName = $data['tmp_name'];
        $avatarDestinationPath = 'images/user-profile/' . $avatarName;

        //make sure file is an image
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $extension = explode('.', $avatarName);
        $extension = end($extension);
        if (in_array($extension, $allowedExtensions)) {
            //make sure image is not too large (1mb+)
            if ($data['size'] < 1000000) {
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

    public function updateUsername(int $userId, string $username): bool
    {
        try {
            $db = Database::getInstance()->getConnection();
            //param binding
            $query = $db->prepare('
                    UPDATE users SET username=:username WHERE id=:id');

            return $query->execute([
                'username' => $username,
                'id' => $userId
            ]);

        } catch (Throwable) {
            return false;
        }
    }

    public function updateAvatar(int $userId, string $avatar): bool
    {
        try {
            $db = Database::getInstance()->getConnection();
            //param binding
            $query = $db->prepare('
                    UPDATE users SET avatar=:avatar WHERE id=:id');

            return $query->execute([
                'avatar' => $avatar,
                'id' => $userId
            ]);

        } catch (Throwable) {
            return false;
        }
    }

    public function seedImages()
    {
        $isAdmin = Auth::admin();
        if (!$isAdmin) {
            Session::put('error', 'Brak uprawnień');
            $this->view->render('/index');
        }

        try {
            $api = new ApiController('https://dog.ceo/api/');
            Session::put('breed-seed', "Dodano {$api->getBreedList()} rekordów do listy ras");
            Session::put('image-seed', "Dodano {$api->getBreedsImages()} nowych zdjęć");

            $this->index();
        } catch (Throwable) {
            Session::put('error', 'Błąd generowania zdjęć z API');
            $this->view->render('/user-profile');
        }
    }
}