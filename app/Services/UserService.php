<?php

namespace App\Services;

use App\Models\Authorization\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param User $user
     * @param string $firstName
     * @param string $lastName
     * @param string|null $username
     * @param string $email
     * @param int $isAdmin
     * @return void
     */
    public static function setUserAttributes(User $user, string $firstName, string $lastName, string $email,
                                             int $isAdmin, ?string $username = null)
    {
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->username = $username;
        $user->email = $email;
        $user->is_admin = $isAdmin;
        $user->save();
    }

    /**
     * @param User $user
     * @param string $plainPassword
     * @return void
     */
    public static function changeUserPassword(User $user, string $plainPassword)
    {
        $user->password = Hash::make($plainPassword);
        $user->save();
    }

    /**
     * @param int $userId
     * @return void
     */
    public static function destroyUser(int $userId)
    {
        User::findOrFail($userId)->delete();
    }
}
