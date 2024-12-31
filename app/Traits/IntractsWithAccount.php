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
        $accountTypes = $this->accountTypes();

        
        if (! $this->account_type || !in_array($this->account_type, array_keys($accountTypes) )) {
            return null;
        }
        
        return $this->hasOne($accountTypes[$this->account_type])->first();
    }



    /**
     * Used to check if account is of spcified type
     * 
     * @param string $type to compare with current account type
     * @return bool
     */
    public function isAccount(string $type): bool
    {
        
        if (! in_array($type, array_keys($this->accountTypes()) ) ) {
            throw new \Exception("No account type $type");
        }

        return $this->account_type == $type;
    }
}
