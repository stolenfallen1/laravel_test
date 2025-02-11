<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function validateToken(Request $request) 
    {
        $token = $request->header('Authorization');
        $timestamp = $request->header('Token-Timestamp');
        $email = $request->header('User-Email');

        if (!$token || !$timestamp) {
            return response([
                'message' => ['Unauthorized']
            ], 401);
        }

        $expectedToken = hash('sha256', $email . $timestamp);
        return hash_equals($expectedToken, $token);
    }
    public function index() 
    {
        // if (!$this->validateToken(request())) {
        //     return response([
        //         'message' => ['Unauthorized']
        //     ], 401);
        // }

        $products = Products::all();
        return response()->json($products);
    }

    public function store(Request $request) 
    {
        if (!$this->validateToken($request)) {
            return response([
                'message' => ['Unauthorized']
            ], 401);
        }

        $validated = $request->validate([
            'name' => 'required',
            'details' => 'required'
        ]);

        $product = Products::create($validated);
        return response()->json($product, 201);
    }

    public function update(Request $request, $id) 
    {
        if (!$this->validateToken($request)) {
            return response([
                'message' => ['Unauthorized']
            ], 401);
        }

        $product = Products::find($id);
        
        if (!$product) {
            return response([
                'message' => ['Product not found']
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required',
            'details' => 'required'
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy(Request $request, $id) 
    {
        if (!$this->validateToken($request)) {
            return response([
                'message' => ['Unauthorized']
            ], 401);
        }

        $product = Products::find($id);
        
        if (!$product) {
            return response([
                'message' => ['Product not found']
            ], 404);
        }

        $product->delete();
        return response()->json(null, 204);
    }
}
