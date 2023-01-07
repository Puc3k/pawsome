<?php

use App\Helpers\Auth;
use App\Helpers\Session;

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-center py-2 py-lg-3">
    <div class="container-fluid position-relative bg-light">
        <a class="navbar-brand d-inline" href="/">
            <img src="/public/images/logo.webp" class="mx-2 mb-2" width="32" height="32" alt="Pawsome logo">
            <span class="fs-4 bold">Pawsome</span>
        </a>
        <button class="navbar-toggler py-0 px-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-md-end" id="navbarsExample05">
            <ul class="navbar-nav mb-2 mb-lg-0 mr-auto align-content-md-center">
                <li class="nav-item d-md-flex align-items-md-center">
                    <a class="nav-link active" aria-current="page" href="/">Strona główna</a>
                </li>
                <li class="nav-item d-md-flex align-items-md-center">
                    <a class="nav-link" href="/ranking">Ranking</a>
                </li>
                <?php if (Auth::admin()): ?>
                    <li class="nav-item dropdown d-md-flex align-items-md-center">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (Session::exists('avatar')) : ?>
                                <img src="/public/images/user-profile/<?= Session::get('avatar') ?>" class="rounded-circle"
                                     alt="User Avatar" width="35" height="35">
                            <?php else : ?>
                                <i class="bi bi-person-circle fs-4"></i>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/user-profile">Twój profil</a></li>
                            <li><a class="dropdown-item" href="/users-list">Lista użytkowników</a></li>
                            <li><a class="dropdown-item" href="/ranking-admin">Cały ranking</a></li>
                            <li><a class="dropdown-item" href="/logout">Wyloguj</a></li>
                        </ul>
                    </li>
                <?php elseif (Auth::user()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (Session::exists('avatar')) : ?>
                                <img src="/public/images/user-profile/<?= Session::get('avatar') ?>" class="rounded-circle"
                                     alt="User Avatar" width="35" height="35">
                            <?php else : ?>
                                <i class="bi bi-person-circle fs-4"></i>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/user-profile">Twój profil</a></li>
                            <li><a class="dropdown-item" href="/ranking-user">Twój ranking</a></li>
                            <li><a class="dropdown-item" href="/logout">Wyloguj</a></li>
                        </ul>
                    </li>
                <?php elseif (Auth::guest()): ?>
                    <li class="nav-item d-md-flex align-items-md-center">
                        <a class="nav-link" href="/login">Zaloguj</a>
                    </li>
                    <li class="nav-item d-md-flex align-items-md-center">
                        <a class="nav-link" href="/register">Zarejestruj</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>