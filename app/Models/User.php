<?php

namespace App\Models;

use Orkester\Persistence\Model;
class User extends Model
{

    public ?int $idUser;
    public ?string $login;
    public ?string $passMD5;
    public ?string $config;
    public ?string $name;
    public ?string $email;
    public ?string $status;
    public ?int $active;
    public ?string $auth0IdUser;
    public ?string $auth0CreatedAt;
    public ?string $lastLogin;
    public ?int $idLanguage;
    public ?array $groups;
    public ?array $memberOf;

}
