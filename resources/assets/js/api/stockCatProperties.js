import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getStockCatPropertiesList(query) {
  return request({
    // url: '/stockCatProperties/list',
    url: ROAST_CONFIG.API_URL + '/stockCatPropertiesList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function stockCatPropertiesAll() {
  return request({
    // url: '/stockCatProperties/list',
    url: ROAST_CONFIG.API_URL + '/stockCatPropertiesList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createStockCatProperties(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/stockCatProperties',
    method: 'post',
    params: { token },
    data
  })
}


export function updateStockCatProperties(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/stockCatProperties/' + data.stkcatpropid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteStockCatProperties(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/stockCatProperties/' + data.stkcatpropid,
    method: 'delete',
    params: {token},
    data
  }) 
}
