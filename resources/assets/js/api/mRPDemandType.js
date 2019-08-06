import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getMRPDemandTypeList(query) {
  return request({
    // url: '/mRPDemandType/list',
    url: ROAST_CONFIG.API_URL + '/mRPDemandTypeList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function mRPDemandTypeAll() {
  return request({
    // url: '/mRPDemandType/list',
    url: ROAST_CONFIG.API_URL + '/mRPDemandTypeList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createMRPDemandType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/mRPDemandType',
    method: 'post',
    params: { token },
    data
  })
}


export function updateMRPDemandType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/mRPDemandType/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteMRPDemandType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/mRPDemandType/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
