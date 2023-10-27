<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark;

class BookmarkController extends Controller
{
    // View for All Bookmarks
    public function dashboard() {
        $user = Auth::user();
        $bookmarks = $user->bookmarks;

        return view('dashboard', compact('bookmarks'));
    }

    /**
     * Show Internal Bookmark
     */
    public function show($slug) {
        $bookmark = Bookmark::where('slug', $slug)->first();
        return view('bookmarks.show', compact('bookmark'));
    }

    /** 
     * Edit a Internal Bookmark
     */
    public function update(Request $request, $id) {
        $request->validate ([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'url' => 'required|url',
            'description' => 'nullable|string',
            'tags' => 'required',
        ]);

        //find the Bookmark
        $bookmark = Bookmark::findorfail($id);
        //update the bookmark
        $bookmark->update ([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'url' => $request->input('url'),
            'description' => $request->input('description'),
            'thumbnail' => $request->input('thumbnail'),
        ]);

        if($bookmark->tags) {
            foreach($bookmark->tags as $tag) {
                $bookmark->detachTag($tag->name);
            }
        }
        $bookmark->attachTag($request->input('tags'));

        //redirect to main page with the success update
        return redirect()->route('bookmark.show',$bookmark->slug)->with('success', 'Bookmark updated successfully');
    }

    /**
     * Show Create
     */
    public function create() {
        return view('bookmarks.create');
    }

    /** 
     * Store new bookmark
     */
    public function store(Request $request) {
        $request->validate ([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'url' => 'required|url',
            'description' => 'nullable|string',
            'tags' => 'required',
        ]);

        //Create a new bookmark with user_id
        $bookmark = new Bookmark ([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'url' => $request->input('url'),
            'description' => $request->input('description'),
            'thumbnail' => $request->input('thumbnail'),
            'user_id' => auth()->user()->id, //Assuming you're using authentication
        ]);

        //Save the boomark to the database
        $bookmark->save();

        $bookmark->attachTag($request->get('tags'));

        //redirect to main page with the success update
        return redirect()->route('dashboard')->with('success', 'Bookmark created successfully');
    }

    /**
     * Search Function
     */
    public function search(Request $request) {
        $searchTerm = $request->input('name'); //Assuming you are searching by name

        $bookmarks = Bookmark::where('name','like', '%' . $searchTerm . '%')->get();

        return view('dashboard', compact('bookmarks'));
    }

    /**
     * Destroy!!
     */
    public function destroy ($id) {
        // Find the bookmark by its ID
        $bookmark = Bookmark::findOrFail($id);

        if($bookmark->tags) {
            foreach($bookmark->tags as $tag) {
                $bookmark->detachTag($tag->name);
            }
        }

        //Delete the bookmark
        $bookmark->delete();

        return redirect()->route('dashboard')->with('success', 'Bookmark deleted succesfully');
    }
}
