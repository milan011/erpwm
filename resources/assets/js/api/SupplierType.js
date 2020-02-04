import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getSupplierTypeList(query) {
  return request({
    // url: '/supplierType/list',
    url: ROAST_CONFIG.API_URL + '/supplierTypeList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function supplierTypeAll() {
  return request({
    // url: '/supplierType/list',
    url: ROAST_CONFIG.API_URL + '/supplierTypeList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createSupplierType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/supplierType',
    method: 'post',
    params: { token },
    data
  })
}


export function updateSupplierType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/supplierType/' + data.typeid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteSupplierType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/supplierType/' + data.typeid,
    method: 'delete',
    params: {token},
    data
  }) 
}
