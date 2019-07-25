import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPaymentTermList(query) {
  return request({
    // url: '/paymentTerm/list',
    url: ROAST_CONFIG.API_URL + '/paymentTermList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function paymentTermAll() {
  return request({
    // url: '/paymentTerm/list',
    url: ROAST_CONFIG.API_URL + '/paymentTermList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPaymentTerm(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/paymentTerm',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePaymentTerm(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/paymentTerm/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deletePaymentTerm(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/paymentTerm/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
