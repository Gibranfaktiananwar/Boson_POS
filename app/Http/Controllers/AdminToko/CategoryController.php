<?php

namespace App\Http\Controllers\AdminToko;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // retrieve all categories from the database
        $categories = Category::orderBy('code', 'asc')->get();
        return view('admintoko.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admintoko.category.form');
    }

    public function store(Request $request)
    {
        //validate inputs
        $request->validate([
            'code' => 'required|string|regex:/^\d+$/',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        //create new required to the database
        Category::create($request->only('code','name', 'description'));

        return redirect()->route('category.index')->with('success', 'Category created successfully!');
    }

    public function edit($id)
    {
        // displays the edit form page of a category based on its ID
        $category = Category::findOrFail($id);
        return view('admintoko.category.form', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // validate inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // save changes based on id category
        $category = Category::findOrFail($id);
        $category->update($request->only('name', 'description'));

        return redirect()->route('category.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        // remove categories from the database based on ID
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Category deleted successfully!');
    }
}
