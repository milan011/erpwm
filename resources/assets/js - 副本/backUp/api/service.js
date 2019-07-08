import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function serviceList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/serviceList',
    method: 'get',
    params: {token, page:query.page}
  })
}

export function serviceAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/serviceAll',
    method: 'get',
    params: {token}
  })
}

export function getService(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getService/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createService(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/service',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateService(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/service/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteService(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/service/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

