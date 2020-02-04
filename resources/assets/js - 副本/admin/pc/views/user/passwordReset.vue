<template>
<div>
  <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="100px" style="width: 400px; margin-left:50px;margin-top:15px">
        <!-- <el-form-item :label="$t('table.type')" prop="type">
          <el-select v-model="temp.type" class="filter-item" placeholder="Please select">
            <el-option v-for="item in calendarTypeOptions" :key="item.key" :label="item.display_name" :value="item.key"/>
          </el-select>
        </el-form-item> -->
        <!-- <el-form-item :label="$t('table.date')" prop="timestamp">
          <el-date-picker v-model="temp.timestamp" type="datetime" placeholder="Please pick a date"/>
        </el-form-item> -->
        <el-form-item :label="$t('user.name')">
          <el-input :disabled="true" v-model="temp.name"/>
        </el-form-item>
        <el-form-item :label="$t('user.nick_name')">
          <el-input :disabled="true" v-model="temp.nick_name"/>
        </el-form-item>
        <el-form-item v-show="true" label="原密码" :prop="oldPassword">
          <el-input :type="passwordType" v-model="temp.oldPassword"/>
        </el-form-item>
        <el-form-item v-show="true" label="新密码" :prop="password">
          <el-input :type="passwordType" v-model="temp.password"/>
        </el-form-item>
        <el-form-item v-show="true" label="确认密码" :prop="password_confirmation">
          <el-input :type="passwordType" v-model="temp.password_confirmation"/>
        </el-form-item>        
      </el-form>
      <div slot="footer" style="margin-left:20px" class="dialog-footer">
        <!-- <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button> -->
        <el-button  size="big" type="primary" @click="resetPassword">{{ $t('table.confirm') }}</el-button>
      </div>
</div>
</template>
<script>
  import { resetPassword} from '@/api/user'
  export default {
    name: 'passwordReset',
    data() {
      const validateRepeatPass = (rule, value, callback) => { /*密码确认校验*/
        if (value !== this.temp.password) {
            callback(new Error('两次输入密码不一致!'));
        } else {
            callback();
        }
      };
      return {
        temp: {
          id: undefined,
          name: null,
          nick_name: null,
          password: '',
          oldPassword: '',
          password_confirmation: '',
        },
        password: 'password',
        oldPassword: 'oldPassword',
        password_confirmation: 'password_confirmation',
        passwordType: 'password',
        checkList: [],
        rules: {
          password: [{ required: true, message: '请输入密码', trigger: 'change' },
            { min: 6, max: 16, message: '密码长度必须是6-16位', trigger: 'change' }
          ],
          oldPassword: [{ required: true, message: '请输入密码', trigger: 'change' },
            { min: 6, max: 16, message: '密码长度必须是6-16位', trigger: 'change' }
          ],
          password_confirmation: [
            { required: true, message: '请确认密码', trigger: 'change' },
            { min: 6, max: 16, message: '密码长度必须是6-16位', trigger: 'change' },
            { validator: validateRepeatPass, trigger: 'change' }
          ],
        },
      };
    },
    computed: {
      //userName() {
        //console.log(this.$store.getters.name)
        //console.log(this.nick_name = this.$store.getters.nickName)
        //this.temp.name = this.$store.getters.name
        //this.temp.nick_name = this.$store.getters.nickName
        //return this.$store.getters.name
      //},
    },
    created() {   
      Promise.all([
        // this.getRolesList()
        this.temp.name = this.$store.getters.name,
        this.temp.nick_name = this.$store.getters.nickName
      ])
    },
    methods: {
      resetPassword() {
        this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          resetPassword(this.temp).then((response) => {
            console.log(response.data)
            console.log(response.data.status)
            // return false
            if(response.data.status){
              // alert('hehe')
              this.$store.dispatch('LogOut').then(() => {
                location.reload()// In order to re-instantiate the vue-router object to avoid bugs
              })
              // this.$router.push({ path: '/login' })
              /*this.$notify({
                title: '成功',
                message: '密码已重置',
                type: 'success',
                duration: 2000
              })*/
            }else{
              this.$notify.error({
                title: '失败',
                message: response.data.message,
                type: 'waring',
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
              break
            }
            //return Promise.reject(error)
          })
        }
      })
      },
    }
  };
</script>
<style lang="scss" scoped>
  
</style>
<!-- <template>
  <div class="login-container">

    <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" auto-complete="on" label-position="left">

      <div class="title-container">
        <h3 class="title">{{ $t('login.title') }}</h3>
        <lang-select class="set-language"/>
      </div>

      <el-form-item prop="username">
        <span class="svg-container svg-container_login">
          <svg-icon icon-class="user" />
        </span>
        <el-input
          v-model="loginForm.username"
          :placeholder="$t('login.username')"
          name="username"
          type="text"
          auto-complete="on"
        />
      </el-form-item>

      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          :type="passwordType"
          v-model="loginForm.password"
          :placeholder="$t('login.password')"
          name="password"
          auto-complete="on"
          @keyup.enter.native="handleLogin" />
        <span class="show-pwd" @click="showPwd">
          <svg-icon icon-class="eye" />
        </span>
      </el-form-item>

      <el-button :loading="loading" type="primary" style="width:100%;margin-bottom:30px;" @click.native.prevent="handleLogin">{{ $t('login.logIn') }}</el-button>

      <div class="tips">
        <span>{{ $t('login.username') }} : admin</span>
        <span>{{ $t('login.password') }} : {{ $t('login.any') }}</span>
      </div>
      <div class="tips">
        <span style="margin-right:18px;">{{ $t('login.username') }} : editor</span>
        <span>{{ $t('login.password') }} : {{ $t('login.any') }}</span>
      </div>

      <el-button class="thirdparty-button" type="primary" @click="showDialog=true">{{ $t('login.thirdparty') }}</el-button>
    </el-form>
  </div>
