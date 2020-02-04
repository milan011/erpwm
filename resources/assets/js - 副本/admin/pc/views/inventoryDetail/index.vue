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
      <el-select  style="width:100px;" :placeholder="$t('info.status')" clearable v-model="listQuery.inventory_type" class="filter-item">
        <el-option v-for="(item, key, index) in typeStatusMap" :key="item" :label="item" :value="key"/>
      </el-select>
      <el-select  clearable style="width:100px;" v-model="listQuery.creater_id" class="filter-item" filterable placeholder="销售">
        <el-option v-for="user in userList" :key="user.id" :label="user.nick_name" :value="user.id"/>
      </el-select>
      <el-select  clearable style="width:100px;" v-model="listQuery.goods_id" class="filter-item" filterable placeholder="礼品">
        <el-option v-for="goods in goodsList" :key="goods.id" :label="goods.name" :value="goods.id"/>
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">{{ $t('table.search') }}</el-button>
    </div>
    <el-table
      v-loading="listLoading"
      :key="tableKey"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;">
      <el-table-column :label="$t('table.id')" width="80%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('inventoryDetail.inventory_code')" align="center">
        <template slot-scope="scope">
          <span>{{scope.row.inventory_code}}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('inventoryDetail.serviceName')" align="center">
        <template slot-scope="scope">
          <span>{{scope.row.belongs_to_service_detail.name}}</span>
          <span v-if="scope.row.inventory_type == 2" >[{{scope.row.belongs_to_service_detail.id}}]</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('inventoryDetail.goodsName')"  show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{scope.row.belongs_to_goods.name}}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('inventoryDetail.type')" width="80%" align="center">
        <template slot-scope="scope">
          <el-tag :type="scope.row.inventory_type | inventoryTypeFilter">{{ typeStatusMap[scope.row.inventory_type] }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('inventoryDetail.inventory_num')" width="50%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.goods_nums }}</span>
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
      <el-table-column :label="$t('table.actions')" align="center" width="90%" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="success" size="mini" @click="handleShow(scope.row)">{{ $t('table.show') }}</el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next"  @current-change="handleCurrentChange"/>
    </div>
    <el-dialog :visible.sync="dialogInfoVisible">
      <el-row>
        <el-col :span="8"><div class="grid-content bg-purple self-style">
          {{ $t('inventoryDetail.goodsName') }}:<span>{{ temp.belongs_to_goods.name }}</span></div>
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple-light self-style">
          <span>{{typeStatusMap[temp.inventory_type]}}</span>
        </div></el-col>
        <el-col :span="8"><div class="grid-content bg-purple-light self-style">
          {{ $t('inventoryDetail.inventory_num') }}:<span>{{temp.goods_nums}}</span>
        </div></el-col>   
      </el-row>
      <div v-if="temp.inventory_type == 2">
        <el-row >
          <el-col :span="24">
            <div class="grid-content bg-purple self-style">
              {{ $t('serviceDetail.serviceName') }}:<span>{{temp.belongs_to_service_detail.name}}</span>
            </div>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12"><div class="grid-content bg-purple-light self-style">
            {{ $t('serviceDetail.customer') }}:<span>{{temp.belongs_to_service_detail.customer}}</span>
          </div></el-col> 
          <el-col :span="12"><div class="grid-content bg-purple-light self-style">
            {{ $t('serviceDetail.customer_telephone') }}:<span>{{temp.belongs_to_service_detail.customer_telephone}}</span>
          </div></el-col> 
        </el-row>
        <el-row>
          <el-col :span="6"><div class="grid-content bg-purple self-style">
            {{ $t('serviceDetail.charge_price') }}:<span>{{temp.belongs_to_service_detail.charge_price}}</span></div>
          </el-col>
          <el-col :span="6"><div class="grid-content bg-purple-light self-style">
            {{ $t('serviceDetail.goods_num') }}:<span>{{temp.belongs_to_service_detail.goods_num}}</span>
          </div></el-col>
          <el-col :span="6"><div class="grid-content bg-purple self-style">
            {{ $t('serviceDetail.goods_cost') }}:<span>{{temp.belongs_to_service_detail.goods_cost}}</span></div>
          </el-col>
          <el-col :span="6"><div class="grid-content bg-purple-light self-style">
            {{ $t('serviceDetail.inventory_percentage') }}:<span>{{temp.belongs_to_service_detail.inventory_percentage}}元</span>
          </div></el-col>
        </el-row>
        <el-row>
          <el-col :span="6"><div class="grid-content bg-purple self-style">
            {{ $t('serviceDetail.inventory_profit') }}:<span>{{temp.belongs_to_service_detail.inventory_profit}}</span></div>
          </el-col>
          <el-col :span="6"><div class="grid-content bg-purple self-style">
            {{ $t('serviceDetail.inventer_ticheng') }}:<span>{{temp.belongs_to_service_detail.inventer_ticheng}}</span></div>
          </el-col>
          <el-col :span="6"><div class="grid-content bg-purple self-style">
            {{ $t('table.date') }}:<span>{{temp.created_at | parseTime('{y}-{m}-{d}') }}</span>
          </div></el-col> 
          <el-col :span="6">
            <div class="grid-content bg-purple self-style">
              {{ $t('serviceDetail.remark') }}:
              <span>
                {{temp.belongs_to_service_detail.remark}}
              </span>
            </div>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24"><div class="grid-content bg-purple-dark self-style"><span>礼品列表</span></div></el-col>
        </el-row>
        <el-row  
          v-for="(goods, group_index) in temp.belongs_to_service_detail.has_many_service_detail_goods" 
          :key="goods.key">
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
      </div>
    </el-dialog>
  </div>
</template>

<script>

import { inventoryDetailList, createInventoryDetail, getInventoryDetail, updateInventoryDetail, deleteInventoryDetail } from '@/api/inventoryDetail'
import { userAll, } from '@/api/user'
import { goodsAll, } from '@/api/goods'
import waves from '@/directive/waves' // 水波纹指令
import { parseTime } from '@/utils'

const calendarTypeOptions = [
  { key: 'web', display_name: 'web' },
  { key: 'api', display_name: 'api' },
]

//出/入库
const chuRuKu = {
  1: '入库',
  2: '出库',
};

// arr to obj ,such as { CN : "China", US : "USA" }
const calendarTypeKeyValue = calendarTypeOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'inventoryDetailList',
  directives: {
    waves
  },
  filters: {
    inventoryTypeFilter(status) {
       if(status == '2'){
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
    return {
      tableKey: 0,
      list: null,
      total: null,
      listLoading: true,
      listQuery: {
        page: 1,
        selectDate: '',
        creater_id: '',
        inventory_type: '',
        goods_id: '',
        withNoPage: false,
      },
      userList: [],
      goodsList: [],
      calendarTypeOptions,
      temp: { 
        belongs_to_goods: {},
        belongs_to_service_detail: {
          has_many_service_detail_goods: {},
        },
      },
      typeStatusMap: chuRuKu,
      dialogInfoVisible: false,
      dialogStatus: '',
    }
  },
  created() {
    Promise.all([
      this.getList(),
      this.getUserList(),
      this.getGoodsList(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      inventoryDetailList(this.listQuery).then(response => {
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
    getGoodsList() {
      goodsAll().then(response => {
        // console.log(response.data)
        // return false
        this.goodsList = response.data.data
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      console.log(this.listQuery)
      // return false
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
    handleShow(row) {
      this.temp = Object.assign({}, row) // copy obj
      console.log(this.temp)
      this.dialogInfoVisible = true   
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