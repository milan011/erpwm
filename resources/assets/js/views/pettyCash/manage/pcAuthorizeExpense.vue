<template>
  <div class="app-container">
    <div class="filter-container">
      <!-- <el-input 
        :placeholder="$t('pcAuthorizeExpense.tabcode')"
        clearable 
        v-model="listQuery.tabcode"
        style="width: 150px;" 
        class="filter-item">
      </el-input> -->
      <el-select clearable style="width:100px;" v-model="listQuery.tabcode" class="filter-item" filterable :placeholder="$t('pcAuthorizeExpense.tabcode')">
        <el-option 
          v-for="tab in pcTabList" 
          v-if="tab.usercode == $store.getters.userid"
          :key="tab.id" 
          :label="tab.tabcode" 
          :value="tab.id"/>
      </el-select>
      <el-select clearable style="width:120px;" v-model="listQuery.codeexpense" class="filter-item" filterable :placeholder="$t('pcAuthorizeExpense.codeexpense')">
        <el-option 
          v-for="expenses in expensesList" 
          :key="expenses.id" 
          :label="expenses.description" 
          :value="expenses.id"/>
      </el-select>
      <el-select clearable style="width:100px;" v-model="listQuery.posted" class="filter-item" filterable :placeholder="$t('pcAuthorizeExpense.posted')">
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
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('pcAuthorizeExpense.counterindex')" width="80%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.counterindex }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcAuthorizeExpense.tabcode')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_pc_tab.tabcode }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcAuthorizeExpense.codeexpense')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_pc_expenses.description }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcAuthorizeExpense.date')"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.date }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcAuthorizeExpense.amount')"  align="center">
        <template slot-scope="scope">
          <span>-{{ scope.row.amount }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('pcAuthorizeExpense.notes')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.notes }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('pcAuthorizeExpense.receipt')"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.receipt }}</span>
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('pcAuthorizeExpense.posted')" show-overflow-tooltip align="center">
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
          <el-button v-if="scope.row.posted == 1" type="success" size="mini" @click="handleApproval(scope.row)">
            批准
          </el-button>
          <!-- <el-button v-if="scope.row.posted == 1" size="mini" type="danger" @click="handleApproval(scope.row,'deleted')">
            {{ $t('table.delete') }}
          </el-button> -->
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next" @current-change="handleCurrentChange" />
    </div>
  </div>
</template>
<script>
  import { getPcAuthorizeExpenseList,  createPcAuthorizeExpense, updatePcAuthorizeExpense, approvalAuthorizeExpense, expensesAll} from '@/api/pcAuthorizeExpense'
  import { pcTabAll } from '@/api/pcTab'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isEmpty } from '@/common.js'
  // import PcAuthorizeExpenseComponents from './components/PcAuthorizeExpenseComponents'

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
  name: 'pcAuthorizeExpense',
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
        posted: 1,
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
      dialogFormVisible: false,
      pcTabList: [],
      expensesList: [],
    }
  },
  created() {
    Promise.all([
      this.getList(),
      this.getAllPcTab(),
      this.getExpensesList(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getPcAuthorizeExpenseList(this.listQuery).then(response => {
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
    handleApproval(row) {
      this.$confirm('确定要批准该报销项?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.temp = Object.assign({}, row)
        approvalAuthorizeExpense(this.temp).then((response) => {
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
              message: '已审批',
              type: 'success',
              duration: 2000
            })
          }   
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消审批'
        });          
      });
    }
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