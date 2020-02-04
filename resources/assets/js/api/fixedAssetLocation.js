import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getFixedAssetLocationList(query) {
  return request({
    // url: '/fixedAssetLocation/list',
    url: ROAST_CONFIG.API_URL + '/fixedAssetLocationList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function fixedAssetLocationAll() {
  return request({
    // url: '/fixedAssetLocation/list',
    url: ROAST_CONFIG.API_URL + '/fixedAssetLocationList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createFixedAssetLocation(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssetLocation',
    method: 'post',
    params: { token },
    data
  })
}


export function updateFixedAssetLocation(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssetLocation/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteFixedAssetLocation(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssetLocation/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
