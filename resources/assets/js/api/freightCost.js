import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getFreightCostList(query) {
  return request({
    // url: '/freightCost/list',
    url: ROAST_CONFIG.API_URL + '/freightCostList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function freightCostAll() {
  return request({
    // url: '/freightCost/list',
    url: ROAST_CONFIG.API_URL + '/freightCostList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createFreightCost(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/freightCost',
    method: 'post',
    params: { token },
    data
  })
}


export function updateFreightCost(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/freightCost/' + data.shipcostfromid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteFreightCost(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/freightCost/' + data.shipcostfromid,
    method: 'delete',
    params: {token},
    data
  }) 
}
