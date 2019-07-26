import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getSalesManList(query) {
  return request({
    // url: '/salesMan/list',
    url: ROAST_CONFIG.API_URL + '/salesManList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function salesManAll() {
  return request({
    // url: '/salesMan/list',
    url: ROAST_CONFIG.API_URL + '/salesManList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createSalesMan(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/salesMan',
    method: 'post',
    params: { token },
    data
  })
}


export function updateSalesMan(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/salesMan/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteSalesMan(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/salesMan/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
