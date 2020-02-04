import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getAccountSectionList(query) {
  return request({
    // url: '/accountSection/list',
    url: ROAST_CONFIG.API_URL + '/accountSectionList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function accountSectionAll() {
  return request({
    // url: '/accountSection/list',
    url: ROAST_CONFIG.API_URL + '/accountSectionList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createAccountSection(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/accountSection',
    method: 'post',
    params: { token },
    data
  })
}


export function updateAccountSection(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/accountSection/' + data.sectionid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteAccountSection(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/accountSection/' + data.sectionid,
    method: 'delete',
    params: {token},
    data
  }) 
}
