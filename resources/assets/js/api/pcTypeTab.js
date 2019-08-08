import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPcTypeTabList(query) {
  return request({
    // url: '/pcTypeTab/list',
    url: ROAST_CONFIG.API_URL + '/pcTypeTabList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function pcTypeTabAll() {
  return request({
    // url: '/pcTypeTab/list',
    url: ROAST_CONFIG.API_URL + '/pcTypeTabList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPcTypeTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcTypeTab',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePcTypeTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcTypeTab/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deletePcTypeTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcTypeTab/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

export function getTypePcExpenses(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/type/' + data.id + '/pcExpenses',
    method: 'get',
    params: {token},
    data
  }) 
}

export function giveTypePcExpenses(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/giveType/' + data.id + '/pcExpenses',
    method: 'post',
    params: {token},
    data
  }) 
}
