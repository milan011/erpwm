<template>
<div>
  <el-dialog :title="rolePermission" :visible.sync="permissionDialogFormVisible">
      <el-form ref="permissionDForm" :model="permissionTemp" label-position="left" label-width="100px">
        <el-checkbox-group v-for="(per, group_index) in permissions" :key="group_index" v-model="checkList">
          <div style="margin-bottom:12px">
            <span style="font-size:16px;margin-right:5px">{{group_index}}:</span>
            <el-checkbox border size="medium" v-for="p in per" :label="p.name" :key="p.id">
              {{p.description}}
            </el-checkbox>
          </div>
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
  // const cityOptions = ['上海', '北京', '广州', '深圳'];
  export default {
    data() {
      return {
        permissionTemp: {
          id: null,
          permissions:[],
        },
        rolePermission:'权限编辑',
        // dialogFormVisible: false,
        permissionDialogFormVisible: false,
        checkList: ['user_list', 'role_list'],
        permissions: []
      };
    },
    created() {    
      Promise.all([
        this.getPermissionList()
      ])
    },
    methods: {
      getPermissionList() {
        getPermissions().then(response => {
          this.permissions = response.data.data
        })
      },
      handlePermission(row) { 
        /*row参数为点击的角色信息对象*/
        getRolePermissions(row).then((response) => {
          this.checkList = response.data
          this.permissionTemp.id = row.id
          this.permissionDialogFormVisible = true
        })

        // this.permissionDialogFormVisible = true
        /*this.$nextTick(() => {
          this.$refs['permissionDForm'].clearValidate()
        })*/
      },
      givePermissions() {
        
        this.permissionTemp.permissions = this.checkList
        // console.log(this.checkList)
        // console.log(this.permissionTemp)
        // return false
        giveRolePermissions(this.permissionTemp).then((response) => {
          this.permissionDialogFormVisible = false
          this.$notify({
            title: '成功',
            message: '创建成功',
            type: 'success',
            duration: 2000
          })
        })
      },
    }
  };
</script>
<style lang="scss" scoped>
  
</style>