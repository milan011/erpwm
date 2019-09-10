<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input 
        :placeholder="$t('fixedAssetCategorie.categorydescription')"
        clearable 
        v-model="listQuery.categorydescription"
        style="width: 150px;" 
        class="filter-item">
      </el-input>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('fixedAssetCategorie.id')" width="60%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column  :label="$t('fixedAssetCategorie.categorydescription')" show-overflow-tooltip width="80%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.categorydescription }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('fixedAssetCategorie.costact')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_costact.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('fixedAssetCategorie.depnact')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_depnact.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('fixedAssetCategorie.disposalact')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_disposalact.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column width="220%" :label="$t('fixedAssetCategorie.accumdepnact')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_accumdepnact.accountname }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('fixedAssetCategorie.cancreate')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.cancreate | statusFilter">
              {{ cancreateStatus[scope.row.cancreate] }}
            </el-tag>
          </span>
        </template>
      </el-table-column> -->
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
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="220px" style="width: 500px;margin:0px auto;">
        <el-form-item :label="$t('fixedAssetCategorie.categorydescription')" prop="categorydescription">
          <el-input v-model="temp.categorydescription" />
        </el-form-item>
        <el-form-item :label="$t('fixedAssetCategorie.costact')" prop="costact">
          <el-select 
            v-model="temp.costact" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入区域搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 0" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('fixedAssetCategorie.depnact')" prop="depnact">
          <el-select 
            v-model="temp.depnact" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入区域搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl != 0" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('fixedAssetCategorie.disposalact')" prop="disposalact">
          <el-select 
            v-model="temp.disposalact" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入区域搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl != 0" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('fixedAssetCategorie.accumdepnact')" prop="accumdepnact">
          <el-select 
            v-model="temp.accumdepnact" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入区域搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 0" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
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
    <!-- <fixedAssetCategorie-components ref="fixedAssetCategorieChild"></fixedAssetCategorie-components>  -->
  </div>
</template>
<script>
  import { getFixedAssetCategorieList,  createFixedAssetCategorie, updateFixedAssetCategorie, deleteFixedAssetCategorie} from '@/api/fixedAssetCategorie'
  import { chartMasterAll } from '@/api/chartMaster'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isEmpty } from '@/common.js'
  // import FixedAssetCategorieComponents from './components/FixedAssetCategorieComponents'

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
  name: 'fixedAssetCategorie',
  // components: { FixedAssetCategorieComponents },
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
        name: '',
        categorydescription: '',
      },
      cancreateStatus:['否', '是'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        categorydescription: '',
        costact: null,
        depnact : null,
        disposalact : null,
        accumdepnact : null,
      },
      dialogFormVisible: false,
      setRateVisible: false,
      fixedAssetCategorieName: '',
      dialogStatus: '',
      textMap: {
        update: '编辑固定资产种类',
        create: '新增固定资产种类'
      },
      pvData: [],
      rules: {
        categorydescription: [
          { required: true, message: '请输入名称', trigger: 'blur' },
          { min: 1, max: 20, message: '长度在1到20个字符', trigger: 'blur'},
        ],
        costact: [{ required: true, message: '请选择库存种类', trigger: 'change' }],
        depnact: [{ required: true, message: '请选择库存种类', trigger: 'change' }],
        disposalact: [{ required: true, message: '请选择库存种类', trigger: 'change' }],
        accumdepnact: [{ required: true, message: '请选择库存种类', trigger: 'change' }],       
      },
      chartMasterList: [],
    }
  },
  created() {
    Promise.all([
      this.getList(),
      this.getAllChartMasters(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getFixedAssetCategorieList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
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
        deleteFixedAssetCategorie(this.temp).then((response) => {
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
        categorydescription: '',
        costact: null,
        depnact : null,
        disposalact : null,
        accumdepnact : null,
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
          createFixedAssetCategorie(this.temp).then((response) => {
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
                duration: 0
              })
            }
            
          })
        }
      })
    },
    handleUpdate(row) {
      row.costact = parseInt(row.costact)
      row.depnact = parseInt(row.depnact)
      row.disposalact = parseInt(row.disposalact)
      row.accumdepnact = parseInt(row.accumdepnact)
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
          updateFixedAssetCategorie(tempData).then((response) => {
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
                duration: 0
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
      this.$refs.fixedAssetCategorieChild.handleStockCategory(row) 
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