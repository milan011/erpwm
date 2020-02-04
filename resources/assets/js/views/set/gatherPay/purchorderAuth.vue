<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('purchorderAuth.id')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('purchorderAuth.userid')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_user.realname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('purchorderAuth.currabrev')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.currabrev }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('purchorderAuth.cancreate')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.cancreate | statusFilter">
              {{ cancreateStatus[scope.row.cancreate] }}
            </el-tag>
          </span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('purchorderAuth.offhold')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.offhold | statusFilter">
              {{ offholdStatus[scope.row.offhold] }}
            </el-tag>
          </span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('purchorderAuth.authlevel')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.authlevel }}</span>
        </template>
      </el-table-column>    
      <el-table-column :label="$t('table.actions')" width="180%" align="center" show-overflow-tooltip class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next" @current-change="handleCurrentChange" />
    </div>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="100px" style="width: 400px;margin:0px auto;">
        <el-form-item :label="$t('purchorderAuth.userid')" prop="userid">
          <el-select 
            v-model="temp.userid" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入用户搜索">
            <el-option v-for="user in userList" :key="user.id" :label="user.realname" :value="user.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('purchorderAuth.currabrev')" prop="currabrev">
          <el-select 
            v-model="temp.currabrev" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入币种搜索">
            <el-option v-for="currencies in currenciesList" :key="currencies.id" :label="currencies.currabrev" :value="currencies.currabrev"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('purchorderAuth.cancreate')">
          <el-switch
                  v-model="temp.cancreate"
                  active-color="#13ce66"
                  inactive-color="#ff4949"
                  active-value="1"
                  inactive-value="0">
                </el-switch>
        </el-form-item>
        <el-form-item :label="$t('purchorderAuth.offhold')" prop="offhold">
          <el-switch
                  v-model="temp.offhold"
                  active-color="#13ce66"
                  inactive-color="#ff4949"
                  active-value="1"
                  inactive-value="0">
                </el-switch>
        </el-form-item>
        <el-form-item :label="$t('purchorderAuth.authlevel')" prop="authlevel">
          <el-input v-model.number="temp.authlevel" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
  import { getPurchorderAuthList, createPurchorderAuth, updatePurchorderAuth, deletePurchorderAuth,} from '@/api/purchorderAuth'
  import { userAll} from '@/api/user'
  import { currenciesAll} from '@/api/currencies'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isEmpty } from '@/common.js'
  // import SwitchRoles from './components/RolePermission'

const calendarTypeOptions = [
  { key: 'web', display_name: 'web' },
  { key: 'api', display_name: 'api' },
]

// arr to obj ,such as { web : "web", api : "api" }
const calendarTypeKeyValue = calendarTypeOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'purchorderAuth',
  // components: { SwitchRoles },
  directives: {
    waves
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'danger',
      }
      return statusMap[status]
    },
    typeFilter(type) {
      return calendarTypeKeyValue[type]
    }
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      total: null,
      listLoading: true,
      listQuery: {
        page: 1,
      },
      cancreateStatus:['否', '是'],
      offholdStatus:['否', '是'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        userid: '',
        currabrev: '',
        offhold: '',
        cancreate: '',
        authlevel: '',
      },
      dialogFormVisible: false,
      setGroupTaxVisible: false,
      setGroupTax: '',
      dialogStatus: '',
      textMap: {
        update: '编辑授权',
        create: '新增授权'
      },
      pvData: [],
      rules: {
        userid: [        
          { required: true, message: '请选择用户', trigger: 'blur' },
        ],
        currabrev: [{ required: true, message: '请选择币种', trigger: 'blur' }],
        authlevel: [        
          {type: 'number', message: '请输入的数字', trigger: 'blur'},
          { required: true, message: '请输入授权水平', trigger: 'blur' },
        ],
      },
      userList: [],
      currenciesList: [],
    }
  },
  created() {
    // this.getList()
    Promise.all([
      this.getList(),
      this.getAllUserList(),
      this.getAllCurrenciesList(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getPurchorderAuthList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllUserList(){
      userAll().then(response => {
        this.userList = response.data
      })
    },
    getAllCurrenciesList(){
      currenciesAll().then(response => {
        this.currenciesList = response.data
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
        deletePurchorderAuth(this.temp).then((response) => {
          // console.log(response.data);
          if(!response.data.status){
            this.$notify({
              title: '失败',
              message: response.data.message,
              type: 'warning',
              duration: 2000
            })
          }else{
            const index = this.list.indexOf(row)
            this.list.splice(index, 1)
            this.total = this.total -1
            this.dialogFormVisible = false
            this.$notify({
              title: '成功',
              message: '删除成功',
              type: 'success',
              duration: 2000
            })
          }   
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        });          
      });
    },
    resetTemp() {
      this.temp = {
        id: undefined,
        userid: undefined,
        currabrev: '',
        offhold: '1',
        cancreate: '1',
        authlevel: 0,
      }
    },
    handleCreate() {
      this.resetTemp()
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          createPurchorderAuth(this.temp).then((response) => {
            console.log(response.data);
            const response_data = response.data
            if(response_data.status){
              this.list.unshift(response_data.data)
              this.total = this.total + 1
              this.dialogFormVisible = false
              this.$notify({
                title: '成功',
                message: response_data.message,
                type: 'success',
                duration: 2000
              })
            }else{
              // this.dialogFormVisible = false
              this.$notify.error({
                title: '失败',
                message: response_data.message,
                duration: 2000
              })
            }
            
          })
        }
      })
    },
    handleUpdate(row) {
      row.userid = parseInt(row.userid)
      this.temp = Object.assign({}, row) // copy obj
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {       
          const tempData = Object.assign({}, this.temp)
          updatePurchorderAuth(tempData).then((response) => {
            console.log(response.data)
            const response_data = response.data
            if(response_data.status){
              for (const v of this.list) {
                if (v.id === this.temp.id) {
                  const index = this.list.indexOf(v)
                  this.list.splice(index, 1, response_data.data)
                  break
                }
              }
              this.dialogFormVisible = false
              this.$notify({
                title: '成功',
                message: response_data.message,
                type: 'success',
                duration: 2000
              })
            }else{
              // this.dialogFormVisible = false
              this.$notify.error({
                title: '失败',
                message: response_data.message,
                duration: 2000
              })
            }
          })
        }
      })
    },
    handleDelete(row) {
      this.$notify({
        title: '成功',
        message: '删除成功',
        type: 'success',
        duration: 2000
      })
      const index = this.list.indexOf(row)
      this.list.splice(index, 1)
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
</style>