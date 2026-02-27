<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocaleRequest;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::where('enabled', true)->get();
        return view('layouts.app', compact('languages'));
    }

    public function setLocale(LocaleRequest $request)
    {
        $locale = $request->validated();
        session(['locale' => $request->locale]);
        app()->setLocale($request->locale);

        if (Auth::check()) {
            $user = Auth::user();
            $user->user_settings->updateOrCreate(
                ['user_id' => $user->id],
                ['language_id' => Language::where('locale', $locale)->first()->id]
            );
        }

        return back();
    }

}