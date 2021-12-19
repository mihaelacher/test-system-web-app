<?php

namespace App\Services;

use App\Models\Authorization\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param User $user
     * @param Request $request
     * @param int $currentUserId
     * @return void
     */
    public static function updateUser(User $user, Request $request, int $currentUserId)
    {
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->is_admin = $request->is_admin;
        $user->updated_by = $currentUserId;
        $user->save();
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $email
     * @param int $isAdmin
     * @param int $currentUserId
     * @return void
     */
    public static function createUser(string $firstName, string $lastName, string $username, string $email, int $isAdmin, int $currentUserId)
    {
        User::insert([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $email,
            'is_admin' => $isAdmin,
            'created_by' => $currentUserId
        ]);
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
}
