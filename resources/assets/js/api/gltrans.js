import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getGltransList(query) {
  return request({
    // url: '/gltrans/list',
    url: ROAST_CONFIG.API_URL + '/gltransList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function gltransAll() {
  return request({
    // url: '/gltrans/list',
    url: ROAST_CONFIG.API_URL + '/gltransList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createGltrans(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/gltrans',
    method: 'post',
    params: { token },
    data
  })
}


export function updateGltrans(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/gltrans/' + data.counterindex,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteGltrans(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/gltrans/' + data.counterindex,
    method: 'delete',
    params: {token},
    data
  }) 
}
