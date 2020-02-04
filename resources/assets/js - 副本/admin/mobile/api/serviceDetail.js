import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function serviceDetailList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/serviceDetailList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function serviceDetailAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/serviceDetailAll',
    method: 'get',
    params: {token}
  })
}

export function getServiceDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getServiceDetail/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createServiceDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/serviceDetail',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateServiceDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/serviceDetail/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteServiceDetail(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/serviceDetail/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

