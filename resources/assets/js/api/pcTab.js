import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPcTabList(query) {
  return request({
    // url: '/pcTab/list',
    url: ROAST_CONFIG.API_URL + '/pcTabList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function pcTabAll() {
  return request({
    // url: '/pcTab/list',
    url: ROAST_CONFIG.API_URL + '/pcTabList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPcTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcTab',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePcTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcTab/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deletePcTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcTab/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
