<?php declare(strict_types=1);


function session(string $key = null, $value = null)
{
    if ($key === null && $value === null) {
        return $_SESSION;
    }

    if (isset($value)) {
        return $_SESSION[$key] = $value;
    }

    if (func_num_args() === 2 && is_null($value)) {
        unset($_SESSION[$key]);
    }

    return $_SESSION[$key] ?? null;
}

function login($user)
{
    session('_user', $user);
}


function logout()
{
    session('_user', null);
}


function auth_user(string $key = null)
{
    if ($key) {
        return session('_user')[$key] ?? null;
    }

    return session('_user');
}


function auth_id()
{
    return auth_user('id');
}
