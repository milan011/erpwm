<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-internalrequest-row style="width: 100%;">
      <el-table-column :label="$t('locations.id')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('locations.locationname')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.locationname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('locations.tel')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.tel }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('locations.fax')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.fax }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('locations.contact')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.contact }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('locations.deladd1')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.deladd1 }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('locations.taxprovinceid')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_taxprovinces.taxprovincename }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('locations.internalrequest')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.internalrequest | statusFilter">
              {{ internalrequestStatus[scope.row.internalrequest] }}
            </el-tag>
          </span>
        </template>
      </el-table-column> 
      <el-table-column :label="$t('table.actions')" width="230%" align="center" show-overflow-tooltip class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="success" size="mini" @click="handleShow(scope.row)">{{ $t('table.content') }}</el-button>
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination v-show="total>0" :internalrequest-page="listQuery.page" :total="total" background layout="total, prev, pager, next" @internalrequest-change="handleCurrentChange" />
    </div>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="100px" style="width: 400px;margin:0px auto;">
        <el-form-item :label="$t('locations.locationname')" prop="locationname">
          <el-input v-model="temp.locationname" />
        </el-form-item>
        <el-form-item :label="$t('locations.contact')" prop="contact">
          <el-input v-model="temp.contact" />
        </el-form-item>
        <el-form-item :label="$t('locations.deladd1')" prop="deladd1">
          <el-input v-model="temp.deladd1" />
        </el-form-item>
        <el-form-item :label="$t('locations.tel')" prop="tel">
          <el-input v-model="temp.tel" />
        </el-form-item>
        <el-form-item :label="$t('locations.fax')" prop="fax">
          <el-input v-model="temp.fax" />
        </el-form-item>
        <el-form-item :label="$t('locations.email')" prop="email">
          <el-input v-model="temp.email" />
        </el-form-item>
        <el-form-item :label="$t('locations.taxprovinceid')" prop="taxprovinceid">
          <el-select 
            v-model="temp.taxprovinceid" 
            class="filter-item">
            <el-option v-for="province in taxProvincesAll"  :key="province.taxprovinceid" :label="province.taxprovincename" :value="province.taxprovinceid"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('locations.cashsalecustomer')" prop="cashsalecustomer">
          <el-select 
            v-model="temp.cashsalecustomer" 
            class="filter-item"
            filterable 
            clearable 
            placeholder="输入名称搜索"
            @change="getAllCustbranch">
            <el-option v-for="debt in debtorsMasterAll"  :key="debt.id" :label="debt.name" :value="debt.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('locations.cashsalebranch')" prop="cashsalebranch">
          <el-select 
            v-model="temp.cashsalebranch" 
            class="filter-item"
            filterable 
            clearable 
            placeholder="输入名称搜索"
            :disabled="cashDisabled">
            <el-option v-for="cust in custbranchAll"  :key="cust.id" :label="cust.brname" :value="cust.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('locations.internalrequest')" prop="internalrequest">
          <el-select 
            v-model="temp.internalrequest" 
            class="filter-item"  
            placeholder="">
            <el-option v-for="(internalrequest, index) in internalrequestStatus" :key="index" :label="internalrequest" :value="index"/>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <el-dialog  :visible.sync="dialogInfoVisible">
      <el-row>
        <el-col :span="24">
          <div class="grid-content bg-purple-dark self-style">
            <span>{{ $t('locations.locationname') }}:{{ temp.locationname }}(库管:{{temp.contact}})</span>
          </div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('locations.locationname') }}:<span>{{temp.locationname}}</span></div>
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('locations.tel') }}:<span>{{temp.tel}}</span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('locations.fax') }}:
          <span>
            {{ temp.fax}}
          </span>
        </div></el-col>               
      </el-row>
      <el-row>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('locations.deladd1') }}:<span>{{temp.deladd1}}</span></div>
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('locations.taxprovinceid') }}:<span>{{temp.taxprovinceid }}</span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('locations.internalrequest') }}:
          <span>
            {{ internalrequestStatus[temp.internalrequest]}}
          </span>
        </div></el-col>               
      </el-row>
      <el-row>
        <el-col :span="12"><div class="grid-content bg-purple self-style">
          {{ $t('locations.cashsalecustomer') }}:<span>{{ temp.cashsalecustomer }}</span></div>
        </el-col>
        <el-col :span="12"><div class="grid-content bg-purple self-style">
          {{ $t('locations.cashsalebranch') }}:<span>{{ temp.cashsalebranch }}</span>
        </div></el-col>               
      </el-row>
    </el-dialog>
  </div>
</template>
<script>
  import { getLocationsList, createLocations, updateLocations, deleteLocations,} from '@/api/locations'
  import { taxProvincesAll } from '@/api/taxProvinces'
  import { debtorsMasterAll } from '@/api/debtorsMaster'
  import { custbranchAll } from '@/api/custbranch'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isTelephone, isFax } from '@/utils/validate'
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

