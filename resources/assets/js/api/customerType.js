import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getCustomerTypeList(query) {
  return request({
    // url: '/customerType/list',
    url: ROAST_CONFIG.API_URL + '/customerTypeList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function customerTypeAll() {
  return request({
    // url: '/customerType/list',
    url: ROAST_CONFIG.API_URL + '/customerTypeList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createCustomerType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/customerType',
    method: 'post',
    params: { token },
    data
  })
}


export function updateCustomerType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/customerType/' + data.typeid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteCustomerType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/customerType/' + data.typeid,
    method: 'delete',
    params: {token},
    data
  }) 
}
