<?php 
    namespace App;

    final class Authentication
    {
        final public static function init(): void
        {

        }

        final public static function setup(): void
        {

        }

        private static function createFakeUser(): void
        {

        }

        final public static function form(): void
        {

        }

        final public static function authenticate(): void
        {

        }

        final public static function secret(): void
        {

        }

        final public static function logout(): void
        {

        }

        final public static function notFound(): void
        {

        }


        final public static function runAction(): void
        {
            match($_GET['action'] ?? ''){
                'form' => self::form(),
                'authenticate' => self::authenticate(),
                'secret' => self::secret(),
                'logout' => self::logout(),
                default => self::notFound()
            };
        }

    }