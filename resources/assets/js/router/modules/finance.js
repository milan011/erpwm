
//后台用户管理路由组
import Layout from '@/views/layout/Layout'

const financeRouter = {
    path: '/finance',
    component: Layout,
    redirect: '/finance/set/accountGroup',
    name: 'finance',
    meta: {
      title: 'finance',
      icon: 'user',
      roles: ['admin', 'manager']
    },
    children: [
<<<<<<< HEAD
      /*{
=======
      {
>>>>>>> ba61a6472e88391d448d68ade147a05e50828e98
        path: 'manage',
        component: resolve => void(require(['@/views/finance/manage/index'], resolve)),
        name: 'financeManage',
        meta: { title: 'financeManage' },
        children: [
          {
<<<<<<< HEAD
            path: 'fixedAssets',
            component: resolve => void(require(['@/views/finance/manage/fixedAssets.vue'], resolve)),
            name: 'fixedAssets',
            meta: { title: 'fixedAssets' },
          },
          {
            path: 'pcClaimExpensesFromTab',
            component: resolve => void(require(['@/views/finance/manage/index.vue'], resolve)),
            name: 'pcClaimExpensesFromTab',
            meta: { title: 'pcClaimExpensesFromTab' },
          },
          {
=======
            path: 'bankTransTo',
            component: resolve => void(require(['@/views/finance/manage/bankTransTo.vue'], resolve)),
            name: 'bankTransTo',
            meta: { title: 'bankTransTo' },
          },
          {
            path: 'bankTransFrom',
            component: resolve => void(require(['@/views/finance/manage/bankTransFrom.vue'], resolve)),
            name: 'bankTransFrom',
            meta: { title: 'bankTransFrom' },
          },
          /*{
>>>>>>> ba61a6472e88391d448d68ade147a05e50828e98
            path: 'pcAuthorizeExpense',
            component: resolve => void(require(['@/views/finance/manage/index.vue'], resolve)),
            name: 'pcAuthorizeExpense',
            meta: { title: 'pcAuthorizeExpense' },
<<<<<<< HEAD
          }
        ]
      },*/
=======
          }*/
        ]
      },
>>>>>>> ba61a6472e88391d448d68ade147a05e50828e98
      /*{
        path: 'select',
        component: resolve => void(require(['@/views/finance/manage/index'], resolve)),
        name: 'financeSelect',
        meta: { title: 'financeSelect' },
        children: [
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
        ]
      },*/
      {
        path: 'set',
        component: resolve => void(require(['@/views/finance/set/index'], resolve)),
        name: 'financeSet',
        meta: { title: 'financeSet' },
        children: [
          {
            path: 'accountGroup',
            component: resolve => void(require(['@/views/finance/set/accountGroup.vue'], resolve)),
            name: 'accountGroup',
            meta: { title: 'accountGroup' },
          },
          {
            path: 'accountSection',
            component: resolve => void(require(['@/views/finance/set/accountSection.vue'], resolve)),
            name: 'accountSection',
            meta: { title: 'accountSection' },
          },
          {
            path: 'chartMaster',
            component: resolve => void(require(['@/views/finance/set/chartMaster.vue'], resolve)),
            name: 'chartMaster',
            meta: { title: 'chartMaster' },
          },
          {
            path: 'tags',
            component: resolve => void(require(['@/views/finance/set/tags.vue'], resolve)),
            name: 'tags',
            meta: { title: 'tags' },
          },
          {
            path: 'bankAccount',
            component: resolve => void(require(['@/views/finance/set/bankAccount.vue'], resolve)),
            name: 'bankAccount',
            meta: { title: 'bankAccount' },
          },
        ]
      },
    ]
}
export default financeRouter