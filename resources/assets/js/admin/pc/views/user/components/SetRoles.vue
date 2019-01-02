<template>
<div>
  <el-dialog :title="setRoles" :visible.sync="roleDialogFormVisible">
      <el-form ref="roleDForm" :model="roleTemp" label-position="left" label-width="100px">
        <el-checkbox-group v-model="checkList">
          <el-checkbox border size="medium" v-for="role in roles" :label="role.name" :key="role.id">
            {{role.description}}
          </el-checkbox>
        </el-checkbox-group>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="roleDialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button type="primary" @click="giveRoles">{{ $t('table.confirm') }}</el-button>
      </div>
  </el-dialog> 
</div>
</template>
<script>
  import { getUserRoles, giveUserRoles } from '@/api/user'
  import { fetchList } from '@/api/role'
  // const cityOptions = ['上海', '北京', '广州', '深圳'];
  export default {
    data() {
      return {
        roleTemp: {
          id: null,
          roles:[],
        },
        RoleListQuery: {
          page: 1
        },
        setRoles:'角色设置',
        // dialogFormVisible: false,
        roleDialogFormVisible: false,
        checkList: [],
        roles: []
      };
    },
    created() {    
      Promise.all([
        this.getRolesList()
      ])
    },
    methods: {
      getRolesList() {
        fetchList(this.RoleListQuery).then(response => {
          this.roles = response.data.data
        })
      },
      handleRoles(row) { 
        /*row参数为点击的用户对象*/
        getUserRoles(row).then((response) => {
          console.log(response.data)
          this.checkList = response.data.data
          this.roleTemp.id = row.id
          this.roleDialogFormVisible = true
        })

        // this.roleDialogFormVisible = true
        /*this.$nextTick(() => {
          this.$refs['roleDForm'].clearValidate()
        })*/
      },
      giveRoles() {
        
        this.roleTemp.roles = this.checkList
        // console.log(this.checkList)
        // console.log(this.roleTemp)
        // return false
        giveUserRoles(this.roleTemp).then((response) => {
          this.roleDialogFormVisible = false
          this.$notify({
            title: '成功',
            message: '角色设置成功',
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