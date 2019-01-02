<template>
<div>
  <el-dialog  :visible.sync="dialogInfoVisible">
      <el-row>
        <el-col :span="24">
          <div class="grid-content bg-purple-dark self-style">
            <span>{{ $t('info.name') }}:{{ temp.name }}({{temp.user_telephone}})</span>
          </div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('info.manager_name') }}:<span>{{temp.manage_name}}</span></div>
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('info.new_telephone') }}:<span>{{temp.new_telephone}}</span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('table.date') }}:
          <span>
            {{ temp.created_at | parseTime('{y}-{m}-{d}') }}
            |
            {{ temp.belongs_to_creater ? temp.belongs_to_creater.nick_name : '' }}
          </span>
        </div></el-col>               
      </el-row>
      <el-row>
        <el-col :span="12"><div class="grid-content bg-purple self-style">
          {{ $t('info.uim_number') }}:<span>{{temp.uim_number}}</span></div>
        </el-col> 
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('info.project_name') }}:<span>{{temp.project_name}}</span>
        </div></el-col>
        <el-col :span="3"><div class="grid-content bg-purple-light self-style">
          {{ $t('info.old_bind') }}:<span>{{ oldBindStatusMap[temp.old_bind]}}</span>
        </div></el-col>
        <el-col :span="3"><div class="grid-content bg-purple-light self-style">
          {{ $t('info.is_jituan') }}:<span>{{ jiTuanStatusMap[temp.is_jituan]}}</span>
        </div></el-col>
      </el-row>
      <el-row>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('info.collections') }}:<span>{{temp.collections}}</span></div>
        </el-col> 
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('info.collections_type') }}:<span>{{ collections_type[(temp.collections_type - 1)]}}</span></div>
        </el-col>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('info.balance_month') }}:<span>{{temp.balance_month }}</span>
        </div></el-col> 
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('info.status') }}:<span>{{ statusMap[temp.status] }}</span></div>
        </el-col>
      </el-row>
      <el-row>  
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('info.netin') }}:<span>{{temp.netin}}</span>
        </div></el-col>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('info.remark') }}:<span>{{temp.remark}}</span></div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24"><div class="grid-content bg-purple-dark self-style"><span>副卡信息</span></div></el-col>
      </el-row>
      <el-row>
        <div v-for="(side, group_index) in temp.side_numbers" :key="side.side_number">
          <el-col :span="12" ><div class="grid-content bg-purple self-style">
          {{ $t('info.side_number_num') }}:<span>{{side.side_number}}</span></div>
          </el-col>
          <el-col :span="12" ><div class="grid-content bg-purple self-style">
          {{ $t('info.side_uim_number') }}:<span>{{side.side_uim_number}}</span></div>
          </el-col>
        </div>
      </el-row>
      <el-row>
        <el-col :span="24"><div class="grid-content bg-purple-dark self-style"><span>套餐信息</span></div></el-col>
      </el-row>
      <el-row>  
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('package.name') }}:<span>{{temp.has_one_package.name}}</span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('package.month_nums') }}:<span>{{temp.has_one_package.month_nums}}</span></div>
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('package.package_price') }}:<span>{{temp.has_one_package.package_price}}</span></div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24"><div class="grid-content bg-purple-dark self-style"><span>返还信息</span></div></el-col>
      </el-row>
      <el-row>
        <div v-for="(info, info_index) in temp.has_many_info_dianxin" :key="info_index">
          <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('infoDianXin.refunds') }}:<span>{{info.refunds}}</span>
          </div></el-col>
          <el-col :span="8"><div class="grid-content bg-purple self-style">
            {{ $t('infoDianXin.balance_month') }}:<span>{{info.balance_month}}</span></div>
          </el-col>
          <el-col :span="8"><div class="grid-content bg-purple self-style">
            {{ $t('infoDianXin.netin') }}:<span>{{info.netin}}</span></div>
          </el-col>
        </div>      
      </el-row>
    </el-dialog>
</div>
</template>
<script>
  import { getInfo } from '@/api/infoSelf'
  import { parseTime } from '@/utils'
  import  { infoSelfStatus, jituanStatus, oldBindStatus, collections_type }  from '@/config.js'
  export default {
    name: 'ShowInfo',
    data() {
      return {
        temp:{
          has_one_package:{name:null,month_nums:null,package_price:null}
        },
        dialogInfoVisible: false,
        statusMap: infoSelfStatus,
        jiTuanStatusMap: jituanStatus,
        oldBindStatusMap: oldBindStatus,
        collections_type: collections_type,
      };
    },
    created() {   
      Promise.all([

      ])
    },
    methods: {
      handleShowInfo(row) {
        // console.log(row.has_many_package_info)
        getInfo(row).then(() => {
          let side_arr     = row.side_number.split("|")
          let side_uim_arr = row.side_uim_number.split("|")
          let side_info    = []

          //side_arr     = row.side_number.split("|")
          //side_uim_arr = row.side_uim_number.split("|")

          //console.log(side_arr)
          //console.log(side_uim_arr)

          Array.prototype.forEach.call(side_arr, (side, index) => {
            /*console.log(index)
            console.log(side)*/
            side_info.push({side_number: side, side_uim_number: side_uim_arr[index]})
          })
          row.side_numbers = side_info
          this.temp = Object.assign({}, row) // copy obj
          // console.log(this.temp)
          this.dialogInfoVisible = true       
        })   
      },
    }
  };
</script>
<style lang="scss" scoped>
  .el-dialog__body {
    padding: 15px 15px;
  }
  .el-dialog__header {
     padding-top: 10px; 
  }
  .el-row {
    margin-bottom: 5px;
    &:last-child {
      margin-bottom: 0;
    }
  }
  .el-col {
    border-radius: 4px;
  }
  .bg-purple-dark {
    background: #99a9bf;
  }
  .bg-purple {
    background: #d3dce6;
  }
  .bg-purple-light {
    background: #e5e9f2;
  }
  .grid-content {
    border-radius: 4px;
    min-height: 36px;
  }
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }
  .self-style{
    text-align: -webkit-center;
    font-size: 20px;
    padding: 10px 0px;
  }
</style>