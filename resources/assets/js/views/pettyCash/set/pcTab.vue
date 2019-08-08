<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('pcTab.id')" width="60%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcTab.tabcode')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.tabcode }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcTab.usercode')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_user_with_uscode.realname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcTab.typetabcode')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_pc_type_tab.typetabdescription }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcTab.tablimit')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.tablimit }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcTab.assigner')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_user_with_assign.realname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcTab.authorizer')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_user_with_authorizer.realname }}</span>
        </template>
      </el-table-column>  
      <el-table-column :label="$t('pcTab.glaccountassignment')" width="150%" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_assignment.accountname }}</span>
        </template>
      </el-table-column> 
      <el-table-column :label="$t('pcTab.glaccountpcash')" width="120%" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_cash.accountname }}</span>
        </template>
      </el-table-column> 
      <el-table-column :label="$t('table.actions')" align="center" width="230%" class-name="small-padding fixed-width">
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
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px" style="width: 400px;margin:0px auto;">
        <el-form-item :label="$t('pcTab.tabcode')" prop="tabcode">
          <el-input v-model="temp.tabcode" />
        </el-form-item>
        <el-form-item :label="$t('pcTab.usercode')" prop="usercode">
          <el-select 
            v-model="temp.usercode" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入用户搜索">
            <el-option v-for="user in userList" :key="user.id" :label="user.realname" :value="user.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('pcTab.typetabcode')" prop="typetabcode">
          <el-select 
            v-model="temp.typetabcode" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入标签种类搜索">
            <el-option v-for="type in pcTypeTabList" :key="type.id" :label="type.typetabdescription" :value="type.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('pcTab.tablimit')" prop="tablimit">
          <el-input-number 
            v-model='temp.tablimit'   
            :min="0" 
            :precision="2"
            label="限额">
          </el-input-number>
        </el-form-item> 
        <el-form-item :label="$t('pcTab.assigner')" prop="assigner">
          <el-select 
            v-model="temp.assigner" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入用户搜索">
            <el-option v-for="user in userList" :key="user.id" :label="user.realname" :value="user.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('pcTab.authorizer')" prop="authorizer">
          <el-select 
            v-model="temp.authorizer" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入用户搜索">
            <el-option v-for="user in userList" :key="user.id" :label="user.realname" :value="user.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('pcTab.glaccountassignment')" prop="glaccountassignment">
          <el-select 
            v-model="temp.glaccountassignment" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_bank_account.accountcode != ''" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>  
        <el-form-item :label="$t('pcTab.glaccountpcash')" prop="glaccountpcash">
          <el-select 
            v-model="temp.glaccountpcash" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
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
  import { getPcTabList,  createPcTab, updatePcTab, deletePcTab} from '@/api/pcTab'
  import { chartMasterAll } from '@/api/chartMaster'
  import { pcTypeTabAll } from '@/api/pcTypeTab'
  import { userAll } from '@/api/user'
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
  name: 'pcTab',
  // components: { SwitchRoles },
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
    return {
      tableKey: 0,
      list: null,
      total: null,
      listLoading: true,
      listQuery: {
        page: 1,
      },
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        usercode: null,
        tabcode: '',
        typetabcode : null,
        tablimit : 0,
        assigner : null,
        authorizer : null,
        glaccountassignment : null,
        glaccountpcash : null,
      },
      dialogFormVisible: false,
      setRateVisible: false,
      pcTabName: '',
      dialogStatus: '',
      textMap: {
        update: '编辑现金标签',
        create: '新增现金标签'
      },
      pvData: [],
      rules: {
        tabcode: [
          { required: true, message: '请输入名称', trigger: 'change' },
          { min: 1, max: 10, message: '长度在1到10个字符', trigger: 'blur'},
        ],
        usercode: [{ required: true, message: '请选择用户', trigger: 'change' }],
        tablimit: [{ required: true, message: '请输入限额', trigger: 'change' }],
        assigner: [{ required: true, message: '请选择用户', trigger: 'change' }],
        authorizer: [{ required: true, message: '请选择用户', trigger: 'change' }],
        typetabcode: [{ required: true, message: '请选择标签种类', trigger: 'change' }],
        glaccountassignment: [{ required: true, message: '请选择会计科目', trigger: 'change' }],
        glaccountpcash: [{ required: true, message: '请选择会计科目', trigger: 'change' }],
      },
      pcTabList: [],
      chartMasterList: [],
      userList: [],
      pcTypeTabList: [],
    }
  },
  created() {
    // this.getList()
    Promise.all([
      this.getList(),
      this.getAllChartMasters(),
      this.getAllUser(),
      this.getAllPcTypeTab(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getPcTabList(this.listQuery).then(response => {
        // console.log(response.data)
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllChartMasters(){
      chartMasterAll().then(response => {
        this.chartMasterList = response.data
      })
    },
    getAllUser(){
      userAll().then(response => {
        this.userList = response.data
      })
    },
    getAllPcTypeTab(){
      pcTypeTabAll().then(response => {
        this.pcTypeTabList = response.data
      })
    },
    handleSetRate(row){
      getTaxRates(row).then(response => {
        console.log(response.data)
        // return false
        this.pcTabList = response.data
        this.pcTabName = row.area
        setTimeout(() => {
          this.setRateVisible = true
        }, 0.5 * 1000)
      })      
    },
    setTaxRateDel(){
      console.log(this.pcTabList)
      // return false
      setTaxRates(this.pcTabList).then(response => {
        if(!response.data.status){
          this.$notify({
            title: '失败',
            message: response.data.message,
            type: 'warning',
            duration: 2000
          })
        }else{
          this.$notify({
            title: '成功',
            message: response.data.message,
            type: 'success',
            duration: 2000
          })
        }
        this.setRateVisible = false
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
        deletePcTab(this.temp).then((response) => {
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
        usercode: null,
        tabcode: '',
        typetabcode : null,
        tablimit : 0,
        assigner : null,
        authorizer : null,
        glaccountassignment : null,
        glaccountpcash : null,
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
          createPcTab(this.temp).then((response) => {
            const response_data = response.data
            if(response_data.status){
              this.temp.id = response_data.data.id
              this.list.unshift(response_data.data)
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
      row.usercode = parseInt(row.usercode)
      row.typetabcode = parseInt(row.typetabcode)
      row.assigner = parseInt(row.assigner)
      row.authorizer = parseInt(row.authorizer)
      row.glaccountassignment = parseInt(row.glaccountassignment)
      row.glaccountpcash = parseInt(row.glaccountpcash)
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
          updatePcTab(tempData).then((response) => {
            console.log(response.data)
            // return false
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
  }
}
</script>
<style lang="scss" scoped>
  .el-dialog__body {
    padding: 15px 15px;
  }
  .el-dialog__header {
     padding-top: 10px; 
  }
  .el-form-item{
    margin-bottom: 15px;
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
  }white
  .bg-purple {
    background: #d3dce6;
  }
  .bg-purple-light {
    background: #e5e9f2;
  }
  .grid-content {
    border-radius: 4px;
    min-height: 16px;
  }
  .row-bg {
    padding: 5px 0;
    background-color: #f9fafc;
  }
  .self-style{
    text-align: -webkit-center;
    font-size: 14px;
    padding: 5px 0px;
  }
</style>