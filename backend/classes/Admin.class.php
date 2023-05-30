<?php
class Admin extends Table
{
  protected static $tableName = "admins";

  public static function create($username, $password)
  {
    $password = password_hash($password, PASSWORD_BCRYPT, [
      "cost" => 10,
    ]);
    return self::commonCreate([
      "username" => $username,
      "password" => $password,
    ]);
  }

  public static function update($newPassword, $username)
  {
    $newPassword = password_hash($newPassword, PASSWORD_BCRYPT, [
      "cost" => 10,
    ]);
    return self::commonUpdate(
      [
        "username" => $username,
      ],
      ["password" => $newPassword]
    );
  }
}
