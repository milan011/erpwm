
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
        component: resolve => void(require(['@/views/set/base/index'], resolve)),
        name: 'setBase',
        meta: { title: 'setBase' },
        children: [
          {
            path: 'taxProvinces',
            component: resolve => void(require(['@/views/set/base/taxProvinces.vue'], resolve)),
            name: 'taxProvinces',
            meta: { title: 'taxProvinces' },
          },
          {
            path: 'taxCategories',
            component: resolve => void(require(['@/views/set/base/taxCategories.vue'], resolve)),
            name: 'taxCategories',
            meta: { title: 'taxCategories' },
          },
          {
            path: 'taxGroups',
            component: resolve => void(require(['@/views/set/base/taxGroups.vue'], resolve)),
            name: 'taxGroups',
            meta: { title: 'taxGroups' },
          },
          {
            path: 'taxAuthorities',
            component: resolve => void(require(['@/views/set/base/taxAuthorities.vue'], resolve)),
            name: 'taxAuthorities',
            meta: { title: 'taxAuthorities' },
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