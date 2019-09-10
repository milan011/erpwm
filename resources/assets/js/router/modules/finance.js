
//后台用户管理路由组
import Layout from '@/views/layout/Layout'

const financeRouter = {  
    path: '/finance',
    component: Layout,
    redirect: '/finance/manage/accountSection',
    name: 'finance',
    meta: {
      title: 'finance',
      icon: 'user',
      roles: ['admin', 'manager']
    },
    children: [
      {
        path: 'manage',
        component: resolve => void(require(['@/views/finance/manage/index'], resolve)),
        name: 'financeManage',
        meta: { title: 'financeManage' },
        children: [
          /*{
            path: 'accountSection',
            component: resolve => void(require(['@/views/finance/manage/accountSection.vue'], resolve)),
            name: 'accountSection',
            meta: { title: 'accountSection' },
          },
          {
            path: 'pcClaimExpensesFromTab',
            component: resolve => void(require(['@/views/finance/manage/index.vue'], resolve)),
            name: 'pcClaimExpensesFromTab',
            meta: { title: 'pcClaimExpensesFromTab' },
          },
          {
            path: 'pcAuthorizeExpense',
            component: resolve => void(require(['@/views/finance/manage/index.vue'], resolve)),
            name: 'pcAuthorizeExpense',
            meta: { title: 'pcAuthorizeExpense' },
          }*/
        ]
      },
      {
        path: 'select',
        component: resolve => void(require(['@/views/finance/select/index'], resolve)),
        name: 'financeSelect',
        meta: { title: 'financeSelect' },
        /*children: [
          {
            path: 'taxProvinces',
            component: resolve => void(require(['@/views/finance/manage/index.vue'], resolve)),
            name: 'taxProvinces',
            meta: { title: 'taxProvinces' },
          },
          {
            path: 'taxCategories',
            component: resolve => void(require(['@/views/finance/manage/index.vue'], resolve)),
            name: 'taxCategories',
            meta: { title: 'taxCategories' },
          }
        ]*/
      },
      {
        path: 'financeSet',
        component: resolve => void(require(['@/views/finance/set/index'], resolve)),
        name: 'financeSet',
        meta: { title: 'financeSet' },
        children: [
          {
            path: 'accountSection',
            component: resolve => void(require(['@/views/finance/set/accountSection.vue'], resolve)),
            name: 'accountSection',
            meta: { title: 'accountSection' },
          },
          {
            path: 'accountGroup',
            component: resolve => void(require(['@/views/finance/set/accountGroup.vue'], resolve)),
            name: 'accountGroup',
            meta: { title: 'accountGroup' },
          },
          /*{
            path: 'maintenanceTask',
            component: resolve => void(require(['@/views/finance/set/maintenanceTask.vue'], resolve)),
            name: 'maintenanceTask',
            meta: { title: 'maintenanceTask' },
          },*/
        ]
      },
    ]
}
export default financeRouter