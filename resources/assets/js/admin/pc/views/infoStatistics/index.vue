<template>
  <div class="app-container">
    <div class="filter-container">
      
      <el-select style="width:100px;" clearable v-model="listQuery.netin_year" class="filter-item" placeholder="入网年">
        <el-option v-for="year in package_year" :key="year.key" :label="year.year" :value="year.year"/>
      </el-select>
      <el-select style="width:100px;" clearable v-model="listQuery.netin_month" class="filter-item" placeholder="入网月">
        <el-option v-for="month in package_month" :key="month.key" :label="month.month" :value="month.month"/>
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">统计</el-button>
    </div>
    <el-table
      v-loading="listLoading"
      :key="tableKey"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;">
      <el-table-column :label="$t('infoStatistics.name')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.nick_name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('infoStatistics.info_nums_all')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.info_nums_num }}|{{ scope.row.info_nums_old }}(小计:{{scope.row.info_nums_all}})</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('infoStatistics.side_nums_all')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.side_nums_num }}|{{ scope.row.side_nums_old }}(小计:{{scope.row.side_nums_all}})</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('infoStatistics.subtotal')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ (scope.row.info_nums_num)+(scope.row.side_nums_num)}}
          |
          {{ (scope.row.info_nums_old)+(scope.row.side_nums_old) }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('infoStatistics.total')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ (scope.row.info_nums_all)+(scope.row.side_nums_all)}}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('infoStatistics.netin')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.netin }}</span>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import { infoStatistics} from '@/api/infoSelf'

import waves from '@/directive/waves' // 水波纹指令
import { package_year ,package_month,}  from '@/config.js'

export default {
  name: 'InfoStatistics',
  directives: {
    waves
  },
  filters: {},
  data() {  
    return {
      temp:{},
      tableKey: 0,
      list: null,
      listLoading: true,
      listQuery: {
        netin_month: new Date().getMonth()+1,
        netin_year: new Date().getFullYear(),
      },
      package_year:package_year,
      package_month:package_month,
    }
  }, 
  created() {
    Promise.all([
      this.getList(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      // console.log(this.listQuery)
      infoStatistics(this.listQuery).then(response => {
        this.list = response.data
        console.log(response.data)
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },  
    handleFilter() {
      this.getList()
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