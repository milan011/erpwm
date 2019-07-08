<template>
  <div class="app-container">
    <div class="filter-container">       
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>

    <el-table
      v-loading="listLoading"
      :key="tableKey"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;">
      <el-table-column :label="$t('table.id')" width="60%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('service.name')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('service.type')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <el-tag :type="scope.row.type | serviceTypeFilter">{{ serviceTypeMap[scope.row.type] }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('service.return_price')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.return_price }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('service.return_ratio')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.return_ratio }}%</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.date')" width="150px" align="center">
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
          <!-- <el-button type="success" size="mini" @click="handleShow(scope.row)">{{ $t('table.show') }}</el-button> -->
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next"  @current-change="handleCurrentChange"/>
    </div>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible" style="min-width: 550px;" >
      <el-form 
        ref="dataForm" 
        :rules="rules" 
        :model="temp" 
        :inline="true"
        label-position="right" 
        label-width="100px" 
        style="width: 100%;">
        <el-form-item :label="$t('service.name')" prop="name">
          <el-input v-model="temp.name"/>
        </el-form-item>
        <el-form-item :label="$t('service.type')" prop="type">
          <el-select @change="typeChange" v-model="temp.type" class="filter-item">
            <el-option v-for="(item, index) in serviceType" :key="item" :label="item" :value="index"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('service.return_price')" prop="return_price">
          <el-input v-model.number="temp.return_price" :disabled="priceVisible" />
        </el-form-item>
        <el-tooltip class="item" effect="dark" content="填写100以内整数" placement="top">
        <el-form-item :label="$t('service.return_ratio')" prop="return_ratio">
          <el-input v-model.number="temp.return_ratio" :disabled="ratioVisible" placeholder="不超过100的数字" />
        </el-form-item>
        </el-tooltip>
        <el-form-item :label="$t('service.remark')">
          <el-input :autosize="{ minRows: 2, maxRows: 4}" v-model="temp.remark" type="textarea" placeholder="备注"/>
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
        <el-col :span="24"><div class="grid-content bg-purple-dark self-style"><span>业务详情</span></div></el-col>
      </el-row>
      <el-row>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('service.name') }}:<span>{{temp.name}}</span></div>
        </el-col>
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('service.type') }}:<span>{{temp.type}}</span>
        </div></el-col>
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('service.return_price') }}:<span>{{temp.return_price}}</span>
        </div></el-col>
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('service.return_ratio') }}:<span>{{temp.return_ratio}}</span>
        </div></el-col>   
      </el-row>
      <el-row>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('table.date') }}:<span>{{temp.created_at | parseTime('{y}-{m}-{d}') }}</span>
        </div></el-col>  
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('service.remark') }}:<span>{{temp.remark}}</span>
        </div></el-col>  
      </el-row>
    </el-dialog>
  </div>
</template>

<script>

// import { fetchList, fetchPv, createPermission, updatePermission, deletePermission } from '@/api/permission'
// import { packageList, createPackage, getPackage, updatePackage, deletePackage } from '@/api/package'
import { serviceList, createService, getService, updateService, deleteService } from '@/api/service'
import waves from '@/directive/waves' // 水波纹指令
import { parseTime } from '@/utils'
import { serviceReturnType }  from '@/config.js'

const calendarTypeOptions = [
  { key: 'web', display_name: 'web' },
  { key: 'api', display_name: 'api' },
]

