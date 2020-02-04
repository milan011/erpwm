<template>
  <div class="app-container">
    <div class="filter-container">
      <!-- <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button> -->
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">{{ $t('table.add') }}</el-button>
    </div>
    <el-table
      v-loading="listLoading"
      :key="tableKey"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;">
      <el-table-column :label="$t('table.id')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('user.nick_name')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.nick_name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('user.telephone')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.telephone }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('user.userShop')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_shop.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.date')" width="150px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at | parseTime('{y}-{m}-{d}') }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('table.title')" min-width="150px">
        <template slot-scope="scope">
          <span class="link-type" @click="handleUpdate(scope.row)">{{ scope.row.title }}</span>
          <el-tag>{{ scope.row.type | typeFilter }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.author')" width="110px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.author }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="showReviewer" :label="$t('table.reviewer')" width="110px" align="center">
        <template slot-scope="scope">
          <span style="color:red;">{{ scope.row.reviewer }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.importance')" width="80px">
        <template slot-scope="scope">
          <svg-icon v-for="n in +scope.row.importance" :key="n" icon-class="star" class="meta-item__icon"/>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.readings')" align="center" width="95">
        <template slot-scope="scope">
          <span v-if="scope.row.pageviews" class="link-type" @click="handleFetchPv(scope.row.pageviews)">{{ scope.row.pageviews }}</span>
          <span v-else>0</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.status')" class-name="status-col" width="100">
        <template slot-scope="scope">
          <el-tag :type="scope.row.status | statusFilter">{{ scope.row.status }}</el-tag>
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('table.actions')" align="center" width="400" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button type="success" size="mini" @click="handleSetRoles(scope.row)">{{ $t('table.setRoles') }}</el-button>
          <el-button type="warning" size="mini" @click="handlePassReset(scope.row)">{{ $t('table.passReset') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next"  @current-change="handleCurrentChange"/>
    </div>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="100px" style="width: 400px; margin-left:50px;">
        <!-- <el-form-item :label="$t('table.type')" prop="type">
          <el-select v-model="temp.type" class="filter-item" placeholder="Please select">
            <el-option v-for="item in calendarTypeOptions" :key="item.key" :label="item.display_name" :value="item.key"/>
          </el-select>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('table.date')" prop="timestamp">
          <el-date-picker v-model="temp.timestamp" type="datetime" placeholder="Please pick a date"/>
        </el-form-item> -->
        <el-form-item :label="$t('user.name')" prop="name">
          <el-input :disabled="userNameDisabled" v-model="temp.name"/>
        </el-form-item>
        <el-form-item :label="$t('user.nick_name')" prop="nick_name">
          <el-input v-model="temp.nick_name"/>
        </el-form-item>
        <el-form-item v-show="passwordVisible" :label="$t('user.password')" prop="password">
          <el-input :type="passwordType" v-model="temp.password"/>
        </el-form-item>
        <el-form-item v-show="passwordVisible" :label="$t('user.passwordRepeat')" prop="password_confirmation">
          <el-input :type="passwordType" v-model="temp.password_confirmation"/>
        </el-form-item>
        <el-form-item :label="$t('user.telephone')" prop="telephone">
          <el-input v-model="temp.telephone"/>
        </el-form-item>
        <el-form-item :label="$t('user.userShop')" prop="shop_id">
          <el-select v-model="temp.shop_id" class="filter-item" placeholder="请选择门店">
            <el-option v-for="shop in shopList" :key="shop.id" :label="shop.name" :value="shop.id"/>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <set-roles ref="roleChild"></set-roles>

  </div>
</template>

<script>
// import { fetchList, fetchPv, createArticle, updateArticle } from '@/api/article'
import { fetchList, fetchPv, createUser, updateUser, deleteUser, passReset } from '@/api/user'
import { shopAll } from '@/api/shop'
import waves from '@/directive/waves' // 水波纹指令
import { parseTime } from '@/utils'
import { isTelephone } from '@/utils/validate'
import  SetRoles  from './components/SetRoles'

const calendarTypeOptions = [
  { key: 'CN', display_name: 'China' },
  { key: 'US', display_name: 'USA' },
  { key: 'JP', display_name: 'Japan' },
  { key: 'EU', display_name: 'Eurozone' }
]

// arr to obj ,such as { CN : "China", US : "USA" }
const calendarTypeKeyValue = calendarTypeOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'userList',
  components: { SetRoles },
  directives: {
    waves
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger'
      }
      return statusMap[status]
    },
    typeFilter(type) {
      return calendarTypeKeyValue[type]
    }
  },
  data() {  
    const validateRepeatPass = (rule, value, callback) => { /*密码确认校验*/
      // console.log(value)
      if (value !== this.temp.password) {
          callback(new Error('两次输入密码不一致!'));
      } else {
          callback();
      }
    }
    const validateTelephone = (rule, value, callback) => {
      if (!isTelephone(value)) {
        callback(new Error('请输入正确格式手机号'))
      } else {
        callback()
      }
    }
    return {
      tableKey: 0,
      list: null,
      shopList: null,
      total: null,
      listLoading: true,
      listQuery: {
        page: 1
      },
      calendarTypeOptions,
      statusOptions: ['published', 'draft', 'deleted'],
      showReviewer: false,
      temp: {
        id: undefined,
        name: null,
        nick_name: null,
        remark: '',
        shop_id: null,
        password: '',
        password_confirmation: '',
        email: 'taochele@sina.com',
        telephone: ''
      },
      password: 'password',
      password_confirmation: 'password_confirmation',
      passwordType: 'password',
      dialogFormVisible: false,
      passwordVisible: true,
      dialogStatus: '',
      userNameDisabled: 'false',
      textMap: {
        update: '用户编辑',
        create: '用户创建'
      },
      dialogPvVisible: false,
      pvData: [],
      rules: {
        name: [{ required: true, message: '请输入用户名', trigger: 'change' }],
        shop_id: [{ required: true, message: '请选择所属门店', trigger: 'blur' }],
        nick_name: [{ required: true, message: '请输入昵称', trigger: 'change' }],
        password: [{ required: true, message: '请输入密码', trigger: 'change' },
          { min: 6, max: 16, message: '密码长度必须是6-16位', trigger: 'change' }
        ],
        password_confirmation: [
          { required: true, message: '请确认密码', trigger: 'change' },
          { min: 6, max: 16, message: '密码长度必须是6-16位', trigger: 'change' },
          { validator: validateRepeatPass, trigger: 'change' }
        ],
        email: [{ type: 'email', required: true, message: '请输入正确格式的邮箱', trigger: 'change' }],
        telephone: [
          { required: true, message: '请输入有效手机号', trigger: 'blur' }, 
          { validator: validateTelephone, trigger: 'change' }     
        ]
      },
      downloadLoading: false
    }
  },
  
  created() {
    this.getList()
    this.getAllShop()
  },
  methods: {
    getList() {
      this.listLoading = true
      fetchList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllShop() {
      shopAll().then(response => {
        this.shopList = response.data.data
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    handleSizeChange(val) {
      this.listQuery.limit = val
      this.getList()
    },
    handleCurrentChange(val) {
      this.listQuery.page = val
      this.getList()
    },
    handleModifyStatus(row, status) {
      this.$confirm('确定要删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.temp = Object.assign({}, row)
        deleteUser(this.temp).then((response) => {
          // console.log(response.data);
          if(response.data.status === 0){
            this.$notify({
              title: '失败',
              message: '删除失败',
              type: 'warning',
              duration: 2000
            })
          }else{
            const index = this.list.indexOf(row)
            this.list.splice(index, 1)
            this.dialogFormVisible = false
            this.$notify({
              title: '成功',
              message: '删除成功',
              type: 'success',
              duration: 2000
            })
          }   
        })
      }).catch((err) => {
        console.log(err)
        /*switch (error.response.status) {
          case 422:
            
          break
        }*/
        this.$message({
          type: 'info',
          message: '已取消删除'
        });          
      });
    },
    resetTemp() {
      /*this.temp = {
        id: undefined,
        name: 'wxm',
        nick_name: 'wxm',
        remark: '闺女',
        shop_id: null,
        password: '111111',
        password_confirmation : '111111',
        email: 'taochele@sina.com',
        telephone: '13731080174'
      }*/
      this.temp = {
        id: undefined,
        name: null,
        nick_name: null,
        shop_id: null,
        remark: '',
        password: '',
        password_confirmation : '',
        email: 'taochele@sina.com',
        telephone: ''
      }
    },
    handleCreate() {
      this.resetTemp()
      this.dialogStatus = 'create'
      this.userNameDisabled = false
      this.passwordVisible = true
      this.dialogFormVisible = true
      this.password = 'password'
      this.password_confirmation = 'password_confirmation'
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          createUser(this.temp).then((response) => {
            /*console.log(response.data)
            return false*/
            let newUser = {
              id: response.data.data.id,
              name: response.data.data.name,
              nick_name: response.data.data.nick_name,
              telephone: response.data.data.telephone,
              created_at: new Date()
            }
            this.list.unshift(newUser)
            this.dialogFormVisible = false
            this.$notify({
              title: '成功',
              message: '创建成功',
              type: 'success',
              duration: 2000
            })
          }).catch((error) => {
            switch (error.response.status) {
              case 422:
                let errMessage = error.response.data.errors
                let messageShow = '<ul style="list-style-type:none;">'
                for (const prop in errMessage) {
                  //console.log(prop)
                  // console.log(`errMessage.${prop} = ${errMessage[prop]}`)
                  //console.log(errMessage[prop])
                  messageShow += '<li style="margin-bottom:5px;">'
                  messageShow += `${errMessage[prop]}`
                  messageShow += '</li>'
                }
                messageShow += '</ul>'

                this.$message({
                  showClose: true,
                  message: messageShow,
                  type: 'error',
                  dangerouslyUseHTMLString: true,
                  duration: 0
                });
                  /*this.$notify.erro({
                    title: '请注意',
                    message: '创建失败',
                    type: 'waring',
                    duration: 2000
                   })*/
                  break
              // 如果响应中的 http code 为 400，那么就弹出一条错误提示给用户
              /*case 400:
                  return this.$Message.error('数据过期,请重新登录')
                  break*/
            }
            return Promise.reject(error)
          })
          /*.catch(function (error) {
            if (error.response) {
              // The request was made and the server responded with a status code
              // that falls out of the range of 2xx
              console.log(error.response.data);
              console.log(error.response.status);
              console.log(error.response.headers);
            } else if (error.request) {
              // The request was made but no response was received
              // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
              // http.ClientRequest in node.js
              console.log(error.request);
            } else {
              // Something happened in setting up the request that triggered an Error
              console.log('Error', error.message);
            }
            console.log(error.config);
          })*/
        }
      })
    },
    handlePassReset(row) {
      this.$confirm('确定要重置?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        // console.log(row.id)
        const userId = { id : row.id}
        passReset(userId).then((response) => {
          // console.log(response.data);
          if(response.data.status === 0){
            this.$notify({
              title: '失败',
              message: '重置失败',
              type: 'warning',
              duration: 2000
            })
          }else{
            this.$notify({
              title: '成功',
              message: '重置成功',
              type: 'success',
              duration: 2000
            })
          }   
        })
      }).catch((err) => {
        console.log(err)
        /*switch (error.response.status) {
          case 422:
            
          break
        }*/
        this.$message({
          type: 'info',
          message: '已取消删除'
        });          
      });
    },
    handleUpdate(row) {
      // console.log(row)
      this.temp = Object.assign({}, row) // copy obj
      this.temp.timestamp = new Date(this.temp.timestamp)
      this.dialogStatus = 'update'
      this.userNameDisabled = true
      this.temp.password = '111111'
      this.temp.password_confirmation = '111111'
      this.passwordVisible = false
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          updateUser(tempData).then(() => {
            for (const v of this.list) {
              if (v.id === this.temp.id) {
                const index = this.list.indexOf(v)
                this.list.splice(index, 1, this.temp)
                break
              }
            }
            this.dialogFormVisible = false
            this.$notify({
              title: '成功',
              message: '更新成功',
              type: 'success',
              duration: 2000
            })
          })
        }
      })
    },
    handleSetRoles(row) {  
      this.$refs.roleChild.handleRoles(row) 
    },
    giveUserRoles() {
      this.$refs.roleChild.giveRoles()
    },
  }
}
</script>
<style type="sass" scop>
  /* .fixed-width .el-button--mini {
    padding: 10px 3px;
    width: 70px;
    margin-left: 0px;
  } */

  .el-form-item__error{
    top:80%;
  }
</style>