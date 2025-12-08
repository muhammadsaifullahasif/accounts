<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Laravel\Facades\Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'type' => 'required',
        ]);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->type = $request->type;
            $user->save();

            $profile_image = '';
            if($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $file_extension = $request->file('profile_image')->extension();
                $file_name = Carbon::now()->timestamp . '.' . $file_extension;
                $this->GenerateUserThumbnailsImage($image, $file_name, 'users');
                $profile_image = $file_name;
            }

            $user->user_meta()->create([
                'user_id' => $user->id,
                'meta_key' => 'profile_image',
                'meta_value' => $profile_image,
            ]);

            return redirect()->route('users.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation_rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'type' => 'required',
        ];
        
        if ($request->password != '') {
            $validation_rules['password'] = 'required|confirmed';
        }
        
        $request->validate($validation_rules);
        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password != '') {
                $user->password = Hash::make($request->password);
            }
            $user->type = $request->type;
            $user->save();
            
            $profile_image = $user->user_meta['profile_image'] ?? '';
            if($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $file_extension = $request->file('profile_image')->extension();
                $file_name = Carbon::now()->timestamp . '.' . $file_extension;
                $this->GenerateUserThumbnailsImage($image, $file_name, 'users');
                $profile_image = $file_name;
            }
            
            $user->user_meta()->updateOrCreate(
                ['user_id' => $id, 'meta_key' => 'profile_image'],
                ['meta_value' => $profile_image]
            );

            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->user_meta['profile_image'] != '') {
            if (File::exists(public_path('uploads/users').'/'.$user->user_meta['profile_image'])) {
                File::delete(public_path('uploads/users').'/'.$user->user_meta['profile_image']);
            }
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User has been deleted successfully!');
    }

    public function GenerateUserThumbnailsImage($image, $imageName, $destinationFolder)
    {
        $destinationPath = public_path('uploads/');
        $img = Image::read($image->path());
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function($constrait) {
            $constrait->aspectRatio();
        })->save($destinationPath . $destinationFolder . '/' . $imageName);
    }
}
