<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesCollection;
use App\Http\Resources\CategoriesResource;
use App\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return (CategoriesCollection::collection(Category::paginate(10)))->additional([
            'msg' => 'categories list',
            'create category' => [
                'href' => route('categories.store'),
                'method' => 'POST',
                'params' => 'name'
            ]
        ]);
    }

    public function userCategories(User $user)
    {
        return CategoriesCollection::collection(Category::where('user_id', $user->id)->paginate(10));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:20'
        ]);

        if (auth()->user()->role == 'admin') {
            $category = new Category();
            $category->user_id = auth()->user()->id;
            $category->name = $request->name;

            if ($category->save()) {
                return response()->json([
                    'msg' => 'the category is created successfully',
                    'data' => [
                        'name' => $category->name,
                        'user' => $category->user->name,
                        'href' => route('categories.show', $category->id)
                    ],
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during create category'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to create category'
            ], 401);
        }
    }

    public function show($id)
    {
        return (new CategoriesResource(Category::find($id)))->additional([
            'update category' => [
                'href' => route('categories.update', $id),
                'method' => 'PUT',
                'params' => 'name'
            ],
            'delete category' => [
                'href' => route('categories.destroy', $id),
                'method' => 'DELETE'
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:20'
        ]);

        if (auth()->user()->role == 'admin') {
            $category = Category::find($id);
            $category->name = $request->name;

            if ($category->save()) {
                return response()->json([
                    'msg' => 'the category is updated successfully',
                    'data' => [
                        'name' => $category->name,
                        'user' => $category->user->name,
                        'href' => route('categories.show', $category->id)
                    ],
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during update category'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to update category'
            ], 401);
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->role == 'admin') {
            $category = Category::find($id);

            if ($category->delete()) {
                return response()->json([
                    'msg' => 'the category is deleted successfully',
                    'categories list' => [
                        'href' => route('categories.index'),
                        'method' => 'GET'
                    ],
                    'create category' => [
                        'href' => route('categories.store'),
                        'method' => 'POST',
                        'params' => 'name',
                    ]
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during delete category'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to delete category'
            ], 401);
        }
    }
}
