<template>
<div>
  <el-dialog  :visible.sync="dialogInfoVisible">
      <el-row>
        <el-col :span="24">
          <div class="grid-content bg-purple-dark self-style">
            <span>{{ $t('infoDianxin.name') }}:{{ temp.name }}</span>
          </div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('infoDianxin.return_telephone') }}:<span>{{temp.return_telephone}}</span></div>
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('infoDianxin.jiakuan') }}:<span>{{temp.jiakuan}}</span>
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
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('infoDianxin.jituan') }}:<span>{{temp.jituan}}</span></div>
        </el-col> 
        <el-col :span="8"><div class="grid-content bg-purple-light self-style">
          {{ $t('infoDianxin.manager') }}:<span>{{temp.manager}}</span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple-light self-style">
          {{ $t('infoDianxin.netin') }}:<span>{{ temp.netin}}</span>
        </div></el-col>
      </el-row>
      <el-row>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('infoDianxin.yongjin') }}:<span>{{temp.yongjin}}</span></div>
        </el-col> 
        <el-col :span="8"><div class="grid-content bg-purple-light self-style">
          {{ $t('infoDianxin.refunds') }}:<span>{{ temp.refunds}}</span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('infoDianxin.balance_month') }}:<span>{{temp.balance_month }}</span>
        </div></el-col> 
      </el-row>
    </el-dialog>
</div>
</template>
<script>
  import { parseTime } from '@/utils'
  export default {
    name: 'ShowInfo',
    data() {
      return {
        temp:{},
        dialogInfoVisible: false,
      };
    },
    created() {   
      Promise.all([

      ])
    },
    methods: {
      handleShowInfo(row) {
        // console.log(row)
        // return false
        this.temp = Object.assign({}, row) // copy obj
        this.dialogInfoVisible = true    
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