import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getSaleTypeList(query) {
  return request({
    // url: '/saleType/list',
    url: ROAST_CONFIG.API_URL + '/saleTypeList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function saleTypeAll() {
  return request({
    // url: '/saleType/list',
    url: ROAST_CONFIG.API_URL + '/saleTypeList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createSaleType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/saleType',
    method: 'post',
    params: { token },
    data
  })
}


export function updateSaleType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/saleType/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteSaleType(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/saleType/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
