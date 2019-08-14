
//后台用户管理路由组
import Layout from '@/views/layout/Layout'

const assetRouter = {
    path: '/asset',
    component: Layout,
    redirect: '/asset/manage/pcAssignCashToTab',
    name: 'asset',
    meta: {
      title: 'asset',
      icon: 'user',
      roles: ['admin', 'manager']
    },
    children: [
      {
        path: 'manage',
        component: resolve => void(require(['@/views/asset/manage/index'], resolve)),
        name: 'assetManage',
        meta: { title: 'assetManage' },
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
          },
          {
            path: 'pcAuthorizeExpense',
            component: resolve => void(require(['@/views/pettyCash/manage/pcAuthorizeExpense.vue'], resolve)),
            name: 'pcAuthorizeExpense',
            meta: { title: 'pcAuthorizeExpense' },
          }
        ]
      },
      {
        path: 'select',
        component: resolve => void(require(['@/views/pettyCash/select/index'], resolve)),
        name: 'assetSelect',
        meta: { title: 'assetSelect' },
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
        path: 'assetSet',
        component: resolve => void(require(['@/views/asset/set/index'], resolve)),
        name: 'assetSet',
        meta: { title: 'assetSet' },
        children: [
          {
            path: 'fixedAssetCategorie',
            component: resolve => void(require(['@/views/asset/set/fixedAssetCategorie.vue'], resolve)),
            name: 'fixedAssetCategorie',
            meta: { title: 'fixedAssetCategorie' },
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
export default assetRouter