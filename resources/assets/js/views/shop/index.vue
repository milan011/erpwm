<template>
  <div class="app-container">
    <div class="filter-container">       
      <el-button class="filter-item" style="margin-left: 10px;"  type="primary" icon="el-icon-edit" @click="handleCreate">
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
      <el-table-column :label="$t('shop.name')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('shop.type')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ shopTypeMap[scope.row.type] }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('shop.shopCity')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_city.city_name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('shop.status')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span><el-tag :type="scope.row.status | shopStatusFilter">{{ shopStatusMap[scope.row.status] }}</el-tag></span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="230" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <!-- <el-button type="success" size="mini" @click="handleShow(scope.row)">{{ $t('table.show') }}</el-button> -->
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('shop.stopUse') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next"  @current-change="handleCurrentChange"/>
    </div>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form 
        ref="dataForm" 
        :rules="rules" 
        :model="temp" 
        :inline="true"
        label-position="right" 
        label-width="100px" 
        style="width: 100%;">
        <el-form-item :label="$t('shop.name')" prop="name">
          <el-input v-model="temp.name"/>
        </el-form-item>
        <el-form-item :label="$t('shop.type')" prop="type">
          <el-select v-model="temp.type" class="filter-item" placeholder="类别">
            <el-option v-for="(item, index) in shopTypeMap" :key="item" :label="item" :value='index'/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('shop.shopCity')">
          <el-cascader
            :options="cityList"
            v-model="temp.shopCity"
            :show-all-levels="false">
          </el-cascader>
        </el-form-item>
        <el-form-item :label="$t('shop.telephone')" prop="telephone">
          <el-input v-model="temp.telephone"/>
        </el-form-item>
        <el-form-item :label="$t('shop.address')" prop="address">
          <el-input style="width:250%;" v-model="temp.address"/>
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

// import { fetchList, fetchPv, createPermission, updatePermission, deletePermission } from '@/api/permission'
// import { packageList, createPackage, getPackage, updatePackage, deletePackage } from '@/api/package'
import { shopList, createShop, getShop, updateShop, deleteShop } from '@/api/shop'
import waves from '@/directive/waves' // 水波纹指令
import { parseTime } from '@/utils'
import { isTelephone } from '@/utils/validate'
import { shopStatus, shopType, cityHasTcl }  from '@/config.js'

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
  name: 'shopList',
  directives: {
    waves
  },
  filters: {
    shopStatusFilter(status) {
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
    }
    const validateTelephone = (rule, value, callback) => {
      if (!isTelephone(value)) {
        callback(new Error('请输入正确格式手机号'))
      } else {
        callback()
      }
    }
    return {
      tableKey: 0,
      list: null,
      total: null,
      listLoading: true,
      listQuery: {
        page: 1,
      },
      cityList: cityHasTcl,
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        name: '',
        shopCity: ['zhinan', 'daohang'],
        type: '',  
        telephone: '',       
        address: '',       
      },
      dialogFormVisible: false,
      shopStatusMap: shopStatus,
      shopTypeMap: shopType,
      dialogInfoVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑门店',
        create: '新增门店'
      },
      rules: {
        name: [{ required: true, message: '请输入名称', trigger: 'blur' }],
        shopCity: [{ required: true, message: '请选择城市', trigger: 'blur' }],
        type: [{ required: true, message: '请选择类别', trigger: 'blur' }],
        address: [{ required: true, message: '请输入地址', trigger: 'blur' }],
        telephone: [
          { required: true, message: '请输入有效手机号', trigger: 'blur' }, 
          { validator: validateTelephone, trigger: 'change' }     
        ]
      },
      downloadLoading: false
    }
  },
  created() {
    this.getShopList()

  },
  methods: {
    getShopList() {
      this.listLoading = true
      shopList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        // console.log(this.list)
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getShopList()
    },
    handleSizeChange(val) {
      this.listQuery.limit = val
      this.getShopList()
    },
    handleCurrentChange(val) {
      this.listQuery.page = val
      this.getShopList()
    },
    month_change(val){
      var list = []
      
      for (let i = 1; i <= val; i++) {
        let priceObj = {}
        priceObj.key   = i
        priceObj.price = null
        // console.log(priceObj)
        list.push(priceObj)
      }

      // console.log(list)

      this.temp.return_moon_price_list = list

    },
    handleModifyStatus(row, status) {
      this.$confirm('确定要停用该门店?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.temp = Object.assign({}, row)
        deleteShop(this.temp).then((response) => {
          // console.log(response.data);
          if(response.data.status === 0){
            this.$notify({
              title: '失败',
              message: '停用失败',
              type: 'warning',
              duration: 2000
            })
          }else{
            const index = this.list.indexOf(row)
            this.list.splice(index, 1)
            this.dialogFormVisible = false
            this.$notify({
              title: '成功',
              message: '停用成功',
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
      /*this.temp = {
        id: undefined,
        name: '一个店',
        shopCity: ['10', '138'],
        type: '1',  
        telephone: '13933814568',       
        address: '石家庄一个地方',  
      }*/
      this.temp = {
        id: undefined,
        name: '',
        shopCity: ['10', '138'],
        type: '1',  
        telephone: '',       
        address: '',  
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
      console.log(this.temp)
      return false
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          createShop(this.temp).then((response) => {
          //console.log(response.data.data);
          // console.log(this.temp)
          //return false
            if(response.data.status){
              let shopData = response.data.data

              // shopData.is_food = shopData.is_food
              /*let newShop = {
                id: shopData.id,
                month_nums: shopData.month_nums,
                package_price: shopData.package_price,
                created_at: new Date()
              }*/
              /*this.temp.id = response.data.data.scalar.id
              this.temp.created_at = response.data.data.scalar.created_at | parseTime('{y}-{m}-{d}')*/
              this.list.unshift(shopData)
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
        // console.log(row)
        this.temp = Object.assign({}, row) // copy obj
        // this.temp.shopCity = ['10', '138']
        const pid = row.belongs_to_city.pid.toString(10) 
        const id = row.belongs_to_city.id.toString(10) 
        this.temp.shopCity = [pid, id]
        // console.log(this.temp.shopCity)
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
            updateShop(tempData).then((response) => {
              console.log(response.data.data)
              if(response.data.status){
                for (const v of this.list) {
                  if (v.id === this.temp.id) {
                    const index = this.list.indexOf(v)
                    this.list.splice(index, 1, response.data.data)
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
  .el-cascader-menu{
    height: auto;
    border-right: solid 0px #e4e7ed;
  }
</style>