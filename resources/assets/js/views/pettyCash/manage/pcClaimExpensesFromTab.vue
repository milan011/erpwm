<template>
  <div class="app-container">
    <div class="filter-container">
      <!-- <el-input 
        :placeholder="$t('pcClaimExpensesFromTab.tabcode')"
        clearable 
        v-model="listQuery.tabcode"
        style="width: 150px;" 
        class="filter-item">
      </el-input> -->
      <el-select clearable style="width:100px;" v-model="listQuery.tabcode" class="filter-item" filterable :placeholder="$t('pcClaimExpensesFromTab.tabcode')">
        <el-option 
          v-for="tab in pcTabList" 
          v-if="tab.usercode == $store.getters.userid"
          :key="tab.id" 
          :label="tab.tabcode" 
          :value="tab.id"/>
      </el-select>
      <el-select clearable style="width:120px;" v-model="listQuery.codeexpense" class="filter-item" filterable :placeholder="$t('pcClaimExpensesFromTab.codeexpense')">
        <el-option 
          v-for="expenses in expensesList" 
          :key="expenses.id" 
          :label="expenses.description" 
          :value="expenses.id"/>
      </el-select>
      <el-select clearable style="width:100px;" v-model="listQuery.posted" class="filter-item" filterable :placeholder="$t('pcClaimExpensesFromTab.posted')">
        <el-option v-for="item in postedStatus" :key="item.key" :label="item.value" :value="item.key"/>
      </el-select>
      <el-date-picker
        v-model="listQuery.date"
        style="bottom:3px;width:220px;"
        type="daterange"
        align="center"
        unlink-panels
        range-separator="- "
        start-placeholder="开始日期"
        end-placeholder="结束日期"
        :picker-options="pickerOptions">
      </el-date-picker>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('pcClaimExpensesFromTab.counterindex')" width="80%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.counterindex }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcClaimExpensesFromTab.tabcode')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_pc_tab.tabcode }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcClaimExpensesFromTab.codeexpense')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_pc_expenses.description }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcClaimExpensesFromTab.date')"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.date }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcClaimExpensesFromTab.amount')"  align="center">
        <template slot-scope="scope">
          <span>-{{ scope.row.amount }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcClaimExpensesFromTab.notes')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.notes }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('pcClaimExpensesFromTab.receipt')"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.receipt }}</span>
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('pcClaimExpensesFromTab.posted')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.posted | statusFilter">
              <span v-if="scope.row.posted == 1">未授权</span>
              <span v-else>{{ scope.row.authorized }}</span>
            </el-tag>
          </span>
        </template>
      </el-table-column>
      <el-table-column  :label="$t('table.actions')" align="center" width="230%" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button v-if="scope.row.posted == 1" type="primary" size="mini" @click="handleUpdate(scope.row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="scope.row.posted == 1" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">
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
        <!-- <el-form-item :label="$t('pcClaimExpensesFromTab.tabcode')" prop="tabcode">
          <el-input v-model="temp.tabcode" />
        </el-form-item> -->
        <el-form-item :label="$t('pcClaimExpensesFromTab.tabcode')" prop="tabcode">
          <el-select 
            v-model="temp.tabcode" 
            class="filter-item" 
            filterable 
            clearable 
            @change="setExpenseList"
            placeholder="输入标签搜索">
            <el-option v-for="tab in pcTabList" v-if="tab.usercode == $store.getters.userid" :key="tab.id" :label="tab.tabcode" :value="tab.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('pcClaimExpensesFromTab.codeexpense')" prop="codeexpense">
          <el-select 
            v-model="temp.codeexpense" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入费用搜索">
            <el-option v-for="expense in setExpensesList" :key="expense.id" :label="expense.description" :value="expense.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('pcClaimExpensesFromTab.date')" prop="date">
          <el-date-picker
            v-model="temp.date"
            type="date"
            value-format="yyyy-MM-dd"
            :picker-options="pickerOptionsAdd"
            placeholder="预支款日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item :label="$t('pcClaimExpensesFromTab.amount')" prop="amount">
          <el-input-number 
            v-model='temp.amount'   
            :min="0" 
            label="金额">
          </el-input-number>
        </el-form-item>  
        <el-form-item :label="$t('pcClaimExpensesFromTab.notes')" prop="notes">
          <el-input v-model="temp.notes" />
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
  import { getPcClaimExpensesFromTabList,  createPcClaimExpensesFromTab, updatePcClaimExpensesFromTab, deletePcClaimExpensesFromTab, expensesAll} from '@/api/pcClaimExpensesFromTab'
  import { pcTabAll } from '@/api/pcTab'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isEmpty } from '@/common.js'
  // import PcClaimExpensesFromTabComponents from './components/PcClaimExpensesFromTabComponents'

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
  name: 'pcClaimExpensesFromTab',
  directives: {
    waves
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        2: 'success',
        1: 'danger',
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
        tabcode: '',
        date: '',
        posted: undefined,
        codeexpense: undefined,
      },
      pickerOptions: {
        shortcuts: [{
          text: '最近一周',
          onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit('pick', [start, end]);
            }
          },
        ],
      },
      pickerOptionsAdd: {
        disabledDate: time => {
          return time.getTime() < Date.now()  
        }
      },
      postedStatus:[ { key:1, value:'未授权'}, { key:2, value: '已授权'}],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        counterindex: undefined,
        tabcode: '',
        notes: '',
        amount : 0,
        codeexpense: undefined,
        date: '',
        posted: '1',
      },
      dialogFormVisible: false,
      setRateVisible: false,
      pcClaimExpensesFromTabName: '',
      dialogStatus: '',
      textMap: {
        update: '编辑报销',
        create: '新增报销'
      },
      pvData: [],
      rules: {
        tabcode: [
          { required: true, message: '请选择标签', trigger: 'blur' },
          // { min: 3, max: 5, message: '长度在3到5个字符', trigger: 'blur'},
        ],
        codeexpense:[{ required: true, message: '请选择费用', trigger: 'change' }],
        amount: [{ required: true, message: '请输入金额', trigger: 'change' }],
        notes: [ 
          { required: true, message: '请填写备注', trigger: 'change'}, 
        ],
        date: [{ required: true, message: '请选择日期', trigger: 'change' }],
      },
      pcTabList: [],
      expensesList: [],
      setExpensesList: [],
    }
  },
  created() {
    /*console.log('hehe')
    console.log(this.$store.getters.userid)
    console.log(this.$store.getters.username)*/
    Promise.all([
      this.getList(),
      this.getAllPcTab(),
      this.getExpensesList(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getPcClaimExpensesFromTabList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllPcTab(){
      pcTabAll().then(response => {
        this.pcTabList = response.data
      })
    },
    getExpensesList(){
      expensesAll().then(response => {
        this.expensesList = response.data
      })
    },
    setExpenseList(val){
      console.log(val)
      this.temp.codeexpense = undefined
      const condition = {tabId: val}
      expensesAll(condition).then(response => {
        this.setExpensesList = response.data
        console.log(this.setExpensesList)
      })
    },
    handleFilter() {
      console.log(this.listQuery)
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
        deletePcClaimExpensesFromTab(this.temp).then((response) => {
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
        counterindex: undefined,
        tabcode: '',
        notes: '',
        amount : 0,
        codeexpense: undefined,
        date: '',
        posted: '1',
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
          createPcClaimExpensesFromTab(this.temp).then((response) => {
            const response_data = response.data
            if(response_data.status){
              this.temp.counterindex = response_data.data.counterindex
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

      row.tabcode = parseInt(row.tabcode)
      row.codeexpense = parseInt(row.codeexpense)
      this.setExpenseList(row.tabcode)
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
          updatePcClaimExpensesFromTab(tempData).then((response) => {
            const response_data = response.data
            if(response_data.status){
              for (const v of this.list) {
                if (v.counterindex === this.temp.counterindex) {
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