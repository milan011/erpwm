import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getFixedAssetCategorieList(query) {
  return request({
    // url: '/fixedAssetCategorie/list',
    url: ROAST_CONFIG.API_URL + '/fixedAssetCategorieList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function fixedAssetCategorieAll() {
  return request({
    // url: '/fixedAssetCategorie/list',
    url: ROAST_CONFIG.API_URL + '/fixedAssetCategorieList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createFixedAssetCategorie(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssetCategorie',
    method: 'post',
    params: { token },
    data
  })
}


export function updateFixedAssetCategorie(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssetCategorie/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteFixedAssetCategorie(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/fixedAssetCategorie/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
