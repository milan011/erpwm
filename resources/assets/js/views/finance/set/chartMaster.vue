<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input 
        :placeholder="$t('chartMaster.accountname')"
        clearable 
        v-model="listQuery.accountname"
        style="width: 150px;" 
        class="filter-item">
      </el-input>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('chartMaster.id')" width="80%" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('chartMaster.accountname')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('chartMaster.group_')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_account_group.groupname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('chartMaster.pandl')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.belongs_to_account_group.pandl | statusFilter">
              {{ pandlStatus[scope.row.belongs_to_account_group.pandl] }}
            </el-tag>
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
          <el-button type="success" size="mini" @click="getGLBudgets(scope.row)">
            {{ $t('chartMaster.createGLBudgets') }}
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
        <el-form-item :label="$t('chartMaster.accountname')" prop="accountname">
          <el-input v-model="temp.accountname" />
        </el-form-item>
        <el-form-item :label="$t('chartMaster.group_')" prop="group_">
          <el-select 
            v-model="temp.group_" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入名称搜索">
            <el-option v-for="group in accountGroupList" :key="group.id" :label="group.groupname" :value="group.id"/>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <el-dialog :visible.sync="setGLBudgetVisible" width="70%">
      <el-row>
        <el-col :span="24">
          <div class="grid-content bg-purple-light self-style">
            <span>{{ $t('chartMaster.createGLBudgets') }}:{{ chartMasterName }}</span>
          </div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="8">
          <div class="grid-content bg-purple-dark self-style" style="margin-bottom:5px">
            {{ $t('chartMaster.proFiscalYear') }} : 年末[{{ currentYearEnd - 1 }}-12-31]
          </div>
          <el-row>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.period') }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.actual') }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.budget') }}
              </div>
            </el-col>               
          </el-row>
          <el-row v-for="(glb, glb_index) in preGlbs" :key="glb_index">
            <el-col :span="8">
              <div class="grid-content bg-purple-dark self-style">
                {{ glb.belongs_to_periods.lastdate_in_period.slice(0,7) }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-dark self-style">
                {{ glb.actual }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content self-style">
                <el-input 
                 class="budget_height" 
                 size="mini" 
                 v-model.number="glb.budget" />
              </div>
            </el-col>           
          </el-row>
          <el-row>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.total') }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ preTotal.actual}}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ preTotal.budget }}
              </div>
            </el-col>               
          </el-row>
          <el-row>   
            <el-col :span="12">
              <div class="grid-content self-style">
                <el-input 
                 class="budget_height" 
                 size="mini" 
                 v-model.number="preBudgeShare" />
              </div>
            </el-col>   
            <el-col :span="12">
              <div class="grid-content self-style">
                <el-button type="success" size="mini" @click="budgeShareSet('pre')">
                   {{ $t('chartMaster.budgeShare') }}
                </el-button>
              </div>
            </el-col>            
          </el-row>
        </el-col>
        <el-col :span="8">
          <div class="grid-content bg-purple-dark self-style" style="margin-bottom:5px">
            {{ $t('chartMaster.currentFiscalYear') }} : 年末[{{ currentYearEnd }}-12-31]
          </div>
          <el-row>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.period') }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.actual') }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.budget') }}
              </div>
            </el-col>               
          </el-row>
          <el-row v-for="(glb, glb_index) in currentGlbs" :key="glb_index">
            <el-col :span="8">
              <div class="grid-content bg-purple-dark self-style">
                {{ glb.belongs_to_periods.lastdate_in_period.slice(0,7) }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-dark self-style">
                {{ glb.actual }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content self-style">
                <el-input class="budget_height" size="mini" v-model.number="glb.budget" />
              </div>
            </el-col>               
          </el-row>
          <el-row>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.total') }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ currentTotal.actual}}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ currentTotal.budget }}
              </div>
            </el-col>               
          </el-row>
          <el-row>   
            <el-col :span="12">
              <div class="grid-content self-style">
                <el-input 
                 class="budget_height" 
                 size="mini" 
                 v-model="currentBudgeShare" />
              </div>
            </el-col>   
            <el-col :span="12">
              <div class="grid-content self-style">
                <el-button type="success" size="mini" @click="budgeShareSet('current')">
                   {{ $t('chartMaster.budgeShare') }}
                </el-button>
              </div>
            </el-col>            
          </el-row>
        </el-col>
        <el-col :span="8">
          <div class="grid-content bg-purple-dark self-style" style="margin-bottom:5px">
            {{ $t('chartMaster.nextFiscalYear') }} : 年末[{{ currentYearEnd + 1 }}-12-31]
          </div>
          <el-row>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.period') }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.actual') }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.budget') }}
              </div>
            </el-col>               
          </el-row>
          <el-row v-for="(glb, glb_index) in nextGlbs" :key="glb_index">
            <el-col :span="8">
              <div class="grid-content bg-purple-dark self-style">
                {{ glb.belongs_to_periods.lastdate_in_period.slice(0,7) }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-dark self-style">
                {{ glb.actual }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content self-style">
                <el-input size="mini" class="budget_height" v-model="glb.budget" />
              </div>
            </el-col>               
          </el-row>
          <el-row>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ $t('chartMaster.total') }}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ nextTotal.actual}}
              </div>
            </el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
                {{ nextTotal.budget }}
              </div>
            </el-col>               
          </el-row>
          <el-row>   
            <el-col :span="12">
              <div class="grid-content self-style">
                <el-input 
                 class="budget_height" 
                 size="mini" 
                 v-model="nextBudgeShare" />
              </div>
            </el-col>   
            <el-col :span="12">
              <div class="grid-content self-style">
                <el-button type="success" size="mini" @click="budgeShareSet('next')">
                   {{ $t('chartMaster.budgeShare') }}
                </el-button>
              </div>
            </el-col>            
          </el-row>
        </el-col>               
      </el-row>
      <!-- <el-form ref="rateDataForm" 
        :rules="rateRules" 
        :model="taxAuthoritiesList" 
        :inline="true"  
        class="demo-form-inline">
        <div v-for="(tax, tax_index) in taxAuthoritiesList" :key="tax_index" :model="tax">
          <el-row v-for="(t, t_index) in tax" :key="t_index" :model="t">
            <el-col :span="8"><div class="grid-content bg-purple-light self-style">
              <el-form-item>
              {{t.province_name}}
              </el-form-item>
            </div></el-col>
            <el-col :span="8"><div class="grid-content bg-purple-light self-style">
            <el-form-item>
              {{t.taxcatname}}
              </el-form-item>
            </div></el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
              <el-form-item>
                <el-input-number 
                  v-model='t.taxrate' 
                  size="mini"  
                  :min="0" 
                  :max="1" 
                  :precision="2"
                  :step="0.1"
                  style="width: 120px;"
                  label="税率">
                </el-input-number>
              </el-form-item>
              </div>
            </el-col>               
          </el-row>
        </div>
      </el-form> -->
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="setGLBudgets">
          {{ $t('table.confirm') }}
        </el-button>
        <el-button @click="setGLBudgetVisible = false">
          {{ $t('table.cancel') }}
        </el-button>
      </div>
  </el-dialog>
    <!-- 组件 -->
    <!-- <chartMaster-components ref="chartMasterChild"></chartMaster-components>  -->
  </div>
