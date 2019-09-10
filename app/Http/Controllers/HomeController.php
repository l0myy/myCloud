<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if (Auth::id() == $id) {
            $files = Storage::allFiles($id);
            $directories = Storage::allDirectories($id);

            return view('index', ['files' => $files, 'dirs' => $directories]);
        }

        return redirect('/' . Auth::id());

    }

    public function home()
    {
        return view('home');
    }

    public function load(Request $request)
    {

        $this->validate($request, [
            'newFile' => 'required',
        ]);

        $originalName = str_replace(' ', '', $request->file('newFile')->getClientOriginalName());

        $path = $request->file('newFile')->storeAs($request->user()->id, $originalName);

        return back()->with('message', 'File successfully loaded!');
    }

    public function delFile(Request $request)
    {

        $this->validate($request, [
            'fileName' => 'required'
        ]);

        Storage::delete($request->fileName);

        return back()->with('message', 'File deleted successfully!');
    }

    public function makeDir(Request $request)
    {

        $this->validate($request, [
            'dirName' => 'required|alpha_dash'
        ]);

        Storage::makeDirectory($request->user()->id . '/' . $request->dirName);

        return back()->with('message', 'Folder created successfully!');
    }

    public function delDir(Request $request)
    {


        $this->validate($request, [
            'dirName' => 'required'
        ]);

        Storage::deleteDirectory($request->dirName);

        return back()->with('message', 'Folder deleted successfully!');
    }

    public function editFile(Request $request)
    {
        $this->validate($request, [
            'newFileName' => 'required',
            'oldFileName' => 'required'
        ]);
        $oldFileName = $request->oldFileName;
        $newFileName = str_replace(' ', '', $request->newFileName);
        $format = pathinfo($oldFileName, PATHINFO_EXTENSION);
        Storage::move($request->oldFileName, $request->user()->id . "/" . $newFileName . "." . $format);

        return back()->with('message', 'File moved successfully!');
    }

    public function linkFile(Request $request)
    {
        $this->validate($request, [
            'fileName' => 'required'
        ]);

        return response()->download(Storage::path(request()->fileName));
    }
}

