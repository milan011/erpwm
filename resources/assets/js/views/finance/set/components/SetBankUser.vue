<template>
<div>
  <el-dialog :title="setUsers" :visible.sync="userDialogFormVisible">
      <el-form ref="roleDForm" :model="userTemp" label-position="left" label-width="100px">
        <el-checkbox-group v-model="checkList">
          <el-checkbox border size="medium" v-for="user in users" :label="user.id" :key="user.id">
            {{user.userid}}
          </el-checkbox>
        </el-checkbox-group>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="userDialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button type="primary" @click="giveUsers">{{ $t('table.confirm') }}</el-button>
      </div>
  </el-dialog> 
</div>
</template>
<script>
  import { getUserRoles, giveUserRoles, userAll } from '@/api/user'
  import { getBankUser,  setBankUser} from '@/api/bankAccount'
  import { fetchList } from '@/api/role'
  // const cityOptions = ['上海', '北京', '广州', '深圳'];
  export default {
    data() {
      return {
        userTemp: {
          id: null,
          users:[],
        },
        RoleListQuery: {
          page: 1
        },
        setUsers:'银行账户授权',
        // dialogFormVisible: false,
        userDialogFormVisible: false,
        checkList: [],
        users: []
      };
    },
    created() {    
      Promise.all([
        this.getUsersList()
      ])
    },
    methods: {
      getUsersList() {
        userAll().then(response => {
          console.log(response.data)
          this.users = response.data
        })
      },
      handleUsers(row) { 
        /*row参数为点击的用户对象*/
        console.log(2)
        console.log(row)
        getBankUser(row.id).then((response) => {
          console.log(response.data)
          this.checkList = response.data
          this.userTemp.id = row.id
          this.userDialogFormVisible = true
        })

        // this.userDialogFormVisible = true
        /*this.$nextTick(() => {
          this.$refs['roleDForm'].clearValidate()
        })*/
      },
      giveUsers() {
        
        // this.roleTemp.roles = this.checkList
        // console.log(this.checkList)
        // console.log(this.roleTemp)
        // return false
        /*giveUserRoles(this.roleTemp).then((response) => {
          this.userDialogFormVisible = false
          this.$notify({
            title: '成功',
            message: '角色设置成功',
            type: 'success',
            duration: 2000
          })
        })*/
      },
    }
  };
</script>
<style lang="scss" scoped>
  
</style>