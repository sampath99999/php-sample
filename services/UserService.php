<?php

namespace App\Services;

class UserService
{
    public static function login()
    {
        self::validateFormData();
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $query = "SELECT id FROM users WHERE username = :username AND password = :password";
        $user = DB->first($query, ["username" => $username, "password" => $password]);
        if (!$user) {
            return response(["message" => "Username or Password incorrect"], 400);
        }
        $token = self::generateToken();
        $query = "UPDATE users SET token = :token WHERE id = :id";
        DB->update($query, ["token" => $token, "id" => $user["id"]]);
        return response(["message" => "Login Success", "token" => $token], 200);
    }

    public static function generateToken()
    {
        $token = bin2hex(random_bytes(32));
        $query = "SELECT id FROM users WHERE token = :token";
        $user = DB->first($query, ["token" => $token]);
        if ($user) {
            return self::generateToken();
        }
        return $token;
    }

    public static function validateToken()
    {
        $token = $_POST['token'];
        $query = "SELECT * FROM users WHERE token = :token";
        $user = DB->first($query, ["token" => $token]);
        if (!$user) {
            return response(["message" => "Token is invalid"], 400);
        }
        Auth->setUser($user);
    }

    public static function validateFormData()
    {
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            response(["message" => "Username & Password is required"], 400);
        }
    }
}
