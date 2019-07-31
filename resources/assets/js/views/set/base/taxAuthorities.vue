<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <el-table-column :label="$t('taxAuthorities.taxid')" width="60%" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.taxid }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('taxAuthorities.description')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.description }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('taxAuthorities.taxglcode')" width="120%" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_by_taxglcode.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('taxAuthorities.purchtaxglaccount')" width="120%" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_by_purchtaxglaccount.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('taxAuthorities.bank')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.bank }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('taxAuthorities.bankacctype')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.bankacctype }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('taxAuthorities.bankacc')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.bankacc }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('taxAuthorities.bankswift')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.bankswift }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="230%" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button type="success" size="mini" @click="handleSetRate(scope.row)">{{ $t('taxAuthorities.setTaxRate') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next" @current-change="handleCurrentChange" />
    </div>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px" style="width: 400px;margin:0px auto;">
        <el-form-item :label="$t('taxAuthorities.description')" prop="description">
          <el-input v-model="temp.description" />
        </el-form-item>
        <el-form-item :label="$t('taxAuthorities.taxglcode')" prop="taxglcode">
          <el-select 
            v-model="temp.taxglcode" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 0" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('taxAuthorities.purchtaxglaccount')" prop="purchtaxglaccount">
          <el-select 
            v-model="temp.purchtaxglaccount" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 0" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('taxAuthorities.bank')" prop="bank">
          <el-input v-model="temp.bank" />
        </el-form-item>
        <el-form-item :label="$t('taxAuthorities.bankacctype')" prop="bankacctype">
          <el-input v-model="temp.bankacctype" />
        </el-form-item>
        <el-form-item :label="$t('taxAuthorities.bankacc')" prop="bankacc">
          <el-input v-model="temp.bankacc" />
        </el-form-item>
        <el-form-item :label="$t('taxAuthorities.bankswift')" prop="bankswift">
          <el-input v-model="temp.bankswift" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <el-dialog :visible.sync="setRateVisible">
      <el-row>
        <el-col :span="24">
          <div class="grid-content bg-purple-dark self-style">
            <span>{{ $t('table.edit') }}税率:{{ taxAuthoritiesName }}</span>
          </div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="8">
          <div class="grid-content bg-purple-dark self-style">
            {{ $t('taxauthrates.dispatchtaxprovince') }}
          </div>
        </el-col>
        <el-col :span="8">
          <div class="grid-content bg-purple-dark self-style">
            {{ $t('taxauthrates.taxcatid') }}
          </div>
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple-dark self-style">{{ $t('taxauthrates.taxrate') }}%</div></el-col>               
      </el-row>
      <el-form ref="rateDataForm" 
        :rules="rateRules" 
        :model="taxAuthoritiesList" 
        :inline="true"  
        class="demo-form-inline">
        <div v-for="(tax, tax_index) in taxAuthoritiesList" :key="tax_index" :model="tax">
          <el-row v-for="(t, t_index) in tax" :key="t_index" :model="t">
            <el-col :span="8"><div class="grid-content bg-purple-light self-style">
              <el-form-item>
              {{t.province_name}}
              </el-form-item>
            </div></el-col>
            <el-col :span="8"><div class="grid-content bg-purple-light self-style">
            <el-form-item>
              {{t.taxcatname}}
              </el-form-item>
            </div></el-col>
            <el-col :span="8">
              <div class="grid-content bg-purple-light self-style">
              <el-form-item>
                <el-input-number 
                  v-model='t.taxrate' 
                  size="mini"  
                  :min="0" 
                  :max="1" 
                  :precision="2"
                  :step="0.1"
                  style="width: 120px;"
                  label="税率">
                </el-input-number>
              </el-form-item>
              </div>
            </el-col>               
          </el-row>
        </div>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="setTaxRateDel">
          {{ $t('table.confirm') }}
        </el-button>
        <el-button @click="setRateVisible = false">
          {{ $t('table.cancel') }}
        </el-button>
      </div>
  </el-dialog>
  </div>
</template>
<script>
  import { getTaxAuthoritiesList,  createTaxAuthorities, updateTaxAuthorities, deleteTaxAuthorities, getTaxRates, setTaxRates} from '@/api/taxAuthorities'
  import { chartMasterAll } from '@/api/chartMaster'
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
  name: 'taxAuthorities',
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
        id: undefined,
        description: '',
        taxglcode : null,
        purchtaxglaccount : null,
        bank : '',
        bankacctype : '',
        bankacc : '',
        bankswift : '',
      },
      dialogFormVisible: false,
      setRateVisible: false,
      taxAuthoritiesName: '',
      dialogStatus: '',
      textMap: {
        update: '编辑税收管辖部门',
        create: '新增税收管辖部门'
      },
      pvData: [],
      rules: {
        description: [{ required: true, message: '请输入名称', trigger: 'blur' }],
        taxglcode: [{ required: true, message: '请选择会计科目', trigger: 'change' }],
        purchtaxglaccount: [{ required: true, message: '请选择会计科目', trigger: 'change' }],
      },
      rateRules: {
        description: [{ required: true, message: '请输入名称', trigger: 'blur' }],
        taxglcode: [{ required: true, message: '请选择会计科目', trigger: 'change' }],
        purchtaxglaccount: [{ required: true, message: '请选择会计科目', trigger: 'change' }],
      },
      taxAuthoritiesList: [],
      chartMasterList: []
    }
  },
  created() {
    // this.getList()
    Promise.all([
      this.getList(),
      this.getAllChartMasters(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getTaxAuthoritiesList(this.listQuery).then(response => {
        // console.log(response.data)
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
    handleSetRate(row){
      getTaxRates(row).then(response => {
        console.log(response.data)
        // return false
        this.taxAuthoritiesList = response.data
        this.taxAuthoritiesName = row.description
        setTimeout(() => {
          this.setRateVisible = true
        }, 0.5 * 1000)
      })
      
    },
    setTaxRateDel(){
      console.log(this.taxAuthoritiesList)
      // return false
      setTaxRates(this.taxAuthoritiesList).then(response => {
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
        this.setRateVisible = false
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
        deleteTaxAuthorities(this.temp).then((response) => {
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
        id: undefined,
        description: '一个会计科目',
        taxglcode : null,
        purchtaxglaccount : null,
        bank : '人民银行',
        bankacctype : '私人银行',
        bankacc : '3652101',
        bankswift : '5214512',
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
          createTaxAuthorities(this.temp).then((response) => {
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
      row.taxglcode = parseInt(row.taxglcode)
      row.purchtaxglaccount = parseInt(row.purchtaxglaccount)
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
          updateTaxAuthorities(tempData).then((response) => {
            console.log(response.data)
            // return false
            const response_data = response.data
            if(response_data.status){
              for (const v of this.list) {
                if (v.taxid === this.temp.taxid) {
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