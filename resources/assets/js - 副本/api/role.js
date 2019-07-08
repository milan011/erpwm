import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function fetchList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/roleList',
    method: 'get',
    params: {query, token, page:query.page}
  })
}

export function createRole(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/role',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateRole(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/role/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteRole(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/role/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

export function getRolePermissions(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/role/' + data.id + '/permissions',
    method: 'get',
    params: {token},
    data
  }) 
}

export function giveRolePermissions(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/giveRole/' + data.id + '/permissions',
    method: 'post',
    params: {token},
    data
  }) 
}

export function getPermissions(token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/permissionAll',
    method: 'get',
    params: {token}
  }) 
}
