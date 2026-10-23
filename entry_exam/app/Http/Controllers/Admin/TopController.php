<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopController extends Controller
{
    public function index(): View
    {
        return view('admin.home');
    }

    /**
     * Switch the display language (applied by the SetLocale middleware)
     */
    public function changeLocale(Request $request, string $locale): RedirectResponse
    {
        $request->session()->put('locale', $locale);

        return back();
    }
}
