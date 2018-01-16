<?php
require_once '../src/connection.php';
require_once '../src/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = User::loadUserByUsername($conn, $username);

        if (!$user) {
            echo '<p>Zły login lub hasło</p>';
            echo '<p><a href="index.php">Powrót</a>';
            exit;
        }

        if (password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = $user->getId();
            header("location:index.php");
        } else {
            echo '<p>Zły login lub hasło</p>';
            echo '<p><a href="index.php">Powrót</a>';
            exit;
        }
    }

} else {

    ?>

    <form method="POST" action="" style="padding: 10px">
        <p>
            <label>
                Login: <input name="username" type="text">
            </label>
        </p>
        <p>
            <label>
                Hasło: <input name="password" type="password">
            </label>
        </p>
        <p>
            <input type="submit">
        </p>
    </form>

    <?php

}
