<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input 
        :placeholder="$t('fixedAssetLocation.locationdescription')"
        clearable 
        v-model="listQuery.locationdescription"
        style="width: 150px;" 
        class="filter-item">
      </el-input>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>
      <!-- <el-tooltip class="item" effect="dark" content="注意:默认只导出当月信息,如需导出其他月,请选择筛选条件" placement="top">
        <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">{{ $t('table.export') }}</el-button>
      </el-tooltip> -->
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('fixedAssetLocation.id')" width="60%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('fixedAssetLocation.locationdescription')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.locationdescription }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('fixedAssetLocation.parentlocationid')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_fixed_asset_location.locationdescription }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="230%" class-name="small-padding fixed-width">
        <template slot-scope="scope">
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
        <el-form-item :label="$t('fixedAssetLocation.locationdescription')" prop="locationdescription">
          <el-input v-model="temp.locationdescription" />
        </el-form-item>
        <el-form-item :label="$t('fixedAssetLocation.parentlocationid')" prop="parentlocationid">
          <el-select 
            v-model="temp.parentlocationid" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入仓库">
            <el-option v-for="location in locationList" v-if="temp.id != location.id" :key="location.id" :label="location.locationdescription" :value="location.id"/>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <!-- 组件 -->
    <!-- <fixedAssetLocation-components ref="fixedAssetLocationChild"></fixedAssetLocation-components>  -->
  </div>
</template>
<script>
  import { getFixedAssetLocationList, fixedAssetLocationAll, createFixedAssetLocation, updateFixedAssetLocation, deleteFixedAssetLocation} from '@/api/fixedAssetLocation'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isEmpty } from '@/common.js'
  // import FixedAssetLocationComponents from './components/FixedAssetLocationComponents'

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
  name: 'fixedAssetLocation',
  // components: { FixedAssetLocationComponents },
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
        locationdescription: '',
      },
      cancreateStatus:['否', '是'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        parentlocationid: null,
        locationdescription: '',
      },
      dialogFormVisible: false,
      setRateVisible: false,
      fixedAssetLocationName: '',
      dialogStatus: '',
      textMap: {
        update: '编辑固定资产地点',
        create: '新增固定资产地点'
      },
      pvData: [],
      rules: {
        locationdescription: [
          { required: true, message: '请输入名称', trigger: 'blur' },
          { min: 1,  message: '请输入至少一个字符', trigger: 'blur'},
        ],
      },
      locationList: [],
    }
  },
  created() {
    Promise.all([
      this.getList(),
      this.getAllLocation(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getFixedAssetLocationList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllLocation(){
      fixedAssetLocationAll().then(response => {
        this.locationList = response.data
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
        deleteFixedAssetLocation(this.temp).then((response) => {
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
        id: undefined,
        parentlocationid: null,
        locationdescription: '',
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
          createFixedAssetLocation(this.temp).then((response) => {
            const response_data = response.data
            if(response_data.status){
              this.temp.id = response_data.data.id
              this.list.unshift(response_data.data)
              this.locationList.unshift(response_data.data)
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
      row.parentlocationid = parseInt(row.parentlocationid)
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
          updateFixedAssetLocation(tempData).then((response) => {
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
      this.$refs.fixedAssetLocationChild.handleStockCategory(row) 
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