// arr to obj ,such as { CN : "China", US : "USA" }
const calendarTypeKeyValue = calendarTypeOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'serviceList',
  directives: {
    waves
  },
  filters: {
    serviceTypeFilter(status) {
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
    const validateReturnMonthPrice = (rule, value, callback) => { /*密码确认校验*/
      console.log(value)
      return false
    };
    return {
      tableKey: 0,
      list: null,
      total: null,
      listLoading: true,
      listQuery: {
        page: 1,
      },
      calendarTypeOptions,
      serviceTypeMap: serviceReturnType,
      showReviewer: false,
      temp: {
        id: undefined,
        name: '',
        return_price: 0,
        type: '1',
        return_ratio: 0, 
        remark: ' ',        
      },
      serviceType: {
        1: '按比例',
        2: '按金额',
      },
      dialogFormVisible: false,
      dialogInfoVisible: false,
      priceVisible: false,
      ratioVisible: true,
      dialogStatus: '',
      textMap: {
        update: '编辑业务',
        create: '新增业务'
      },
      rules: {
        return_price: [
          { required: true, message: '请输入返还金额' },
          { type: 'number',  message: '价格应为数字' },
        ],
        return_ratio: [
          { required: true, message: '请输入返还比例' },
          { type: 'number',  message: '价格应为数字' },
        ],
        name: [{ required: true, message: '请输入名称', trigger: 'blur' }],
      },
      downloadLoading: false
    }
  },
  created() {
    this.getServiceList()

  },
  methods: {
    getServiceList() {
      this.listLoading = true
      serviceList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        // console.log(this.list)
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    typeChange(val){
      if(val == '1'){ //按比例
        this.priceVisible = true
        this.ratioVisible = false
      }else{
        this.priceVisible = false
        this.ratioVisible = true
      }
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getServiceList()
    },
    handleCurrentChange(val) {
      this.listQuery.page = val
      this.getServiceList()
    },
    handleModifyStatus(row, status) {
      this.$confirm('确定要删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.temp = Object.assign({}, row)
        deleteService(this.temp).then((response) => {
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
        name: '',
        return_price: 0,
        type: '2',
        return_ratio: 0, 
        remark: ' ', 
      }
      this.priceVisible = false
      this.ratioVisible = true
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
      /*console.log(this.temp)
      return false*/
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          createService(this.temp).then((response) => {
          //console.log(response.data.data);
          // console.log(this.temp)
          //return false
            if(response.data.status){
              let serviceData = response.data.data

              // serviceData.is_food = serviceData.is_food
              /*let newService = {
                id: serviceData.id,
                month_nums: serviceData.month_nums,
                package_price: serviceData.package_price,
                created_at: new Date()
              }*/
              /*this.temp.id = response.data.data.scalar.id
              this.temp.created_at = response.data.data.scalar.created_at | parseTime('{y}-{m}-{d}')*/
              this.list.unshift(serviceData)
              this.dialogFormVisible = false
              this.$notify({
                title: '成功',
                message: response.data.message,
                type: 'success',
                duration: 2000
              })
            }else{
              this.$notify.error({
                title: '失败',
                message: response.data.message,
                duration: 2000
              })
            }           
          })
        }
      })
    },
    handleShow(row) {
      // console.log(row.has_many_package_info)
      this.temp = Object.assign({}, row) // copy obj
      console.log(this.temp)
      this.dialogInfoVisible = true       
 
    },
    handleUpdate(row) {
        row.return_price   = parseInt(row.return_price)
        row.return_ratio   = parseInt(row.return_ratio)
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
            updateService(tempData).then((response) => {
              // console.log(response)
              if(response.data.status){
                for (const v of this.list) {
                  if (v.id === this.temp.id) {
                    const index = this.list.indexOf(v)
                    this.list.splice(index, 1, this.temp)
                    break
                  }
                }
                this.dialogFormVisible = false
                this.$notify({
                  title: '成功',
                  message: '更新成功',
                  type: 'success',
                  duration: 2000
                })
              }else{
                this.$notify.error({
                  title: '失败',
                  message: response.data.message,
                  duration: 2000
                })
              }              
           })      
        }
      })
    },
  }
}
</script>
<style type="sass" scop>
  /* .fixed-width .el-button--mini {
    padding: 10px 3px;
    width: 70px;
    margin-left: 0px;
  }
  .el-table--medium td, .el-table--medium th {
    padding: 7px 0;
  }  */
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