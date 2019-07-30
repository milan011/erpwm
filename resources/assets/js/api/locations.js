import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getLocationsList(query) {
  return request({
    // url: '/locations/list',
    url: ROAST_CONFIG.API_URL + '/locationsList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function locationsAll() {
  return request({
    // url: '/locations/list',
    url: ROAST_CONFIG.API_URL + '/locationsList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createLocations(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/locations',
    method: 'post',
    params: { token },
    data
  })
}


export function updateLocations(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/locations/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteLocations(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/locations/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
