<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $sales = Sale::where('isSold', false)
                    ->with(['category', 'user'])
                    ->latest()
                    ->paginate(12);
                    
        return view('sales.index', compact('sales'));
    }

    public function adminIndex()
    {
        $sales = Sale::with(['category', 'user'])
                 ->latest()
                 ->paginate(15);
        return view('admin.sales.index', compact('sales'));
    }
    
    public function create()
    {
        $categories = Category::all();
        $maxImages = Setting::where('name', 'max_images')->first()->maxImages ?? 5;
        
        return view('sales.create', compact('categories', 'maxImages'));
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'product' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'thumbnail' => 'required|image|max:2048',
                'images.*' => 'image|max:2048'
            ]);
            
            $maxImages = Setting::where('name', 'max_images')->first()->maxImages ?? 5;
            
            if ($request->hasFile('images') && count($request->file('images')) > $maxImages) {
                return back()->withErrors(['images' => "Maximum {$maxImages} images allowed"]);
            }
            
            $sale = Sale::create([
                'category_id' => $validated['category_id'],
                'user_id' => auth()->id(),
                'product' => $validated['product'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'thumbnail' => file_get_contents($request->file('thumbnail'))
            ]);
            
            $folderName = now()->format('Y-m-d_H-i-s') . '_' . $sale->id . '_' . auth()->id();
            $productPath = 'products/' . $folderName;
        
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store($productPath, 'private');
                    $sale->images()->create(['path' => $path]);
                }
            }
        
            return redirect()->route('sales.index')->with('success', 'Product listed successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating sale: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error creating product. Please try again.']);
        }
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $this->authorize('update', $sale);
        $categories = Category::all();
        return view('sales.edit', compact('sale', 'categories'));
    }

    public function update(Request $request, Sale $sale)
    {
        // Allow marking as sold if user owns the sale
        if ($request->has('isSold') && Auth::id() === $sale->user_id) {
            $sale->update(['isSold' => $request->isSold]);
            return back()->with('success', 'Product status updated successfully');
        }
        
        // Regular update authorization
        $this->authorize('update', $sale);
    
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0'
        ]);
    
        $sale->update($validated);
        return redirect()->route('sales.show', $sale)->with('success', 'Product updated successfully');
    }

    public function destroy(Sale $sale)
    {
        $this->authorize('delete', $sale);
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Product deleted successfully');
    }
}