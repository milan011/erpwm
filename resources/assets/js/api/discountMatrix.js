import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getDiscountMatrixList(query) {
  return request({
    // url: '/discountMatrix/list',
    url: ROAST_CONFIG.API_URL + '/discountMatrixList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function discountMatrixAll() {
  return request({
    // url: '/discountMatrix/list',
    url: ROAST_CONFIG.API_URL + '/discountMatrixList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createDiscountMatrix(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/discountMatrix',
    method: 'post',
    params: { token },
    data
  })
}


export function updateDiscountMatrix(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/discountMatrix/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteDiscountMatrix(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/discountMatrix/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
