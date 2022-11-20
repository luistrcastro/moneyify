<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Scopes\AuthUserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class CategoryAPIController extends ApiBaseController
{

    protected Model $model;

    public function __construct()
    {
        $this->model = app(Category::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = Category::all();

        return $this->sendSuccess($result->toArray(), 'Categories retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $input = $request->validated();
        $newCategory = Category::create($input);
        return $this->sendSuccess($newCategory, 'New category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = Category::find($this->model->decodeHash($id));
        if (!$result) return $this->sendError('Category not found');
        return $this->sendSuccess($result, 'Category retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function update(int $id, UpdateCategoryRequest $request): JsonResponse
    {
        $input = $request->validated();

        $category = Category::find($this->model->decodeHash($id));
        if (empty($category)){
            return $this->sendError('Category not found');
        }

        $category->fill($input)->save();
        return $this->sendSuccess($input, 'New category created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $category = Category::find($this->model->decodeHash($id));
        if (empty($category)){
            return $this->sendError('Category not found');
        }

        $category->delete();
        return $this->sendSuccess([], 'New category created successfully');
    }

    /**
     * Display base Categories (Categories where user_id = null)
     *
     * @return JsonResponse
     */
    public function baseCategories(): JsonResponse
    {
        $result = Category::withoutGlobalScope(AuthUserScope::class)->whereNull('user_id')->get();
        return $this->sendSuccess($result->toArray(), 'Base categories retrieved successfully');    }
}
