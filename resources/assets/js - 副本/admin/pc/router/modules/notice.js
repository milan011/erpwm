
//权限管理路由组
import Layout from '@adminPc/views/layout/Layout'

const noticeRouter = {
    path: '/notice',
    component: Layout,
    redirect: '/notice/index',
    name: 'notice',
    meta: {
      title: '',
      icon: 'message'
    },
    children: [
      {
        path: 'index',
        component: resolve => void(require(['@adminPc/views/notice/index'], resolve)),
        name: 'noticeList',
        meta: { title: 'notice' }
      }
    ]
}
export default noticeRouter