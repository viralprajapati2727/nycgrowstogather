<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use DB;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class TeamController extends Controller
{
    public function index(){
        $blog = Team::orderBy('id','desc')->get();
        return view('admin.team.index',compact('blog'));
    }

    public function ajaxData(Request $request){
        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = Team::orderBy('id','desc');
        if(!empty($keyword)){
            $Query->where('name','like','%'.$keyword.'%');
        }

        $data = datatables()->of($Query)
        ->addColumn('title', function ($Query) {
            return $Query->name;
        })
        ->addColumn('position', function ($Query) {
            return $Query->position;
        })
        ->addColumn('email', function ($Query) {
            return $Query->email;
        })
        ->addColumn('description', function ($Query) {
            return $Query->description;
        })
        ->addColumn('src', function ($Query) {
            $icon = Helper::images(config('constant.team_url')).$Query->src;
            return "<a class='fancy-pop-image' data-fancybox='images".$Query->id."'  href='".$icon."'><img class='custom-image' src='".$icon."'></a>";
        })
        ->addColumn('status', function ($Query) {
            $text = "<span class='badge badge-danger'><a href='javascript:;' class='type-status' data-active='1' data-id='".$Query->id."'>INACTIVE</a></span>";
            if($Query->status == 1){
                $text = "<span class='badge badge-success'><a href='javascript:;' class='type-status' data-active='0' data-id='".$Query->id."'>ACTIVE</a></span>";
            }
            return $text;
        })
        ->addColumn('action', function ($Query) {
            $action_link = "";
            $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_team openTeamPopoup' data-title = '".$Query->name."' data-position = '".$Query->position."' data-email = '".$Query->email."' data-description = '".$Query->description."' data-src ='".$Query->src."' data-id = '".$Query->id."'><i class='icon-pencil7 mr-3 text-primary edit_business_category'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='team_deleted' data-id='".$Query->id . "' data-active='2' data-inuse=''><i class='icon-trash mr-3 text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','status','src'])
        ->make(true);
        return $data;
    }


    //Add Dance/Music Type
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $status = 0;
            if(isset($request->status)){
                $status = 1;
            }
            $image = $request->file('src');
            $teamData = [
                'name' => $request->title,
                'position' => $request->position,
                'email' => $request->email,
                'description' => $request->description,
                'status' => $status
            ];

            $fileName = $request->id;
            if($request->hasFile('src')){
                $fileName = 'Img-' . time() . '.' . $image->getClientOriginalExtension();
                Helper::uploaddynamicFile(config('constant.team_url'), $fileName,$image);
                $teamData['src'] = $fileName;

                if(isset($request->old_src)){
                    Helper::checkFileExists(config('constant.team_url') . $request->old_src, true, true);
                }
            }
            Team::updateOrCreate(['id' => $request->id], $teamData);
            DB::commit();
            if($request->id){
                return redirect()->route('admin.teams.index')->with('success','Team Member has been updated Successfully');
            }
            return redirect()->route('admin.teams.index')->with('success','Team Member has been added Successfully');
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 0,'message' =>'Something Went Wrong']);
        }
    }

    public function edit($id){
        $team = Team::where('id',$id)->first();
        return view('admin.team.index',compact('team'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $team = Team::find($id);
                $team->delete();
                $team->deleted_by = $user->id;
                $team->save();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Team Member has been deleted Successfully');
            } catch(Exception $e){
                DB::rollback();
                return array('status' => '0', 'msg_fail' => 'Something went wrong');
            }
        }
    }

    //Change Status
    public function changeStatus(Request $request){
        DB::beginTransaction();
        try {
            $team = Team::findOrFail($request->id);
            if ($team->status == 0) {
                $team->status = '1';
                $team->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Team Member has been activated successfully");
            } else {
                $team->status = '0';
                $team->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Team Member has been inactivated successfully");
            }
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
