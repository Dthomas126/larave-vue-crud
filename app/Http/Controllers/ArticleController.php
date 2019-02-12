<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Resources\Article as ArticleResource;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\Articles;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       $article = Article::orderBy('id','desc')->paginate(5);
        
        
        return   Articles::collection($article);

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
        Article::create([
            'title' => $request->title,
            'body'=>$request->body,
            'author' => $request->author
        ]);

        return Articles::collection(Article::paginate(5)->orderBy('id','asc'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $article = Article::find($id);

        return new Articles($article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $article = Article::find($id);

        $article->title = $request->title;
        $article->author = $request->author;
        $article->body = $request->body;

        $article->save();


        return Articles::collection(Article::paginate(5)->orderBy('id', 'asc'));


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //delete article
        $article->delete();

        $articles = Article::orderBy('id','desc')->paginate(5);

        return  Articles::collection($articles);
    }
}