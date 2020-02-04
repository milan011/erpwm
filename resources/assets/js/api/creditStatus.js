import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getCreditStatusList(query) {
  return request({
    // url: '/creditStatus/list',
    url: ROAST_CONFIG.API_URL + '/creditStatusList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function creditStatusAll() {
  return request({
    // url: '/creditStatus/list',
    url: ROAST_CONFIG.API_URL + '/creditStatusList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createCreditStatus(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/creditStatus',
    method: 'post',
    params: { token },
    data
  })
}


export function updateCreditStatus(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/creditStatus/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteCreditStatus(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/creditStatus/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
