<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post; 
use App\User;
use DB; 
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
   
    public function __construct()
    {
        
        $this->middleware('auth', ['except' => ['index', 'show']]);
        
    }

    public function index()
    
    {         
 
    $anynamePost =  Post::orderBy('created_at','desc')->paginate(2);
    //created_at name of column in database
      return view('posts.index')->with('anynamePostAKA',$anynamePost); 
    }

    
    public function create()
    {
  
         return view('posts.create'); 

    }
    public function store(Request $request)
    {
        //validation
        $this-> validate($request,[
            'titleTextbox' => 'required', 
            'bodyTextBox' => 'required',  
             'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle File Upload
       
        if ($request->hasFile( 'cover_imageAnyname')) {
            // Get filename with the extension
            $filenameWithExt = $request->file( 'cover_imageAnyname')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file( 'cover_imageAnyname')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $path = $request->file( 'cover_imageAnyname')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        //creat a new Post
        $postAnyname = new Post; // can the object of app\Post.php
        //title and body is from database
        $postAnyname -> title = $request -> input('titleTextbox');
        $postAnyname -> body = $request -> input('bodyTextBox');
        $postAnyname -> user_id = auth()->user()->id; 
        $postAnyname -> cover_image = $fileNameToStore; 
        $postAnyname -> save();
        
        return redirect('/posts') -> with ('success', 'Post Created successfully !'); 
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pName = Post::find($id); 
        return view('pages.show')->with('postNameAKA', $pName ); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pName = Post::find($id); 
        if(auth()->user()->id !== $pName->user_id)
        {
           
            return redirect('/posts')->with('error', 'Un-Authorized Page'); 
       
        }
       
        return view('posts.edit')->with('postNameAKA', $pName); 
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //validation
        $this-> validate($request,[
            'titleTextbox' => 'required', 
            'bodyTextBox' => 'required'  
        ]);
        
        if ($request->hasFile( 'cover_imageAnyname')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_imageAnyname')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_imageAnyname')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $path = $request->file('cover_imageAnyname')->storeAs('public/cover_images', $fileNameToStore);
        } 


        //find new Post
        $postAnyname = Post::find($id);  
       
        $postAnyname -> title = $request -> input('titleTextbox');
        $postAnyname -> body = $request -> input('bodyTextBox'); 

        if ($request->hasFile( 'cover_imageAnyname')) {
            $postAnyname-> cover_image = $fileNameToStore;  
        }
        $postAnyname -> save(); 
        
        return redirect('/posts') -> with ('success', 'Post Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //first find the post
        $post = Post::find($id);

        if (auth()->user()->id !== $post->user_id) {
   
            return redirect('/posts')->with('error', 'Un-Authorized Page'); 
        }

        if ($post->cover_image != 'noimage.jpg') {
            // Delete Image
            Storage::delete('public/cover_images/' . $post->cover_image);
        }

        $post -> delete(); 
        return redirect('/posts') -> with ('success', 'Post Deleted successfully!'); 
        
    }
}
