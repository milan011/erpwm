<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('taxGroups.taxgroupid')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.taxgroupid }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('taxGroups.taxgroupdescription')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.taxgroupdescription }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" show-overflow-tooltip class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button type="success" size="mini" @click="handleSetTax(scope.row)">{{ $t('taxGroups.setGroupTax') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next" @current-change="handleCurrentChange" />
    </div>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="100px" style="width: 400px;margin:0px auto;">
        <el-form-item :label="$t('taxGroups.taxgroupdescription')" prop="taxgroupdescription">
          <el-input v-model="temp.taxgroupdescription" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <el-dialog :title="setGroupTax" :visible.sync="setGroupTaxVisible">
      <el-form :inline="true" v-for="(tax, group_index) in taxAuthoritiesList" :key="group_index" :model="tax" class="demo-form-inline">
        <el-form-item label="税种:">
          <span>{{ tax.description }}</span>
        </el-form-item>
        <el-form-item label="计算顺序:" >
          <el-input-number 
            v-model="tax.calculationorder" 
            size="mini"  
            :min="0" 
            :max="5" 
            style="width: 90px;"
            label="计算顺序">
          </el-input-number>
        </el-form-item>
        <el-form-item label="之前税种:">
          <el-select 
            style="width: 90px;" 
            v-model="tax.taxontax" 
            class="filter-item" 
            placeholder="之前税种">
            <el-option v-for="(status, status_index) in tanxontaxStatus" :key="status_index" :label="status" :value="status_index"/>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button-group v-if="tax.assigned">
            <el-button 
            size="mini" 
            type="danger"   
            @click="taxGroupDelete(tax)">
              删除
            </el-button>
            <el-button 
            size="mini" 
            type="success" 
            @click="taxGroupUpdate(tax)">
              修改
            </el-button>
          </el-button-group>
          <el-button-group v-else>
            <el-button size="mini" type="success" @click="taxGroupAdd(tax)">分配</el-button>
          </el-button-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="setGroupTaxVisible = false">{{ $t('table.confirm') }}</el-button>
      </div>
  </el-dialog>
  </div>
</template>
<script>
  import { getTaxGroupsList, getTaxGroupAuthorities, createTaxGroups, updateTaxGroups, deleteTaxGroups,  getTaxGroups, setTaxGroupAuthorities} from '@/api/taxGroups'
  import {taxAuthoritiesAll} from '@/api/taxAuthorities'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isEmpty } from '@/common.js'
  // import SwitchRoles from './components/RolePermission'

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
  name: 'taxGroups',
  // components: { SwitchRoles },
  directives: {
    waves
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger'
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
      },
      tanxontaxStatus:['否', '是'],
      taxGroupTemp: {
          id: null,
          permissions:[],
      },
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        taxgroupid: undefined,
        taxgroupdescription: '',
      },
      dialogFormVisible: false,
      setGroupTaxVisible: false,
      setGroupTax: '',
      dialogStatus: '',
      textMap: {
        update: '编辑税收组',
        create: '新增税收组'
      },
      pvData: [],
      rules: {
        taxgroupdescription: [{ required: true, message: '请输入名称', trigger: 'blur' }],
      },
      taxAuthoritiesList: []
    }
  },
  created() {
    // this.getList()
    Promise.all([
      this.getList(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getTaxGroupsList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    handleSetTax(row){
      getTaxGroupAuthorities(row).then(response => {
        console.log(response.data)
        this.taxAuthoritiesList = response.data
        setTimeout(() => {
          this.setGroupTax = '税目组:' + row.taxgroupdescription
          this.setGroupTaxVisible = true
        }, 0.5 * 1000)
      })
      
    },
    taxGroupAdd(tax){
      tax.del = 'add'
      setTaxGroupAuthorities(tax).then(response => {
        if(!response.data.status){
          this.$notify({
            title: '失败',
            message: response.data.message,
            type: 'warning',
            duration: 2000
          })
        }else{
          this.$notify({
            title: '成功',
            message: response.data.message,
            type: 'success',
            duration: 2000
          })
        }
        this.setGroupTaxVisible = false
      })
    },
    taxGroupUpdate(tax){
      tax.del = 'update'
      setTaxGroupAuthorities(tax).then(response => {
        if(!response.data.status){
          this.$notify({
            title: '失败',
            message: response.data.message,
            type: 'warning',
            duration: 2000
          })
        }else{
          this.$notify({
            title: '成功',
            message: response.data.message,
            type: 'success',
            duration: 2000
          })
        }
        this.setGroupTaxVisible = false
      })
    },
    taxGroupDelete(tax){
      tax.del = 'delete'
      setTaxGroupAuthorities(tax).then(response => {
        if(!response.data.status){
          this.$notify({
            title: '失败',
            message: response.data.message,
            type: 'warning',
            duration: 2000
          })
        }else{
          this.$notify({
            title: '成功',
            message: response.data.message,
            type: 'success',
            duration: 2000
          })
        }
        this.setGroupTaxVisible = false
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
        deleteTaxGroups(this.temp).then((response) => {
          // console.log(response.data);
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
        taxgroupid: undefined,
        taxgroupdescription: '',
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
          createTaxGroups(this.temp).then((response) => {
            console.log(response.data);
            const response_data = response.data
            if(response_data.status){
              this.temp.taxgroupid = response_data.data.taxgroupid
              this.list.unshift(response_data.data)
              this.dialogFormVisible = false
              this.$notify({
                title: '成功',
                message: response_data.message,
                type: 'success',
                duration: 2000
              })
            }else{
              // this.dialogFormVisible = false
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
          updateTaxGroups(tempData).then((response) => {
            console.log(response.data)
            const response_data = response.data
            if(response_data.status){
              for (const v of this.list) {
                if (v.taxgroupid === this.temp.taxgroupid) {
                  const index = this.list.indexOf(v)
                  this.list.splice(index, 1, this.temp)
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
              // this.dialogFormVisible = false
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
  } */
</style>