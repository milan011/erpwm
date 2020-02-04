<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input 
        :placeholder="$t('bankAccount.bankaccountcode')"
        clearable 
        v-model="listQuery.name"
        style="width: 150px;" 
        class="filter-item">
      </el-input>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('bankAccount.id')" show-overflow-tooltip width="80%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('bankAccount.currcode')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.currcode }}</span>
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('bankAccount.accountcode')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('bankAccount.invoice')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.invoice | statusFilter">{{ invoiceStatus[scope.row.invoice] }}</el-tag>
          </span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('bankAccount.bankaccountcode')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.bankaccountcode }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('bankAccount.bankaccountname')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.bankaccountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('bankAccount.bankaccountnumber')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.bankaccountnumber }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('bankAccount.bankaddress')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.bankaddress }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="230%" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <!-- <el-button type="success" size="mini" @click="handleShow(scope.row)">
            {{ $t('table.content') }}
          </el-button> -->
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button type="success" size="mini" @click="handleSetBankUser(scope.row)">
            {{ $t('bankAccount.setBankUser') }}
          </el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next" @current-change="handleCurrentChange" />
    </div>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px" style="width: 400px;margin:0px auto;">
        <el-form-item :label="$t('bankAccount.accountcode')" prop="accountcode">
          <el-select 
            v-model="temp.accountcode" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入区域搜索">
            <el-option v-for="chart in chartMasterList" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('bankAccount.bankaccountname')" prop="bankaccountname">
          <el-input v-model="temp.bankaccountname" />
        </el-form-item>
        <el-form-item :label="$t('bankAccount.bankaddress')" prop="bankaddress">
          <el-input v-model="temp.bankaddress" />
        </el-form-item>
        <el-form-item :label="$t('bankAccount.invoice')">
          <el-radio-group @change="" v-model="temp.invoice">
            <el-radio-button label="0">否</el-radio-button>
            <el-radio-button label="1">优先处理</el-radio-button>
          </el-radio-group>
        </el-form-item>  
        <el-form-item :label="$t('bankAccount.bankaccountcode')" prop="bankaccountcode">
          <el-input v-model="temp.bankaccountcode" />
        </el-form-item>
        <el-form-item :label="$t('bankAccount.bankaccountnumber')" prop="bankaccountnumber">
          <el-input v-model="temp.bankaccountnumber" />
        </el-form-item>
        
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <!-- 组件 -->
    <set-bank-user ref="bankUserChild"></set-bank-user>
    <!-- <bankAccount-components ref="bankAccountChild"></bankAccount-components>  -->
  </div>
</template>
<script>
  import { getBankAccountList,  createBankAccount, updateBankAccount, deleteBankAccount} from '@/api/bankAccount'
  import { chartMasterAll } from '@/api/chartMaster'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isEmpty } from '@/common.js'
  import  SetBankUser  from './components/SetBankUser'
  // import BankAccountComponents from './components/BankAccountComponents'

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
  name: 'bankAccount',
  components: { SetBankUser },
  // components: { BankAccountComponents },
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
        name: '',
      },
      invoiceStatus:['否', '优先处理'],
      calendarTypeOptions,
      showReviewer: false,
      temp: { 
        id: undefined,
        accountcode: undefined,
        invoice : '1',
        bankaccountcode : '',
        bankaccountnumber: '',
        bankaccountname: '',
        bankaddress: '',
      },
      dialogFormVisible: false,
      setRateVisible: false,
      bankAccountName: '',
      dialogStatus: '',
      textMap: {
        update: '编辑银行账户',
        create: '新增银行账户'
      },
      pvData: [],
      rules: {
        bankaccountname: [
          { required: true, message: '请输入开户行名称', trigger: 'blur' },
          { min: 1, max: 50, message: '长度在1到50个字符', trigger: 'blur'},
        ],
        accountcode: [{ required: true, message: '请选择会计科目', trigger: 'change' }],
        bankaccountcode: [
          { required: true, message: '请输入户名', trigger: 'blur' },
          { min: 1, max: 50, message: '长度在1到50个字符', trigger: 'blur'},
        ],
        bankaccountnumber: [
          { required: true, message: '请输入账号', trigger: 'blur' },
          { min: 1, max: 50, message: '长度在1到50个字符', trigger: 'blur'},
        ],
        bankaddress: [
          { required: true, message: '请输入开户行地址', trigger: 'blur' },
          { min: 1, max: 50, message: '长度在1到50个字符', trigger: 'blur'},
        ],
      },
      chartMasterList: [],
    }
  },
  created() {
    Promise.all([
      this.getList(),
      this.getAllChartMasters(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getBankAccountList(this.listQuery).then(response => {
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
        deleteBankAccount(this.temp).then((response) => {
          if(!response.data.status){
            this.$notify({
              title: '失败',
              message: response.data.message,
              type: 'warning',
              duration: 8000
            })
          }else{
            const index = this.list.indexOf(row)
            this.list.splice(index, 1)
            this.total = this.total - 1
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
        accountcode: undefined,
        invoice : '1',
        bankaccountcode : '',
        bankaccountnumber: '',
        bankaccountname: '',
        bankaddress: '',
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
          createBankAccount(this.temp).then((response) => {
            const response_data = response.data
            if(response_data.status){
              this.temp.id = response_data.data.id
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
      row.accountcode = parseInt(row.belongs_to_chart_master.id)
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
          updateBankAccount(tempData).then((response) => {
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
    handleShow(row) {
      row.taxprovinceid = row.belongs_to_taxprovinces.taxprovincename
      row.cashsalebranch = row.belongs_to_custbranch.brname
      row.cashsalecustomer = row.belongs_to_debtors_master.name
      this.temp = Object.assign({}, row) // copy obj
      console.log(this.temp)
      this.dialogInfoVisible = true
    },
    handleSetBankUser(row) {  //银行账户授权
      this.$refs.bankUserChild.handleUsers(row) 
    },
    SetBankUser() {
      this.$refs.bankUserChild.giveUsers()
    },
    /*handleSetChild(row){
      this.$refs.bankAccountChild.handleStockCategory(row) 
    },*/
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