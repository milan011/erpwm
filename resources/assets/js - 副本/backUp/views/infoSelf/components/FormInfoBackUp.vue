<template>
<div>
  <el-dialog :title="textMap[dialogStatus]" :visible.sync="infoDialogFormVisible">
      <el-form 
        ref="dataForm" 
        :rules="rules" 
        :model="temp" 
        :inline="false"
        label-position="right" 
        label-width="100px" 
        style="margin-left:0px;margin-right: 60px;">
        <!-- <el-form-item :label="$t('table.type')" prop="type">
          <el-select v-model="temp.type" class="filter-item" placeholder="Please select">
            <el-option v-for="item in calendarTypeOptions" :key="item.key" :label="item.display_name" :value="item.key"/>
          </el-select>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('table.date')" prop="timestamp">
          <el-date-picker v-model="temp.timestamp" type="datetime" placeholder="Please pick a date"/>
        </el-form-item> -->
        <!-- <el-row>
          <el-col :span="8"><div class="grid-content bg-purple self-style">
            <el-form-item :label="$t('info.name')"  prop="name">
              <el-input :disabled="infoNameDisabled" v-model="temp.name"/>
            </el-form-item>
          </div></el-col>
          <el-col :span="8"><div class="grid-content bg-purple-light self-style">
                <el-form-item :label="$t('info.name')" prop="name">
              <el-input :disabled="infoNameDisabled" v-model="temp.name"/>
            </el-form-item>
              </div></el-col>
              <el-col :span="8"><div class="grid-content bg-purple-light self-style">
                <el-form-item :label="$t('info.name')" prop="name">
              <el-input :disabled="infoNameDisabled" v-model="temp.name"/>
            </el-form-item>
              </div></el-col>  
        </el-row> -->
        <div class="createPost-main-container">
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('info.netin')">
                <el-col :span="11">
                  <el-select v-model="temp.netin_year" class="filter-item" placeholder="Please select">
                    <el-option v-for="year in package_year" :key="year.key" :label="year.year" :value="year.year"/>
                  </el-select>
                </el-col>
                <el-col class="line" :span="2" style="text-align:center">-</el-col>
                <el-col :span="11">
                  <el-select v-model="temp.netin_month" class="filter-item" placeholder="Please select">
                    <el-option v-for="month in package_month" :key="month.key" :label="month.month" :value="month.month"/>
                  </el-select>
                </el-col>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item :label="$t('info.manager_name')" align="center" prop="manage_id">          
                <el-select v-model="temp.manage_id" class="filter-item" filterable placeholder="输入姓名可搜索">
                  <el-option v-for="manage in managerList" :key="manage.id" :label="manage.name" :value="manage.id"/>
                </el-select> 
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('info.project_name')" align="center" prop="project_name">
                <el-input  v-model="temp.project_name"/>
              </el-form-item>
            </el-col>   
            <el-col :span="12">
              <el-form-item :label="$t('info.name')" align="center" prop="name">
                <el-input  v-model="temp.name"/>
              </el-form-item>
            </el-col> 
          </el-row>
          <el-row>
            <el-col :span="24">
              <el-form-item :label="$t('info.new_telephone')" prop="new_telephone">
              <el-col :span="9">
                <el-input :disabled="infoNewTelephoneDisabled" placeholder="新号码" v-model="temp.new_telephone"/>
              </el-col>
              <el-col class="line" :span="1" style="text-align:center">-</el-col>
              <el-col :span="9">
                <el-input placeholder="UIM码" v-model="temp.uim_number"/>
              </el-col>
            </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="24">
              <el-form-item :label="$t('info.side_number')">
              <div v-model="temp.side_numbers" v-for="(side, side_index) in temp.side_numbers" :key="side_index" >
                <el-col :span="9">
                  <el-input style="margin-bottom:5px;" placeholder="副卡" v-model="side.side_number"/>
                </el-col>
                <el-col class="line" :span="1" style="text-align:center;margin-bottom:5px;">-</el-col>
                <el-col :span="9">
                  <el-input style="margin-bottom:5px;" placeholder="副卡UIM码" v-model="side.uim"/>
                </el-col>
                <el-col :span="4">
                  <el-button 
                    v-if="side.add" 
                    @click="sideAdd" 
                    style="margin-left:5px;" 
                    type="success" >
                    {{ $t('info.side_number_add') }}
                  </el-button>
                  <el-button 
                    v-else  
                    @click="sideRemove($event)" 
                    style="margin-left:5px;" 
                    type="danger" 
                    :dataIndex="side_index">
                    {{ $t('info.side_number_remove') }}
                  </el-button>
                </el-col>
              </div>
            </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('info.package')" align="center" prop="package_id">
                <el-select v-model="temp.package_id" class="filter-item" @change="packageChange($event)" filterable placeholder="输入套餐搜索">
                  <el-option v-for="package in packageAll" :key="package.id" :label="package.name" :value="package.id"/>
                </el-select>
              </el-form-item>
            </el-col>   
            <el-col :span="12">
              <el-form-item :label="$t('info.user_telephone')" align="center" prop="user_telephone">
                <el-input  v-model="temp.user_telephone"/>
              </el-form-item>
            </el-col> 
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('info.old_bind')">
                <el-switch
                  v-model="temp.old_bind"
                  active-color="#13ce66"
                  inactive-color="#ff4949">
                </el-switch>
              </el-form-item>
            </el-col>   
            <el-col :span="12">
              <el-form-item :label="$t('info.is_jituan')">
                <el-switch
                  v-model="temp.is_jituan"
                  active-color="#13ce66"
                  inactive-color="#ff4949"
                  active-value="1"
                  inactive-value="0">
                </el-switch>
              </el-form-item>
            </el-col> 
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('info.collections')" prop="collections">
                <el-input  v-model.number="temp.collections"/>
              </el-form-item>
            </el-col>   
            <el-col :span="12">
              <el-form-item :label="$t('info.collections_type')">
                <el-select v-model="temp.collections_type" class="filter-item">
                  <el-option v-for="(item, index) in collections_types" :key="item" :label="item" :value="(index+1)"/>
                </el-select>
              </el-form-item>
            </el-col> 
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('info.remark')">
                <el-input :autosize="{ minRows: 2, maxRows: 4}" v-model="temp.remark" type="textarea" placeholder="备注"/>
              </el-form-item>
            </el-col>   
          </el-row>
        </div>
        <!-- <el-form-item :label="$t('info.netin')" prop="name">
          <el-col :span="9">
            <el-date-picker v-model="temp.timestamp" type="datetime" placeholder="选择日期"  style="width: 100%;"></el-date-picker>
          </el-col>
          <el-col class="line" :span="1" style="text-align:center">-</el-col>
          <el-col :span="9">
            <el-time-picker type="fixed-time" placeholder="选择时间" v-model="temp.time"  style="width: 100%;"></el-time-picker>
          </el-col>
        </el-form-item> -->
        
          <!-- <el-form-item :label="$t('info.manage_name')" align="center" prop="name">
          <el-col :span="12">
            <el-input :disabled="infoNameDisabled" v-model="temp.name"/>
            </el-col>
          </el-form-item> -->
          
        
        <!-- <el-col :span="11">
          <el-form-item :label="$t('info.name')" prop="name">
          <el-col :span="11">
            <el-input :disabled="infoNameDisabled" v-model="temp.name"/>
          </el-col>
          <el-col :span="11">
            <el-input :disabled="infoNameDisabled" v-model="temp.name"/>
          </el-col>
        </el-form-item>
        </el-col> -->
        <!-- <el-form-item :label="$t('info.new_telephone')" prop="name">
          <el-col :span="11">
            <el-input :disabled="infoNameDisabled" placeholder="新号码" v-model="temp.name"/>
          </el-col>
          <el-col class="line" :span="2" style="text-align:center">-</el-col>
          <el-col :span="11">
            <el-input :disabled="infoNameDisabled" placeholder="UIM码" v-model="temp.name"/>
          </el-col>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('info.side_number')" prop="name">
          <el-col :span="9">
            <el-input :disabled="infoNameDisabled" placeholder="副卡" v-model="temp.name"/>
          </el-col>
          <el-col class="line" :span="1" style="text-align:center">-</el-col>
          <el-col :span="9">
            <el-input :disabled="infoNameDisabled" placeholder="副卡UIM码" v-model="temp.name"/>
          </el-col>
          <el-button  type="primary" >{{ $t('info.side_number_add') }}</el-button>
        </el-form-item>
        
          <el-form-item :label="$t('info.package')" align="center">
          <el-col :span="12">
            <el-select v-model="temp.status" class="filter-item" placeholder="Please select">
              <el-option v-for="item in statusOptions" :key="item" :label="item" :value="item"/>
            </el-select></el-col>
          </el-form-item> -->
        
        <!-- <el-form-item v-show="passwordVisible" :label="$t('info.password')" :prop="password">
          <el-input :type="passwordType" v-model="temp.password"/>
        </el-form-item>
        <el-form-item v-show="passwordVisible" :label="$t('info.passwordRepeat')" :prop="password_confirmation">
          <el-input :type="passwordType" v-model="temp.password_confirmation"/>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('info.telephone')" prop="telephone">
          <el-input v-model="temp.telephone"/>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('info.email')" prop="email">
          <el-input v-model="temp.email"/>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('info.remark')">
          <el-input :autosize="{ minRows: 2, maxRows: 4}" v-model="temp.remark" type="textarea" placeholder="备注"/>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('info.wx_number')" prop="wx_number">
          <el-input v-model="temp.wx_number"/>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('info.status')">
          <el-select v-model="temp.status" class="filter-item" placeholder="Please select">
            <el-option v-for="item in statusOptions" :key="item" :label="item" :value="item"/>
          </el-select>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('info.importance')">
          <el-rate v-model="temp.importance" :colors="['#99A9BF', '#F7BA2A', '#FF9900']" :max="3" style="margin-top:8px;"/>
        </el-form-item> -->
        
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="infoDialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
  </el-dialog> 
