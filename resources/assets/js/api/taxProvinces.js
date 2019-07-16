import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getTaxProvincesList(query) {
  return request({
    // url: '/taxProvinces/list',
    url: ROAST_CONFIG.API_URL + '/taxProvincesList',
    method: 'get',
    params: { query, token, page: query.page }
  })
}

export function taxProvincesAll() {
  return request({
    // url: '/taxProvinces/list',
    url: ROAST_CONFIG.API_URL + '/taxProvincesList',
    method: 'get',
    params: { token, withOutPage: true }
  })
}

export function createTaxProvinces(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxProvinces',
    method: 'post',
    params: { token },
    data
  })
}


export function updateTaxProvinces(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxProvinces/' + data.taxprovinceid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteTaxProvinces(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxProvinces/' + data.taxprovinceid,
    method: 'delete',
    params: { token },
    data
  })
}