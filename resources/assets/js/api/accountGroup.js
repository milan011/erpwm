import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getAccountGroupList(query) {
  return request({
    // url: '/accountGroup/list',
    url: ROAST_CONFIG.API_URL + '/accountGroupList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function accountGroupAll() {
  return request({
    // url: '/accountGroup/list',
    url: ROAST_CONFIG.API_URL + '/accountGroupList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createAccountGroup(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/accountGroup',
    method: 'post',
    params: { token },
    data
  })
}


export function updateAccountGroup(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/accountGroup/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteAccountGroup(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/accountGroup/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
