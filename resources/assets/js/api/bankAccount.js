import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getBankAccountList(query) {
  return request({
    // url: '/bankAccount/list',
    url: ROAST_CONFIG.API_URL + '/bankAccountList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function bankAccountAll() {
  return request({
    // url: '/bankAccount/list',
    url: ROAST_CONFIG.API_URL + '/bankAccountList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createBankAccount(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/bankAccount',
    method: 'post',
    params: { token },
    data
  })
}


export function updateBankAccount(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/bankAccount/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}

export function getBankUser(id) {
  return request({
    // url: '/bankAccount/list',
    url: ROAST_CONFIG.API_URL + '/getBankUser/' + id,
    method: 'get',
    params: {token}
  })
}

export function setBankUser(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/setBankUser/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}

export function deleteBankAccount(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/bankAccount/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
