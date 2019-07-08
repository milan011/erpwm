import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function fetchList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/permissionList',
    method: 'get',
    params: {token, page:query.page}
  })
}

export function fetchArticle(id) {
  return request({
    url: '/article/detail',
    method: 'get',
    params: { id }
  })
}

export function fetchPv(pv) {
  return request({
    url: '/article/pv',
    method: 'get',
    params: { pv }
  })
}

export function createPermission(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/permission',
    method: 'post',
    data,
    params: {token}
  })
}

export function updatePermission(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/permission/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deletePermission(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/permission/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
