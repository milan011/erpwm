import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getAreaList(query) {
  return request({
    // url: '/area/list',
    url: ROAST_CONFIG.API_URL + '/areaList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function areaAll() {
  return request({
    // url: '/area/list',
    url: ROAST_CONFIG.API_URL + '/areaList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createArea(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/area',
    method: 'post',
    params: { token },
    data
  })
}


export function updateArea(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/area/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteArea(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/area/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
