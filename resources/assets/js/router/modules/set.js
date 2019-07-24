
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
          {
            path: 'periodsInquiry',
            component: resolve => void(require(['@/views/set/base/periodsInquiry.vue'], resolve)),
            name: 'periodsInquiry',
            meta: { title: 'periodsInquiry' },
          },
        ]
      },
      {
        path: 'gatherPay',
        component: resolve => void(require(['@/views/set/gatherPay/index'], resolve)),
        name: 'setGatherPay',
        meta: { title: 'setGatherPay' },
        children: [
          {
            path: 'saleType',
            component: resolve => void(require(['@/views/set/gatherPay/saleType.vue'], resolve)),
            name: 'saleType',
            meta: { title: 'saleType' },
          },
          {
            path: 'taxCategories',
            component: resolve => void(require(['@/views/set/base/taxCategories.vue'], resolve)),
            name: 'taxCategories',
            meta: { title: 'taxCategories' },
          },
        ]
      },
    ]
}
export default setRouter