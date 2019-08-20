
//后台用户管理路由组
import Layout from '@/views/layout/Layout'

const assetRouter = {
    path: '/asset',
    component: Layout,
    redirect: '/asset/manage/fixedAssets',
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
            path: 'fixedAssets',
            component: resolve => void(require(['@/views/asset/manage/fixedAssets.vue'], resolve)),
            name: 'fixedAssets',
            meta: { title: 'fixedAssets' },
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
            path: 'fixedAssetLocation',
            component: resolve => void(require(['@/views/asset/set/fixedAssetLocation.vue'], resolve)),
            name: 'fixedAssetLocation',
            meta: { title: 'fixedAssetLocation' },
          },
          {
            path: 'maintenanceTask',
            component: resolve => void(require(['@/views/asset/set/maintenanceTask.vue'], resolve)),
            name: 'maintenanceTask',
            meta: { title: 'maintenanceTask' },
          },
        ]
      },
    ]
}
export default assetRouter