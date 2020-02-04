<template>
<div>
  <el-dialog :title="stockCategory" :visible.sync="stockCategoryDialogFormVisible">
      <el-form ref="stockCategoryDForm" :model="stockCategoryTemp" label-position="left" label-width="100px">
        <el-checkbox-group v-model="checkList">
          <el-checkbox border size="medium" v-for="stock in stockCategoryList" :label="stock.id" :key="stock.id">
            {{ stock.categorydescription }}
          </el-checkbox>
        </el-checkbox-group>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="stockCategoryDialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button type="primary" @click="giveStockCategorys">{{ $t('table.confirm') }}</el-button>
      </div>
  </el-dialog> 
</div>
</template>
<script>
  import { getRoleStockCategory, giveRoleStockCategory } from '@/api/role'
  import { stockCategoryAll } from '@/api/stockCategory'
  export default {
    name: 'StockCategory',
    data() {
      return {
        stockCategoryTemp: {
          id: null,
          list:[],
        },
        stockCategory:'物料组维护',
        stockCategoryDialogFormVisible: false,
        checkList: [],
        stockCategoryList: [],
        stockCategorys: []
      };
    },
    created() {    
      Promise.all([
        this.getStockCategoryList(),
      ])
    },
    methods: {
      getStockCategoryList(){
        stockCategoryAll().then(response => {
          this.stockCategoryList = response.data
        })
      },
      handleStockCategory(row) { 
        /*row参数为点击的角色信息对象*/
        getRoleStockCategory(row).then((response) => {
          // console.log(response.data.data)
          this.checkList = response.data.data
          this.stockCategoryTemp.id = row.id
          this.stockCategoryDialogFormVisible = true
        })
      },
      /*givePermissions() {
        
        this.stockCategoryTemp.list = this.checkList
        // console.log(this.checkList)
        // console.log(this.stockCategoryTemp)
        // return false
        giveRoleStockCategory(this.stockCategoryTemp).then((response) => {
          this.stockCategoryDialogFormVisible = false
          this.$notify({
            title: '成功',
            message: '创建成功',
            type: 'success',
            duration: 2000
          })
        })
      },*/
      giveStockCategorys(){
        console.log(this.checkList)
        this.stockCategoryTemp.list = this.checkList
        giveRoleStockCategory(this.stockCategoryTemp).then((response) => {
          this.stockCategoryDialogFormVisible = false
          this.$notify({
            title: '成功',
            message: '创建成功',
            type: 'success',
            duration: 2000
          })
        })
      },
    }
  };
</script>

<style type="sass" scop>
  /* .fixed-width .el-button--mini {
    padding: 10px 3px;
    width: 70px;
    margin-left: 0px;
  } */
  .el-checkbox {
    margin-left: 10px;
  }
</style>