<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input 
        :placeholder="$t('accountGroup.groupname')"
        clearable 
        v-model="listQuery.name"
        style="width: 150px;" 
        class="filter-item">
      </el-input>
      <el-select clearable style="width:110px;" v-model="listQuery.sectioninaccounts" class="filter-item" filterable placeholder="会计要素">
        <el-option v-for="sec in accountSectionList" :key="sec.sectionid" :label="sec.sectionname" :value="sec.sectionid"/>
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('accountGroup.id')" width="60%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('accountGroup.groupname')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.groupname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('accountGroup.sectioninaccounts')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_account_section.sectionname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('accountGroup.pandl')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <el-tag :type="scope.row.pandl | statusFilter">
            <span>{{ pandlType[scope.row.pandl] }}</span>
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('accountGroup.sequenceintb')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.sequenceintb }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('accountGroup.pid')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_account_group.groupname }}</span>
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
          <el-button size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">
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
        <el-form-item :label="$t('accountGroup.groupname')" prop="groupname">
          <el-input v-model="temp.groupname" />
        </el-form-item>
        <el-form-item :label="$t('accountGroup.sectioninaccounts')" prop="sectioninaccounts">
          <el-select 
            v-model="temp.sectioninaccounts" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入要素搜索">
            <el-option v-for="sec in accountSectionList" :key="sec.sectionid" :label="sec.sectionname" :value="sec.sectionid"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('accountGroup.pandl')" prop="pandl">
          <el-select 
            v-model="temp.pandl" 
            class="filter-item">
            <el-option v-for="(item, index) in pandlType" :key="index" :label="item" :value="index"/>
          </el-select>
        </el-form-item>
        <el-form-item label="是否顶层组">
          <el-radio-group @change="topGroupSel" v-model="isTopGroup">
            <el-radio-button :label="true">顶层组</el-radio-button>
            <el-radio-button :label="false">非顶层组</el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item 
          v-if="!isTopGroup" 
          :rules="!this.isTopGroup?rules.pid:[{ required: false, message: '请选择父组', trigger: 'change' }]"
          :label="$t('accountGroup.pid')" 
          prop="pid">
          <el-select 
            v-model="temp.pid" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入要素搜索">
            <el-option v-if="(temp.id != group.id) && (group.pid == '0')" v-for="group in accountGroupList" :key="group.id" :label="group.groupname" :value="group.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('accountGroup.sequenceintb')" prop="sequenceintb">
          <el-input-number 
            v-model='temp.sequenceintb' 
            :min="0" 
            :max="10000"   
            label="试算表行次">
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
    <!-- <accountGroup-components ref="accountGroupChild"></accountGroup-components>  -->
  </div>
</template>
<script>
  import { getAccountGroupList,  createAccountGroup, updateAccountGroup, deleteAccountGroup} from '@/api/accountGroup'
  import { accountSectionAll } from '@/api/accountSection'
  import { accountGroupAll } from '@/api/accountGroup'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isEmpty } from '@/common.js'
  // import AccountGroupComponents from './components/AccountGroupComponents'

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
  name: 'accountGroup',
  // components: { AccountGroupComponents },
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
        groupname: '',
        sectioninaccounts: '',
      },
      pandlType:['否', '是'],
      isTopGroup: true,
      showGroup: false,
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        groupname: '',
        sectioninaccounts: null,
        pandl: 0,
        sequenceintb:null,
        pid: null,
        topGroup: true,
      },
      dialogFormVisible: false,
      setRateVisible: false,
      accountGroupName: '',
      dialogStatus: '',
      textMap: {
        update: '编辑会计要素',
        create: '新增会计要素'
      },
      pvData: [],
      rules: {
        groupname: [
          { required: true, message: '请输入名称', trigger: 'change' },
          // { min: 3, max: 5, message: '长度在3到5个字符', trigger: 'blur'},
        ],
        sectioninaccounts: [
          { required: true, message: '请选择会计要素', trigger: 'change' },
        ],
        sequenceintb:[
          { required: true, message: '请填写试算表行次', trigger: 'change' },
        ],
        pid:[
          { required: true, message: '请选择父组', trigger: 'change' },
        ],
      },
      accountSectionList: [],
      accountGroupList: [],
    }
  },
  created() {
    Promise.all([
      this.getList(),
      this.getAllAccountSection(),
      this.getAllAccountGroupAll(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getAccountGroupList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllAccountSection(){
      accountSectionAll().then(response => {
        this.accountSectionList = response.data
      })
    },
    getAllAccountGroupAll(){
      accountGroupAll().then(response => {
        this.accountGroupList = response.data
      })
    },
    topGroupSel(val){
      this.temp.topGroup = val
      // console.log(this.temp.pid)
      if(this.temp.pid == 0){
        this.temp.pid = null
      }
      console.log(this.temp.topGroup)
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
        deleteAccountGroup(this.temp).then((response) => {
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
        groupname: '',
        sectioninaccounts: null,
        pandl: 0,
        sequenceintb: '',
        pid: null,
        topGroup: true,
      }
    },
    handleCreate() {
      this.resetTemp()
      this.dialogStatus = 'create'
      this.isTopGroup = true
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          createAccountGroup(this.temp).then((response) => {
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
      row.sectioninaccounts = parseInt(row.sectioninaccounts)
      // row.pid = parseInt(row.pid)
      // console.log(row)

      if(row.pid == 0){ //顶层组
        this.isTopGroup = true 
        // row.pid = undefined
        row.pid = parseInt(row.pid)
      }else{ //非顶层组
        this.isTopGroup = false
        row.pid = parseInt(row.pid)
      }

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
          updateAccountGroup(tempData).then((response) => {
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
      this.$refs.accountGroupChild.handleStockCategory(row) 
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