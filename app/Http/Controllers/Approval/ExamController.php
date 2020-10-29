<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approval\ApprovalHistory\Approval\ApprovalHistory\Approval\ApprovalHistory\Approval\ApprovalHistory\ExamRequest;
use App\Http\Requests\Approval\ApprovalHistory\Approval\ApprovalHistory\Approval\ApprovalHistory\Approval\ApprovalHistory\IdRequest;
use App\Models\Approve;
use App\Models\Form;
use Illuminate\Http\Request;
use function MongoDB\BSON\fromJSON;

class ExamController extends Controller
{

    /**
     * 审核通过
     * @author Dujingwen <github.com/DJWKK>
     * @param  $request
     * @return json
     */
    public function pass(IdRequest $request){
        $info  = getDinginfo($request['code']);
        $role = $info->role;
        $name = $info->name;
        //        借用部门负责人
        //        实验室借用管理员
        //        实验室中心主任
        //        设备管理员
        $role = '设备管理员';
        $name = '设备管理员';

        $form_id = $request->get('form_id');
        $form_type = Form::findType($form_id);

        if ($form_type == 1 || $form_type == 5){
            Approve::updateName($form_id,$role,$name);
            $form_status = Form::findStatus($form_id);
            Form::updatedStatus($role,$form_id,$form_status);
            Form::updatedStatuss($form_type,$role,$form_id,$form_status);
        }else if($form_type == 3) {
            Approve::updateName($form_id,$role,$name);
            Approve::updateNames($form_id,$role,$name);
            $form_status = Form::findStatus($form_id);
            Form::updatedStatus($role,$form_id,$form_status);
            Form::updatedStatuss($form_type,$role,$form_id,$form_status);
        }
        return $form_id?
            json_success('审核表单'.$form_id.'成功!',1,200) :
            json_fail('审核表单'.$form_id.'失败!',null,100);
    }

    /**
     * 审核通过
     * @author Dujingwen <github.com/DJWKK>
     * @param ExamRequest $request
     * @return json
     */
    public function noPass(ExamRequest $request){
        $info  = getDinginfo($request['code']);
        $role = $info->role;
        $name = $info->name;
        //        借用部门负责人
        //        实验室借用管理员
        //        实验室中心主任
        //        设备管理员
        $role = '借用部门负责人';
        $name = '借用部门负责人';

        $form_id = $request->get('form_id');
        $form_type = Form::findType($form_id);
        $reason = $request->get('reason');
        if ($form_type == 1 || $form_type == 5){
            Approve::noUpdateName($form_id,$role,$name,$reason);
            $form_status = Form::findStatus($form_id);
            Form::noUpdateStatus($role,$form_id,$form_status);
            Form::npUpdatedStatuss($role,$form_id,$form_status);
        }else if($form_type == 3){
            Approve::noUpdateName($form_id,$role,$name,$reason);
            Approve::noUpdateNames($form_id,$role,$name,$reason);
            $form_status = Form::findStatus($form_id);
            Form::noUpdateStatus($role,$form_id,$form_status);
            Form::npUpdatedStatuss($role,$form_id,$form_status);
        }
        return $form_id?
            json_success('审核表单不通过'.$form_id.'成功!',1,200) :
            json_fail('审核表单不通过'.$form_id.'失败!',null,100);
    }
}

