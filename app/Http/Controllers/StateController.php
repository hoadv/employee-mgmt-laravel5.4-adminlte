<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\State;
use App\Country;

class StateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(["index", "create", "store", "edit", "update", "search", "destroy"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $states = DB::table('state')
        ->leftJoin('country', 'state.country_id', '=', 'country.id')
        ->select('state.id', 'state.name', 'country.name as country_name', 'country.id as country_id')
        ->paginate(5);
        return view('system-mgmt/state/index', ['states' => $states]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('system-mgmt/state/create', ['countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Country::findOrFail($request['country_id']);
        $this->validateInput($request);
         State::create([
            'name' => $request['name'],
            'country_id' => $request['country_id']
        ]);

        return redirect()->intended('system-management/state');
    }

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
    public function edit($id)
    {
        $state = State::find($id);
        // Redirect to state list if updating state wasn't existed
        if ($state == null || count($state) == 0) {
            return redirect()->intended('/system-management/state');
        }

        $countries = Country::all();
        return view('system-mgmt/state/edit', ['state' => $state, 'countries' => $countries]);
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
        $state = State::findOrFail($id);
         $this->validate($request, [
        'name' => 'required|max:60'
        ]);
        $input = [
            'name' => $request['name'],
            'country_id' => $request['country_id']
        ];
        State::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/state');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        State::where('id', $id)->delete();
         return redirect()->intended('system-management/state');
    }

    public function loadStates($countryId) {
        $states = State::where('country_id', '=', $countryId)->get(['id', 'name']);

        return response()->json($states);
    }
    
    /**
     * Search state from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
            'name' => $request['name']
            ];

       $states = $this->doSearchingQuery($constraints);
       return view('system-mgmt/state/index', ['states' => $states, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = State::query();
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where( $fields[$index], 'like', '%'.$constraint.'%');
            }

            $index++;
        }
        return $query->paginate(5);
    }
    private function validateInput($request) {
        $this->validate($request, [
        'name' => 'required|max:60|unique:state'
    ]);
    }
}
