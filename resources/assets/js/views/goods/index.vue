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
      <el-table-column :label="$t('goods.name')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('goods.brand')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.brand }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('goods.goods_from')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.goods_from }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('goods.type')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.type }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('goods.bottom_price')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.bottom_price }}</span>
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('goods.in_price')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.in_price }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('goods.goods_spec')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.goods_spec }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('goods.goods_unit')"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.goods_unit }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('goods.is_food')" width="80%" align="center">
        <template slot-scope="scope">
          <el-tag :type="scope.row.is_food | foodStatusFilter">{{ foodStatusMap[scope.row.is_food]}}</el-tag>
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
          <el-button type="success" size="mini" @click="handleShow(scope.row)">{{ $t('table.show') }}</el-button>
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
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
        <el-form-item :label="$t('goods.name')" prop="name">
          <el-input v-model="temp.name"/>
        </el-form-item>
        <el-form-item :label="$t('goods.brand')" prop="brand">
          <el-input v-model="temp.brand"/>
        </el-form-item>
        <el-form-item :label="$t('goods.goods_from')" prop="goods_from">
          <el-input v-model="temp.goods_from"/>
        </el-form-item>
        <el-form-item :label="$t('goods.type')" prop="type">
          <el-input v-model="temp.type"/>
        </el-form-item>
        <!-- <el-form-item :label="$t('goods.bottom_price')" prop="bottom_price">
          <el-input v-model.number="temp.bottom_price"/>
        </el-form-item> -->
        <el-form-item :label="$t('goods.in_price')" prop="in_price">
          <el-input v-model.number="temp.in_price"/>
        </el-form-item> 
        <el-form-item :label="$t('goods.goods_spec')" prop="goods_spec">
          <el-input v-model="temp.goods_spec"/>
        </el-form-item>
        <el-form-item :label="$t('goods.goods_unit')" prop="goods_unit">
          <el-input v-model="temp.goods_unit"/>
        </el-form-item>
        <el-form-item :label="$t('goods.is_food')" style="width:38%">
                <el-switch
                  v-model="temp.is_food"
                  active-color="#13ce66"
                  active-value="1"
                  inactive-value="0"
                  inactive-color="#ff4949">
                </el-switch>
              </el-form-item>
        <el-form-item :label="$t('goods.remark')">
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
        <el-col :span="24"><div class="grid-content bg-purple-dark self-style"><span>商品详情</span></div></el-col>
      </el-row>
      <el-row>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('goods.name') }}:<span>{{temp.name}}</span></div>
        </el-col>
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('goods.brand') }}:<span>{{temp.brand}}</span>
        </div></el-col>
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('goods.goods_from') }}:<span>{{temp.goods_from}}</span>
        </div></el-col>
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('goods.goods_spec') }}:<span>{{temp.goods_spec}}</span>
        </div></el-col>   
      </el-row>
      <el-row>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('goods.type') }}:<span>{{temp.type}}</span></div>
        </el-col>
        <!-- <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('goods.bottom_price') }}:<span>{{temp.bottom_price}}</span>
        </div></el-col> -->
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('goods.in_price') }}:<span>{{temp.in_price}}</span>
        </div></el-col> 
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('table.date') }}:<span>{{temp.created_at | parseTime('{y}-{m}-{d}') }}</span>
        </div></el-col>    
      </el-row>
      <el-row>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('goods.is_food') }}:<span>{{ foodStatusMap[temp.is_food] }}</span></div>
        </el-col>
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('goods.remark') }}:<span>{{temp.remark}}</span>
        </div></el-col>
            
      </el-row>
    </el-dialog>
  </div>
</template>

<script>

// import { fetchList, fetchPv, createPermission, updatePermission, deletePermission } from '@/api/permission'
// import { packageList, createPackage, getPackage, updatePackage, deletePackage } from '@/api/package'
import { goodsList, createGoods, getGoods, updateGoods, deleteGoods } from '@/api/goods'
import waves from '@/directive/waves' // 水波纹指令
import { parseTime } from '@/utils'
import { foodStatus }  from '@/config.js'

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
  name: 'goodsList',
  directives: {
    waves
  },
  filters: {
    foodStatusFilter(status) {
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
      showReviewer: false,
      temp: {
        id: undefined,
        name: '',
        brand: '',
        goods_from: '',
        type: '',
        //bottom_price: '',
        in_price: '',
        goods_spec: '',
        goods_unit: '',  
        remark: ' ',
        is_food: '1'          
      },
      dialogFormVisible: false,
      foodStatusMap: foodStatus,
      dialogInfoVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑商品',
        create: '新增商品'
      },
      rules: {
        /*bottom_price: [
          { required: true, message: '请输入底价' },
          { type: 'number',  message: '价格应为数字' },
        ],*/
        in_price: [
          { required: true, message: '请输入进价' },
          { type: 'number',  message: '价格应为数字' },
        ],
        name: [{ required: true, message: '请输入名称', trigger: 'blur' }],
        brand: [{ required: true, message: '请输入品牌', trigger: 'blur' }],
        goods_from: [{ required: true, message: '请输入进货地', trigger: 'blur' }],
        type: [{ required: true, message: '请输入类别', trigger: 'blur' }],
        goods_spec: [{ required: true, message: '请输入规格', trigger: 'blur' }],
        goods_unit: [{ required: true, message: '请输入单位', trigger: 'blur' }],
      },
      downloadLoading: false
    }
  },
  created() {
    this.getGoodsList()

  },
  methods: {
    getGoodsList() {
      this.listLoading = true
      goodsList(this.listQuery).then(response => {
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
      this.getGoodsList()
    },
    handleSizeChange(val) {
      this.listQuery.limit = val
      this.getGoodsList()
    },
    handleCurrentChange(val) {
      this.listQuery.page = val
      this.getGoodsList()
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
      this.$confirm('确定要删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.temp = Object.assign({}, row)
        deleteGoods(this.temp).then((response) => {
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
      /*this.temp = {
        id: undefined,
        name: '鲁花高油酸花生油',
        brand: '鲁花',
        goods_from: '山东鲁花集团商贸有限公司石家庄分公司',
        type: '高油酸花生油',
        bottom_price: 129,
        in_price: 108,
        goods_spec: '750ml*2',
        goods_unit: '盒',  
        is_food: '1',
        remark: ' ' 
      }*/
      this.temp = {
        id: undefined,
        name: '',
        brand: '',
        goods_from: '',
        type: '',
        //bottom_price: 0,
        in_price: 0,
        goods_spec: '',
        goods_unit: '',  
        is_food: '1',
        remark: ' ' 
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
      /*console.log(this.temp)
      return false*/
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          createGoods(this.temp).then((response) => {
          //console.log(response.data.data);
          // console.log(this.temp)
          //return false
            if(response.data.status){
              let goodsData = response.data.data

              // goodsData.is_food = goodsData.is_food
              /*let newGoods = {
                id: goodsData.id,
                month_nums: goodsData.month_nums,
                package_price: goodsData.package_price,
                created_at: new Date()
              }*/
              /*this.temp.id = response.data.data.scalar.id
              this.temp.created_at = response.data.data.scalar.created_at | parseTime('{y}-{m}-{d}')*/
              this.list.unshift(goodsData)
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
        //row.bottom_price   = parseInt(row.bottom_price)
        row.in_price       = parseFloat(row.in_price)
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
            updateGoods(tempData).then((response) => {
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