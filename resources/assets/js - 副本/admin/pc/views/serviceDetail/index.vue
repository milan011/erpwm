<template>
  <div class="app-container">
    <div class="filter-container"> 
      <el-date-picker
      v-model="listQuery.selectDate"
      type="daterange"
      range-separator="至"
      start-placeholder="开始日期"
      end-placeholder="结束日期"
      value-format="yyyy-MM-dd HH-mm-ss">
      </el-date-picker>
      <el-select v-if="isAdmin" clearable style="width:100px;" v-model="listQuery.creater_id" class="filter-item" filterable placeholder="销售">
        <el-option v-for="user in userList" :key="user.id" :label="user.nick_name" :value="user.id"/>
      </el-select>
      <el-select  clearable style="width:100px;" v-model="listQuery.service_id" class="filter-item" filterable placeholder="业务">
        <el-option v-for="ser in allService" :key="ser.id" :label="ser.name" :value="ser.id"/>
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>      
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
      <el-table-column :label="$t('table.id')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('serviceDetail.customer')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.customer }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('serviceDetail.customer_telephone')" width="120%" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.customer_telephone }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('serviceDetail.serviceName')" width="150%" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('serviceDetail.charge_price')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.charge_price }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('serviceDetail.inventory_percentage')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.inventory_percentage }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('serviceDetail.goods_num')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.goods_num }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('serviceDetail.goods_cost')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.goods_cost }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('serviceDetail.inventory_profit')" align="center">
        <template slot-scope="scope">
          <el-tag :type="scope.row.inventory_profit | profitFilter">{{ scope.row.inventory_profit }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('serviceDetail.inventer_ticheng')" align="center">
        <template slot-scope="scope">
          <el-tag :type="scope.row.inventory_profit | profitFilter">{{ scope.row.inventer_ticheng }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.date')" width="150px" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.belongs_to_creater">
            {{scope.row.belongs_to_creater.nick_name}}
            |
            {{ scope.row.created_at | parseTime('{y}-{m}-{d}') }}
          </span>
          <span v-else></span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="200" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="success" size="mini" @click="handleShow(scope.row)">{{ $t('table.show') }}</el-button>
          <!-- <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button> -->
          <el-button v-if="isAdmin" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
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
        :inline="false"
        label-position="right" 
        label-width="100px" 
        style="margin-left:0px;margin-right: 60px;">
        <div class="createPost-main-container">
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('serviceDetail.serviceName')" align="center" prop="service_id">         
                <el-select v-model="temp.service_id" class="filter-item" filterable placeholder="输入业务搜索">
                  <el-option v-for="ser in allService" :key="ser.id" :label="ser.name" :value="ser.id"/>
                </el-select> 
              </el-form-item>
            </el-col>   
            <el-col :span="12">
              <el-form-item :label="$t('serviceDetail.charge_price')" prop="charge_price">
                <el-input v-model.number="temp.charge_price"   />
              </el-form-item>
            </el-col> 
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('serviceDetail.customer')" prop="customer">
                <el-input v-model="temp.customer"  />
              </el-form-item>
            </el-col>   
            <el-col :span="12">
              <el-form-item :label="$t('serviceDetail.customer_telephone')" prop="customer_telephone">
                <el-input v-model="temp.customer_telephone"  />
              </el-form-item>
            </el-col> 
          </el-row>
          <el-row>
            <el-col :span="24">
              <el-form-item :label="$t('serviceDetail.goodsList')">
              <div style="float:left;" v-model="temp.goodsIdList" v-for="(goods, goods_index) in temp.goodsIdList" :key="goods_index" >
                <el-col :span="12">
                  <el-select style="margin-bottom:5px;" clearable v-model="goods.goodsId" class="filter-item" filterable placeholder="输入商品搜索">
                      <el-option v-for="goo in allGoods" :key="goo.id" :label="goo.name" :value="goo.id"/>
                  </el-select>
                </el-col>
                <el-col :span="9">
                  <!-- <el-input style="margin-bottom:5px;" placeholder="数量" v-model="goods.num"/> -->
                  <el-input-number 
                    v-model="goods.num" 
                    size="small" 
                    style="margin-bottom:5px;" 
                    :min="1" 
                    :max="100" 
                    label="数量">
                  </el-input-number>
                </el-col>
                <el-col :span="2">
                  <el-button 
                    v-if="goods.add" 
                    @click="goodsAdd" 
                    style="margin-left:5px;" 
                    type="success" >
                    {{ $t('serviceDetail.goodsAdd') }}
                  </el-button>
                  <el-button 
                    v-else  
                    @click="goodsRemove($event)" 
                    style="margin-left:5px;" 
                    type="danger" 
                    :dataIndex="goods_index">
                    {{ $t('serviceDetail.goodsRemove') }}
                  </el-button>
                </el-col>
              </div>
            </el-form-item>
            </el-col>
          </el-row>
          <el-row>   
            <el-col :span="12">
              <el-form-item :label="$t('serviceDetail.remark')">
                <el-input :autosize="{ minRows: 2, maxRows: 4}" v-model="temp.remark" type="textarea" placeholder="备注"/>
              </el-form-item>
            </el-col> 
          </el-row>
        </div>       
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <el-dialog  :visible.sync="dialogInfoVisible">
      <el-row>
        <el-col :span="24"><div class="grid-content bg-purple self-style">
          {{ $t('serviceDetail.serviceName') }}:<span>{{temp.name}}</span></div>
        </el-col> 
      </el-row>
      <el-row>
        <el-col :span="12"><div class="grid-content bg-purple-light self-style">
          {{ $t('serviceDetail.customer') }}:<span>{{temp.customer}}</span>
        </div></el-col> 
        <el-col :span="12"><div class="grid-content bg-purple-light self-style">
          {{ $t('serviceDetail.customer_telephone') }}:<span>{{temp.customer_telephone}}</span>
        </div></el-col> 
      </el-row>
      <el-row>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('serviceDetail.charge_price') }}:<span>{{temp.charge_price}}</span></div>
        </el-col>
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('serviceDetail.goods_num') }}:<span>{{temp.goods_num}}</span>
        </div></el-col>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('serviceDetail.goods_cost') }}:<span>{{temp.goods_cost}}</span></div>
        </el-col>
        <el-col :span="6"><div class="grid-content bg-purple-light self-style">
          {{ $t('serviceDetail.inventory_percentage') }}:<span>{{temp.inventory_percentage}}元</span>
        </div></el-col>
      </el-row>
      <el-row>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('serviceDetail.inventory_profit') }}:<span>{{temp.inventory_profit}}</span></div>
        </el-col>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('serviceDetail.inventer_ticheng') }}:<span>{{temp.inventer_ticheng}}</span></div>
        </el-col>
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('table.date') }}:<span>{{temp.created_at | parseTime('{y}-{m}-{d}') }}</span>
        </div></el-col> 
        <el-col :span="6"><div class="grid-content bg-purple self-style">
          {{ $t('serviceDetail.remark') }}:<span>{{temp.remark}}</span></div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24"><div class="grid-content bg-purple-dark self-style"><span>赠品详情</span></div></el-col>
      </el-row>
      <el-row v-for="(goods, group_index) in temp.has_many_service_detail_goods" :key="goods.key">
        <el-col :span="12" >
          <div class="grid-content bg-purple self-style">
            <span>{{goods.goods_name}}</span>
          </div>
        </el-col>
        <el-col :span="6" >
          <div class="grid-content bg-purple self-style">
            {{ $t('serviceDetail.goods_num') }}:<span>{{goods.goods_num}}</span>
          </div>
        </el-col>
        <el-col :span="6" >
          <div class="grid-content bg-purple self-style">
            <span>{{goods.goods_price}}元</span>
          </div>
        </el-col>
      </el-row>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>

