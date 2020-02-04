import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPurchorderAuthList(query) {
  return request({
    // url: '/purchorderAuth/list',
    url: ROAST_CONFIG.API_URL + '/purchorderAuthList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function purchorderAuthAll() {
  return request({
    // url: '/purchorderAuth/list',
    url: ROAST_CONFIG.API_URL + '/purchorderAuthList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPurchorderAuth(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/purchorderAuth',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePurchorderAuth(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/purchorderAuth/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deletePurchorderAuth(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/purchorderAuth/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
