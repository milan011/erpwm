<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('paymentTerm.id')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('paymentTerm.termsindicator')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.termsindicator }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('paymentTerm.terms')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.terms }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('paymentTerm.paymenttype')" align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.paymenttype | statusFilter">
              {{ paymentTypeMap[scope.row.paymenttype] }}
            </el-tag>
          </span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('paymentTerm.panmentEnd')" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.paymenttype == '1'">次月{{ scope.row.dayinfollowingmonth }}日前</span>
          <span v-else>{{ scope.row.daysbeforedue }}天后</span>
        </template>
      </el-table-column>     
      <el-table-column :label="$t('table.actions')" align="center" show-overflow-tooltip class-name="small-padding fixed-width">
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
        <el-form-item :label="$t('paymentTerm.termsindicator')" prop="termsindicator">
          <el-input v-model.number="temp.termsindicator" />
        </el-form-item>
        <el-form-item :label="$t('paymentTerm.terms')" prop="terms">
          <el-input v-model="temp.terms" />
        </el-form-item>
        <el-form-item :label="$t('paymentTerm.paymenttype')">
          <el-radio-group @change="changeType" v-model="temp.paymenttype">
            <el-radio-button label="1">次月截止</el-radio-button>
            <el-radio-button label="2">N天后截止</el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-show="currentPaymentType == '1'" :label="$t('paymentTerm.dayinfollowingmonth')" prop="dayinfollowingmonth">
          <el-input v-model.number="temp.dayinfollowingmonth" />
        </el-form-item>
        <el-form-item v-show="currentPaymentType == '2'" :label="$t('paymentTerm.daysbeforedue')" prop="daysbeforedue">
          <el-input v-model.number="temp.daysbeforedue" />
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
  import { getPaymentTermList, createPaymentTerm, updatePaymentTerm, deletePaymentTerm,} from '@/api/paymentTerm'
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
  name: 'paymentTerm',
  // components: { SwitchRoles },
  directives: {
    waves
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        2: 'info',
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
      tanxontaxStatus:['否', '是'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        termsindicator: '',
        terms: '',
        daysbeforedue: '',
        dayinfollowingmonth: '',
        paymenttype: '',
      },
      currentPaymentType: '1',
      dialogFormVisible: false,
      setGroupTaxVisible: false,
      setGroupTax: '',
      dialogStatus: '',
      textMap: {
        update: '编辑付款条款',
        create: '新增付款条款'
      },
      paymentTypeMap:{1: '次月截止', 2:'N天后'},
      pvData: [],
      rules: {
        termsindicator: [        
          {type: 'number', message: '编码为数字类型', trigger: 'blur'},
          { required: true, message: '请输入编码', trigger: 'blur' },
        ],
        terms: [{ required: true, message: '请输入描述', trigger: 'blur' }],
        dayinfollowingmonth: [        
          {type: 'number',min: 1, max: 31, message: '请输入1-31数字', trigger: 'blur'},
          { required: true, message: '请输入日期', trigger: 'blur' },
        ],
        daysbeforedue:[        
          {type: 'number', min: 1, max: 300, message: '请输入1-300数字', trigger: 'blur'},
          { required: true, message: '请输入天数', trigger: 'blur' },
        ],
      },
      taxAuthoritiesList: []
    }
  },
  created() {
    // this.getList()
    Promise.all([
      this.getList(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getPaymentTermList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    changeType(val){
      this.currentPaymentType = val
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
        deletePaymentTerm(this.temp).then((response) => {
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
        termsindicator: undefined,
        terms: '',
        daysbeforedue: '',
        dayinfollowingmonth: '',
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
          createPaymentTerm(this.temp).then((response) => {
            console.log(response.data);
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
      row.daysbeforedue = parseInt(row.daysbeforedue)
      row.dayinfollowingmonth = parseInt(row.dayinfollowingmonth)
      row.termsindicator = parseInt(row.termsindicator)
      this.temp = Object.assign({}, row) // copy obj
      this.dialogStatus = 'update'
      this.currentPaymentType = this.temp.paymenttype
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {       
          const tempData = Object.assign({}, this.temp)
          updatePaymentTerm(tempData).then((response) => {
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