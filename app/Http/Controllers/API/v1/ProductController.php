<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Controllers\Api\BaseApiController as BaseApiController;

class ProductController  extends BaseApiController
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
            return $this->respondNotFound('Product not found!');
        }else{
            $result = [
                'ProductCategory' => ProductResource::collection($products),
                // 'links' => [
                //     'first' => $products->toArray()['first_page_url'] ?? null,
                //     'last' => $products->toArray()['last_page_url'] ?? null,
                //     'prev' => $products->toArray()['prev_page_url'] ?? null,
                //     'next' => $products->toArray()['next_page_url'] ?? null,
                // ],
                'paginator' => [
                    "current_page" => $products->currentPage(),
                    "last_page" =>  $products->lastPage(),
                    "path" =>  $products->path(),
                    "per_page" =>  $products->perPage(),
                    "total" =>  $products->total()
                ]
            ]; 
            return $this->sendResponse($result, 'article retrieved successfully.');
        }
    }

    public function favProduct($id)
    {
        // return response()->json([Auth::user()], 200); 
        $favorite = Favorite::where('product_id', $id)->where('user_id', \Auth::id())->first();
        if($favorite){
            $favorite->delete();
            return response()->json(['message'=>'Product remved favorite successfully.'], 200); 
            // return response()->json(['error'=>'Unauthorised'], 401); 
        }else{
            $favorite               = new Favorite;
            $favorite->product_id   = $id;
            $favorite->user_id      = \Auth::id();
            $favorite->save();
            return response()->json(['message'=>'Product added favorite successfully.'], 200); 
        }
    }
}
