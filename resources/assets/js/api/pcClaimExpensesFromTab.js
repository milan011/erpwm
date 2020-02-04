import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPcClaimExpensesFromTabList(query) {
  return request({
    // url: '/pcClaimExpensesFromTab/list',
    url: ROAST_CONFIG.API_URL + '/pcClaimExpensesFromTabList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function expensesAll(query) {
  return request({
    // url: '/pcClaimExpensesFromTab/list',
    url: ROAST_CONFIG.API_URL + '/getExpenses',
    method: 'get',
    params: {token, query}
  })
}

export function pcClaimExpensesFromTabAll() {
  return request({
    // url: '/pcClaimExpensesFromTab/list',
    url: ROAST_CONFIG.API_URL + '/pcClaimExpensesFromTabList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPcClaimExpensesFromTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcClaimExpensesFromTab',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePcClaimExpensesFromTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcClaimExpensesFromTab/' + data.counterindex,
    method: 'put',
    params: { token },
    data
  })
}
export function deletePcClaimExpensesFromTab(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcClaimExpensesFromTab/' + data.counterindex,
    method: 'delete',
    params: {token},
    data
  }) 
}
