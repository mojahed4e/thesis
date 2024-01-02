<?php
namespace App\Http\Controllers;

use App\User;
use App\Term;
use App\Http\Requests\TermRequest;

class TermController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Term::class);
    }

    /**
     * Display a listing of the terms
     *
     * @param \App\Term  $model
     * @return \Illuminate\View\View
     */
    public function index(Term $model)
    {
        $this->authorize('manage-items', User::class);

        return view('terms.index', ['terms' => $model->Active()->get()]);
    }

    /**
     * Show the form for creating a new Term
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('terms.create');
    }

    /**
     * Store a newly created term in storage
     *
     * @param  \App\Http\Requests\TermRequest  $request
     * @param  \App\Term  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TermRequest $request, Term $model)
    {
        $model->create($request->all());

        return redirect()->route('term.index')->withStatus(__('Term successfully created.'));
    }

    /**
     * Show the form for editing the specified term
     *
     * @param  \App\Term  $term
     * @return \Illuminate\View\View
     */
    public function edit(Term $term)
    {
        return view('terms.edit', compact('term'));
    }

    /**
     * Update the specified term in storage
     *
     * @param  \App\Http\Requests\TermRequest  $request
     * @param  \App\Term  $term
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TermRequest $request, Term $term)
    {
        $term->update($request->all());

        return redirect()->route('term.index')->withStatus(__('Term successfully updated.'));
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  \App\Term  $term
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Term $term)
    {
        $term->update(
            [           
                'status' => 2
			]
        );
        return redirect()->route('term.index')->withStatus(__('Term successfully deleted.'));
    }
}
