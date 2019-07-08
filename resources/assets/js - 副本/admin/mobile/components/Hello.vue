<template>
    <div>
        <h1>Hello, Larvuent!</h1>
        <p id="hh">{{ msg }}</p>
        <el-dropdown-item divided>
            <span style="display:block;" @click="logout">退出</span>
        </el-dropdown-item>
    </div>
</template>

<script>
export default {
    data() {
        return {
            msg: '你添加吗,胸弟?',
        }
    },
    methods: {
        logout() {
            this.$store.dispatch('LogOut').then(() => {
            location.reload()// In order to re-instantiate the vue-router object to avoid bugs
        })
    }
  }
}
</script>

<style >
    #hh{
        color:red;
    }
</style>