<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use App\Models\Feedbackmenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function master(){
        return view('admin.masters.feedback');
    }


public function store(Request $request)
{
    $validated = $request->validate([
        'menus' => 'required|array',
        'menus.*.name' => 'required|string|max:255',
        'menus.*.submenus' => 'sometimes|array',
        'menus.*.submenus.*.name' => 'required_with:menus.*.submenus|string|max:255',
        'menus.*.submenus.*.response_type' => 'required_with:menus.*.submenus|in:Yes/No,Rating,Text',
    ]);

    DB::transaction(function () use ($validated) {
        foreach ($validated['menus'] as $menuData) {
            $menu = Feedbackmenu::create([
                'name' => $menuData['name']
            ]);

            foreach ($menuData['submenus'] ?? [] as $submenuData) {
                $menu->submenus()->create([
                    'name' => $submenuData['name'],
                    'response_type' => $submenuData['response_type'],
                ]);
            }
        }
    });

    return back()->with('success', 'Menus and submenus saved successfully.');
}

}
