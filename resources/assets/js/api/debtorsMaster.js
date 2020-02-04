import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getDebtorsMasterList(query) {
  return request({
    // url: '/debtorsMaster/list',
    url: ROAST_CONFIG.API_URL + '/debtorsMasterList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function debtorsMasterAll() {
  return request({
    // url: '/debtorsMaster/list',
    url: ROAST_CONFIG.API_URL + '/debtorsMasterList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createDebtorsMaster(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/debtorsMaster',
    method: 'post',
    params: { token },
    data
  })
}


export function updateDebtorsMaster(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/debtorsMaster/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteDebtorsMaster(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/debtorsMaster/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
