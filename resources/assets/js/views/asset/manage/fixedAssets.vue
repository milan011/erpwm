<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input 
        placeholder="部分描述"
        clearable 
        v-model="listQuery.description"
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

      <el-table-column :label="$t('fixedAssets.assetid')" width="60%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.assetid }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('fixedAssets.description')"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.description }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('fixedAssets.assetlocation')"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_fixed_asset_location.locationdescription }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('fixedAssets.assetcategoryid')"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_fixed_asset_categorie.categorydescription }}</span>
        </template>
      </el-table-column>
      
      <!-- <el-table-column :label="$t('fixedAssets.datepurchased')"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.datepurchased }}</span>
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('table.actions')" align="center" width="230%" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="success" size="mini" @click="handleShow(scope.row)">
            {{ $t('table.content') }}
          </el-button>
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
        <el-form-item :label="$t('fixedAssets.description')" prop="description">
          <el-input v-model="temp.description" />
        </el-form-item>
        <el-form-item :label="$t('fixedAssets.longdescription')" prop="longdescription">
          <el-input v-model="temp.longdescription" />
        </el-form-item>
        <el-form-item :label="$t('fixedAssets.assetcategoryid')" prop="assetcategoryid">
          <el-select 
            v-model="temp.assetcategoryid" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入种类搜索">
            <el-option v-for="cate in assetcategoryList" :key="cate.id" :label="cate.categorydescription" :value="cate.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('fixedAssets.assetlocation')" prop="assetlocation">
          <el-select 
            v-model="temp.assetlocation" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入地点搜索">
            <el-option v-for="location in assetlocationList" :key="location.id" :label="location.locationdescription" :value="location.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('fixedAssets.barcode')" prop="barcode">
          <el-input v-model="temp.barcode" />
        </el-form-item>
        <el-form-item :label="$t('fixedAssets.serialno')" prop="serialno">
          <el-input v-model="temp.serialno" />
        </el-form-item>
        <el-form-item :label="$t('fixedAssets.depntype')" prop="depntype">
          <el-select 
            v-model="temp.depntype" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入类型搜索">
            <el-option v-for="(value, key, index) in depnTypeList" :key="index" :label="value" :value="key"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('fixedAssets.depnrate')" prop="depnrate">
          <el-input-number 
            v-model='temp.depnrate'   
            :min="0" 
            :max="100" 
            :step="1"
            label="折旧率">%
          </el-input-number>
        </el-form-item>
        <!-- <el-form-item :label="$t('fixedAssets.paymenttype')">
          <el-radio-group @change="" v-model="temp.paymenttype">
            <el-radio-button label="1">次月截止</el-radio-button>
            <el-radio-button label="2">N天后截止</el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item :label="$t('fixedAssets.cancreate')">
          <el-switch
            v-model="temp.cancreate"
            active-color="#13ce66"
            inactive-color="#ff4949"
            active-value="1"
            inactive-value="0">
          </el-switch>
        </el-form-item>
           -->
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <el-dialog width="80%" :visible.sync="dialogInfoVisible">
      <el-row>
        <el-col :span="24">
          <div class="grid-content bg-purple-dark self-style">
            <span>{{ $t('fixedAssets.description') }}:{{temp.description}}</span>
          </div>
        </el-col>
      </el-row>
      <el-row>
        <el-col v-if="temp.belongs_to_fixed_asset_categorie" :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.assetcategoryid') }}:{{temp.belongs_to_fixed_asset_categorie.categorydescription}}<span></span></div>
        </el-col>
        <el-col v-if="temp.belongs_to_fixed_asset_location" :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.assetlocation') }}:{{temp.belongs_to_fixed_asset_location.locationdescription}}<span></span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.serialno') }}:{{temp.serialno}}
        </div></el-col>               
      </el-row>
      <el-row>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.barcode') }}:{{temp.barcode}}<span></span></div>
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.cost') }}:{{temp.cost}}<span></span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.accumdepn') }}:{{temp.accumdepn}}
        </div></el-col>               
      </el-row>

      <el-row>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.depnrate') }}:{{temp.depnrate}}<span></span></div>
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.disposalproceeds') }}:{{temp.disposalproceeds}}<span></span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.depntype') }}:{{depnTypeList[temp.depntype]}}
        </div></el-col>               
      </el-row>
      <el-row>
        <el-col :span="12"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.datepurchased') }}:{{temp.datepurchased}}<span></span></div>
        </el-col>
        <el-col :span="12"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.disposaldate') }}:{{temp.disposaldate}}<span></span>
        </div></el-col>            
      </el-row>
      <el-row>
        <el-col :span="24"><div class="grid-content bg-purple self-style">
          {{ $t('fixedAssets.longdescription') }}:{{temp.longdescription}}<span></span></div>
        </el-col>           
      </el-row>
    </el-dialog>
    <!-- 组件 -->
    <!-- <fixedAssets-components ref="fixedAssetsChild"></fixedAssets-components>  -->
  </div>
