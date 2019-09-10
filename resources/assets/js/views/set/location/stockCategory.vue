<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table v-loading="listLoading" :key="tableKey" :data="list" border fit highlight-maxkgs-row style="width: 100%;">
      <el-table-column :label="$t('stockCategory.id')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stockCategory.categorydescription')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.categorydescription }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stockCategory.stocktype')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ stockTypeList[scope.row.stocktype] }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stockCategory.defaulttaxcatid')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_tax_categories.taxcatname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stockCategory.stockact')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_stockact.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stockCategory.wipact')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_wipact.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stockCategory.materialuseagevarac')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_materialuseagevarac.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stockCategory.adjglact')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_adjglact.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stockCategory.issueglact')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_issueglact.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stockCategory.purchpricevaract')" show-overflow-tooltip align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.belongs_to_chart_master_with_purchpricevaract.accountname }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" width="240%" align="center" show-overflow-tooltip class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="handleUpdate(scope.row)">{{ $t('table.edit') }}</el-button>
          <el-button type="success" size="mini" @click="setOrherCat(scope.row)">{{ $t('stockCategory.setOrther') }}</el-button>
          <el-button v-if="scope.row.status!='deleted'" size="mini" type="danger" @click="handleModifyStatus(scope.row,'deleted')">{{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination v-show="total>0" :maxkgs-page="listQuery.page" :total="total" background layout="total, prev, pager, next" @maxkgs-change="handleCurrentChange" />
    </div>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px" style="width: 400px;margin:0px auto;">
        <el-form-item :label="$t('stockCategory.categorydescription')" prop="categorydescription">
          <el-input v-model.number="temp.categorydescription" />
        </el-form-item>
        <el-form-item :label="$t('stockCategory.defaulttaxcatid')" prop="defaulttaxcatid">
          <el-select 
            v-model="temp.defaulttaxcatid" 
            class="filter-item" 
            filterable 
            clearable 
             placeholder="输入税目搜索">
            <el-option v-for="saleType in taxCategoriesList" :key="saleType.taxcatid" :label="saleType.taxcatname" :value="saleType.taxcatid"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('stockCategory.stocktype')" prop="stocktype">
          <el-select 
            v-model="temp.stocktype" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入类别搜索">
            <el-option v-for="(value, key, index) in stockTypeList" :key="index" :label="value" :value="key"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('stockCategory.stockact')" prop="stockact">
          <el-select 
            v-model="temp.stockact" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 0" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('stockCategory.wipact')" prop="wipact">
          <el-select 
            v-model="temp.wipact" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 0" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('stockCategory.adjglact')" prop="adjglact">
          <el-select 
            v-model="temp.adjglact" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 1" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('stockCategory.issueglact')" prop="issueglact">
          <el-select 
            v-model="temp.issueglact" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 1" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('stockCategory.purchpricevaract')" prop="purchpricevaract">
          <el-select 
            v-model="temp.purchpricevaract" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 1" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('stockCategory.materialuseagevarac')" prop="materialuseagevarac">
          <el-select 
            v-model="temp.materialuseagevarac" 
            class="filter-item" 
            filterable 
            clearable 
            placeholder="输入会计科目搜索">
            <el-option v-for="chart in chartMasterList" v-if="chart.belongs_to_account_group.pandl == 1" :key="chart.id" :label="chart.accountname" :value="chart.id"/>
          </el-select>
        </el-form-item>
        <!-- <el-form-item :label="$t('stockCategory.quantitybreak')" prop="quantitybreak">
          <el-input-number 
            v-model='temp.quantitybreak' 
            :min="0" 
            label="数量分割">
          </el-input-number>
        </el-form-item>
        <el-form-item :label="$t('stockCategory.discountrate')" prop="discountrate">
          <el-input-number 
            v-model='temp.discountrate'   
            :min="0" 
            :max="1" 
            :precision="2"
            :step="0.1"
            label="折扣率">
          </el-input-number> -->
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
    <el-dialog width="70%" title="附加信息" :visible.sync="setCatFormVisible">
      <el-row>
        <el-col :span="24">
          <div class="el-table-add-row" style="width:100%;margin-bottom:5px;" @click="add()">
            <span>+ 添加</span>
          </div>
        </el-col>
      <el-col :span="24">
       <!-- <el-table size="mini" :key="catTableKey" :data="ortherInfo" border style="width: 100%" highlight-current-row> -->
       <el-table size="mini" :key="catTableKey" :data="ortherInfo" border style="width: 100%" highlight-current-row>
          <el-table-column :label="$t('stockCategory.label')" align="center">
            <template slot-scope="scope">
              <span v-if="scope.row.isSet">
                <el-input :disabled="true" v-show="false" size="mini" placeholder="标签名称" v-model="scope.row.stkcatpropid">
                </el-input>
                <el-input size="mini" placeholder="标签名称" v-model="scope.row.label">
               </el-input>
             </span>
             <span v-else>{{scope.row.label}}</span>
            </template>
          </el-table-column>
          <el-table-column  width="120%" :label="$t('stockCategory.controltype')" align="center">
            <template slot-scope="scope">
              <span v-if="scope.row.isSet">
                <!-- <el-input size="mini" placeholder="请输入内容" v-model="scope.row.controltype">
                </el-input> -->
                <el-select 
                  v-model="scope.row.controltype" 
                  class="filter-item" >
                  <el-option v-for="(control, index) in controltypeStatus" :key="index" :label="control" :value="index"/>
                </el-select>
             </span>
             <span v-else>{{controltypeStatus[scope.row.controltype]}}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('stockCategory.defaultvalue')" align="center">
            <template slot-scope="scope">
              <span v-if="scope.row.isSet">
               <el-input size="mini" placeholder="请输入内容" v-model="scope.row.defaultvalue">
               </el-input>
             </span>
             <span v-else>{{scope.row.defaultvalue}}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('stockCategory.maximumvalue')" align="center">
            <template slot-scope="scope">
              <span v-if="scope.row.isSet">
               <el-input size="mini" placeholder="请输入内容" v-model="scope.row.maximumvalue">
               </el-input>
             </span>
             <span v-else>{{scope.row.maximumvalue}}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('stockCategory.reqatsalesorder')" align="center">
            <template slot-scope="scope">
              <span v-if="scope.row.isSet">
               <!-- <el-input size="mini" placeholder="请输入内容" v-model="scope.row.reqatsalesorder">
               </el-input> -->
               <el-select 
                  v-model="scope.row.reqatsalesorder" 
                  class="filter-item" >
                  <el-option v-for="(reqat, index) in reqatsalesorderStatus" :key="index" :label="reqat" :value="index"/>
                </el-select>
             </span>
             <span v-else>{{reqatsalesorderStatus[scope.row.reqatsalesorder]}}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('stockCategory.minimumvalue')" align="center">
            <template slot-scope="scope">
              <span v-if="scope.row.isSet">
               <el-input size="mini" placeholder="请输入内容" v-model="scope.row.minimumvalue">
               </el-input>
             </span>
             <span v-else>{{scope.row.minimumvalue}}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('stockCategory.numericvalue')" align="center">
            <template slot-scope="scope">
              <span v-if="scope.row.isSet">
                <el-select 
                  v-model="scope.row.numericvalue" 
                  class="filter-item" >
                  <el-option v-for="(numer, index) in numericvalueStatus" :key="index" :label="numer" :value="index"/>
                </el-select>
             </span>
             <span v-else>{{numericvalueStatus[scope.row.numericvalue]}}</span>
            </template>
          </el-table-column>
         <!-- <el-table-column v-for="(item,index) in master_user.columns" :key="index" :label="item.label" :prop="item.prop" :width="item.width">
           <template slot-scope="scope">
             <span v-if="scope.row.isSet">
               <el-input size="mini" placeholder="请输入内容" v-model="master_user.sel[item.prop]">
               </el-input>
             </span>
             <span v-else>{{scope.row[item.prop]}}</span>
           </template>
         </el-table-column> -->
         <el-table-column label="操作">
           <template slot-scope="scope">
             <span v-show="scope.row.isSet" class="el-tag el-tag--success el-tag--mini" style="cursor: pointer;" @click.stop="saveRow(scope.row)">
               保存
             </span>
             <span v-show="!scope.row.isSet" class="el-tag el-tag--primary el-tag--mini" style="cursor: pointer;" @click="editRow(scope.row)">
               编辑
             </span>
             <span class="el-tag el-tag--danger el-tag--mini" style="cursor: pointer;" @click="deleteRow(scope.row)">
               删除
             </span>
           </template>
         </el-table-column>
       </el-table>
     </el-col>  
   </el-row>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="setDel()">{{ $t('table.confirm') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
  import { getStockCategoryList, createStockCategory, updateStockCategory, deleteStockCategory,} from '@/api/stockCategory'
  import { getStockCatPropertiesList, createStockCatProperties, updateStockCatProperties, deleteStockCatProperties,} from '@/api/stockCatProperties'
  import { saleTypeAll } from '@/api/saleType'
  import { chartMasterAll } from '@/api/chartMaster'
  import { taxCategoriesAll } from '@/api/taxCategories'
  import waves from '@/directive/waves' // 水波纹指令
  import { parseTime } from '@/utils'
  import { isTelephone, isFax } from '@/utils/validate'
  import { isEmpty } from '@/common.js'
  import {stockType} from '@/config.js'

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

  name: 'stockCategory',
  // components: { SwitchRoles },
  directives: {
    waves
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'danger',
      }
      return statusMap[status]
    },
  },
  data() {
    return {
      tableKey: 0,
      catTableKey: 1,
      list: null,
      total: null,
      listLoading: true,
      listQuery: {
        page: 1,
      },
      controltypeStatus:['文字框', '选择框', '复选框', '日期框'],
      reqatsalesorderStatus:['否', '是'],
      numericvalueStatus:['否', '是'],
      calendarTypeOptions,
      showReviewer: false,
      temp: {
        id: undefined,
        stocktype: '',
        defaulttaxcatid: '',
        quantitybreak: undefined,
        discountrate: 0,
      },
      dialogFormVisible: false,
      setCatFormVisible: false,
      setGroupTax: '',
      dialogStatus: '',
      textMap: {
        update: '编辑折扣',
        create: '新增折扣'
      },
      pvData: [],
      rules: {
        categorydescription:[
          { required: true, message: '请填写物料组描述', trigger: 'change' }
        ],
        defaulttaxcatid: [        
          { required: true, message: '请选择默认税目', trigger: 'change' },
        ],
        stocktype: [        
          { required: true, message: '请选择凭证类型', trigger: 'change' },
        ],
        stockact: [
          { required: true, message: '请选择存货科目', trigger: 'change' },    
        ],
        adjglact: [ 
          { required: true, message: '请选择盘点差异科目', trigger: 'change'}, 
        ],
        purchpricevaract: [ 
          { required: true, message: '请选择价格差科目', trigger: 'change'}, 
        ],
        materialuseagevarac: [ 
          { required: true, message: '请选择数量差科目', trigger: 'change'}, 
        ],
        wipact: [ 
          { required: true, message: '请选择在制品科目', trigger: 'change'}, 
        ],
        issueglact: [
          { required: true, message: '请选择易耗品科目', trigger: 'change'}, 
        ],
      },
      rules2:{
        label:[{required: true, message: '请填写标签', trigger: 'change'}],
      },
      stockTypeList: stockType,
      chartMasterList: [],
      taxCategoriesList: [],
      ortherInfo: null,
      catId: null,
    }
  },
  created() {
    Promise.all([
      this.getList(),
      this.getAllSaleType(),
      this.getAllChartMasters(),
      this.getAllTaxCategories(),
    ])
  },
  methods: {
    getList() {
      this.listLoading = true
      getStockCategoryList(this.listQuery).then(response => {
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
    getAllTaxCategories(){
      taxCategoriesAll().then(response => {
        this.taxCategoriesList = response.data
      })
    },
    getAllSaleType(){
      saleTypeAll().then(response => {
        this.saleTypeList = response.data
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
    setOrherCat(row){
      this.ortherInfo = row.has_many_stock_cat_properties
      this.catId = row.id
      this.setCatFormVisible = true
    },
    add() {
       for (let i of this.ortherInfo) {
         if (i.isSet) return this.$message.warning("请先保存当前编辑项");
       }
       let j = {
          'stkcatpropid': undefined,
          'categoryid': this.catId,
          'label': '',
          'controltype': 0,
          'defaultvalue': 0,
          'maximumvalue': 0,
          'reqatsalesorder': 0,
          'minimumvalue': 0,
          'numericvalue': 0,
          "isSet": true,
       };
       this.ortherInfo.push(j);
       // this.master_user.sel = JSON.parse(JSON.stringify(j));
     },
     saveRow(row) { //保存

      // console.log(row)    
      if(row.stkcatpropid){
        //修改
        updateStockCatProperties(row).then((response) => {
          console.log(response.data);
          // console.log('呵呵')
          const response_data = response.data
          if(response_data.status){
            for (const v of this.ortherInfo) {
              if (v.stkcatpropid === row.stkcatpropid) {
                const index = this.ortherInfo.indexOf(v)
                this.ortherInfo.splice(index, 1, response_data.data)
                break
              }
            }
            this.$notify({
              title: '成功',
              message: response_data.message,
              type: 'success',
              duration: 2000
            })
          }else{
            // this.dialogFormVisible = false
            if(response_data.code == 422){
              //验证失败
              /*this.$notify.error({
                title: '验证失败啦',
                message: response_data.message,
                duration: 2000
              })*/
              let errMessage = response_data.msg
              let messageShow = '<ul style="list-style-type:none;">'
              for (const prop in errMessage) {
                //console.log(prop)
                // console.log(`errMessage.${prop} = ${errMessage[prop]}`)
                //console.log(errMessage[prop])
                messageShow += '<li style="margin-bottom:5px;">'
                messageShow += `${errMessage[prop]}`
                messageShow += '</li>'
              }
              messageShow += '</ul>'
              this.$message({
                showClose: true,
                message: messageShow,
                type: 'error',
                dangerouslyUseHTMLString: true,
                duration: 0
              });
            }else{
              this.$notify.error({
                title: '失败',
                message: response_data.message,
                duration: 2000
              })
            }       
          }   
        })
      }else{
        //新增    
        createStockCatProperties(row).then((response) => {
          console.log(response.data);
          const response_data = response.data
          if(response_data.status){
            for (const v of this.ortherInfo) {
              if (v.stkcatpropid === row.stkcatpropid) {
                const index = this.ortherInfo.indexOf(v)
                this.ortherInfo.splice(index, 1, response_data.data)
                break
              }
            }
            this.$notify({
              title: '成功',
              message: response_data.message,
              type: 'success',
              duration: 2000
            })
          }else{
            // this.dialogFormVisible = false
            if(response_data.code == 422){
              //验证失败
              /*this.$notify.error({
                title: '验证失败啦',
                message: response_data.message,
                duration: 2000
              })*/
              let errMessage = response_data.msg
              let messageShow = '<ul style="list-style-type:none;">'
              for (const prop in errMessage) {
                //console.log(prop)
                // console.log(`errMessage.${prop} = ${errMessage[prop]}`)
                //console.log(errMessage[prop])
                messageShow += '<li style="margin-bottom:5px;">'
                messageShow += `${errMessage[prop]}`
                messageShow += '</li>'
              }
              messageShow += '</ul>'
              this.$message({
                showClose: true,
                message: messageShow,
                type: 'error',
                dangerouslyUseHTMLString: true,
                duration: 0
              });
            }else{
              this.$notify.error({
                title: '失败',
                message: response_data.message,
                duration: 2000
              })
            }        
          }    
        })
      }
      
      /* let data = JSON.parse(JSON.stringify(this.master_user.sel));
       for (let k in data) {
         row[k] = data[k] //将sel里面的value赋值给这一行。ps(for....in..)的妙用，细心的同学发现这里我并没有循环对象row
       }
       row.isSet = false;*/
     },
     editRow(row) { //编辑
       for (let i of this.ortherInfo) {
         if (i.isSet) return this.$message.warning("请先保存当前编辑11项");
       }
       row.isSet = true
     },
     setDel(){
        for (let i of this.ortherInfo) {
           if (i.isSet) return this.$message.warning("请先保存当前编辑11项");
        }
        this.setCatFormVisible = false
     },
     deleteRow(row) { //删除
       this.$confirm('确定要删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteStockCatProperties(row).then((response) => {
          // console.log(response.data);
          if(!response.data.status){
            this.$notify({
              title: '失败',
              message: response.data.message,
              type: 'warning',
              duration: 2000
            })
          }else{
            const index = this.ortherInfo.indexOf(row)
            this.ortherInfo.splice(index, 1)
            this.total = this.total -1
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
    handleModifyStatus(row, status) {
      this.$confirm('确定要删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.temp = Object.assign({}, row)
        deleteStockCategory(this.temp).then((response) => {
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
        stocktype: null,
        quantitybreak: 0,
        defaulttaxcatid: null,
        discountrate: 0,
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
          console.log(this.temp)
          createStockCategory(this.temp).then((response) => {
            console.log(response.data);
            const response_data = response.data
            if(response_data.status){
              this.list.unshift(response_data.data)
              this.total = this.total + 1
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
      row.stockact = parseInt(row.stockact)
      row.adjglact = parseInt(row.adjglact)
      row.issueglact = parseInt(row.issueglact)
      row.purchpricevaract = parseInt(row.purchpricevaract)
      row.materialuseagevarac = parseInt(row.materialuseagevarac)
      row.wipact = parseInt(row.wipact)
      row.defaulttaxcatid = parseInt(row.defaulttaxcatid)
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
          updateStockCategory(tempData).then((response) => {
            console.log(response.data)
            const response_data = response.data
            if(response_data.status){
              for (const v of this.list) {
                if (v.id === this.temp.id) {
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
  .el-table-add-row {
   margin-top: 10px;
   width: 100%;
   height: 34px;
   border: 1px dashed #c1c1cd;
   border-radius: 3px;
   cursor: pointer;
   justify-content: center;
   display: flex;
   line-height: 34px;
 }
</style>