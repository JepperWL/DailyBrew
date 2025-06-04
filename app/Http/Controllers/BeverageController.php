<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use Storage;

class BeverageController extends Controller
{
    //
   
    public function show($id)
    {
        $beverage = Beverage::with(['category', 'brand'])->findOrFail($id);
        return view('admin.detail', compact('beverage'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('admin.add-product', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $data = $request->all();
        $data['is_available'] = true; 
        
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('beverages', 'public');
            $data['image'] = $imageName;
        }

        Beverage::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'Product created successfully.');
    }
    public function edit($id)
    {
        $beverage = Beverage::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('admin.edit-product', compact('beverage', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $beverage = Beverage::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'is_available' => 'nullable',
        ]);
        $data['is_available'] = $request->is_available == '1' ? true : false;
    
        $data = $request->all();
        
        if ($request->hasFile('image')) {
        
            if ($beverage->image && Storage::disk('public')->exists($beverage->image)) {
                Storage::disk('public')->delete($beverage->image);
            }
            
            $imageName = $request->file('image')->store('beverages', 'public');
            $data['image'] = $imageName;
        } else {
            
            unset($data['image']);
        }

        $beverage->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $beverage = Beverage::findOrFail($id);
        
        if ($beverage->image && Storage::disk('public')->exists($beverage->image)) {
            Storage::disk('public')->delete($beverage->image);
        }
        
        $beverage->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Product deleted successfully.');
    }
    
}