</template>
<script>
  import { getFixedAssetsList,  createFixedAssets, updateFixedAssets, deleteFixedAssets} from '@/api/fixedAssets'
  import { fixedAssetCategorieAll } from '@/api/fixedAssetCategorie'
  import { fixedAssetLocationAll } from '@/api/fixedAssetLocation'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { depnType, stockType } from '@/config.js'
  // import FixedAssetsComponents from './components/FixedAssetsComponents'

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
  name: 'fixedAssets',
  // components: { FixedAssetsComponents },
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
        description: '',
      },
      cancreateStatus:['否', '是'],
      depnTypeList:depnType,
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        assetid: undefined,
        description: '',
        longdescription: '',
        assetlocation : null,
        assetcategoryid : null,
        depntype : null,
        depnrate: 0,
        barcode: '',
        serialno: '',
      },
      dialogFormVisible: false,
      dialogInfoVisible: false,
      setRateVisible: false,
      fixedAssetsName: '',
      dialogStatus: '',
      textMap: {
        update: '编辑',
        create: '新增'
      },
      pvData: [],
      rules: {
        longdescription: [
          { required: true, message: '请输入资产描述', trigger: 'change' },
          { min: 1, max: 50, message: '长度在1到50个字符', trigger: 'change'},
        ],
        description: [
          { required: true, message: '请输入资产摘要', trigger: 'change' },
        ],
        assetlocation: [{ required: true, message: '请选择资产地点', trigger: 'change' }],
        assetcategoryid: [{ required: true, message: '请选择资产种类', trigger: 'change' }],
        depntype: [{ required: true, message: '请选择折旧类型', trigger: 'change' }],
        /*serialno: [
          { required: true, message: '请输入序列号', trigger: 'change' },
          { min: 1, max: 50, message: '长度在1到50个字符', trigger: 'change'},
        ],*/
        depnrate:[{required: true, message: '请填写折旧率', trigger: 'change'}],
        barcode: [
          // { required: true, message: '请输入条形码', trigger: 'change' },
          { min: 1, max: 20, message: '长度在1到50个字符', trigger: 'change'},
        ],
      },
      assetcategoryList: [],
      assetlocationList: [],
    }
  },
  created() {
    Promise.all([
      this.getList(),
      this.getAllAssetcategory(),
      this.getAllassetlocation(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getFixedAssetsList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getAllAssetcategory(){
      fixedAssetCategorieAll().then(response => {
        this.assetcategoryList = response.data
      })
    },
    getAllassetlocation(){
      fixedAssetLocationAll().then(response => {
        this.assetlocationList = response.data
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
        deleteFixedAssets(this.temp).then((response) => {
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
        assetid: undefined,
        description: '',
        longdescription: '',
        assetlocation : null,
        assetcategoryid : null,
        depntype : null,
        depnrate: 0,
        barcode: '',
        serialno: '',
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
          createFixedAssets(this.temp).then((response) => {
            const response_data = response.data
            if(response_data.status){
              this.temp.assetid = response_data.data.assetid
              this.list.unshift(response_data.data)
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
      row.assetlocation = parseInt(row.assetlocation)
      row.assetcategoryid = parseInt(row.assetcategoryid)
      // row.depntype = parseInt(row.depntype)
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
          updateFixedAssets(tempData).then((response) => {
            const response_data = response.data
            if(response_data.status){
              for (const v of this.list) {
                if (v.assetid === this.temp.assetid) {
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
      this.temp = Object.assign({}, row) // copy obj
      console.log(this.temp)
      this.dialogInfoVisible = true
    },
    handleSetChild(row){
      this.$refs.fixedAssetsChild.handleStockCategory(row) 
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