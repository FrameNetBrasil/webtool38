<?php

return [
    'fetchStyle' => \PDO::FETCH_ASSOC,
    'login' => [
        'module' => "",
        'class' => "MAuthDbMD5",
        'check' => false,
        'shared' => true,
        'auto' => false
    ],
    'mad' => [
        'module' => "",
        'access' => "App\Models\Acesso",
        'group' => "App\Models\Group",
        'log' => "App\Models\Log",
        'session' => "App\Models\Session",
        'transaction' => "App\Models\Transaction",
        'user' => "App\Models\User"
    ],
];
