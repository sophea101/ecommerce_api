<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCard;
use Illuminate\Support\Facades\Auth;
use App\User;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->paginate(20);
        $categories = Category::all();
        return ProductResource::collection($products)
                            ->additional([
                                'categories'    => CategoryResource::collection($categories),
                                'banner'        => Product::orderBy('id', 'desc')->select('name as title', 'image')->get()->take(5),
                                'status'        => 200,
                                'message'       => 'product get successfully.',
                            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function filterByCategory($id)
    {
        $products = Product::with('category')->where('category_id', $id)->paginate(20);
        $categories = Category::all();
        if($products->isEmpty()){
            return response()->json([
                'ProductCategory' => ProductResource::collection($products),
                'message'=>'Product added card successfully.',
                'links' => [
                    'first'     => $products->toArray()['first_page_url'] ?? null,
                    'last'      => $products->toArray()['last_page_url'] ?? null,
                    'prev'      => $products->toArray()['prev_page_url'] ?? null,
                    'next'      => $products->toArray()['next_page_url'] ?? null,
                ],
                'meta' => [
                    "current_page"  => $products->currentPage(),
                    "last_page"     =>  $products->lastPage(),
                    "path"          =>  $products->path(),
                    "per_page"      =>  $products->perPage(),
                    "total"         =>  $products->total()
                ],
                'status'    => 200,
                'message'   => 'product get successfully.'
            ], 200); 
        }else{
            return response()->json(['error'=>'Product not found!'], 404); 
        }

       
    }

    public function favProduct($id)
    {
        // return response()->json([Auth::user()], 200); 
        $product_card = ProductCard::where('product_id', $id)->where('user_id', \Auth::id())->first();
        if($product_card){
            $product_card->delete();
            return response()->json(['message'=>'Product remved card successfully.'], 200); 
            // return response()->json(['error'=>'Unauthorised'], 401); 
        }else{
            $product_card               = new ProductCard;
            $product_card->product_id   = $id;
            $product_card->user_id      = \Auth::id();
            $product_card->save();
            return response()->json(['message'=>'Product added card successfully.'], 200); 
        }
    }
}
