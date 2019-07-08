<template>
<div>
  <el-dialog :title="textMap[dialogStatus]" :visible.sync="infoDianxinDialogFormVisible">
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
              <el-form-item :label="$t('infoDianxin.netin')">
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
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('infoDianxin.return_telephone')" align="center" prop="return_telephone">
                <el-input  v-model="temp.return_telephone"/>
              </el-form-item>
            </el-col>   
            <el-col :span="12">
              <el-form-item :label="$t('infoDianxin.name')" align="center" prop="name">
                <el-input  v-model="temp.name"/>
              </el-form-item>
            </el-col> 
          </el-row>
          <el-row>  
            <el-col :span="12">
              <el-form-item :label="$t('infoDianxin.manager')" align="center" prop="manager">
                <el-input  v-model="temp.manager"/>
              </el-form-item>
            </el-col> 
            <el-col :span="12">
              <el-form-item :label="$t('infoDianxin.jituan')" align="center" prop="jituan">
                <el-input  v-model="temp.jituan"/>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>   
            <el-col :span="12">
              <el-form-item :label="$t('infoDianxin.yongjin')" align="center">
                <el-input  v-model="temp.yongjin"/>
              </el-form-item>
            </el-col> 
            <el-col :span="12">
              <el-form-item :label="$t('infoDianxin.refunds')" align="center" prop="refunds">
                <el-input  v-model.number="temp.refunds"/>
              </el-form-item>
            </el-col> 
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item :label="$t('infoDianxin.jiakuan')" align="center" prop="jiakuan">
                <el-input  v-model.number="temp.jiakuan"/>
              </el-form-item>
            </el-col>   
            <el-col :span="12">
              <el-form-item :label="$t('infoDianxin.balance_month')" align="center" prop="balance_month">
                <el-input  v-model.number="temp.balance_month"/>
              </el-form-item>
            </el-col> 
          </el-row>
        </div>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="infoDianxinDialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{ $t('table.confirm') }}</el-button>
        <el-button v-else type="primary" @click="updateData">{{ $t('table.confirm') }}</el-button>
      </div>
  </el-dialog> 
