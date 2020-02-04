
//权限管理路由组
import Layout from '@/views/layout/Layout'

const roleRouter = {
    path: '/role',
    component: Layout,
    redirect: '/role/index',
    name: 'role',
    meta: {
      title: 'role',
      icon: 'people',
      roles: ['admin']
    },
    children: [
      {
        path: 'index',
        component: resolve => void(require(['@/views/role/index'], resolve)),
        name: 'roleList',
        meta: { title: 'roleManger' }
      },
      {
        path: 'permission',
        component: resolve => void(require(['@/views/permissions/index'], resolve)),
        name: 'permissionList',
        meta: { title: 'permission' }
      },
    ]
}
export default roleRouter