<?php

namespace App;

use PDO;

final class Authentication
{
    final public static function init(): void
    {
        start_session();
    }

    final public static function setup(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS users(
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        email VARCHAR(50) NOT NULL,
                        password VARCHAR(255) NOT NULL
                        )";
        $connection = Connection::getConnection();
        $connection->exec($sql);

        self::createFakeUser();
    }

    private static function createFakeUser(): void
    {
        $connection = Connection::getConnection();
        $query = 'SELECT * FROM users WHERE email = :email';
        $parameters = ['email' => 'fake@desarrollo.com'];
        $smtp = $connection->prepare($query);
        $smtp->execute($parameters);

        if (! $smtp->rowCount()) {
            $sql = 'INSERT INTO users (email, password) VALUES (:email, :password)';
            $parameters = [
                'email' => 'fake@email.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
            ];
            $connection
                ->prepare(query: $sql)
                ->execute(params: $parameters);
        }
    }

    final public static function form(): void
    {
        if (is_logged_in()) {
            redirect('?action=secret');
        }

        Twig::render(
            'login',
            [
                'error' => flash('error'),
                'email' => flash('email'),
                'authenticateUrl' => '?action=authenticate',
            ]
        );
    }

    final public static function authenticate(): void
    {
        if (! isPost()) {
            flash('error', 'Metodo HTTP no permitido');
            redirect('?action=form');
        }

        if (is_logged_in()) {
            redirect('?action=secret');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $connection = Connection::getConnection();
        $query = 'SELECT * FROM users WHERE email = :email';
        $parameters = ['email' => $email];
        $smtp = $connection->prepare(query: $query);
        $smtp->execute(params: $parameters);

        if ($smtp->rowCount()) {

            $user = $smtp->fetch(PDO::FETCH_ASSOC);

            if (password_verify(password: $password, hash: $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                ];
                redirect(path: '?action=secret');
            }
        }

        flash(key: 'error', message: 'Credenciales incorrectas');
        flash(key: 'email', message: $email);
        redirect(path: '?action=form');
    }

    final public static function secret(): void {
        if (! is_logged_in()) {
            redirect('?action=form');
        }

        Twig::render(
            'secret',
        [
            'logoutUrl' => '?action=logout',
        ]);
    }

    final public static function logout(): void {
        session_destroy();
        redirect(path:'?action=form');
    }

    final public static function notFound(): void
    {
        Twig::render(
            view: 'errors/404',
        );
    }


    final public static function runAction(): void
    {
        match ($_GET['action'] ?? '') {
            'form' => self::form(),
            'authenticate' => self::authenticate(),
            'secret' => self::secret(),
            'logout' => self::logout(),
            default => self::notFound()
        };
    }
}
