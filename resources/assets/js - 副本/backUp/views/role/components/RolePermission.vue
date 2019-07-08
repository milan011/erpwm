<template>
<div>
  <el-dialog :title="rolePermission" :visible.sync="permissionDialogFormVisible">
      <el-form ref="permissionDForm" :model="permissionTemp" label-position="left" label-width="100px">
        <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
          <div style="margin: 15px 0;"></div>
          <el-checkbox-group v-model="checkedCities" @change="handleCheckedCitiesChange">
            <el-checkbox v-for="city in cities" :label="city" :key="city">{{city}}</el-checkbox>
          </el-checkbox-group>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="permissionDialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button type="primary" @click="givePermissions">{{ $t('table.confirm') }}</el-button>
      </div>
  </el-dialog> 
</div>
</template>
<script>
  import { getRolePermissions, getPermissions, giveRolePermissions } from '@/api/role'
  const cityOptions = ['上海', '北京', '广州', '深圳'];
  export default {
    data() {
      return {
        permissionTemp: {},
        rolePermission:'权限编辑',
        // dialogFormVisible: false,
        permissionDialogFormVisible: false,
        checkAll: false,
        checkedCities: ['上海', '北京'],
        cities: cityOptions,
        isIndeterminate: true
      };
    },
    created() {    
      Promise.all([
        this.cities = ['上海', '北京', '广州', '深圳'],
        this.getPermissionList()
      ])
    },
    methods: {
      handleSetPermission() {    
        this.permissionDialogFormVisible = true
        this.$nextTick(() => {
          this.$refs['permissionDForm'].clearValidate()
        })
      },
      handleCheckAllChange(val) {
        this.checkedCities = val ? cityOptions : [];
        this.isIndeterminate = false;
      },
      handleCheckedCitiesChange(value) {
        let checkedCount = value.length;
        this.checkAll = checkedCount === this.cities.length;
        this.isIndeterminate = checkedCount > 0 && checkedCount < this.cities.length;
      },
      getPermissionList() {
        getPermissions().then(response => {
          this.permissionList = response.data.data
        })
      },
      handlePermission(row) { 
        /*row参数为点击的角色信息对象*/
        this.permissionDialogFormVisible = true
        this.$nextTick(() => {
          this.$refs['permissionDForm'].clearValidate()
        })
      },
      givePermissions() {
        this.$refs['permissionDForm'].validate(() => {
          console.log(this.checkedCities)
          return false
          giveRolePermissions(this.permissionTemp).then((response) => {
            this.permissionDialogFormVisible = false
            this.$notify({
              title: '成功',
              message: '创建成功',
              type: 'success',
              duration: 2000
            })
          })
        })
      },
    }
  };
</script>
