
//后台用户管理路由组
import Layout from '@adminPc/views/layout/Layout'

const userRouter = {
    path: '/user',
    component: Layout,
    redirect: '/user/index',
    name: 'user',
    meta: {
      title: 'user',
      icon: 'user',
      roles: ['admin', 'manager']
    },
    children: [
      {
        path: 'index',
        component: resolve => void(require(['@adminPc/views/user/index'], resolve)),
        name: 'userList',
        meta: { title: 'userList' }
      },
      {
        path: 'permission',
        component: resolve => void(require(['@adminPc/views/permissions/index'], resolve)),
        name: 'permissionList',
        meta: { title: 'permission' }
      },
      {
        path: 'role',
        component: resolve => void(require(['@adminPc/views/role/index'], resolve)),
        name: 'roleList',
        meta: { title: 'role' }
      }
    ]
}
export default userRouter