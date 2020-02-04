import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPcAuthorizeExpenseList(query) {
  return request({
    // url: '/pcAuthorizeExpense/list',
    url: ROAST_CONFIG.API_URL + '/pcAuthorizeExpenseList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function expensesAll(query) {
  return request({
    // url: '/pcAuthorizeExpense/list',
    url: ROAST_CONFIG.API_URL + '/getExpenses',
    method: 'get',
    params: {token, query}
  })
}

export function pcAuthorizeExpenseAll() {
  return request({
    // url: '/pcAuthorizeExpense/list',
    url: ROAST_CONFIG.API_URL + '/pcAuthorizeExpenseList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPcAuthorizeExpense(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcAuthorizeExpense',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePcAuthorizeExpense(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcAuthorizeExpense/' + data.counterindex,
    method: 'put',
    params: { token },
    data
  })
}
export function approvalAuthorizeExpense(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/approvalAuthorizeExpense/' + data.counterindex,
    method: 'post',
    params: {token},
    data
  }) 
}
