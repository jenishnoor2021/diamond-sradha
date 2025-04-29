<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Dimond;
use App\Models\Process;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{

    public function login(Request $req)
    {
        // return $req->input();
        $user = User::where(['username' => $req->username])->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return redirect()->back()->with('alert', 'Username or password is not matched');
            // return "Username or password is not matched";
        } else {
            Auth::loginUsingId($user->id);
            $req->session()->put('user', $user);
            return redirect('/admin/dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function indexProcessed(Request $request, $status)
    {
        $dimonds = Dimond::where('status', $status)->orderBy('id', 'DESC')->get();
        return view('admin.dimond.index', compact('dimonds'));
    }

    public function dashboard()
    {
        $pending_count = Dimond::where('status', 'Pending')->count();
        $completed_count = Dimond::where('status', 'Completed')->count();
        $deliverd_count = Dimond::where('status', 'Delivered')->count();
        $total_count = Dimond::where('status', '!=', 'Delivered')->count();
        $processing_count = Dimond::where('status', 'Processing')->count();
        $outercount = Dimond::where('status', 'OutterProcessing')->count();

        $outerdesignation = Designation::where('category', 'Outter')->pluck('name')->toArray();
        $innerdesignation = Designation::where('category', 'Inner')->pluck('name')->toArray();

        $innerCounts = Process::whereIn('designation', $innerdesignation)
            ->whereNull('return_weight')
            ->select('designation', DB::raw('COUNT(*) as count'))
            ->groupBy('designation')
            ->pluck('count', 'designation')->toArray();

        $outterCounts = Process::whereIn('designation', $outerdesignation)
            ->whereNull('return_weight')
            ->select('designation', DB::raw('COUNT(*) as count'))
            ->groupBy('designation')
            ->pluck('count', 'designation')->toArray();

        $innerProcessDiamondIds = Process::whereIn('designation', $innerdesignation)
            ->whereNull('return_weight')
            ->pluck('dimonds_id')
            ->toArray();

        $processingDiamonds = Dimond::whereNotIn('id', $innerProcessDiamondIds)
            ->whereNotIn('status', ['Delivered', 'Completed', 'Pending'])
            ->count();

        $workers = Worker::where('is_active', 1)->get();

        $selectedMonth = request()->input('month', Carbon::now()->format('Y-m')); // e.g., '2024-01'

        $workerData = [];
        foreach ($workers as $worker) {
            $query = Process::where('worker_name', $worker->fname)
                ->whereYear('created_at', Carbon::parse($selectedMonth)->year)
                ->whereMonth('created_at', Carbon::parse($selectedMonth)->month);

            // Get distinct diamonds with their issue weights
            $distinctDiamonds = $query->select('dimonds_id', DB::raw('MAX(issue_weight) as max_issue_weight'))
                ->groupBy('dimonds_id')
                ->get();

            // Get distinct diamonds count
            $diamondCount = $distinctDiamonds->count();

            // Get total issue weight of distinct diamonds
            $issueWeight = $distinctDiamonds->sum('max_issue_weight');

            // Calculate average weight per distinct diamond
            $avgWeight = $diamondCount > 0 ? $issueWeight / $diamondCount : 0;

            // Store data for worker
            $workerData[] = [
                'name' => $worker->fname . ' ' . $worker->lname,
                'issueWeight' => $issueWeight,
                'diamondCount' => $diamondCount,
                'avgWeight' => round($avgWeight, 2)
            ];
        }

        return view('admin.index', compact('pending_count', 'processing_count', 'completed_count', 'deliverd_count', 'total_count', 'outercount', 'innerdesignation', 'innerCounts', 'outerdesignation', 'outterCounts', 'processingDiamonds', 'workerData', 'selectedMonth'));
    }

    public function profiledit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.profile.edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        // $user = User::where('id',1)->first();
        // $user->password = Hash::make($request->new_password);
        // $user->save();
        // return redirect()->back()->with("success","Password changed successfully !");
        // return $request;
        $user = Session::get('user');
        if (!(Hash::check($request->get('current_password'), $user->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }

        if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Session::get('user');
        $user->password = bcrypt($request->get('new_password'));
        $user->save();

        return redirect()->back()->with("success", "Password changed successfully !");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {}
}
