<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use MongoDB\BSON;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
    }

    public function show(Request $request, $id)
    {
        $category = Category::find($id);
        $path = Category::getFullPath($category);
        $products = Category::getProductsForLeafCategories($category);
        $maxPrice = $products->max('price');
        if($maxPrice == null) // kada nema proizvoda
            $maxPrice = 0;
        if($category->children()->exists()) {
            $subCategories = $category->children;
        } else {
            $filters = Category::getFiltersAndCount($category);
            if(!empty($filters)) {
                $filters = array_filter($filters, function($filter) {   // izbace se prazni
                    return (!empty($filter));
                });
            }
        }

        // Ako je search bar napravio request (glupo resenje, ali da ne menjam sad)
        if(isset($request['keyword'])) {
            $products = Product::where('name', 'like', '%'.$request['keyword'].'%')->get();
        }

        return view('categories',
           compact('category', 'path', 'products', 'subCategories', 'maxPrice', 'filters'));
    }

    public function getFilteredData(Request $request, $id)
    {
        if(!empty($request->all())) {
            if(count($request->all()) === 1 && isset($request['price'])) {
                // Ako je samo price onda se filtrira posebno, jer u query-ju ispod
                // mora da ima elemenata u okviru $or niza, a ovako nece biti
                $category = Category::find($id);
                $products = Category::getProductsForLeafCategories($category)
                    ->where('price', '<=', floatval($request['price']));
            } else {
                $products = self::buildFilterQuery($request->all(), $id); // filtrira
            }
            return compact('products');
        }
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
        if ($request->category_id != null) {
            $category->category_id = $request->category_id;
        }
        $category->details = $request->additionalFields;
        $category->save();
        return response()->json($category);
    }

    public function update(Request $request)
    {
        $category = Category::find($request->id);
        $category->name = $request->name;
        if ($request->category_id != null) {
            $category->category_id = $request->category_id;
        }
        $category->details = $request->additionalFields;
        $category->save();
        return response()->json($category);
    }

 
    private static function buildFilterQuery($filters, $category_id)
    {
        // treba da dodam i da matchuje kategoriju prvo
        $query = [[
            '$match' => [
                '$and' => [
                    [
                        'category_id' => $category_id
                    ],
                    [
                        'price' => [
                                '$lte' => floatval($filters['price'])
                            ]   // uvek filtrira po ceni
                    ],
                    [
                        '$or' => []
                    ]
                ]
            ]
        ]];
         // $query[0]['$match']['$and'][1]['$or'] ovde ubacujem
        foreach($filters as $key => $filter) {
            if($key === 'price') continue; // preskoci jer je vec dodato iznad
            $filterArray = [$key => ['$in' => []]];  // $filterArray[$key]['$in']
            foreach($filter as $value) {
                $filterArray[$key]['$in'][] = $value;
            }            
            $query[0]['$match']['$and'][2]['$or'][] = $filterArray;
        }
        $result = Product::raw()->aggregate($query);
        $products = [];
        foreach($result as $doc) {
            $products[] = $doc;
        }
        return $products;
    }

    public function getCategories()
    {
        $categories = Category::all();
        $tree = Category::buildTree($categories, 0);

        $response = ['tree' => $tree, 'categories' => $categories];

        return response()->json($response);
    }

    public function getById(Request $request)
    {
        $category = Category::find($request->id);

        return response()->json($category);
    }

    public function destroy(Request $request, $id)
    {
       // $result = [];
        $category = Category::find($id);
        $children =  Category::allChildren($category);
        foreach($children as $child) {
            $child->delete();
        }
        $category->delete();
        return response()->json($children);
    }

}
