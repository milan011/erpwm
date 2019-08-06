<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-current-row style="width: 100%;">
      <!-- <el-table-column :label="$t('mRPCalendar.id')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('mRPCalendar.calendardate')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.calendardate }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('mRPCalendar.daynumber')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.daynumber }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('mRPCalendar.manufacturingflag')" align="center">
        <template slot-scope="scope">
          <span>
            <el-tag :type="scope.row.manufacturingflag | statusFilter">
            {{ manufacturingflagStatus[scope.row.manufacturingflag] }}
            </el-tag>
          </span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('table.actions')" align="center" show-overflow-tooltip class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column> -->
    </el-table>
    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :total="total" background layout="total, prev, pager, next" :page-size="10" @current-change="handleCurrentChange" />
    </div>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="100px" style="width: 400px;">
        <el-form-item label="开始日期" prop="startDate">
          <el-date-picker
              v-model="temp.startDate"
              type="date"
              value-format="yyyy-MM-dd"
              placeholder="开始日期"
              :picker-options="pickerOptionsStart">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="结束日期" prop="endDate">
          <el-date-picker
              v-model="temp.endDate"
              type="date"
              value-format="yyyy-MM-dd"
              placeholder="结束日期"
              :picker-options="pickerOptionsEnd">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="不包含">
          <el-checkbox-group v-model="temp.checkList">
            <el-checkbox border size="medium" v-for="week in weekList" :label="week.key" :key="week.key" :value="week.key">
              {{week.day}}
            </el-checkbox>
          </el-checkbox-group>
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
  import { mRPCalendarAll, createMRPCalendar} from '@/api/mRPCalendar'
import waves from '@/directive/waves' // 水波纹指令
import { parseTime } from '@/utils'
import { weekDay }  from '@/config'
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
  name: 'mRPCalendar',
  // components: { SwitchRoles },
  directives: {
    waves
  },
  filters: {
    statusFilter(status) {
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
        startDate: '',
        endDate: '',
        checkList: [],
      },
      manufacturingflagStatus: ['否', '是'],
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑MRC日历',
        create: '新增MRC日历'
      },
      weekList: weekDay,
      checkList: [],
      pickerOptionsStart: {
        disabledDate: time => {
          // 可通过箭头函数的方式访问到this
          /*let curDate = new Date(new Date().toLocaleDateString()).getTime();
          let three = 90 * 24 * 3600 * 1000;
          let threeMonths = curDate + three;
          return time.getTime() < Date.now() - 8.64e7 || time.getTime() > threeMonths;*/
          let curDate = new Date(Date.now()) 
          let threeMonths = curDate.setDate(curDate.getDate() + 90)
          // return time.getTime() < Date.now() - 8.64e7;
          //不能选择当天之前和之后90天的日期
          return time.getTime() < Date.now() ||  time.getTime() > threeMonths  
        }
      },
      pickerOptionsEnd: {
        disabledDate: time => {
          /*let curDate = new Date(new Date().toLocaleDateString()).getTime();
          let three = 90 * 24 * 3600 * 1000;
          let threeMonths = curDate + three;
          return time.getTime() < Date.now() - 8.64e7 || time.getTime() < this.startDate || time.getTime() > threeMonths*/
          // return time.getTime() < Date.now() - 8.64e7 || time.getTime() < new Date(this.startDate).getTime()- 8.64e7;
          let startDate = new Date(this.temp.startDate).getTime()    //获取开始时间的时间戳       
          let day = new Date(startDate)     
          let currDay = day.setDate(day.getDate() - 1)    //获取开始时间的前一天
          let curDate = new Date(Date.now()) 
          let threeMonths = curDate.setDate(curDate.getDate() + 90)
          if (startDate) {         
            //不能选择开始时间之前的日期及当天之后的日期
            return (time.getTime() < currDay || time.getTime() > threeMonths)          
          } else {        
            //不能选择当天之后的日期
            return time.getTime() < Date.now() || time.getTime() > threeMonths            
          }
        }
      },
      pvData: [],
      rules: {
        startDate: [{ required: true, message: '请选择日期', trigger: 'blur' }],
        endDate: [{ required: true, message: '请选择日期', trigger: 'blur' }],
      },
    }
  },
  created() {
    // this.getList()
    Promise.all([
      this.getList(),
    ])
  },
  methods: {
    /*getList() {
      this.listLoading = true
      getMRPCalendarList(this.listQuery).then(response => {
        // console.log(response.data)
        this.list = response.data.data
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },*/
    getList() {
      this.listLoading = true
      mRPCalendarAll().then(response => {
        // console.log(response.data)
        this.list = response.data
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
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
        deleteMRPCalendar(this.temp).then((response) => {
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
        startDate: '',
        endDate: '',
        checkList: [],
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
          createMRPCalendar(this.temp).then((response) => {
            console.log(response.data);
            const response_data = response.data
            if(response_data.status){
              // this.list.unshift(response_data.data)
              this.getList()
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
          updateMRPCalendar(tempData).then((response) => {
            console.log(response.data)
            const response_data = response.data
            if(response_data.status){
              for (const v of this.list) {
                if (v.taxprovinceid === this.temp.taxprovinceid) {
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
  .el-checkbox {
    margin-left: 10px;
  }
</style>