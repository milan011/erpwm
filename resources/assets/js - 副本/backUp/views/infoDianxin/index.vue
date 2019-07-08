<template>
  <div class="app-container">
    <el-alert
    title="请注意:"
    type="error"
    v-if="errorAlertVisible"
    :description="alertMessage"
    style="margin-bottom:5px">
    </el-alert>
    <div class="filter-container">
      <el-input 
        :placeholder="$t('infoDianxin.return_telephone')"
        clearable 
        v-model="listQuery.selectTelephone"
        style="width: 150px;" 
        class="filter-item">
      </el-input>
      <el-select style="width:100px;" clearable v-model="listQuery.netin_year" class="filter-item" placeholder="入网年">
        <el-option v-for="year in package_year" :key="year.key" :label="year.year" :value="year.year"/>
      </el-select>
      <el-select style="width:100px;" clearable v-model="listQuery.netin_month" class="filter-item" placeholder="入网月">
        <el-option v-for="month in package_month" :key="month.key" :label="month.month" :value="month.month"/>
      </el-select>
      <el-select v-if="isAdmin" style="width:100px;" :placeholder="$t('info.status')" clearable v-model="listQuery.status" class="filter-item">
        <el-option v-for="(item, key, index) in statusMap" :key="item" :label="item" :value="key"/>
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>
      <el-tooltip class="item" effect="dark" content="注意:默认只导出当月信息,如需导出其他月,请选择筛选条件" placement="top">
        <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">{{ $t('table.export') }}</el-button>
      </el-tooltip>
      <upload-excel-component 
        :on-success="handleSuccess" 
        :before-upload="beforeUpload"/>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">{{ $t('table.add') }}</el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-circle-check" @click="exampleExcelDown">{{ $t('infoDianxin.exampleExcelDown') }}</el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-message" @click="infoDeal">{{ $t('infoDianxin.infoDeal') }}</el-button>
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
      <el-table-column :label="$t('infoDianxin.return_telephone')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.return_telephone }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('infoDianxin.refunds')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.refunds }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('infoDianxin.balance_month')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.balance_month }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('infoDianxin.netin')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.netin }}</span>
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('infoDianxin.name')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('infoDianxin.netin')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.netin || '' }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="isAdmin" :label="$t('infoDianxin.status')" align="center">
        <template slot-scope="scope">
          <el-tag :type="scope.row.status | statusFilter">{{ statusMap[scope.row.status] }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('infoDianxin.import')" width="150px" align="center">
        <template slot-scope="scope">
          <span>
            {{ scope.row.created_at | parseTime('{y}-{m}-{d}') }}
            |
            <!-- {{ scope.row.belongs_to_creater ? scope.row.belongs_to_creater.nick_name : '' }} -->
            <span v-if="scope.row.belongs_to_creater">{{scope.row.belongs_to_creater.nick_name}}</span>
            <span v-else></span>
          </span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="230" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="success" size="mini" @click="handleShow(scope.row)">{{ $t('table.show') }}</el-button>
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next"  @current-change="handleCurrentChange"/>
    </div>
    <form-info ref="formInfoChild" v-bind:infoSelfList="this.list" v-on:addNewInfo="addList($event)"></form-info>
    <show-info ref="showInfoChild" ></show-info>
  </div>
</template>

<script>
import { infoDianxinList, deleteInfoDianxin, importInfoDianxin, dealInfoDianxin } from '@/api/infoDianxin'
import waves from '@/directive/waves' // 水波纹指令
import { parseTime } from '@/utils'
import { isTelephone, isReturnMonth, isBalanceMonth } from '@/utils/validate'
import  ShowInfo  from './components/ShowInfo'
import FormInfo from './components/FormInfo'
import  { isEmpty }  from '@/common.js'
import { export_table_to_excel, export_json_to_excel } from '@/vendor/Export2Excel.js'
import { infoDianxinStatus, package_year ,package_month }  from '@/config.js'
import UploadExcelComponent from '@/components/UploadExcel/upload.vue'

export default {
  name: 'infoSelfList',
  components: { FormInfo, ShowInfo, UploadExcelComponent },
  directives: {
    waves
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        2: 'success',
        1: 'danger'
      }
      return statusMap[status]
    },
    jituanStatusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'danger'
      }
      return statusMap[status]
    },
    typeFilter(type) {
      return calendarTypeKeyValue[type]
    }
  },
  data() {  
    return {
      temp:{},
      tableKey: 0,
      list: null,
      listExport: null,
      total: null,
      listLoading: true,
      listQuery: {
        page: 1,
        withNoPage: false,
        netin_month: '',
        netin_year: '',
        selectTelephone: '',
        status: '',
      },
      isAdmin: false,
      package_year:package_year,
      package_month:package_month,
      statusMap: infoDianxinStatus,
      downloadLoading: false,
      errorAlertVisible: false,
      alertMessage: ''
    }
  },
  
  created() {
    Promise.all([
      this.getList(),
      this.isAdminOrManager(),
      // console.log(this.$store.getters.roles)
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      infoDianxinList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    isAdminOrManager() {
      let userRole = this.$store.getters.roles
      //console.log(userRole.indexOf('admin'))
      //console.log(userRole.indexOf('manager'))
      if((userRole.indexOf('admin') >= 0) || (userRole.indexOf('manager') >= 0)){
        this.isAdmin = true
      }
    
    },
    addList(newInfo){
      // console.log(newInfo)
      if(newInfo.update){
        for (const v of this.list) {
          if (v.id === newInfo.id) {
            const index = this.list.indexOf(v)
            this.list.splice(index, 1, newInfo)
            break
          }
        }
      }else{
        this.list.unshift(newInfo)
      }   
    },
    exampleExcelDown(){
      let url = "api/infoDianxin/exampleExcelDownload?token=" + this.$store.getters.token;
      window.location.href = url;
    },
    beforeUpload(file) {
      const isLt1M = file.size / 1024 / 1024 < 1
      // console.log(file)
      if (isLt1M) {
        return true
      }

      this.$message({
        message: '导入的excel文件不能大于1MB',
        type: 'warning'
      })
    },
    /*uploadFail(err, file, fileList){
      console.log(err)
      console.log(file)
      console.log(fileList)
    },*/
    handleSuccess({ results, header }) {
      
      // console.log(results)
      let infoCheck = true
      let errorLine = ''
      const allowHeader = ["套餐名称", "返款号码", "集团名称", "返款金额", "价款", "结算月", "客户经理", "佣金方案", "返还日期"]
      
      //console.log(header.toString() === allowHeader.toString())
      if(header.toString() !== allowHeader.toString()){ //检查表头
        // this.showMessage('您导入到excel表头不符合标准,请下载标准表格')
        this.alertMessage = '您导入到excel表头不符合标准,请下载标准表格'
        this.errorAlertVisible = true
        return false
      }

      if(isEmpty(results)){
        this.alertMessage = '您导入到excel表是空表'
        this.errorAlertVisible = true
        return false
      }

      //检查每行数据,不能有空数据
      //检查结算月是否符合
      //检查返还电话是否符合
      //检查返还日期是否符合
      Array.prototype.forEach.call(results, (info, index) => {
        //console.log(info.返款号码)
        //console.log(!isTelephone(info.返款号码))
        //console.log(isTelephone(info.返款号码))
        //console.log(!isBalanceMonth(info.结算月))
        //console.log(isBalanceMonth(info.结算月))
        //console.log(!isReturnMonth(info.返还日期))
        //console.log(isReturnMonth(info.返还日期))
        // return false
        if((!isTelephone(info.返款号码)) || (!isBalanceMonth(info.结算月)) || (!isReturnMonth(info.返还日期))){
          //console.log(index)
          //console.log(info.返款号码)
          //console.log(info.结算月)
          //console.log(info.返还日期)
          errorLine += (index+2) + ','
          infoCheck = false
        }
        /*if(!isBalanceMonth(info.结算月)){
          console.log(index)
          console.log(info.结算月)
        }
        if(!isReturnMonth(info.返还日期)){
          console.log(index)
          console.log(info.返还日期)
        }*/
      })
      if(!infoCheck){
        this.alertMessage = '请您检查错误行返款号码(有效手机号码),结算月(1-12之间的数字),返还日期格式(201801).错误发生在以下行:' + errorLine
        this.errorAlertVisible = true
        return false
      }
      // console.log(allowHeader)
      this.errorAlertVisible = false

      // console.log(results)
      importInfoDianxin(results).then(response => {
        // console.log(response.data)
        let returnInfo = response.data
        let message = ''

        message += '您本次成功导入'
        message += '<span style="color:green">'
        message += returnInfo.success_count
        message += '</span>'
        message += '条数据'
        if(returnInfo.un_success_count > 0){
          message += ',尚有'
          message += '<span style="color:red">'
          message += returnInfo.un_success_count
          message += '</span>'
          message += '条数据未导入,点击"确定"导出!<br>'
          message += '未导入数据可能是系统已存在相同信息'
        }  
        this.getList()
        this.$alert(message, '导入情况', {
        confirmButtonText: '确定',
        dangerouslyUseHTMLString: true,
          callback: action => {
            if(action  == 'confirm'){
              if(returnInfo.un_success_count > 0){
                const tHeader  = ['套餐名称','返款号码','集团名称','返款金额','价款', '结算月', '客户经理', '佣金方案', '返还日期']
                const filterVal = ['name','return_telephone','jituan','refunds','jiakuan', 'balance_month', 'manager', 'yongjin', 'netin']
                const data = this.formatJson(filterVal, returnInfo.dataNotImport)
                // console.log(data)
                // return false
                const tableName = '未导入信息'
                export_json_to_excel({
                  header: tHeader,
                  data,
                  filename: tableName
                })
              }
            }
          } 
        })        
      }).catch((err) => {
        // console.log(err)
        if(!err){
          this.alertMessage = err
          this.errorAlertVisible = true 
        }            
      })
    },
    handleDownload() {
      // this.handleFilter()
      this.downloadLoading = true
      
      /*if(this.listQuery.netin_year == '' && 
          this.listQuery.netin_month == '' &&
          this.listQuery.selectTelephone == ''  &&
          this.listQuery.status == ''){ //没有任何搜索条件
        if(this.listQuery.netin_year == ''){
          this.listQuery.netin_year = new Date().getFullYear()
        } 
        if(this.listQuery.netin_month == ''){
          this.listQuery.netin_month = new Date().getMonth()+1
        }
      }  */
      /*if(this.listQuery.netin_year == ''){
        this.listQuery.netin_year = new Date().getFullYear()
      } 
      if(this.listQuery.netin_month == ''){
        this.listQuery.netin_month = new Date().getMonth()+1
      }*/  
      // console.log(this.listQuery)
      // return false
      this.listQuery.withNoPage = true
      infoDianxinList(this.listQuery).then(response => {
        this.listExport = response.data.data
        // console.log(this.listExport)
        // return false
        /*Array.prototype.forEach.call(this.listExport, child => {
          // console.log(child)
          if(child.side_number !== ''){
            let side_arr     = child.side_number.split("|")
            let side_uim_arr = child.side_uim_number.split("|")
            child.side_info    = ''
  
            //side_arr     = row.side_number.split("|")
            //side_uim_arr = row.side_uim_number.split("|")
  
            //console.log(side_arr)
            //console.log(side_uim_arr)
  
            Array.prototype.forEach.call(side_arr, (side, index) => {
              //console.log(index)
              //console.log(side)
              let s_info = side + '(' + side_uim_arr[index] + ')' + "\r\n"
              child.side_info +=  s_info
            })
            // child.side_info = child.side_info.substr(1,child.side_info.length-2);slice(start,[end])
            // console.log(child.side_info)
            child.side_info = child.side_info.slice(0,child.side_info.length-2)
          }
          

          //console.log(this.listExport)
          child.date      = child.created_at.slice(0,10)
          child.isJituan = this.jiTuanStatusMap[child.is_jituan]
          child.oldBind  = this.oldBindStatusMap[child.old_bind]
          child.packageName  = child.has_one_package.name
          child.collectionsType  = this.collectionsTypeMap[child.collections_type]
          child.creater  = child.belongs_to_creater.nick_name

        })*/
        const tHeader  = ['套餐名称','返款号码','集团名称','返款金额','价款', '结算月', '客户经理', '佣金方案', '返还日期']
        const filterVal = ['name','return_telephone','jituan','refunds','jiakuan', 'balance_month', 'manager', 'yongjin', 'netin']
        const data = this.formatJson(filterVal, this.listExport)
        // console.log(data)
        // return false
        const tableName = '电信信息表'
        export_json_to_excel({
          header: tHeader,
          data,
          filename: tableName
        })
        this.downloadLoading = false
      })   
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => {
        if (j === 'timestamp') {
          return parseTime(v[j])
        } else {
          return v[j]
        }
      }))
    },
    handleFilter() {
      // console.log(this.listQuery)
      // return false
      this.listQuery.page = 1
      this.listQuery.withNoPage = false
      this.getList()
    },
    handleCurrentChange(val) {
      this.listQuery.page = val
      this.listQuery.withNoPage = false
      // console.log(this.listQuery)
      // return false
      this.getList()
    },
    handleModifyStatus(row, status) {
      this.$confirm('确定要删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.temp = Object.assign({}, row)
        deleteInfoDianxin(this.temp).then((response) => {
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
        // alert('1')
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
    infoDeal(){
      this.listLoadingFull = true
      this.listLoading = true
      dealInfoDianxin().then((response) => {
        console.log(response.data);

        if(response.data.status){
          
          this.$notify({
            title: '成功',
            message: response.data.message,
            type: 'success',
            duration: 2000
          })
        }else{
          if(response.data.status_code == 4029){
            this.$notify({
              title: '失败',
              message: response.data.error.message,
              type: 'warning',
              duration: 2000
            })
          }else{
            this.$notify({
              title: '失败',
              message: '处理信息失败',
              type: 'warning',
              duration: 2000
            })
          }
        }
        this.listLoading = false
        /*if(response.data.status === 0){
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
        }*/   
      }).catch((err) => {
        // console.log(err)
        /*switch (err.response.status) {
          case 429:
            this.$message({
            type: 'info',
            message: '操作过于频繁,请5分钟后重试'
          })
          break
          default:
            this.$message({
              type: 'info',
              message: '信息处理失败'
            })  
          break
        }*/ 
        this.$message({
          type: 'info',
          message: '信息处理失败'
        }) 
        this.listLoading = false        
      })
    },
    handleCreate() { 
      this.$refs.formInfoChild.handleCreateInfo() 
    },
    handleUpdate(row) {  
      this.$refs.formInfoChild.handleUpdateInfo(row) 
    },
    handleShow(row) {
      this.$refs.showInfoChild.handleShowInfo(row)
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