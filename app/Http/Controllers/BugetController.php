<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\MenuModel;
use App\Models\Programme;
use App\Models\ProgrammeManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BugetController extends Controller
{
// function for the create menu
    public function create()
    {
         if (! auth('admin')->user()->hasAccess('budget', 'view')) {
        abort(403, 'You do not have access to Budget module.');
    }
        return view('admin.Budget.expenditure');
    }


public function store_expenditure(Request $request)
{
    $validated = $request->validate([
        'menus' => 'required|array',
        'menus.*.name' => 'required|string|max:255',
        'menus.*.submenus' => 'sometimes|array',
        'menus.*.submenus.*.name' => 'required_with:menus.*.submenus|string|max:255',
    ]);

    DB::transaction(function () use ($validated) {
        foreach ($validated['menus'] as $menuData) {
            $menu = MenuModel::create(['name' => $menuData['name']]);
            
            foreach ($menuData['submenus'] ?? [] as $submenuData) {
                $menu->submenus()->create(['name' => $submenuData['name']]);
            }
        }
    });

    return back()->with('success', 'Menus and submenus saved successfully.');
}


public function selectMultiple(Request $request)
{
    $menus = MenuModel::all();
    $programmes=ProgrammeManagement::all();

    $budgets = Budget::with('menu','submenu','programme')->paginate(10);

    //dd($budgets);

    return view('admin.Budget.budget', compact('menus','programmes','budgets'));
}
public function getSubmenusWithMenuName($id)
{

    
    $menu = MenuModel::with('submenus')->findOrFail($id);
    $submenus = $menu->submenus->map(function($submenu) use ($menu) {
        return [
            'id' => $submenu->id,
            'name' => $submenu->name,
            'menu_name' => $menu->name,
        ];
    });

    return response()->json($submenus);
}

//function for a store budgets
 public function storeBudget(Request $request)
    {

    if (! auth('admin')->user()->hasAccess('budget', 'add')) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

        // return $request;
       $request->validate([
    'programme_id' => 'required|exists:programmes,id',
    'budgets' => 'required|array',
    'budgets.*.menu_id' => 'required|exists:menus,id',
    'budgets.*.submenu_id' => 'required|exists:submenus,id',
    'budgets.*.price' => 'required|numeric|min:0',
    'budgets.*.quantity' => 'required|integer|min:1',
    'budgets.*.total' => 'required|numeric|min:0',
]);

foreach ($request->budgets as $item) {
    Budget::create([
        'programme_id' => $request->programme_id,
        'menu_id' => $item['menu_id'],
        'submenu_id' => $item['submenu_id'],
        'price' => $item['price'],
        'quantity' => $item['quantity'],
        'total' => $item['total'],
    ]);
}


        return redirect()->back()->with('success', 'Budget allocation saved successfully!');
    }



public function getProgrammeDetails($id)
{
    $programme = ProgrammeManagement::findOrFail($id);

    $startDate = \Carbon\Carbon::parse($programme->from_date)->format('d M Y');
    $endDate = \Carbon\Carbon::parse($programme->to_date)->format('d M Y');
    $dates = $startDate === $endDate ? $startDate : "$startDate to $endDate";

    return response()->json([
        'title'     => $programme->title,
        'duration'  => $programme->duration . ' days',
        'strength'  => $programme->strength . ' participants',
        'venue'     => $programme->venue,
        'dates'     => $programme->date,
    ]);
}

public function destroy($id)
{
    if (! auth('admin')->user()->hasAccess('budget', 'delete')) {
        abort(403, 'You do not have permission to delete budget items.');
    }

    $budget = Budget::findOrFail($id);
    $budget->delete();

    return redirect()->back()->with('success', 'Budget item deleted successfully.');
}
}