</div>
</template>
<script>
  import { createInfo, updateInfo, deleteInfo, getInfo } from '@/api/infoSelf'
  import { isTelephone } from '@/utils/validate'
  import { managerAll } from '@/api/manager'
  import { packageAll } from '@/api/package'
  import  { package_year ,package_month, collections_type }  from '@/config.js'
  import  { isEmpty }  from '@/common.js'
  export default {
    data() {
      const validateTelephone = (rule, value, callback) => {
        if (!isTelephone(value)) {
          callback(new Error('请输入正确格式手机号'))
        } else {
          callback()
        }
      }
      props:["infoSelfList"]
      return {
        temp: {
          id: undefined,
          name: '',
          new_telephone: '',
          user_telephone: '',
          uim_number: '',
          remark: ' ',
          netin_year: new Date().getFullYear(),
          netin_month: new Date().getMonth()+1,
          manage_id: null,
          package_name: '',
          package_id: null,
          project_name: '大唐',
          collections: 200,
          newPackageName: '',
          side_numbers: [
            {side_number:'', uim: '', add: true}, 
          ],
          collections_type: 1,
          old_bind: '0',
          is_jituan: '0',
        },
        infoNewTelephoneDisabled: false,
        collections_types: collections_type,
        infoNameDisabled:false,
        package_year:package_year,
        package_month:package_month,
        managerList: [],
        packageAll: [],
        rules: {
          name: [{ required: true, message: '请输入客户姓名', trigger: 'change' }],
          package_id: [{ required: true, message: '请选择套餐', trigger: 'change' }],
          manage_id: [{ required: true, message: '请选择客户经理', trigger: 'change' }],
          project_name: [{ required: true, message: '请输入项目名称', trigger: 'change' }],
          /*uim_number: [
            { required: true, message: '请输入19位UIM码', trigger: 'change' },
            { len: 19, message: '请输入19位UIM码', trigger: 'change' }
          ],*/
          collections: [
            { required: true, message: '请确认收款数字' },
            { type: 'number',  message: '收款应为数字' },
          ],
          user_telephone: [
            { required: true, message: '请输入有效手机号', trigger: 'blur' }, 
            { validator: validateTelephone, trigger: 'change' }     
          ],
          new_telephone: [
            { required: true, message: '请输入有效手机号', trigger: 'blur' }, 
            { validator: validateTelephone, trigger: 'change' }     
          ]
        },
        textMap: {
          update: '信息编辑',
          create: '信息添加'
        },
        infoDialogFormVisible: false,
        dialogStatus: '',
        checkList: ['info_list', 'role_list'],
        permissions: []
      };
    },
    created() {  
      
    },
    mounted(){
      Promise.all([
        this.getManagerList(),
        this.getPackageList(),
      ])
    },
    methods: {
      sideAdd(){ //添加副卡
        let newSide = {side_number:'', uim: '', add: false}
        this.temp.side_numbers.push(newSide)
      },
      packageChange(event){
        //console.log(event)
        //console.log(this.packageAll)

        Array.prototype.forEach.call(this.packageAll, child => {
          // console.log(child)
          if(event === child.id){
            // console.log(child.name)
            this.temp.newPackageName = child.name
          }
        });
      },
      sideRemove(event){ //删除副卡
        this.temp.side_numbers.splice(event.currentTarget.getAttribute('dataIndex'),1)
      },
      addInfoSelfList: function(value){
        this.$emit("addNewInfo", value)
      },
      getManagerList() {
        managerAll().then(response => {
          /*console.log(response.data.data)
          return false*/
          this.managerList = response.data.data
        })
      },
      getPackageList() {
        packageAll().then(response => {
          /*console.log(response.data.data)
          return false*/
          this.packageAll = response.data.data
        })
      },
      resetTemp() {
        this.temp = {
          id: undefined,
          name: null,
          new_telephone: null,
          user_telephone: null,
          uim_number: null,
          remark: ' ',
          netin_year: new Date().getFullYear(),
          netin_month: new Date().getMonth()+1,
          manage_id: null,
          package_id: null,
          package_name: '',
          project_name: '',
          collections: 200,
          newPackageName: '',
          side_numbers: [
            {side_number:'', uim: '', add: true}, 
          ],
          collections_type: 1,
          old_bind: '0',
          is_jituan: '0',
        }
      },
      /*handlePermission(row) { 
        // row参数为点击的角色信息对象
        getRolePermissions(row).then((response) => {
          this.checkList = response.data
          this.permissionTemp.id = row.id
          this.infoDialogFormVisible = true
        })

        // this.infoDialogFormVisible = true
        this.$nextTick(() => {
          this.$refs['permissionDForm'].clearValidate()
        })
      },*/
      handleCreateInfo(){
        this.resetTemp()
        this.dialogStatus = 'create'
        this.infoDialogFormVisible = true
        this.infoNewTelephoneDisabled = false
        this.$nextTick(() => {
          this.$refs['dataForm'].clearValidate()
        })
      },
      createData() {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            createInfo(this.temp).then((response) => {
              /*console.log(this.temp)
              return false*/
              if(response.data.status){ //添加成功
                let resData = response.data.data
                /*let newInfo = {
                  id: resData.id,
                  new_telephone: resData.new_telephone,
                  project_name: resData.project_name,
                  is_jituan: resData.is_jituan,
                  name: resData.name,
                  netin: resData.netin,
                  old_bind: resData.old_bind,
                  side_number: resData.side_number,
                  package_id: resData.package_id,
                  manage_id: resData.manage_id,
                  collections: resData.collections,
                  side_uim_number: resData.side_uim_number,
                  side_number_num: resData.side_number_num,
                  user_telephone: resData.user_telephone,
                  status: resData.status,
                  belongs_to_creater: {nick_name: this.$store.getters.nickName},
                  created_at: new Date()
                }*/
                // console.log(newInfo)
                this.addInfoSelfList(resData)
                this.infoDialogFormVisible = false
                this.$notify({
                  title: '成功',
                  message: '创建成功',
                  type: 'success',
                  duration: 2000
                })
              }else{ //添加失败
                this.$notify.error({
                  title: '注意',
                  message: response.data.message,
                  duration: 2000
                })
              }           
            }).catch((error) => {
              // console.log(error)
              switch (error.response.status) {
                case 422:
                  let errMessage = error.response.data.errors
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
                  /*this.$notify.erro({
                    title: '请注意',
                    message: '创建失败',
                    type: 'waring',
                    duration: 2000
                   })*/
                  break
              // 如果响应中的 http code 为 400，那么就弹出一条错误提示给用户
              /*case 400:
                  return this.$Message.error('数据过期,请重新登录')
                  break*/
            }
            return Promise.reject(error)
          })
          /*.catch(function (error) {
            if (error.response) {
              // The request was made and the server responded with a status code
              // that falls out of the range of 2xx
              console.log(error.response.data);
              console.log(error.response.status);
              console.log(error.response.headers);
            } else if (error.request) {
              // The request was made but no response was received
              // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
              // http.ClientRequest in node.js
              console.log(error.request);
            } else {
              // Something happened in setting up the request that triggered an Error
              console.log('Error', error.message);
            }
            console.log(error.config);
          })*/
        }
      })
    },
    handleUpdateInfo(row) {

      let netin_arr     = row.netin.split('-')
      let side_arr      = row.side_number.split('|')
      let side_uim_arr  = row.side_uim_number.split('|')
      row.side_numbers  = []

      /*
      console.log(netin_arr)
      console.log(side_arr)
      console.log(side_uim_arr)
      console.log(isEmpty(side_uim_arr))*/

      if(!isEmpty(side_arr)){
        Array.prototype.forEach.call(side_arr, (child, index) => {
          //console.log(child)
          //console.log(index)
          let addAction = false
          if(index == 0) addAction = true 
          // side = {side_number: child, uim: side_uim_arr[index], add: addAction}
          row.side_numbers.push({side_number: child, uim: side_uim_arr[index], add: addAction})     
        })
      }

      // console.log(row.side_numbers)

      row.netin_year  = netin_arr[0]
      row.netin_month = netin_arr[1]
      row.collections = parseInt(row.collections)
      row.collections_type = parseInt(row.collections_type)
      
      /*if(row.is_jituan == '1'){
            row.is_jituan = true
          }else{
            row.is_jituan = false
          }
          if(row.old_bind == '1'){
            row.old_bind = true
          }else{
            row.old_bind = false
          }*/
      this.temp = Object.assign({}, row) // copy obj
      //this.temp.timestamp = new Date(this.temp.timestamp)
      // console.log(row)
      // console.log(this.temp)
      this.dialogStatus = 'update'
      this.infoNewTelephoneDisabled = true,
      this.infoDialogFormVisible = true 
      //this.$nextTick(() => {
      //  this.$refs['dataForm'].clearValidate()
      //})
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          updateInfo(tempData).then((response) => {
            if(response.data.status){
              /*for (const v of this.list) {
                if (v.id === this.temp.id) {
                  const index = this.list.indexOf(v)
                  this.list.splice(index, 1, this.temp)
                  break
                }
              }*/
              // console.log(this.temp.newPackageName)
              // console.log(this.temp)
              this.temp.update = true
              this.temp.has_one_package = response.data.data.has_one_package
              this.temp.side_number = response.data.data.side_number
              this.temp.side_number_num = response.data.data.side_number_num
              this.temp.is_jituan = response.data.data.is_jituan
              this.temp.netin = response.data.data.netin
              this.addInfoSelfList(this.temp)
              this.infoDialogFormVisible = false
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
  };
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
  .el-select {
    display: inline;
    position: relative;
  }
</style>