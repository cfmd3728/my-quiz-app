<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * 管理画面トップページ兼カテゴリー一覧表示
     */
    public function top()
    {
        //カテゴリー一覧を取得
        $categories=Category::get();
        //dd($categories);
        return  view('admin.top',[
            'categories'=>$categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    //カテゴリー新規登録画面表示
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    //カテゴリー新規登録処理
    public function store(StoreCategoryRequest $request)
    {
        // dd('カテゴリー新規登録処理のルートです',$request);
        // dd($request->name,$request->description);
        $category=new Category();
        $category->name       =$request->name;
        $category->description=$request->description;
        $category->save();
        return redirect()->route('admin.top');

    }

    //カテゴリー詳細表示兼クイズ一覧表示画面
    public function show(Request $request, int $categoryId)
    {
        //dd($categoryId,$request);
        $category=Category::with('quizzes')->findOrFail($categoryId);
       
       return  view('admin.categories.show',[
            'category'=>$category,
            'quizzes'=>$category->quizzes
            
        ]);

        
    }

    /**
     * カテゴリー編集画面表示
     */
    public function edit(Request $request,int $categoryId)
    {
        $category=Category::findOrFail($categoryId);
       
       return  view('admin.categories.edit',[
            'category'=>$category
        ]);
        
    }

    /**
     * カテゴリー更新処理
     */
    public function update(UpdateCategoryRequest $request, int $categoryId)
    {
       
        $category=Category::findOrFail($categoryId);
        $category->name       =$request->name;
        $category->description=$request->description;
        $category->save();
        return redirect()->route('admin.categories.show',['categoryId' => $categoryId]);


    }

    //カテゴリー削除処理
    public function destroy(Request $request,int $categoryId)
    {
       
        $category=Category::findOrFail($categoryId);
        $category->delete();
        return redirect()->route('admin.top');


    }
}
