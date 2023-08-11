<?php

namespace App\Http\Controllers\AppManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\User;
use App\Models\Role;
use App\Models\Role_user;

use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //DB::enableQueryLog();
        $users = User::select('users.id', 'users.name', 'users.email', 'users.active');

        if (trim($request->role_id) != '' or trim($request->role_id) != null) {
            $users = $users->join('role_users', 'users.id', '=', 'role_users.user_id')
                ->where('role_users.role_id', $request->role_id);
        }

        if ((trim($request->order_field) != '' or trim($request->order_field) != null) and (trim($request->order_dir) != '' or trim($request->order_dir) != null)) {
            $users = $users->orderBy('users.' . $request->order_field, $request->order_dir);
        }

        if (trim($request->search) != '' or trim($request->search) != null) {
            $users = $users->where('users.name', 'like', '%' . $request->search . '%')
                ->orWhere('users.email', 'like', '%' . $request->search . '%');
        }

        $users = $users->orderBy('users.id', 'desc')
            //->distinct()
            ->groupBy('users.id', 'users.name', 'users.email', 'users.active')
            ->paginate($request->per_page);

        //dd(DB::getQueryLog());

        return response()->json($users, 200);
    }

    public function get_role()
    {
        $role = Role::get();
        die('tes');

        return response()->json($role, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|min:6|max:20',
            'name' => 'required|max:100',
            'photo' => 'mimes:jpeg,jpg,png|max:5120'
        ], [
            'email.unique' => 'Username can not be same.',
            'photo.mimes' => 'Photo must have extension png.',
            'photo.max' => 'Photo must not be greater than 5120 kilobytes.'
        ]);

        $error = 0;
        $a = 0;
        $data = array();
        $data['errors'] = [];

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach ($errors as $value) {
                $data['errors'][$a] = $value[0];
                $a++;
            }

            $error = 1;
        }

        //echo $request->file('photo')->getClientOriginalExtension();
        //die();

        if ($request->file('photo') !== NULL) {
            if ($request->file('photo')->getClientOriginalExtension() !== 'png') {
                $data['errors'][$a] = 'Photo must have extension png.';
                $a++;
                $error = 1;
            }
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            return response()->json(DB::transaction(function () use ($request) {
                // PHOTO
                if ($request->file('photo') !== NULL) {
                    $uploadFile = $request->file('photo');

                    $nameFile = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $extensionFile = $uploadFile->getClientOriginalExtension();

                    $resultNameFile = $nameFile.".".$extensionFile;

                    $nameFile2 = $nameFile;

                    $i = 2;
                    while(file_exists(base_path().'/public/profile/'.$nameFile.".".$extensionFile))
                    {
                        $nameFile = (string)$nameFile2.$i;
                        $resultNameFile = $nameFile.".".$extensionFile;
                        $i++;
                    }

                    $destinationPath = base_path().'/public/profile/';

                    $uploadFile->move($destinationPath, $resultNameFile);
                }
                else {
                    $resultNameFile = NULL;
                }

                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->photo = $resultNameFile;
                $user->active = 'Yes';
                $user->save();

                if (count($request->role) > 0) {
                    foreach ($request->role as $r) {
                        $role_user = new Role_user();
                        $role_user->user_id = $user->id;
                        $role_user->role_id = $r;

                        $role_user->save();
                        //$role = Role_user::find($r);
                        //$user->attachRole($role);
                    }
                }

                $data['status'] = 'success';
                $data['message'] = 'Success add data user';

                return $data;
            }), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $user = User::with('roles')->where('id', $id)->firstOrFail();

        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('roles')->where('id', $id)->first();

        return response()->json($user, 200);
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100|unique:users,email,'.$id.',id,deleted_at,NULL',
            'name' => 'required|max:100',
            'photo' => 'mimes:jpeg,jpg,png|max:5120'
        ], [
            'email.unique' => 'Username can not be same.',
            'photo.mimes' => 'Photo must have extension png.',
            'photo.max' => 'Photo must not be greater than 5120 kilobytes.'
        ]);

        $error = 0;
        $a = 0;
        $data = array();
        $data['errors'] = [];

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach ($errors as $value) {
                $data['errors'][$a] = $value[0];
                $a++;
            }

            $error = 1;
        }

        //echo $request->file('photo')->getClientOriginalExtension();
        //die();

        if ($request->file('photo') !== NULL) {
            if ($request->file('photo')->getClientOriginalExtension() !== 'png') {
                $data['errors'][$a] = 'Photo must have extension png.';
                $a++;
                $error = 1;
            }
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            $user = User::find($id);

            // PHOTO
            if ($request->status_photo == 1) {
                if ($user->photo !== NULL) {
                    $filepath = public_path() . DIRECTORY_SEPARATOR . 'profile'. DIRECTORY_SEPARATOR . $user->photo;

                    try {
                        File::delete($filepath);
                    } catch (FileNotFoundException $e) {
                        // File sudah dihapus/tidak ada
                    }
                }
                if ($request->file('photo') !== NULL) {
                    $uploadFile = $request->file('photo');

                    $nameFile = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $extensionFile = $uploadFile->getClientOriginalExtension();

                    $resultNameFile = $nameFile.".".$extensionFile;

                    $nameFile2 = $nameFile;

                    $i = 2;
                    while(file_exists(base_path().'/public/profile/'.$nameFile.".".$extensionFile))
                    {
                        $nameFile = (string)$nameFile2.$i;
                        $resultNameFile = $nameFile.".".$extensionFile;
                        $i++;
                    }

                    $destinationPath = base_path().'/public/profile/';

                    $uploadFile->move($destinationPath, $resultNameFile);
                }
                else {
                    $resultNameFile = NULL;
                }
            } else {
                $resultNameFile = $user->photo;
            }

            $role = Role_user::where('user_id', '=', $id);
            $role->delete();

            // echo $request->status;die();

            $user->update([
                'email' => $request->email,
                'name' => $request->name,
                'photo' => $resultNameFile
            ]);

            if (count($request->role) > 0) {
                foreach ($request->role as $r) {
                    $role_user = new Role_user();
                    $role_user->user_id = $user->id;
                    $role_user->role_id = $r;

                    $role_user->save();
                    //$role = Role_user::find($r);
                    //$user->attachRole($role);
                }
            }

            $data['status'] = 'success';
            $data['message'] = 'Success edit data user';

            return response()->json($data, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json(DB::transaction(function () use ($id) {
            $user = User::findOrFail($id);

            $user->roles()->detach();

            $user->delete();

            $data['status'] = 'success';
            $data['message'] = 'Success delete data user';

            return $data;
        }), 200);
    }
}
