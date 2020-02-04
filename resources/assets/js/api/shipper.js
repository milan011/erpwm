import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getShipperList(query) {
  return request({
    // url: '/shipper/list',
    url: ROAST_CONFIG.API_URL + '/shipperList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function shipperAll() {
  return request({
    // url: '/shipper/list',
    url: ROAST_CONFIG.API_URL + '/shipperList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createShipper(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/shipper',
    method: 'post',
    params: { token },
    data
  })
}


export function updateShipper(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/shipper/' + data.shipper_id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteShipper(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/shipper/' + data.shipper_id,
    method: 'delete',
    params: {token},
    data
  }) 
}
