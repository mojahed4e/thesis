<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
namespace App\Http\Controllers;

use App\Tag;
use App\User;
use App\Http\Requests\TagRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tag::class);
    }

    /**
     * Display a listing of the tags
     *
     * @param \App\Tag  $model
     * @return \Illuminate\View\View
     */
    public function index(Tag $model)
    {
        $this->authorize('manage-items', User::class);

        return view('tags.index', ['tags' => $model->all()]);
    }

    /**
     * Show the form for creating a new tag
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created tag in storage
     *
     * @param  \App\Http\Requests\TagRequest  $request
     * @param  \App\Tag  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TagRequest $request, Tag $model)
    {
        $model->create($request->all());

        return redirect()->route('tag.index')->withStatus(__('Tag successfully created.'));
    }


    /**
     * Store a newly created tag in storage
     *
     * @param  \App\Http\Requests\TagRequest  $request
     * @param  \App\Tag  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ajaxStore(TagRequest $request, Tag $model)
    {
        $vTagName = trim(rawurldecode($request->name));
        $aTagsFound = $model->Active()->where(Str::upper('name'), '=', Str::upper($vTagName))->get();

        $vMessage = "Problem in input data";
        $vStatus = 0;
        $vTagID = 0;
        if(count($aTagsFound) > 0){
             $vMessage = "Keyword already exists";
             $vStatus = 0;
        }
        else {
            $vTagID = $model->create($request->merge([
                        'name' => rawurldecode($request->name),
                        'shortname' => rawurldecode($request->shortname)
                     ])->all());
            $vMessage = "Keyword successfully created";
            $vStatus = 1;
        }
        $tags = $model->Active()->get();
        return response()->json(['status'=> $vStatus,'message' => $vMessage,'insertID' => $vTagID]);       
    }

    /**
     * Show the form for editing the specified tag
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\View\View
     */
    public function edit(Tag  $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TagRequest  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->all());

        return redirect()->route('tag.index')->withStatus(__('Tag successfully updated.'));
    }

    /**
     * Remove the specified tag from storage
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Tag $tag)
    {
        if (!$tag->items->isEmpty()) {
            return redirect()->route('tag.index')->withErrors(__('This tag has items attached and can\'t be deleted.'));
        }

        $tag->delete();

        return redirect()->route('tag.index')->withStatus(__('Tag successfully deleted.'));
    }
}
