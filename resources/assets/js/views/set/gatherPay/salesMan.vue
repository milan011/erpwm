<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('salesMan.id')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('salesMan.salesmanname')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.salesmanname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('salesMan.smantel')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.smantel }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('salesMan.smanfax')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.smanfax }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('salesMan.commissionrate1')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.commissionrate1 }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('salesMan.breakpoint')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.breakpoint }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('salesMan.commissionrate2')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.commissionrate2 }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('salesMan.current')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.current | statusFilter">
              {{ currentStatus[scope.row.current] }}
            </el-tag>
          </span>
        </template>
      </el-table-column> 
      <el-table-column :label="$t('table.actions')" width="180%" align="center" show-overflow-tooltip class-name="small-padding fixed-width">
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
        <el-form-item :label="$t('salesMan.salesmanname')" prop="salesmanname">
          <el-input v-model="temp.salesmanname" />
        </el-form-item>
        <el-form-item :label="$t('salesMan.smantel')" prop="smantel">
          <el-input v-model="temp.smantel" />
        </el-form-item>
        <el-form-item :label="$t('salesMan.smanfax')" prop="smanfax">
          <el-input v-model="temp.smanfax" />
        </el-form-item>
        <el-form-item :label="$t('salesMan.commissionrate1')" prop="commissionrate1">
          <el-input v-model="temp.commissionrate1" />
        </el-form-item>
        <el-form-item :label="$t('salesMan.breakpoint')" prop="breakpoint">
          <el-input v-model="temp.breakpoint" />
        </el-form-item>
        <el-form-item :label="$t('salesMan.commissionrate2')" prop="commissionrate1">
          <el-input v-model="temp.commissionrate2" />
        </el-form-item>
        <el-form-item :label="$t('salesMan.current')" prop="current">
          <el-select 
            v-model="temp.current" 
            class="filter-item"  
            placeholder="">
            <el-option v-for="(current, index) in currentStatus" :key="index" :label="current" :value="index"/>
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
  import { getSalesManList, createSalesMan, updateSalesMan, deleteSalesMan,} from '@/api/salesMan'
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
  console.log('电话')
  console.log(rule)
  console.log(value)
  if (!isTelephone(value)) {
    console.log('没通过')
    callback(new Error('请输入正确格式手机号'))
  } else {
    console.log('通过')
    callback()
  }
}
const validateFax = (rule, value, callback) => {
  console.log('传真')
  console.log(rule)
  console.log(value)
  if (!isFax(value)) {
    console.log('没通过')
    callback(new Error('请输入正确格式传真号'))
  } else {
    console.log('通过')
    callback()
  }
}

export default {

  name: 'salesMan',
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
      currentStatus:['否', '是'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        salesmanname: '',
        smantel: '',
        commissionrate1: '',
        smanfax: '',
        breakpoint: '',
        commissionrate2: '',
        current: '',
      },
      dialogFormVisible: false,
      setGroupTaxVisible: false,
      setGroupTax: '',
      dialogStatus: '',
      textMap: {
        update: '编辑授权',
        create: '新增授权'
      },
      pvData: [],
      rules: {
        salesmanname: [        
          { required: true, message: '请输入名称', trigger: 'blur' },
        ],
        smantel: [
          { required: true, message: '请输入有效手机号', trigger: 'blur' }, 
          { validator: validateTelephone, trigger: 'change' }     
        ],
        smanfax: [ 
          { validator: validateFax, trigger: 'blur' }     
        ],
      },

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
      getSalesManList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
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
        deleteSalesMan(this.temp).then((response) => {
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
        salesmanname: '金牌辅助',
        smantel: '13731080174',
        smanfax: '',
        commissionrate1: 0,
        breakpoint: 0,
        commissionrate2: 0,
        current: 1,
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
          createSalesMan(this.temp).then((response) => {
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
      row.current = parseInt(row.current)
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
          updateSalesMan(tempData).then((response) => {
            console.log(response.data)
            const response_data = response.data
            if(response_data.status){
              for (const v of this.list) {
                if (v.panmentid === this.temp.panmentid) {
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