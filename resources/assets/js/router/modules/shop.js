
//权限管理路由组
import Layout from '@/views/layout/Layout'

const shopRouter = {
    path: '/shop',
    component: Layout,
    redirect: '/shop/index',
    name: 'shop',
    meta: {
      title: '',
      icon: 'theme',
      roles: ['admin', 'manager']
    },
    children: [
      {
        path: 'index',
        component: resolve => void(require(['@/views/shop/index'], resolve)),
        name: 'shopList',
        meta: { title: 'shop' }
      }
    ]
}
export default shopRouter