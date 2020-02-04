import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getCustbranchList(query) {
  return request({
    // url: '/custbranch/list',
    url: ROAST_CONFIG.API_URL + '/custbranchList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function custbranchAll(query) {
  return request({
    // url: '/custbranch/list',
    url: ROAST_CONFIG.API_URL + '/custbranchList',
    method: 'get',
    params: {token, query, withOutPage:true}
  })
}

export function createCustbranch(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/custbranch',
    method: 'post',
    params: { token },
    data
  })
}


export function updateCustbranch(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/custbranch/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteCustbranch(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/custbranch/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
