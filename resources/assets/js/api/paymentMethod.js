import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPaymentMethodList(query) {
  return request({
    // url: '/paymentMethod/list',
    url: ROAST_CONFIG.API_URL + '/paymentMethodList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function paymentMethodAll() {
  return request({
    // url: '/paymentMethod/list',
    url: ROAST_CONFIG.API_URL + '/paymentMethodList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPaymentMethod(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/paymentMethod',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePaymentMethod(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/paymentMethod/' + data.paymentid,
    method: 'put',
    params: { token },
    data
  })
}
export function deletePaymentMethod(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/paymentMethod/' + data.paymentid,
    method: 'delete',
    params: {token},
    data
  }) 
}
