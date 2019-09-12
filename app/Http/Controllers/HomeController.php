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
     * @param $id - user_id from the table Users
     * @var $newFiles - array with all files without home directory
     * @var $newDirs - array with all directories without home directory
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $newFiles = array();
        $newDirs = array();
        if (Auth::id() == $id) {
            $files = Storage::allFiles($id);
            foreach ($files as $file => $val) {
                array_push($newFiles, str_replace(Auth::id() . "/", '', $files[$file]));
            }

            $directories = Storage::allDirectories($id);
            foreach ($directories as $dirs => $val) {
                array_push($newDirs, str_replace(Auth::id() . "/", '', $directories[$dirs]));
            }
            return view('index', ['files' => $newFiles, 'dirs' => $newDirs]);
        }
        return redirect('/' . Auth::id());
    }

    /**
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * @var $id - user id
     */
    public function home()
    {
        $id = Auth::id();

        return redirect('/' .$id);
    }

    /**
     *
     * Function for load file to the home user directory
     * and return back on the home page with the status message
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @var $request ->newFile - new file object
     * @var $newFileName - name of the loaded file
     * @var $newDirName - directory where file should be create
     */
    public function load(Request $request)
    {

        $this->validate($request, [
            'newFile' => 'required',
        ]);

        $id = Auth::id();

        $newFileName = str_replace(' ', '', $request->file('newFile')->getClientOriginalName());

        $newDirName = $request->newDirName;

        if ($newDirName !== $id . "/") {
            $newDirName = $id . "/" . $newDirName . "/";
        }

        if (Storage::exists($newDirName . $newFileName)) {
            return back()->with('error', 'File already exists in this directory.');
        }
        else{
            $path = $request->file('newFile')->storeAs($newDirName,$newFileName);
            return back()->with('message', 'File successfully loaded!');
        }
    }

    /**
     *
     * Function for delete files from the home user directory
     * and revert you back to the home page with status message
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     *
     * @var $fileName - file name for delete
     * @var $id - user id
     */
    public function delFile(Request $request)
    {

        $this->validate($request, [
            'fileName' => 'required'
        ]);

        $id = Auth::id();

        $fileName = $id . "/" . $request->fileName;

        Storage::delete($fileName);

        return back()->with('message', 'File deleted successfully!');
    }

    /**
     *
     * Function for create a new directory with the provided by user name
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @var $dirName - name for new directory
     * @var $newDirName - directory where new directory should be create
     * @var $id - user id
     */
    public function makeDir(Request $request)
    {

        $this->validate($request, [
            'dirName' => 'required|alpha_dash'
        ]);

        $newDirName = $request->newDirName;

        $id = $request->user()->id;

        if ($newDirName !== $id) {
            $newDirName = $id . "/" . $newDirName . "/";
        }

        $dirName = str_replace(' ', '', $request->dirName);

        Storage::makeDirectory($newDirName . '/' . $dirName);

        return back()->with('message', 'Folder created successfully!');
    }

    /**
     *
     *  Function for delete directory provided by user directory name
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @var $dirName - name of directory to delete
     * @var $id - user id
     */
    public function delDir(Request $request)
    {
        $this->validate($request, [
            'dirName' => 'required'
        ]);

        $id = Auth::id();

        $dirName = $id . "/" . $request->dirName;

        Storage::deleteDirectory($dirName);

        return back()->with('message', 'Folder deleted successfully!');
    }

    /**
     *
     * Function for move or edit file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @var $newFileName - new file name
     * @var $format - format of old file
     * @var $oldFileName - file name which should be edit/move
     * @var $id - user id
     */
    public function editFile(Request $request)
    {
        $id = Auth::id();

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


        $oldFileName =$id . "/" . $oldFileName;

        if ($newDirName == $id) {
            $newFileName = $id . "/" . $newFileName;
        } else {
            $newFileName = $id . "/" . $newDirName . "/" . $newFileName;
        }

        if (Storage::exists($newFileName)) {
            return back()->with('error', 'File already exists in this directory.');
        } else {
            Storage::move($oldFileName, $newFileName);
            return back()->with('message', 'File moved successfully!');
        }
    }

    /**
     *
     * Function for revert user url for download file
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @var $request ->fileName - file name which user want to download
     *
     */
    public function linkFile(Request $request)
    {
        $this->validate($request, [
            'fileName' => 'required'
        ]);

        return response()->download(Storage::path($request->user()->id . "/" . request()->fileName));
    }
}

