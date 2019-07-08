import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function fetchList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/userList',
    method: 'get',
    query,
    params: {token, page:query.page}
  })
}

export function userAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/userAll',
    method: 'get',
    params: {token}
  })
}

export function getRoles(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/roleList',
    method: 'get',
    query,
    params: {token, page:query.page}
  })
}

export function getUserRoles(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/user/' + data.id + '/roles',
    method: 'get',
    params: {token},
    data
  }) 
}

export function giveUserRoles(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/giveUser/' + data.id + '/roles',
    method: 'post',
    params: {token},
    data
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

export function createUser(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/user',
    method: 'post',
    params: { token },
    data
  })
}

export function resetPassword(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/resetPassword',
    method: 'post',
    params: { token },
    data
  })
}

export function updateUser(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/user/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteUser(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/user/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
