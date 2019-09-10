<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-maxkgs-row style="width: 100%;">
      <el-table-column :label="$t('freightCost.shipcostfromid')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.shipcostfromid }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('freightCost.shipperid')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_shipper.shippername }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('freightCost.locationfrom')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_locations.locationname }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('freightCost.destinationcountry')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.destinationcountry }}</span>
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('freightCost.destination')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.destination }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('freightCost.cubrate')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.cubrate }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('freightCost.kgrate')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.kgrate }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('freightCost.maxcub')" width="120%" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.maxcub }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('freightCost.maxkgs')" width="120%" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            {{ scope.row.maxkgs }}
          </span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('freightCost.fixedprice')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.fixedprice }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('freightCost.minimumchg')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            {{ scope.row.minimumchg }}
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
      <el-pagination v-show="total>0" :maxkgs-page="listQuery.page" :total="total" background layout="total, prev, pager, next" @maxkgs-change="handleCurrentChange" />
    </div>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px" style="width: 400px;margin:0px auto;">
        <el-form-item :label="$t('freightCost.locationfrom')" prop="locationfrom">
          <el-select 
            v-model="temp.locationfrom" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入仓库搜索">
            <el-option v-for="location in locationList" :key="location.id" :label="location.locationname" :value="location.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('freightCost.shipperid')" prop="shipperid">
          <el-select 
            v-model="temp.shipperid" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入物流搜索">
            <el-option v-for="shipper in shipperList" :key="shipper.shipper_id" :label="shipper.shippername" :value="shipper.shipper_id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('freightCost.destination')" prop="destination">
          <el-input v-model="temp.destination" />
        </el-form-item>
        <el-form-item :label="$t('freightCost.cubrate')" prop="cubrate">
          <el-input v-model.number="temp.cubrate" />
        </el-form-item>
        <el-form-item :label="$t('freightCost.kgrate')" prop="kgrate">
          <el-input v-model.number="temp.kgrate" />
        </el-form-item>
        <el-form-item :label="$t('freightCost.maxkgs')" prop="maxkgs">
          <el-input v-model.number="temp.maxkgs" />
        </el-form-item>
        <el-form-item :label="$t('freightCost.maxcub')" prop="maxcub">
          <el-input v-model.number="temp.maxcub" />
        </el-form-item>
        <el-form-item :label="$t('freightCost.fixedprice')" prop="fixedprice">
          <el-input v-model.number="temp.fixedprice" />
        </el-form-item>
        <el-form-item :label="$t('freightCost.minimumchg')" prop="minimumchg">
          <el-input v-model.number="temp.minimumchg" />
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
  import { getFreightCostList, createFreightCost, updateFreightCost, deleteFreightCost,} from '@/api/freightCost'
  import { locationsAll } from '@/api/locations'
  import { shipperAll } from '@/api/shipper'
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

  name: 'freightCost',
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
      maxkgsStatus:['否', '是'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        shipcostfromid: undefined,
        locationfrom: '',
        shipperid: '',
        destination: undefined,
        cubrate: 0,
        kgrate: 0,
        maxkgs: 0,
        fixedprice: 0,
        maxcub: 0,
        minimumchg: 0,
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
        locationfrom: [        
          { required: true, message: '请选择仓库', trigger: 'change' },
        ],
        shipperid: [        
          { required: true, message: '请选择物流', trigger: 'change' },
        ],
        destination: [
          { required: true, message: '请输入目的地', trigger: 'change' },    
        ],
        cubrate: [ 
          { required: true, message: '请输入单价'},
          { type: 'number', message: '单价必须是数字'}     
        ],
        kgrate: [ 
          { required: true, message: '请输入单价'},
          { type: 'number', message: '单价必须是数字'}     
        ],
        maxkgs: [ 
          { required: true, message: '请输入重量上限'},
          { type: 'number', message: '重量上限必须是数字'}     
        ],
        maxcub: [ 
          { required: true, message: '请输入体积上限'},
          { type: 'number', message: '体积上限必须是数字'}     
        ],
        fixedprice: [ 
          { required: true, message: '请输入固定运费'},
          { type: 'number', message: '固定运费必须是数字'}     
        ],
        minimumchg: [ 
          { required: true, message: '请输入最低运费'},
          { type: 'number', message: '最低运费必须是数字'}     
        ],
      },
      locationList: [],
      shipperList: [],
    }
  },
  created() {
    // this.getList()
    Promise.all([
      this.getList(),
      this.getAllLocation(),
      this.getAllShipper(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getFreightCostList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllLocation(){
      locationsAll().then(response => {
        this.locationList = response.data
      })
    },
    getAllShipper(){
      shipperAll().then(response => {
        this.shipperList = response.data
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
        deleteFreightCost(this.temp).then((response) => {
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
        shipcostfromid: undefined,
        locationfrom: null,
        destination: '',
        shipperid: null,
        cubrate: 0,
        kgrate: 0,
        maxkgs: 0,
        fixedprice: 0,
        maxcub: 0,
        minimumchg: 0,
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
          console.log(this.temp)
          createFreightCost(this.temp).then((response) => {
            console.log(response.data);
            const response_data = response.data
            if(response_data.status){
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
      row.shipperid = parseInt(row.shipperid)
      row.locationfrom = parseInt(row.locationfrom)
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
          updateFreightCost(tempData).then((response) => {
            console.log(response.data)
            const response_data = response.data
            if(response_data.status){
              for (const v of this.list) {
                if (v.shipcostfromid === this.temp.shipcostfromid) {
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