<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;

class DeletePersonalData
{
    /**
     * due to legal restrictions we can not store any personal data,
     * but we want to keep data consistency and keep all user information that
     * are allowed by the Outer Rim Galaxy law: (rank, duties, position)
     * so we just mask all sensitive fields
     *
     * @param User $user
     */
    public function action(User $user)
    {
        $user->name = $this->getMask();
        $user->email = $this->getMask() . '@' . $this->getMask() . 'example.com';
        $user->password = $this->getMask();
        $user->origin = $this->getMask();
        $user->save();
    }

    /**
     * @return string
     */
    protected function getMask(): string
    {
        return str_repeat('_', 10);
    }
}
