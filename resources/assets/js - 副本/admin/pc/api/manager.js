import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function managerList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/managerList',
    method: 'get',
    params: {token, page:query.page}
  })
}

export function managerAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/managerAll',
    method: 'get',
    params: {token}
  })
}

export function createManager(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/manager',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateManager(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/manager/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteManager(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/manager/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
