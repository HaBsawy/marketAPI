<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubCategoriesCollection;
use App\Http\Resources\SubCategoriesResource;
use App\SubCategory;
use App\User;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        return (SubCategoriesCollection::collection(SubCategory::paginate(10)))->additional([
            'msg' => 'subcategories list',
            'create subcategory' => [
                'href' => route('subcategories.store'),
                'method' => 'POST',
                'params' => 'category_id, name'
            ]
        ]);
    }

    public function userSubCategory(User $user)
    {
        return SubCategoriesCollection::collection(SubCategory::where('user_id', $user->id)->paginate(10));
    }

    public function categorySubCategory(Category $category)
    {
        return SubCategoriesCollection::collection(SubCategory::where('category_id', $category->id)->paginate(10));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|max:20'
        ]);

        if (auth()->user()->role == 'admin') {
            $subcategory = new SubCategory();
            $subcategory->user_id = auth()->user()->id;
            $subcategory->category_id = $request->category_id;
            $subcategory->name = $request->name;

            if ($subcategory->save()) {
                return response()->json([
                    'msg' => 'the subcategory is created successfully',
                    'data' => [
                        'name' => $subcategory->name,
                        'category' => $subcategory->category->name,
                        'user' => $subcategory->user->name,
                        'href' => route('subcategories.show', $subcategory->id)
                    ],
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during create subcategory'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to create subcategory'
            ], 401);
        }
    }

    public function show($id)
    {
        return (new SubCategoriesResource(SubCategory::find($id)))->additional([
            'update subcategory' => [
                'href' => route('subcategories.update', $id),
                'method' => 'PUT',
                'params' => 'name'
            ],
            'delete subcategory' => [
                'href' => route('subcategories.destroy', $id),
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
            $subcategory = SubCategory::find($id);
            $subcategory->name = $request->name;

            if ($subcategory->save()) {
                return response()->json([
                    'msg' => 'the subcategory is updated successfully',
                    'data' => [
                        'name' => $subcategory->name,
                        'category' => $subcategory->category->name,
                        'user' => $subcategory->user->name,
                        'href' => route('subcategories.show', $subcategory->id)
                    ],
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during update subcategory'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to update subcategory'
            ], 401);
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->role == 'admin') {
            $subcategory = SubCategory::find($id);

            if ($subcategory->delete()) {
                return response()->json([
                    'msg' => 'the subcategory is deleted successfully',
                    'subcategories list' => [
                        'href' => route('subcategories.index'),
                        'method' => 'GET'
                    ],
                    'create category' => [
                        'href' => route('subcategories.store'),
                        'method' => 'POST',
                        'params' => 'category_id, name',
                    ]
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during delete subcategory'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to delete subcategory'
            ], 401);
        }
    }
}
