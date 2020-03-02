<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input 
        :placeholder="$t('bankTransTo.condetionName')"
        clearable 
        v-model="listQuery.name"
        style="width: 150px;" 
        class="filter-item">
      </el-input>
      <el-select clearable style="width:100px;" v-model="listQuery.condetion" class="filter-item" filterable placeholder="条件">
        <!-- <el-option v-for="user in userList" :key="user.id" :label="user.nick_name" :value="user.id"/> -->
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>
      <!-- <el-tooltip class="item" effect="dark" content="注意:默认只导出当月信息,如需导出其他月,请选择筛选条件" placement="top">
        <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">{{ $t('table.export') }}</el-button>
      </el-tooltip> -->
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('bankTransTo.id')" show-overflow-tooltip width="80%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('bankTransTo.chart')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart.chartdescription }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('bankTransTo.cancreate')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.cancreate | statusFilter">
              {{ cancreateStatus[scope.row.cancreate] }}
            </el-tag>
          </span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.date')" width="150px" align="center">
        <template slot-scope="scope">
          <span>
            {{ scope.row.lastcompleted | parseTime('{y}-{m}-{d}') }}
            <!-- |
            <span v-if="scope.row.belongs_to_creater">{{scope.row.belongs_to_creater.nick_name}}</span>
            <span v-else></span> -->
          </span>
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
          <!-- <el-button type="success" size="mini" @click="handleSetChild(scope.row)">
            {{ $t('table.setOrther') }}
          </el-button> -->
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
        <el-form-item :label="$t('bankTransTo.name')" prop="name">
          <el-input v-model="temp.name" />
        </el-form-item>
        <el-form-item :label="$t('bankTransTo.chart')" prop="chart">
          <el-select 
            v-model="temp.chart" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入区域搜索">
            <el-option v-for="chart in chartMasterList" :key="chart.id" :label="chart.chartdescription" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('bankTransTo.paymenttype')">
          <el-radio-group @change="" v-model="temp.paymenttype">
            <el-radio-button label="1">次月截止</el-radio-button>
            <el-radio-button label="2">N天后截止</el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item :label="$t('bankTransTo.cancreate')">
          <el-switch
            v-model="temp.cancreate"
            active-color="#13ce66"
            inactive-color="#ff4949"
            active-value="1"
            inactive-value="0">
          </el-switch>
        </el-form-item>
        <el-form-item :label="$t('bankTransTo.discountrate')" prop="discountrate">
          <el-input-number 
            v-model='temp.discountrate'   
            :min="0" 
            :max="1" 
            :precision="2"
            :step="0.1"
            label="折扣率">
          </el-input-number>
        </el-form-item>  
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <!-- 组件 -->
    <!-- <bankTransTo-components ref="bankTransToChild"></bankTransTo-components>  -->
  </div>
</template>
<script>
  import { getBankTransList,  createBankTrans, updateBankTrans, deleteBankTrans} from '@/api/bankTrans'
  import { chartMasterAll } from '@/api/chartMaster'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isEmpty } from '@/common.js'
  // import BankTransToComponents from './components/BankTransToComponents'

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
  name: 'bankTransTo',
  // components: { BankTransToComponents },
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
        condetionName: '',
        userBanks: [],
        payment: true, //付款
        // Receipts: true, //收款
      },
      cancreateStatus:['否', '是'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        name: '',
        chart: null,
        cancreate : '1',
        discountrate : 0,
        paymenttype: '1',
      },
      dialogFormVisible: false,
      setRateVisible: false,
      bankTransToName: '',
      dialogStatus: '',
      textMap: {
        update: '编辑',
        create: '新增'
      },
      pvData: [],
      rules: {
        name: [
          { required: true, message: '请输入名称', trigger: 'blur' },
          { min: 3, max: 5, message: '长度在3到5个字符', trigger: 'blur'},
        ],
        chart: [{ required: true, message: '请选择库存种类', trigger: 'change' }],
        quantitybreak: [ 
          { required: true, message: '折扣率', trigger: 'change'}, 
        ],
      },
      chartMasterList: [],
    }
  },
  created() {
    Promise.all([
      // this.getTransList(),
      this.getList(),
      // this.myFunction(),
      // this.getAllChartMasters(),
    ])
  },
  methods: {
    async getUserBanks(){    
      return new Promise((resolve, reject) => {  
        var that = this 
        var userid = that.$store.getters.userid 
        // console.log(that.$store.getters)   
        setTimeout(function() {
          console.log(userid)
          console.log('先获取用户授权银行账户啊啊啊')
          console.log(that.listQuery.userBanks)
          // console.log(that.$store.getters.userid)
          
          that.listQuery.userBanks = ['2', '2', '2']
          console.log(that.listQuery.userBanks)

          resolve()
        }, 3000)
        // console.log('先获取用户授权银行账户2')
        // resolve()
      })
    },

    /*ajax1() {
      return new Promise((resolve, reject) => {
           setTimeout(function() {
               resolve('先获取用户授权银行账户')
           }, 3000)
      })
    },

    ajax2() {
       return new Promise((resolve, reject) => {
           setTimeout(function() {
               resolve('再获取列表')
           }, 2000)
      })
    },

    async myFunction() {
       const x = await this.ajax1()
       console.log(x)
       const y = await this.ajax2()
       //等待两个异步ajax请求同时执行完毕后打印出数据
       console.log(x, y)
    },*/

    async getList() {
      console.log('开始')
      // await this.getUserBanks()
      this.getUserBanks().then(() => {

        this.listLoading = true
        // console.log('333')
        console.log('再获取列表a ')
        console.log(this.listQuery)
      })
      /*this.listLoading = true
      // console.log('333')
      console.log('再获取列表')
      console.log(this.listQuery)*/
      /*return new Promise((resolve, reject) => {  
        this.listLoading = true
        console.log('333')
        console.log('再获取列表')
        console.log(this.listQuery)
  
        getBankTransList(this.listQuery).then(response => {
          // this.list = response.data.data
          // this.total = response.data.total
          // Just to simulate the time of the request
          setTimeout(() => {
            this.listLoading = false
          }, 1.5 * 1000)
        })
        resolve()
      }) */ 
    },
    /*async getTransList(){
      await this.getUserBanks()
      this.getList()
    },*/
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
        deleteBankTrans(this.temp).then((response) => {
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
        name: '',
        chart: null,
        cancreate : '1',
        discountrate : 0,
        paymenttype: '1',
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
          createBankTrans(this.temp).then((response) => {
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
      row.chart = parseInt(row.chart)
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
          updateBankTrans(tempData).then((response) => {
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
    handleSetChild(row){
      this.$refs.bankTransToChild.handleStockCategory(row) 
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