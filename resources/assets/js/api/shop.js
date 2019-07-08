import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function shopList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/shopList',
    method: 'get',
    query,
    params: {token, page:query.page}
  })
}

export function shopAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/shopAll',
    method: 'get',
    params: {token}
  })
}

export function getShop(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getShop/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createShop(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/shop',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateShop(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/shop/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteShop(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/shop/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

