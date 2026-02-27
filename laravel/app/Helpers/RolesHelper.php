<?php

namespace App\Helpers;

use App\Models\Role;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RolesHelper
{
    private static function insert_user_role($user_id, $role_id)
    {
        if ($user_id == null) return;
        DB::table('users_roles')->updateOrInsert(
            [
                'user_id' => $user_id,
                'role_id' => $role_id,
            ],
            [
                'updated_at' => Carbon::now()->setTimezone('UTC'),
                'created_at' => Carbon::now()->setTimezone('UTC'),
            ]
        );
    }

    public static function user_has_the_roles($user_id, $roles_slug)
    {
        $role_slugs = is_array($roles_slug) ? $roles_slug : [$roles_slug];

        return DB::table('users_roles')
            ->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->where('users_roles.user_id', $user_id)
            ->whereIn('roles.slug', $role_slugs)
            ->exists();
    }

    public static function user_has_the_role($user_id, $role_slug)
    {
        $has_the_role = DB::table('users_roles')
            ->where('user_id', $user_id);

        return $has_the_role->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->where('roles.slug', $role_slug)
            ->exists();
    }

    public static function get_role_id_by_slug($slug)
    {
        $role = Role::where('slug', $slug)->first();
        return $role?->id;
    }

    public static function insert_user_role_by_id($user_id, $role_id)
    {
        self::insert_user_role($user_id, $role_id);
    }
}
