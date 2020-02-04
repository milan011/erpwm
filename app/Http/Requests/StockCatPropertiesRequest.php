<?php

namespace App\Http\Requests;

use App\Http\Requests\CommentRequest;

// use Illuminate\Foundation\Http\FormRequest;

class StockCatPropertiesRequest extends CommentRequest
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
        // dd($this->all());
        $stkcatpropid = $this->route('id');
        // dd($id);
        if (!empty($stkcatpropid)) {
            //更新
            return [
                'label'        => 'required',
                'maximumvalue' => 'required|numeric',
                'minimumvalue' => 'required|numeric',
                'defaultvalue' => 'required',
            ];
        } else {
            // 新增
            return [
                'label'        => 'required|unique:stockcatproperties',
                'maximumvalue' => 'required|numeric',
                'minimumvalue' => 'required|numeric',
                'defaultvalue' => 'required',
                // 'role_id' => 'required|numeric|min:1',
            ];
        }

    }

    /**
     * Get the error messages for the defined validation rules.
     * 验证失败信息提示
     * @return array
     */
    public function messages()
    {
        return [
            'label.required'        => '请输入标签名',
            'label.unique'          => '标签名名重复',
            'maximumvalue.required' => '请输入最大值',
            'maximumvalue.numeric'  => '最大值应为数字',
            'minimumvalue.required' => '请输入最小值',
            'minimumvalue.numeric'  => '最小值应为数字',
            'defaultvalue.required' => '请输入默认值',
        ];
    }
}
