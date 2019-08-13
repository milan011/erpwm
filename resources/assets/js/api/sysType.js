import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getSysTypeList(query) {
  return request({
    // url: '/sysType/list',
    url: ROAST_CONFIG.API_URL + '/sysTypeList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function sysTypeAll() {
  return request({
    // url: '/sysType/list',
    url: ROAST_CONFIG.API_URL + '/sysTypeList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createSysType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/sysType',
    method: 'post',
    params: { token },
    data
  })
}


export function updateSysType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/sysType/' + data.typeid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteSysType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/sysType/' + data.typeid,
    method: 'delete',
    params: {token},
    data
  }) 
}
