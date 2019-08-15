import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getFixedAssetItemList(query) {
  return request({
    // url: '/fixedAssetItem/list',
    url: ROAST_CONFIG.API_URL + '/fixedAssetItemList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function fixedAssetItemAll() {
  return request({
    // url: '/fixedAssetItem/list',
    url: ROAST_CONFIG.API_URL + '/fixedAssetItemList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createFixedAssetItem(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssetItem',
    method: 'post',
    params: { token },
    data
  })
}


export function updateFixedAssetItem(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssetItem/' + data.assetid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteFixedAssetItem(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssetItem/' + data.assetid,
    method: 'delete',
    params: {token},
    data
  }) 
}