</template>

<script>
import { isvalidUsername } from '@/utils/validate'
import LangSelect from '@/components/LangSelect'

export default {
  name: 'Login',
  components: { LangSelect},
  data() {
    const validateUsername = (rule, value, callback) => {
      if (!isvalidUsername(value)) {
        callback(new Error('Please enter the correct user name'))
      } else {
        callback()
      }
    }
    const validatePassword = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('The password can not be less than 6 digits'))
      } else {
        callback()
      }
    }
    return {
      loginForm: {
        username: 'wcg',
        password: '111111'
      },
      loginRules: {
        // username: [{ required: true, trigger: 'blur', validator: validateUsername }],
        username: [{ required: true, trigger: 'blur'}],
        password: [{ required: true, trigger: 'blur', validator: validatePassword }]
      },
      passwordType: 'password',
      loading: false,
      showDialog: false,
      redirect: undefined
    }
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect
      },
      immediate: true
    }

  },
  created() {
    // window.addEventListener('hashchange', this.afterQRScan)
  },
  destroyed() {
    // window.removeEventListener('hashchange', this.afterQRScan)
  },
  mounted(){
    // console.log($t(login.username));
  },
  methods: {
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = ''
      } else {
        this.passwordType = 'password'
      }
    },
    handleLogin() {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true
          this.$store.dispatch('LoginByUsername', this.loginForm).then(() => {
            this.loading = false
            this.$router.push({ path: this.redirect || '/dashboard' })
          }).catch(() => {
            this.loading = false
          })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    afterQRScan() {
      // const hash = window.location.hash.slice(1)
      // const hashObj = getQueryObject(hash)
      // const originUrl = window.location.origin
      // history.replaceState({}, '', originUrl)
      // const codeMap = {
      //   wechat: 'code',
      //   tencent: 'code'
      // }
      // const codeName = hashObj[codeMap[this.auth_type]]
      // if (!codeName) {
      //   alert('第三方登录失败')
      // } else {
      //   this.$store.dispatch('LoginByThirdparty', codeName).then(() => {
      //     this.$router.push({ path: '/' })
      //   })
      // }
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss">
  /* 修复input 背景不协调 和光标变色 */
  /* Detail see https://github.com/PanJiaChen/vue-element-admin/pull/927 */

  $bg:#283443;
  $light_gray:#eee;
  $cursor: #fff;

  @supports (-webkit-mask: none) and (not (cater-color: $cursor)) {
    .login-container .el-input input{
      color: $cursor;
      &::first-line {
        color: $light_gray;
      }
    }
  }

  /* reset element-ui css */
  .login-container {
    .el-input {
      display: inline-block;
      height: 47px;
      width: 85%;
      input {
        background: transparent;
        border: 0px;
        -webkit-appearance: none;
        border-radius: 0px;
        padding: 12px 5px 12px 15px;
        color: $light_gray;
        height: 47px;
        caret-color: $cursor;
        &:-webkit-autofill {
          -webkit-box-shadow: 0 0 0px 1000px $bg inset !important;
          -webkit-text-fill-color: $cursor !important;
        }
      }
    }
    .el-form-item {
      border: 1px solid rgba(255, 255, 255, 0.1);
      background: rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      color: #454545;
    }
  }
</style>

<style rel="stylesheet/scss" lang="scss" scoped>
$bg:#2d3a4b;
$dark_gray:#889aa4;
$light_gray:#eee;

.login-container {
  position: fixed;
  height: 100%;
  width: 100%;
  background-color: $bg;
  .login-form {
    position: absolute;
    left: 0;
    right: 0;
    width: 520px;
    max-width: 100%;
    padding: 35px 35px 15px 35px;
    margin: 120px auto;
  }
  .tips {
    font-size: 14px;
    color: #fff;
    margin-bottom: 10px;
    span {
      &:first-of-type {
        margin-right: 16px;
      }
    }
  }
  .svg-container {
    padding: 6px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
    &_login {
      font-size: 20px;
    }
  }
  .title-container {
    position: relative;
    .title {
      font-size: 26px;
      color: $light_gray;
      margin: 0px auto 40px auto;
      text-align: center;
      font-weight: bold;
    }
    .set-language {
      color: #fff;
      position: absolute;
      top: 5px;
      right: 0px;
    }
  }
  .show-pwd {
    position: absolute;
    right: 10px;
    top: 7px;
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
    user-select: none;
  }
  .thirdparty-button {
    position: absolute;
    right: 35px;
    bottom: 28px;
  }
}
</style>
 -->