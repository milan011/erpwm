import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function serviceGoodsDetailList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/serviceGoodsDetailList',
    method: 'get',
    params: {token, page:query.page}
  })
}

export function serviceGoodsDetailAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/serviceGoodsDetailAll',
    method: 'get',
    params: {token}
  })
}

export function getServiceGoodsDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getServiceGoodsDetail/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createServiceGoodsDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/serviceGoodsDetail',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateServiceGoodsDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/serviceGoodsDetail/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteServiceGoodsDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/serviceGoodsDetail/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

