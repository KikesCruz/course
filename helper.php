<?php

function flash(string $key, ?string $message = null): ?string
{
    if (isset($_SESSION['flash'][$key])) {
        $value = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $value;
    }

    $_SESSION['flash'][$key] = $message;
    return $message;
}

function start_session(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

function redirect(string $path): void
{
    header("Location: $path");
    exit;
}

function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}