import { goodsAll } from '@/api/goods'
import { userAll, } from '@/api/user'
import { serviceAll } from '@/api/service'
import { isTelephone } from '@/utils/validate'
import { serviceDetailList, createServiceDetail, getServiceDetail, updateServiceDetail, deleteServiceDetail } from '@/api/serviceDetail'
import waves from '@/directive/waves' // 水波纹指令
import { parseTime } from '@/utils'

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
  name: 'serviceDetail',
  directives: {
    waves
  },
  filters: {
    profitFilter(profit) {
      if(profit <= 0){
        return 'danger'
      }else{
        return 'success'
      }
    },
    typeFilter(type) {
      return calendarTypeKeyValue[type]
    }
  },
  data() {
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
        selectDate: '',
        creater_id: '',
        service_id: '',
        goods_id: '',
      },
      isAdmin: false,
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        serviceName: '',
        service_id: '',
        customer: '',
        customer_telephone: '',
        charge_price: 0,
        goodsIdList: [
          {goodsId:'', num: '1',  add: true}, 
        ],
        remark: '',
      },
      dialogFormVisible: false,
      dialogInfoVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑',
        create: '新增'
      },
      userList: [],
      allGoods: [],
      allService: [],
      rules: {
        charge_price: [
          { required: true, message: '请输入套餐价格', trigger: 'change' },
          { type: 'number',  message: '价格应为数字' },
        ],
        customer: [{ required: true, message: '请输入客户', trigger: 'blur' }],
        service_id: [{ required: true, message: '请选择业务', trigger: 'blur' }],
        customer_telephone: [
          { required: true, message: '请输入有效手机号', trigger: 'blur' }, 
          { validator: validateTelephone, trigger: 'change' }     
        ],
        /*return_month_price: [
          { required: true, message: '请输入金额', trigger: 'blur' }, 
        ]*/
      },
      downloadLoading: false
    }
  },
  created() {
    this.getList()
    this.isAdminOrManager()
    this.getUserList()
  },
  mounted(){
    this.getAllGoods()
    this.getAllService()
  },
  methods: {
    getList() {
      this.listLoading = true
      serviceDetailList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    getUserList() {
      userAll().then(response => {
        // console.log(response.data)
        // return false
        this.userList = response.data
      })
    },
    isAdminOrManager() {
      let userRole = this.$store.getters.roles
      if((userRole.indexOf('admin') >= 0) || (userRole.indexOf('manager') >= 0)){
        this.isAdmin = true
      }
    },
    goodsAdd(){ //添加礼品
        let newGoods = {goodsId:'', num: '1',  add: false}
        this.temp.goodsIdList.push(newGoods)
    },
    goodsRemove(event){ //删除副卡
      this.temp.goodsIdList.splice(event.currentTarget.getAttribute('dataIndex'),1)
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
    getAllGoods() {
      goodsAll().then(response => {
        /*console.log(response.data.data)
        return false*/
        this.allGoods = response.data.data
      })
    },
    getAllService() {
      serviceAll().then(response => {
        /*console.log(response.data.data)
        return false*/
        this.allService = response.data.data
      })
    },
    handleModifyStatus(row, status) {
      this.$confirm('确定要删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.temp = Object.assign({}, row)
        deleteServiceDetail(this.temp).then((response) => {
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
        serviceName: '',
        service_id: '',
        customer: '',
        customer_telephone: '',
        charge_price: 0,
        goodsIdList: [
          {goodsId:'', num: '1',  add: true}, 
        ],
        remark: '',
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
          //判断是否有重复商品
          // console.log(this.temp.goodsIdList)
          const goodsIdList = []
          Array.prototype.forEach.call(this.temp.goodsIdList, child => {
            // console.log(child.goodsId)
            goodsIdList.push(child.goodsId)
          });
          // console.log(goodsIdList)
          Array.prototype.deduplication = function () {
            const o = {},
            len = this.length
            const result = [];
            for (let i = 0; i < len; i ++) {
            if (!o[this[i]]) {
            o[this[i]] = 1
            result[result.length] = this[i]
            }
            }
            return result;
          }
          const goodsIdList2 = goodsIdList.deduplication()
          /*console.log(goodsIdList2)
          console.log(goodsIdList.length)
          console.log(goodsIdList2.length)*/
          if(goodsIdList.length != goodsIdList2.length){
            alert('您选择的赠品有重复')
            return false
          }
          
          createServiceDetail(this.temp).then((response) => {
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
      // console.log(row.has_many_serviceDetail_info)
      /*getServiceDetail(row).then((response) => {
        row.return_moon_price_list = []
        Array.prototype.forEach.call(response.data.data.has_many_serviceDetail_info, child => {
          //console.log(child.return_month)
          //console.log(child.return_price)
          let obj = {key:child.return_month,price:parseFloat(child.return_price)} 
          row.return_moon_price_list.unshift(obj)
        })  
               
      }) */ 
      this.temp = Object.assign({}, row) // copy obj
      console.log(this.temp)
      this.dialogInfoVisible = true 
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
  .el-date-editor .el-range-separator{
    padding:0px;
  }
  .filter-container .filter-item {
    margin-bottom: 5px;
  }
</style>