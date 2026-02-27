<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\User;
use App\Mail\NewCompanyCreated;
use Illuminate\Support\Facades\Mail;
use App\Helpers\RolesHelper;

class CompanyObserver
{
    public function created(Company $company): void
    {
        $admin_users = User::all()->filter(function ($user) {
            return RolesHelper::user_has_the_role($user->id, 'admin');
        });

        foreach ($admin_users as $admin) {
            Mail::to($admin->email)->queue(new NewCompanyCreated($company));
        }
    }

    public function updated(Company $company): void
    {
        //
    }

    public function deleted(Company $company): void
    {
        //
    }

    public function restored(Company $company): void
    {
        //
    }

    public function forceDeleted(Company $company): void
    {
        //
    }
}