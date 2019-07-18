import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getTaxAuthoritiesList(query) {
  return request({
    // url: '/taxAuthorities/list',
    url: ROAST_CONFIG.API_URL + '/taxAuthoritiesList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function taxAuthoritiesAll() {
  return request({
    // url: '/taxAuthorities/list',
    url: ROAST_CONFIG.API_URL + '/taxAuthoritiesList',
    method: 'get',    
    params: {token, withOutPage: true}
  })
}

export function createTaxAuthorities(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxAuthorities',
    method: 'post',
    params: { token },
    data
  })
}

export function updateTaxAuthorities(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxAuthorities/' + data.taxid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteTaxAuthorities(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxAuthorities/' + data.taxid,
    method: 'delete',
    params: {token},
    data
  }) 
}
