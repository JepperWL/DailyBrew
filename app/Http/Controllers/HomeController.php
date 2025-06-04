<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beverage;
use App\Models\Brand;
use App\Models\Category;

class HomeController extends Controller
{
    //
    
     public function index()
    {
        $todaysRecommendations = Beverage::with(['category', 'brand'])
            ->where('is_available', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        $featuredProducts = Beverage::with(['category', 'brand'])
            ->where('is_available', true)
            ->whereNotIn('id', $todaysRecommendations->pluck('id'))
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($featuredProducts->count() < 4) {
            $featuredProducts = Beverage::with(['category', 'brand'])
                ->where('is_available', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
        }

        $brands = Brand::orderBy('name')->get();

        return view('user.home', compact('todaysRecommendations', 'featuredProducts', 'brands'));
    
    }
    

     public function detail($id)
    {
       $beverage = Beverage::with(['category', 'brand'])->findOrFail($id);
    
  
    $recommendedProducts = Beverage::with(['category', 'brand'])
        ->where('id', '!=', $beverage->id)
        ->where('is_available', true)
        ->inRandomOrder()
        ->take(2)
        ->get();

    return view('user.detail', compact('beverage', 'recommendedProducts'));
 }

     public function shop(Request $request)
    {
        $query = Beverage::with(['category', 'brand']);

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('brand', function($brandQuery) use ($searchTerm) {
                      $brandQuery->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

       $query->orderByRaw('is_available DESC');

        $beverages = $query->paginate(12);
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('user.shop', compact('beverages', 'brands', 'categories'));
    }

}
