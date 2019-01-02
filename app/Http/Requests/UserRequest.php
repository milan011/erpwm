<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:zr_users',
            'nick_name' => 'required|unique:zr_users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'telephone' => 'required|phone_number',
            // 'role_id' => 'required|numeric|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * 验证失败信息提示
     * @return array
     */
    public function messages() {
        return [
            'name.required' => '请输入用户名',
            'name.unique' => '用户名重复',
            'nick_name.unique' => '实际姓名重复',
            'nick_name.required' => '请输入实际姓名',
            'password.required' => '请输入密码',
            'password.min' => '密码不能少于6位',
            'password.confirmed' => '两次输入的密码不一致',
            'password_confirmation.required' => '请确认密码',
            'password_confirmation.min' => '确认密码不能少于6位',
            'telephone.required' => '请输入手机号码',
            'telephone.phone_number' => '手机号码无效',
            /*'role_id.required' => '请选择角色',
            'role_id.min' => '请选择角色',*/
            /*'email.required' => '请输入邮箱地址',
            'email.unique' => '邮箱地址已被使用',
            'email.email' => '邮箱格式错误',*/
        ];
    }
}
