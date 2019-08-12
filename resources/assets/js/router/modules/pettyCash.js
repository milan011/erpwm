
//后台用户管理路由组
import Layout from '@/views/layout/Layout'

const pettyCashRouter = {
    path: '/pettyCash',
    component: Layout,
    redirect: '/pettyCash/manage/pcAssignCashToTab',
    name: 'pettyCash',
    meta: {
      title: 'pettyCash',
      icon: 'user',
      roles: ['admin', 'manager']
    },
    children: [
      {
        path: 'manage',
        component: resolve => void(require(['@/views/pettyCash/manage/index'], resolve)),
        name: 'pettyCashManage',
        meta: { title: 'pettyCashManage' },
        children: [
          {
            path: 'pcAssignCashToTab',
            component: resolve => void(require(['@/views/pettyCash/manage/pcAssignCashToTab.vue'], resolve)),
            name: 'pcAssignCashToTab',
            meta: { title: 'pcAssignCashToTab' },
          },
          {
            path: 'pcClaimExpensesFromTab',
            component: resolve => void(require(['@/views/pettyCash/manage/pcClaimExpensesFromTab.vue'], resolve)),
            name: 'pcClaimExpensesFromTab',
            meta: { title: 'pcClaimExpensesFromTab' },
          }
        ]
      },
      {
        path: 'select',
        component: resolve => void(require(['@/views/pettyCash/select/index'], resolve)),
        name: 'pettyCashSelect',
        meta: { title: 'pettyCashSelect' },
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
          }
        ]
      },
      {
        path: 'pettyCashSet',
        component: resolve => void(require(['@/views/pettyCash/set/index'], resolve)),
        name: 'pettyCashSet',
        meta: { title: 'pettyCashSet' },
        children: [
          {
            path: 'pcTypeTab',
            component: resolve => void(require(['@/views/pettyCash/set/pcTypeTab.vue'], resolve)),
            name: 'pcTypeTab',
            meta: { title: 'pcTypeTab' },
          },
          {
            path: 'pcTab',
            component: resolve => void(require(['@/views/pettyCash/set/pcTab.vue'], resolve)),
            name: 'pcTab',
            meta: { title: 'pcTab' },
          },
          {
            path: 'pcExpenses',
            component: resolve => void(require(['@/views/pettyCash/set/pcExpenses.vue'], resolve)),
            name: 'pcExpenses',
            meta: { title: 'pcExpenses' },
          },
        ]
      },
    ]
}
export default pettyCashRouter