<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * Get all files and directories from the home folder ./user_id
     * and redirect you to the home page
     *
     * @return \Illuminate\Http\Response
     * @param $id - user_id from the table Users
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

    /**
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function home()
    {
        return redirect('/' . Auth::id());
    }

    /**
     *
     * Function for load file to the home user directory
     * and return back on the home page with the status message
     *
     * @param Request $request
     * @var $request->newFile - new file object
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function load(Request $request)
    {

        $this->validate($request, [
            'newFile' => 'required',
        ]);

        $originalName = str_replace(' ', '', $request->file('newFile')->getClientOriginalName());

        $path = $request->file('newFile')->storeAs($request->user()->id, $originalName);

        return back()->with('message', 'File successfully loaded!');
    }

    /**
     *
     * Function for delete files from the home user directory
     * and revert you back to the home page with status message
     *
     * @param Request $request
     * @var $request->fileName - file name for delete
     * @return \Illuminate\Http\RedirectResponse
     *
     *
     */
    public function delFile(Request $request)
    {

        $this->validate($request, [
            'fileName' => 'required'
        ]);

        Storage::delete($request->fileName);

        return back()->with('message', 'File deleted successfully!');
    }

    /**
     *
     * Function for create a new directory with the provided by user name
     *
     * @param Request $request
     * @var $request->dirName - name for new directory
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function makeDir(Request $request)
    {

        $this->validate($request, [
            'dirName' => 'required|alpha_dash'
        ]);

        Storage::makeDirectory($request->user()->id . '/' . $request->dirName);

        return back()->with('message', 'Folder created successfully!');
    }

    /**
     *
     *  Function for delete directory provided by user directory name
     *
     * @param Request $request
     * @var $request->dirName - name of directory to delete
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function delDir(Request $request)
    {
        $this->validate($request, [
            'dirName' => 'required'
        ]);

        Storage::deleteDirectory($request->dirName);

        return back()->with('message', 'Folder deleted successfully!');
    }

    /**
     *
     * Function for move or edit file
     *
     * @param Request $request
     * @var $request->oldFileName - file name for edit/move
     * @var $request->newFileName - new file name
     * @var $format - format of old file
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function editFile(Request $request)
    {
        $oldFileName = $request->oldFileName;

        //Add format for the new file
        $format = pathinfo($oldFileName, PATHINFO_EXTENSION);

       //Delete spaces in the new file name
        $newFileName = str_replace(' ', '', $request->newFileName);

        //Check if user changing file name
        if ($newFileName == null) {
            $newFileName = $oldFileName;
        } else {
            $newFileName = $newFileName . "." . $format;
        }
        $newDirName = $request->newDirName;

        //Check if user move file
        if ($newDirName == null) {
            $newDirName = $request->user()->id;
        }
        $newFileName = str_replace($request->user()->id . "/", '', $newFileName);

        Storage::move($oldFileName, $newDirName . "/" . $newFileName);

        return back()->with('message', 'File moved successfully!');
    }

    /**
     *
     * Function for revert user url for download file
     *
     * @param Request $request
     * @var $request->fileName - file name which user want to download
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     */
    public function linkFile(Request $request)
    {
        $this->validate($request, [
            'fileName' => 'required'
        ]);

        return response()->download(Storage::path(request()->fileName));
    }
}

