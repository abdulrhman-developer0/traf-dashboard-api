<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait IntractsWithAccount
{
    /**
     * used to retrieve the current account of authenticated user
     * 
     * @return Model The model of account
     */
    public function account(): ?Model
    {
        return new Model();
    }

    

    /**
     * Used to check if account is of spcified type
     * 
     * @param string $type to compare with current account type
     * @return bool
     */
    public function isAccount(string $type): bool
    {
        return false;
    }
}