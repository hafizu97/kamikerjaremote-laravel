<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    protected $userRepo;

    protected $profileRepo;

    public function __construct(UserRepository $userRepo, ProfileRepository $profileRepo)
    {
        $this->userRepo = $userRepo;
        $this->profileRepo = $profileRepo;
    }

    public function index(Request $request)
    {
        $result = $this->profileRepo->whereHas('skillsets', function ($query) use ($request) {
            $query->where('skill_set_name', 'LIKE', '%'.$request->skill.'%');
        })->get();

        if ($request->has('q')) {

            $model = \App\User::when($request->q, function ($query) use ($request) {
                $query->member()->where(function ($query) use ($request) {
                    $query->where('first_name', 'LIKE', '%'.$request->q.'%')
                            ->orWhere('last_name', 'LIKE', '%'.$request->q.'%');
                });
            })->paginate(10);
    
            $model->appends($request->only('q'));
            
            if ($request->ajax()) {
                return \Response::json(
                    \View::make('admins.browse.partials._result-browse', compact('model'))->render()
                );
            }
        } else {
            
            $model = \App\User::member()->paginate(10);

            if ($request->ajax()) {
                return \Response::json(
                    \View::make('admins.browse.partials._result-browse', compact('model'))->render()
                );
            }
        }

        return view('admins.browse.browse', compact('model'));
    }
}
