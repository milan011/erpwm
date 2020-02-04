import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPcAssignCashToTabList(query) {
  return request({
    // url: '/pcAssignCashToTab/list',
    url: ROAST_CONFIG.API_URL + '/pcAssignCashToTabList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function pcAssignCashToTabAll() {
  return request({
    // url: '/pcAssignCashToTab/list',
    url: ROAST_CONFIG.API_URL + '/pcAssignCashToTabList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPcAssignCashToTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcAssignCashToTab',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePcAssignCashToTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcAssignCashToTab/' + data.counterindex,
    method: 'put',
    params: { token },
    data
  })
}
export function deletePcAssignCashToTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcAssignCashToTab/' + data.counterindex,
    method: 'delete',
    params: {token},
    data
  }) 
}
