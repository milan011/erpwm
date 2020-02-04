
//后台用户管理路由组
import Layout from '@/views/layout/Layout'

const userRouter = {
    path: '/user',
    component: Layout,
    redirect: '/user/index',
    name: 'User',
    meta: {
      title: '',
      icon: 'user',
      roles: ['admin', 'manager']
    },
    children: [
      {
        path: 'index',
        component: resolve => void(require(['@/views/user/index'], resolve)),
        name: 'userList',
        meta: { title: 'user' }
      },
      /*{
        hidden: true,
        path: 'passwordReset',
        component: resolve => void(require(['@/views/user/passwordReset'], resolve)),
        name: 'userAdd',
        meta: { title: 'passwordReset' }
      }*/
    ]
}
export default userRouter