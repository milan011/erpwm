import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function inventoryList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/inventoryList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function inventoryAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/inventoryAll',
    method: 'get',
    params: {token}
  })
}

export function getInventory(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getInventory/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createInventory(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/inventory',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateInventory(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/inventory/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteInventory(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/inventory/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

