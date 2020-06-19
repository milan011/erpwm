import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getBankTransList(query) {
  return request({
    // url: '/bankTrans/list',
    url: ROAST_CONFIG.API_URL + '/bankTransList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function bankTransAll() {
  return request({
    // url: '/bankTrans/list',
    url: ROAST_CONFIG.API_URL + '/bankTransList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createBankTrans(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/bankTrans',
    method: 'post',
    params: { token },
    data
  })
}


export function updateBankTrans(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/bankTrans/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteBankTrans(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/bankTrans/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

export function getUserBanks(query) {
  console.log('query',query)
  return request({
    // url: '/bankTrans/list',
    url: ROAST_CONFIG.API_URL + '/userBankList/' + query.id,
    method: 'get',
    // params: {token, query}
    params: {token}
  })
}
