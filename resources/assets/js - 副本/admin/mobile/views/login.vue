<template>
<div id="mydiv">
    <!-- 提醒栏 -->
    <div class="tipslogin">
      <span>你需要登录才能继续仿问</span>
      <span class="closeSpan">关闭</span>
    </div>
    <div class="imgDiv">
      <!-- <img src="./imgs/tblogo.png" alt="" class="logo"> -->
    </div>
    <!-- 操作区域 -->
    <div class="operateDiv">
      <!-- 账号 -->
      <mt-field  :placeholder="accountPlaceholder"  v-model="uname" class="myinput"></mt-field>
      <!-- 密码 -->
      <mt-field  v-show="isUserAccount" placeholder="请输入密码"  type="password" v-model="upass" class="myinput"></mt-field>
      <!-- 短信验证码 -->
      <mt-field  v-show="!isUserAccount" placeholder="校验码" v-model="getCode" class="myinput">
          <span class="getCode">获取短信验证码</span>
      </mt-field>
      <!-- 免费注册块 -->
      <div class="registerDiv">
        <span>免费注册</span>
        <span v-show="forgetPassShowroNot">忘记密码</span>
      </div>
      <!-- 登录和切换登录方式块 -->
      <div>
        <mt-button size="large" class="mybutton" @click.native="login">登录</mt-button>
        <mt-button size="large"  type="primary" class="mybutton" @click.native="changeLoginMethod">{{defaultAccountText}}</mt-button>
      </div>
    </div>
  </div>
</template>