
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
            path: 'customerType',
            component: resolve => void(require(['@/views/set/gatherPay/customerType.vue'], resolve)),
            name: 'customerType',
            meta: { title: 'customerType' },
          },
          {
            path: 'supplierType',
            component: resolve => void(require(['@/views/set/gatherPay/supplierType.vue'], resolve)),
            name: 'supplierType',
            meta: { title: 'supplierType' },
          },
          {
            path: 'creditStatus',
            component: resolve => void(require(['@/views/set/gatherPay/creditStatus.vue'], resolve)),
            name: 'creditStatus',
            meta: { title: 'creditStatus' },
          },
          {
            path: 'paymentTerm',
            component: resolve => void(require(['@/views/set/gatherPay/paymentTerm.vue'], resolve)),
            name: 'paymentTerm',
            meta: { title: 'paymentTerm' },
          },
          {
            path: 'purchorderAuth',
            component: resolve => void(require(['@/views/set/gatherPay/purchorderAuth.vue'], resolve)),
            name: 'purchorderAuth',
            meta: { title: 'purchorderAuth' },
          },
          {
            path: 'paymentMethod',
            component: resolve => void(require(['@/views/set/gatherPay/paymentMethod.vue'], resolve)),
            name: 'paymentMethod',
            meta: { title: 'paymentMethod' },
          },        
          {
            path: 'shipper',
            component: resolve => void(require(['@/views/set/gatherPay/shipper.vue'], resolve)),
            name: 'shipper',
            meta: { title: 'shipper' },
          },        
          {
            path: 'freightCost',
            component: resolve => void(require(['@/views/set/gatherPay/freightCost.vue'], resolve)),
            name: 'freightCost',
            meta: { title: 'freightCost' },
          },
          {
            path: 'discountMatrix',
            component: resolve => void(require(['@/views/set/gatherPay/discountMatrix.vue'], resolve)),
            name: 'discountMatrix',
            meta: { title: 'discountMatrix' },
          },
        ]
      },
      {
        path: 'setSale',
        component: resolve => void(require(['@/views/set/gatherPay/index'], resolve)),
        name: 'setSale',
        meta: { title: 'setSale' },
        children: [
          {
            path: 'saleType',
            component: resolve => void(require(['@/views/set/gatherPay/saleType.vue'], resolve)),
            name: 'saleType',
            meta: { title: 'saleType' },
          },
          {
            path: 'salesMan',
            component: resolve => void(require(['@/views/set/gatherPay/salesMan.vue'], resolve)),
            name: 'salesMan',
            meta: { title: 'salesMan' },
          },
          {
            path: 'area',
            component: resolve => void(require(['@/views/set/gatherPay/area.vue'], resolve)),
            name: 'area',
            meta: { title: 'area' },
          },
          {
            path: 'salesGLPosting',
            component: resolve => void(require(['@/views/set/gatherPay/salesGLPosting.vue'], resolve)),
            name: 'salesGLPosting',
            meta: { title: 'salesGLPosting' },
          },
          {
            path: 'cOGSGLPosting',
            component: resolve => void(require(['@/views/set/gatherPay/cOGSGLPosting.vue'], resolve)),
            name: 'cOGSGLPosting',
            meta: { title: 'cOGSGLPosting' },
          },
        ]
      },
      {
        path: 'setLocation',
        component: resolve => void(require(['@/views/set/location/index'], resolve)),
        name: 'setLocation',
        meta: { title: 'setLocation' },
        children: [
          {
            path: 'stockCategory',
            component: resolve => void(require(['@/views/set/location/stockCategory.vue'], resolve)),
            name: 'stockCategory',
            meta: { title: 'stockCategory' },
          },
          {
            path: 'locations',
            component: resolve => void(require(['@/views/set/location/locations.vue'], resolve)),
            name: 'locations',
            meta: { title: 'locations' },
          },
          {
            path: 'unitsOfMeasure',
            component: resolve => void(require(['@/views/set/location/unitsOfMeasure.vue'], resolve)),
            name: 'unitsOfMeasure',
            meta: { title: 'unitsOfMeasure' },
          },
          {
            path: 'mRPCalendar',
            component: resolve => void(require(['@/views/set/location/mRPCalendar.vue'], resolve)),
            name: 'mRPCalendar',
            meta: { title: 'mRPCalendar' },
          },
          {
            path: 'mRPDemandType',
            component: resolve => void(require(['@/views/set/location/mRPDemandType.vue'], resolve)),
            name: 'mRPDemandType',
            meta: { title: 'mRPDemandType' },
          },
          {
            path: 'department',
            component: resolve => void(require(['@/views/set/location/department.vue'], resolve)),
            name: 'department',
            meta: { title: 'department' },
          },
        ]
      },
    ]
}
export default setRouter