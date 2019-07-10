
//后台用户管理路由组
import Layout from '@/views/layout/Layout'

const setRouter = {
    path: '/set',
    component: Layout,
    redirect: '/set/base/taxCategories',
    name: 'set',
    meta: {
      title: 'set',
      icon: 'user',
      roles: ['admin', 'manager']
    },
    children: [
      {
        path: 'base',
        component: resolve => void(require(['@/views/set/base/taxCategories.vue'], resolve)),
        name: 'setBase',
        meta: { title: 'setBase' },
        children: [
          {
            path: 'taxCategories',
            component: resolve => void(require(['@/views/set/base/taxCategories.vue'], resolve)),
            name: 'taxCategories',
            meta: { title: 'setBaseTaxCategories' },
          },
          {
            path: 'role',
            component: resolve => void(require(['@/views/role/index'], resolve)),
            name: 'roleList',
            meta: { title: 'role' },
          },
        ]
      },
      {
        path: 'permission',
        component: resolve => void(require(['@/views/permissions/index'], resolve)),
        name: 'permissionList',
        meta: { title: 'permission' },
      },
    ]
}
export default setRouter