</template>
<script>
  import { getChartMasterList,  createChartMaster, updateChartMaster, deleteChartMaster, getChartMasterGLB, setChartMasterGLB} from '@/api/chartMaster'
  import { accountGroupAll } from '@/api/accountGroup'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isNumber } from '@/utils/validate'
  import { isEmpty } from '@/common.js'
  // import ChartMasterComponents from './components/ChartMasterComponents'

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
  name: 'chartMaster',
  // components: { ChartMasterComponents },
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
        accountname: '',
        condetion: '',
      },
      pandlStatus:['资产负责表', '损益表'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        accountname: '',
        group_: null,
      },
      currentBudgeShare: 0,
      preBudgeShare: 0,
      nextBudgeShare: 0,
      currentTotal: {
        actual: 0,
        budget: 0,
      },
      preTotal: {
        actual: 0,
        budget: 0,
      },
      nextTotal: {
        actual: 0,
        budget: 0,
      },
      currentGlbs: [],
      preGlbs: [],
      nextGlbs: [],
      dialogFormVisible: false,
      setGLBudgetVisible: false,
      setRateVisible: false,
      chartMasterName: '',
      dialogStatus: '',
      currentYearEnd: new Date().getFullYear(),
      textMap: {
        update: '编辑会计科目',
        create: '新增会计科目'
      },
      pvData: [],
      rules: {
        accountname: [
          { required: true, message: '请输入名称', trigger: 'change' },
          { min: 1, max: 50, message: '长度在1到50个字符', trigger: 'change'},
        ],
        group_: [{ required: true, message: '请选择科目组', trigger: 'change' }],
      },
      accountGroupList: [],
    }
  },
  created() {
    Promise.all([
      this.getList(),
      this.getAllAccountGroup(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getChartMasterList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllAccountGroup(){
      accountGroupAll().then(response => {
        this.accountGroupList = response.data
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
    getGLBudgets(row){ //获取科目总帐预算信息
      // console.log(row)
      var that = this
      that.chartMasterName = row.accountname
      getChartMasterGLB(row).then(response => {
        // console.log(response.data)
        const gblData = response.data.data.glbInfo
        const gblTotal = response.data.data.totalInfo
        that.currentGlbs = gblData.current //当前财年总帐预算
        that.preGlbs = gblData.pre         //上财年总帐预算
        that.nextGlbs = gblData.next       //下财年总帐预算

        that.preTotal = gblTotal[0]
        that.currentTotal = gblTotal[1]
        that.nextTotal = gblTotal[2]
        /*Array.prototype.forEach.call(that.currentGlbs, (info, index) => {
          //循环遍历当前财年信息,获取实际与预算总计
          that.currentTotal.actual += info.actual
          that.currentTotal.budget += info.budget
        })
        Array.prototype.forEach.call(that.preGlbs, (info, index) => {
          //循环遍历上财年信息,获取实际与预算总计
          // console.log(info)
          that.preTotal.actual += info.actual
          that.preTotal.budget += info.budget
        })
        Array.prototype.forEach.call(that.nextGlbs, (info, index) => {
          //循环遍历下财年信息,获取实际与预算总计
          // console.log(info)
          that.nextTotal.actual += info.actual
          that.nextTotal.budget += info.budget
        })*/
      })
      that.currentBudgeShare = 0
      that.preBudgeShare = 0
      that.nextBudgeShare = 0
      that.setGLBudgetVisible = true
    },
    budgeShareSet(data){
      /*console.log(data)
      console.log(this.preBudgeShare)
      console.log(!isNumber(this.preBudgeShare))
      console.log(isNumber(this.preBudgeShare))*/
      switch(data) {
        case 'pre': //处理上年预算分摊
           if(!isNumber(this.preBudgeShare)){
              this.$notify.error({
                title: '失败',
                message: '预算金额应为数字',
                duration: 2000
              })
           }else{
              // console.log(Math.round(this.preBudgeShare/12))
              let preShareVal = Math.round(this.preBudgeShare/12)
              Array.prototype.forEach.call(this.preGlbs, (info, index) => {
                //循环遍历上财年信息,设定预算分摊值
                info.budget = preShareVal
              })
           }
           break;
        case 'current': //处理当前年预算分摊
           if(!isNumber(this.currentBudgeShare)){
              this.$notify.error({
                title: '失败',
                message: '预算金额应为数字',
                duration: 2000
              })
           }else{
              // console.log(Math.round(this.currentBudgeShare/12))
              let currentShareVal = Math.round(this.currentBudgeShare/12)
              Array.prototype.forEach.call(this.currentGlbs, (info, index) => {
                //循环遍历上财年信息,设定预算分摊值
                info.budget = currentShareVal
              })
           }
           break;
        case 'next': //处理下一年预算分摊
           if(!isNumber(this.nextBudgeShare)){
              this.$notify.error({
                title: '失败',
                message: '预算金额应为数字',
                duration: 2000
              })
           }else{
            // console.log(Math.round(this.nextBudgeShare/12))
            let nextShareVal = Math.round(this.nextBudgeShare/12)
              Array.prototype.forEach.call(this.nextGlbs, (info, index) => {
                //循环遍历上财年信息,设定预算分摊值
                info.budget = nextShareVal
              })
           }
           break;
        default:
          this.$notify.error({
            title: '失败',
            message: '预算金额应为数字',
            duration: 2000
          })
      }
    },
    setGLBudgets(){
      /*console.log(this.currentGlbs)
      console.log(this.preGlbs)
      console.log(this.nextGlbs)*/
      const budgetsData =  [...this.currentGlbs, ...this.preGlbs, ...this.nextGlbs]
      let is_number = true
      Array.prototype.forEach.call(budgetsData, (info, index) => {
        //循环遍历预算金额判断是否为数字
        if(!isNumber(info.budget)){
            is_number = false
            return 
        }
      })
      if(!is_number){
        this.$notify.error({
          title: '失败',
          message: '预算金额应为数字',
          duration: 2000
        })
      }else{
        setChartMasterGLB({allBudgets: budgetsData}).then(response => {
          this.$notify({
            title: '成功',
            message: '总帐预算设置成功',
            type: 'success',
            duration: 2000
          })
          this.setGLBudgetVisible = false
        })
      }
    },
    handleModifyStatus(row, status) {
      this.$confirm('确定要删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.temp = Object.assign({}, row)
        deleteChartMaster(this.temp).then((response) => {
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
        accountname: '',
        group_: null,
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
          createChartMaster(this.temp).then((response) => {
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
      row.group_ = parseInt(row.group_)
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
          updateChartMaster(tempData).then((response) => {
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
  }
}
</script>
<style lang="scss" scoped>
  .el-dialog__body {
    padding: 15px 15px;
  }
  .el-dialog__header {
     padding-top: 0px; 
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
  .self-style{
    height:35px;
  }
  .budget_height{
    padding:2px;
    top: -10%;
  }
</style>