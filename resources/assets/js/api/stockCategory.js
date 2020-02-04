import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getStockCategoryList(query) {
  return request({
    // url: '/stockCategory/list',
    url: ROAST_CONFIG.API_URL + '/stockCategoryList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function stockCategoryAll() {
  return request({
    // url: '/stockCategory/list',
    url: ROAST_CONFIG.API_URL + '/stockCategoryList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createStockCategory(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/stockCategory',
    method: 'post',
    params: { token },
    data
  })
}


export function updateStockCategory(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/stockCategory/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteStockCategory(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/stockCategory/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