</div>
</template>
<script>
  import { createInfoDianxin, updateInfoDianxin, deleteInfoDianxin, getInfoDianxin } from '@/api/infoDianxin'
  import { isTelephone } from '@/utils/validate'
  //import { managerAll } from '@/api/manager'
  //import { packageAll } from '@/api/package'
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
          name: '18元套餐',
          return_telephone: '13731080174',
          manager: '阿宝',
          jituan: '阿宝集团',
          netin_year: new Date().getFullYear(),
          netin_month: new Date().getMonth()+1,
          yongjin: ' ',
          refunds: 200,
          jiakuan: 100,
          balance_month: 0,
        },

        package_year:package_year,
        package_month:package_month,
        rules: {
          name: [{ required: true, message: '请输入套餐名', trigger: 'change' }],
          jituan: [{ required: true, message: '请输入集团', trigger: 'change' }],
          manager: [{ required: true, message: '请输入客户经理', trigger: 'change' }],
          refunds: [
            { required: true, message: '请确认返款金额' },
            { type: 'number',  message: '返款金额应为数字' },
          ],
          jiakuan: [
            { required: true, message: '请确认价款金额' },
            { type: 'number',  message: '价款应为数字' },
          ],
          balance_month: [
            { required: true, message: '请确认返还月' },
            { type: 'number',  message: '返还月应为数字' },
          ],
          return_telephone: [
            { required: true, message: '请输入有效手机号', trigger: 'blur' }, 
            { validator: validateTelephone, trigger: 'change' }     
          ],
        },
        textMap: {
          update: '信息编辑',
          create: '信息添加'
        },
        infoDianxinDialogFormVisible: false,
        dialogStatus: '',
        // checkList: ['info_list', 'role_list'],
      };
    },
    created() {  
      Promise.all([
        /*this.getManagerList(),
        this.getPackageList(),*/
      ])
    },
    methods: {
      /*sideAdd(){ //添加副卡
        let newSide = {side_number:'', uim: '', add: false}
        this.temp.side_numbers.push(newSide)
      },*/
      /*packageChange(event){
        //console.log(event)
        //console.log(this.packageAll)

        Array.prototype.forEach.call(this.packageAll, child => {
          // console.log(child)
          if(event === child.id){
            // console.log(child.name)
            this.temp.newPackageName = child.name
          }
        });
      },*/
      /*sideRemove(event){ //删除副卡
        this.temp.side_numbers.splice(event.currentTarget.getAttribute('dataIndex'),1)
      },*/
      addInfoSelfList: function(value){
        this.$emit("addNewInfo", value)
      },
      /*getManagerList() {
        managerAll().then(response => {
          console.log(response.data.data)
          return false
          this.managerList = response.data.data
        })
      },*/
      /*getPackageList() {
        packageAll().then(response => {
          console.log(response.data.data)
          return false
          this.packageAll = response.data.data
        })
      },*/
      resetTemp() {
        this.temp = {
          id: undefined,
          name: '18元套餐',
          return_telephone: '13731080174',
          manager: '阿宝',
          jituan: '阿宝集团',
          netin_year: new Date().getFullYear(),
          netin_month: new Date().getMonth()+1,
          yongjin: ' ',
          refunds: 200,
          jiakuan: 100,
          balance_month: 0,
        }
      },
      /*handlePermission(row) { 
        // row参数为点击的角色信息对象
        getRolePermissions(row).then((response) => {
          this.checkList = response.data
          this.permissionTemp.id = row.id
          this.infoDianxinDialogFormVisible = true
        })

        // this.infoDianxinDialogFormVisible = true
        this.$nextTick(() => {
          this.$refs['permissionDForm'].clearValidate()
        })
      },*/
      handleCreateInfo(){
        this.resetTemp()
        this.dialogStatus = 'create'
        this.infoDianxinDialogFormVisible = true
        this.$nextTick(() => {
          this.$refs['dataForm'].clearValidate()
        })
      },
      createData() {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            createInfoDianxin(this.temp).then((response) => {
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
                /*console.log(resData)
                return false*/
                //resData.netin_year    = resData.netin.substring(0,4)
                // resData.belongs_to_creater   = resData.belongs_to_creater
                this.addInfoSelfList(resData)
                this.infoDianxinDialogFormVisible = false
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
              console.log(error)
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

      /*getInfo(row).then((response) => {
        console.log(response.data)
        return false
             
      })*/ 
      /*let netin_arr     = row.netin.split('-')
      let side_arr      = row.side_number.split('|')
      let side_uim_arr  = row.side_uim_number.split('|')
      row.side_numbers  = []*/

      /*
      console.log(netin_arr)
      console.log(side_arr)
      console.log(side_uim_arr)
      console.log(isEmpty(side_uim_arr))*/

      /*if(!isEmpty(side_arr)){
        Array.prototype.forEach.call(side_arr, (child, index) => {
          //console.log(child)
          //console.log(index)
          let addAction = false
          if(index == 0) addAction = true 
          // side = {side_number: child, uim: side_uim_arr[index], add: addAction}
          row.side_numbers.push({side_number: child, uim: side_uim_arr[index], add: addAction})     
        })
      }*/

      // console.log(row.side_numbers)

      /*row.netin_year  = netin_arr[0]
      row.netin_month = netin_arr[1]
      row.collections = parseInt(row.collections)
      row.collections_type = parseInt(row.collections_type)*/
      
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
      row.netin_year    = row.netin.substring(0,4)
      row.netin_month   = row.netin.substring(4,6)
      row.jiakuan       = parseInt(row.jiakuan)
      row.refunds       = parseInt(row.refunds)
      row.balance_month = parseInt(row.balance_month)

      this.temp = Object.assign({}, row) // copy obj
      //this.temp.timestamp = new Date(this.temp.timestamp)
      //console.log(row)
      //console.log(typeof(row.netin))
      // console.log(this.temp)
      this.dialogStatus = 'update' 
      this.infoNewTelephoneDisabled = true,
      this.infoDianxinDialogFormVisible = true 
      //this.$nextTick(() => {
      //  this.$refs['dataForm'].clearValidate()
      //})
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          updateInfoDianxin(tempData).then((response) => {
            if(response.data.status){
              /*for (const v of this.list) {
                if (v.id === this.temp.id) {
                  const index = this.list.indexOf(v)
                  this.list.splice(index, 1, this.temp)
                  break
                }
              }*/
              // console.log(this.temp.newPackageName)
              
              this.temp.update          = true
              this.temp.has_one_package = response.data.data.has_one_package
              this.temp.side_number     = response.data.data.side_number
              this.temp.side_number_num = response.data.data.side_number_num
              this.temp.is_jituan       = response.data.data.is_jituan
              this.temp.netin           = response.data.data.netin
              //this.temp.netin_year    = response.data.data.netin.substring(0,4)
              //this.temp.netin_month   = response.data.data.netin.substring(4,6)
              console.log(this.temp)
              this.addInfoSelfList(this.temp)
              this.infoDianxinDialogFormVisible = false
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