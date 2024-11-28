<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface HasAccounts
{
    /**
     * Used to define available account types with models
     * 
     * @static
     * @return array the acount types with models ['admin' => Admin::clas, ...]
     */
    public static function accountTypes(): array;

    /**
     * used to retrieve the current account of authenticated user
     * 
     * @return Model The model of account
     */
    public function account(): ?    Model;

    /**
     * Used to check if account is of spcified type
     * 
     * @param string $type to compare with current account type
     * @return bool
     */
    public function isAccount(string $type): bool;
}