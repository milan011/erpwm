import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getFixedAssetsList(query) {
  return request({
    // url: '/fixedAssets/list',
    url: ROAST_CONFIG.API_URL + '/fixedAssetsList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function fixedAssetsAll() {
  return request({
    // url: '/fixedAssets/list',
    url: ROAST_CONFIG.API_URL + '/fixedAssetsList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createFixedAssets(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssets',
    method: 'post',
    params: { token },
    data
  })
}


export function updateFixedAssets(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssets/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteFixedAssets(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssets/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
