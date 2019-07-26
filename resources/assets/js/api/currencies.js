import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getCurrenciesList(query) {
  return request({
    // url: '/currencies/list',
    url: ROAST_CONFIG.API_URL + '/currenciesList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function currenciesAll() {
  return request({
    // url: '/currencies/list',
    url: ROAST_CONFIG.API_URL + '/currenciesList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createCurrencies(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/currencies',
    method: 'post',
    params: { token },
    data
  })
}


export function updateCurrencies(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/currencies/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteCurrencies(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/currencies/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
