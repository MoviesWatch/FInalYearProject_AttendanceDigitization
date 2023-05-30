<?php

class AdminAuth
{
    public static function login($admin)
    {
        $_SESSION["db"] = Database::$masterDB;
        $adminFromDB = Admin::commonRead([
          "username" => $admin->username,
        ]);

        if (empty($adminFromDB)) {
            echo json_encode(CustomException::invalidUsername());
            exit();
        }

        $genuine = password_verify($admin->password, $adminFromDB[0]->password);

        if ($genuine) {
            session_regenerate_id();
            $_SESSION["username"] = $admin->username;
            return [
              "username" => $admin->username,
            ];
        } else {
            echo json_encode(CustomException::invalidPassword());
            exit();
        }
    }

    public static function verify()
    {
        if (!isset($_SESSION["username"])) {
            echo json_encode(CustomException::notLoggedIn());
            exit();
        }
    }

    public static function logout()
    {
        session_destroy();
        return true;
        exit();
    }

    public static function updatePassword($newPassword)
    {
        self::verify();
        return Admin::update($newPassword, ["username" => $_SESSION["username"]]);
    }
}
