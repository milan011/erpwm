import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function inventoryDetailList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/inventoryDetailList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function inventoryDetailAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/inventoryDetailAll',
    method: 'get',
    params: {token}
  })
}

export function getInventoryDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getInventoryDetail/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createInventoryDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/inventoryDetail',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateInventoryDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/inventoryDetail/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteInventoryDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/inventoryDetail/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