const validateTelephone = (rule, value, callback) => {
  if (!isTelephone(value)) {
    callback(new Error('请输入正确格式手机号'))
  } else {
    callback()
  }
}
const validateFax = (rule, value, callback) => {
  // console.log(!value)
  if(value){
      if (!isFax(value)) {
      callback(new Error('请输入正确格式传真号'))
    } else {
      callback()
    }
  }else{
    callback()
  }
  
}

export default {

  name: 'locations',
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
      internalrequestStatus:['否', '是'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        locationname: '金牌辅助',
        tel: '13731080174',
        fax: undefined,
        contact: '',
        deladd1: '',
        taxprovinceid: 1,
        internalrequest: 1,
        // belongs_to_custbranch: {brname: ''},
      },
      dialogFormVisible: false,
      dialogInfoVisible: false,
      cashDisabled: true,
      setGroupTax: '',
      dialogStatus: '',
      textMap: {
        update: '编辑授权',
        create: '新增授权'
      },
      pvData: [],
      taxProvincesAll: [],
      debtorsMasterAll: [],
      custbranchAll: [],
      rules: {
        locationname: [        
          { required: true, message: '请输入名称', trigger: 'blur' },
        ],
        contact: [        
          { required: true, message: '请输入保管员', trigger: 'blur' },
        ],
        deladd1: [        
          { required: true, message: '请输入交货地址', trigger: 'blur' },
        ],
        taxprovinceid: [        
          { required: true, message: '请选择纳税区域', trigger: 'blur' },
        ],
        cashsalecustomer: [        
          { required: true, message: '请选择默认客户', trigger: 'blur' },
        ],
        cashsalebranch: [        
          { required: true, message: '请选择客户分公司', trigger: 'blur' },
        ],
        tel: [
          { required: true, message: '请输入有效手机号', trigger: 'blur' }, 
          { validator: validateTelephone, trigger: 'blur' }     
        ],
        fax: [ 
          { validator: validateFax, trigger: 'blur' }     
        ],
        email: [{
              required: true,//是否必填
              message: '请输入邮箱地址',//错误提示信息
              trigger: 'blur'//检验方式（blur为鼠标点击其他地方，）
          },
          {
              type: 'email',//要检验的类型（number，email，date等）
              message: '请输入正确的邮箱地址',
              trigger: ['blur', 'change'] //（change为检验的字符变化的时候）
          }
        ],
        
      },
    }
  },
  created() {
    // this.getList()
    Promise.all([
      this.getList(),
      this.getAllTaxProvinces(),
      this.getAllDebtorsMaster(),
      // this.getAllCustbranch(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getLocationsList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllTaxProvinces(){
      taxProvincesAll().then(response => {
        this.taxProvincesAll = response.data
      })
    },
    getAllDebtorsMaster(){
      debtorsMasterAll().then(response => {
        this.debtorsMasterAll = response.data
      })
    },
    getAllCustbranch(event){
      // console.log(event)
      const list = { 'deb_id':event}
      custbranchAll(list).then(response => {
        console.log(response.data)
        if(response.data.length == 0){
          this.$notify({
              title: '失败',
              message: '该客户尚无分公司信息',
              type: 'warning',
              duration: 2000
            })
          this.custbranchAll = []
          this.temp.cashsalebranch = null
          this.cashDisabled  = true
        }else{
          this.custbranchAll = response.data
          this.cashDisabled  = false
        }
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
        deleteLocations(this.temp).then((response) => {
          // console.log(response.data);
          if(!response.data.status){
            this.$notify({
              title: '失败',
              message: response.data.message,
              type: 'warning',
              duration: 0
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
        locationname: '金牌辅助',
        tel: '13731080174',
        fax: undefined,
        contact: '',
        deladd1: '',
        taxprovinceid: 1,
        internalrequest: 1,
      }
    },
    handleCreate() {
      this.resetTemp()
      // return false
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          console.log(this.temp)
          createLocations(this.temp).then((response) => {
            console.log(response.data);
            const response_data = response.data
            if(response_data.status){
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
      this.getAllCustbranch(row.cashsalecustomer)
      row.taxprovinceid   = parseInt(row.taxprovinceid)
      row.internalrequest = parseInt(row.internalrequest)
      row.cashsalecustomer = parseInt(row.cashsalecustomer)
      row.cashsalebranch = parseInt(row.cashsalebranch)
      this.temp = Object.assign({}, row) // copy obj
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.cashDisabled  = false
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {       
          const tempData = Object.assign({}, this.temp)
          updateLocations(tempData).then((response) => {
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
<style type="sass" scop>
  .el-dialog__body {
    padding: 15px 15px;
  }
  .el-dialog__header {
     padding-top: 10px; 
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
  }
  .bg-purple {
    background: #d3dce6;
  }
  .bg-purple-light {
    background: #e5e9f2;
  }
  .grid-content {
    border-radius: 4px;
    min-height: 36px;
  }
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }
  .self-style{
    text-align: -webkit-center;
    font-size: 20px;
    padding: 10px 0px;
  }
</style>