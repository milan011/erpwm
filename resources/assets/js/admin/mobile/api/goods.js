import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function goodsList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/goodsList',
    method: 'get',
    params: {token, page:query.page}
  })
}

export function goodsAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/goodsAll',
    method: 'get',
    params: {token}
  })
}

export function getGoods(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getGoods/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createGoods(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/goods',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateGoods(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/goods/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteGoods(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/goods/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

